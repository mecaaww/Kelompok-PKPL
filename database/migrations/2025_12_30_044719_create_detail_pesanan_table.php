<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('detail_pesanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->constrained('pesanan')->onDelete('cascade');
            $table->foreignId('obat_id')->constrained('obat');
            $table->integer('jumlah');
            $table->integer('harga_satuan_asli'); // Harga sebelum diskon
            $table->integer('persentase_diskon'); // Menyimpan % diskon saat dibeli
            $table->integer('harga_setelah_diskon'); // Harga final per item
            $table->integer('subtotal'); // jumlah * harga_setelah_diskon
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pesanan');
    }
};
