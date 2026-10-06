<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\PesananController;
use App\Models\Keranjang;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

uses(Tests\TestCase::class);

beforeEach(function () {
    // Database sementara di memori, data asli di MySQL (pkpl) tidak tersentuh.
    config([
        'database.default' => 'sqlite',
        'database.connections.sqlite.database' => ':memory:',
        'jwt.secret' => str_repeat('k', 64),
    ]);
    DB::purge('sqlite');
    DB::setDefaultConnection('sqlite');

    Schema::connection('sqlite')->create((new User)->getTable(), function ($table) {
        $table->id();
        $table->string('username')->nullable();
        $table->string('email')->nullable();
        $table->string('password')->nullable();
        $table->string('role')->nullable();
        $table->rememberToken();
        $table->timestamps();
    });

    Schema::connection('sqlite')->create((new Keranjang)->getTable(), function ($table) {
        $table->id();
        $table->unsignedBigInteger('user_id')->nullable();
        $table->unsignedBigInteger('obat_id')->nullable();
        $table->integer('jumlah')->default(1);
        $table->timestamps();
    });
});

function loginSebagaiUser(): User
{
    $user = new User;
    $user->forceFill([
        'username' => 'penguji',
        'email' => 'penguji@example.com',
        'password' => bcrypt('rahasia123'),
        'role' => 'pelanggan',
    ])->save();

    auth()->guard('api')->setToken(JWTAuth::fromUser($user));

    return $user;
}

// NFR-009 / TC-EH-1: hapus item yang tidak ada tidak boleh dibalas "success"
it('hapus() tidak membalas success saat item keranjang tidak ditemukan', function () {
    loginSebagaiUser();

    $response = (new KeranjangController)->hapus(999999);

    expect($response->getData(true)['status'] ?? null)->not->toBe('success');
})->group('NFR-009');

// NFR-009 / TC-EH-2: simpan() tanpa login dibalas 401
it('simpan() menolak pengguna yang belum login dengan status 401', function () {
    $response = (new PesananController)->simpan(Request::create('/pesanan', 'POST'));

    expect($response->getStatusCode())->toBe(401);
})->group('NFR-009');

// NFR-009 / TC-EH-3: simpan() dengan keranjang kosong dibalas 400
it('simpan() membalas 400 saat keranjang kosong', function () {
    loginSebagaiUser();

    $response = (new PesananController)->simpan(Request::create('/pesanan', 'POST'));

    expect($response->getStatusCode())->toBe(400)
        ->and($response->getData(true)['message'])->toBe('Keranjang kosong');
})->group('NFR-009');

// NFR-0010 / TC-EH-4: login dengan kredensial salah menampilkan pesan yang jelas
it('login() menampilkan pesan jelas saat email atau password salah', function () {
    $request = Request::create('/login', 'POST', [
        'email' => 'tidak.ada@example.com',
        'password' => 'salah',
    ]);

    $response = (new AuthController)->login($request);

    expect($response->getSession()->get('error'))->toBe('Email atau password salah');
})->group('NFR-010');

// NFR-0010 / TC-EH-5: login dengan field kosong menampilkan pesan validasi yang jelas
it('login() memberi pesan validasi yang jelas saat field kosong', function () {
    $pesan = null;

    try {
        (new AuthController)->login(Request::create('/login', 'POST', []));
    } catch (ValidationException $e) {
        $pesan = $e->errors()['email'][0] ?? null;
    }

    expect($pesan)->toBe('Email wajib diisi');
})->group('NFR-010');
