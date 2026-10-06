<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PesananController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

uses(Tests\TestCase::class);

beforeEach(function () {
    // Database sementara di memori, data asli di MySQL tidak tersentuh.
    config([
        'database.default' => 'sqlite',
        'database.connections.sqlite.database' => ':memory:',
        'jwt.secret' => str_repeat('k', 64),
    ]);
    DB::purge('sqlite');
    DB::setDefaultConnection('sqlite');

    $schema = Schema::connection('sqlite');

    $schema->create('users', function ($table) {
        $table->id();
        $table->string('username')->nullable();
        $table->string('email')->nullable();
        $table->string('password')->nullable();
        $table->string('role')->nullable();
        $table->rememberToken();
        $table->timestamps();
    });

    $schema->create('obat', function ($table) {
        $table->id();
        $table->string('nama');
        $table->integer('harga');
        $table->integer('stok')->default(0);
        $table->integer('diskon_persen')->default(0);
        $table->string('kategori');
        $table->string('path_gambar')->nullable();
        $table->timestamps();
    });

    $schema->create('keranjang', function ($table) {
        $table->id();
        $table->unsignedBigInteger('user_id');
        $table->unsignedBigInteger('obat_id');
        $table->integer('jumlah');
        $table->timestamps();
    });

    $schema->create('pesanan', function ($table) {
        $table->id();
        $table->string('nomor_invoice')->unique();
        $table->unsignedBigInteger('user_id');
        $table->decimal('latitude', 10, 8)->nullable();
        $table->decimal('longitude', 11, 8)->nullable();
        $table->text('alamat_lengkap')->nullable();
        $table->string('detail_alamat')->nullable();
        $table->integer('ongkir')->default(0);
        $table->integer('total_harga');
        $table->string('status_pesanan')->default('menunggu pembayaran');
        $table->string('metode_pembayaran')->nullable();
        $table->timestamps();
    });

    $schema->create('detail_pesanan', function ($table) {
        $table->id();
        $table->unsignedBigInteger('pesanan_id');
        $table->unsignedBigInteger('obat_id');
        $table->integer('jumlah');
        $table->integer('harga_satuan_asli');
        $table->integer('persentase_diskon');
        $table->integer('harga_setelah_diskon');
        $table->integer('subtotal');
        $table->timestamps();
    });
});

function dataObatUji(array $override = []): array
{
    return array_merge([
        'nama' => 'Obat Uji '.uniqid(),
        'harga' => 10000,
        'stok' => 10,
        'diskon_persen' => 0,
        'kategori' => 'Obat Bebas',
        'path_gambar' => null,
        'created_at' => now(),
        'updated_at' => now(),
    ], $override);
}

function isiObatUji(int $jumlah, array $override = []): void
{
    for ($i = 0; $i < $jumlah; $i++) {
        DB::table('obat')->insert(dataObatUji($override));
    }
}

function loginPerformance(): User
{
    $user = new User;
    $user->forceFill([
        'username' => 'penguji',
        'email' => 'penguji@example.com',
        'password' => bcrypt('rahasia123'),
        'role' => 'pelanggan',
    ])->save();

    auth()->guard('api')->setToken(JWTAuth::fromUser($user));
    auth()->guard('api')->user(); // simpan user di guard, supaya tidak dihitung sebagai query

    return $user;
}

function isiKeranjangUji(User $user, int $jumlahItem): void
{
    DB::table('keranjang')->delete();

    for ($i = 0; $i < $jumlahItem; $i++) {
        $obatId = DB::table('obat')->insertGetId(dataObatUji());

        DB::table('keranjang')->insert([
            'user_id' => $user->id,
            'obat_id' => $obatId,
            'jumlah' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}

function requestCheckoutUji(): Request
{
    return Request::create('/pesanan', 'POST', [
        'lat' => -7.9,
        'lng' => 112.6,
        'alamat_lengkap' => 'Jl. Uji Coba',
        'detail_alamat' => 'Blok A1',
        'ongkir' => 2000,
        'total_bayar' => 22000,
        'metode_pembayaran' => 'Transfer Bank',
    ]);
}

// NFR-011 / TC-PERF-1: jumlah query tidak boleh naik seiring jumlah item
it('simpan() tidak menambah query seiring bertambahnya item keranjang', function () {
    $user = loginPerformance();

    $hitungQuery = function (int $jumlahItem) use ($user) {
        isiKeranjangUji($user, $jumlahItem);

        DB::flushQueryLog();
        DB::enableQueryLog();
        (new PesananController)->simpan(requestCheckoutUji());
        $jumlah = count(DB::getQueryLog());
        DB::disableQueryLog();

        return $jumlah;
    };

    $satuItem = $hitungQuery(1);
    $limaItem = $hitungQuery(5);

    expect($limaItem)->toBe($satuItem);
});

// NFR-013 / TC-PERF-2: data obat dimuat dengan satu query (tidak ada N+1)
it('simpan() memuat data obat dengan satu query saja', function () {
    $user = loginPerformance();
    isiKeranjangUji($user, 5);

    DB::flushQueryLog();
    DB::enableQueryLog();
    (new PesananController)->simpan(requestCheckoutUji());

    $queryObat = collect(DB::getQueryLog())
        ->filter(fn ($q) => preg_match('/^select .* from ["`]obat["`]/i', $q['query']))
        ->count();

    expect($queryObat)->toBe(1);
})->group('NFR-013');

