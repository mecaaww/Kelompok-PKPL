<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('deskripsi_obat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('obat_id')
                  ->constrained('obat')
                  ->cascadeOnDelete();

            $table->string('label');
            $table->text('nilai');
            $table->integer('urutan')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deskripsi_obat');
    }
};

