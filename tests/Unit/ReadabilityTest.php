<?php

require_once __DIR__ . '/InspectionHelpers.php';

// Pertanyaan 1: Apakah nama class, method, dan variabel menjelaskan fungsinya?
it('nama class method dan variabel utama mudah dipahami', function () {
    $pesanan = sourceCode('app/Http/Controllers/PesananController.php');
    $keranjang = sourceCode('app/Http/Controllers/KeranjangController.php');
    $auth = sourceCode('app/Http/Controllers/AuthController.php');

    expect($pesanan)
        ->toContain('class PesananController')
        ->toContain('public function simpan')
        ->toContain('$hargaFinal')
        ->toContain('$subtotalItem')
        ->toContain("'total_harga'")
        ->toContain("'status_pesanan'");

    expect($keranjang)
        ->toContain('class KeranjangController')
        ->toContain('public function tambah');

    expect($auth)
        ->toContain('class AuthController')
        ->toContain('public function login');
});

// Pertanyaan 2: Apakah proyek memiliki aturan struktur dan indentasi?
it('proyek memiliki aturan indentasi dan format dasar', function () {
    $editorConfig = sourceCode('.editorconfig');

    expect($editorConfig)
        ->toContain('indent_style = space')
        ->toContain('indent_size = 4')
        ->toContain('trim_trailing_whitespace = true');
});
