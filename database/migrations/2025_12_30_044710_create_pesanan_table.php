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
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_invoice')->unique(); // Contoh: INV-20251230-0001
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->text('alamat_lengkap')->nullable();
            $table->string('detail_alamat')->nullable();
            $table->integer('ongkir')->default(0);
            $table->integer('total_harga'); // Total setelah diskon dan ongkir
            $table->string('status_pesanan')->default('menunggu pembayaran'); // pending, dikemas, dikirim, selesai, dibatalkan
            $table->string('metode_pembayaran')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};
