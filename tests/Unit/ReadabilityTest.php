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

// Pertanyaan 3: Apakah rumus diskon tidak banyak diduplikasi?
it('logika perhitungan diskon tidak tersebar pada banyak view', function () {
    $files = [
        'resources/views/home.blade.php',
        'resources/views/partials/_produk_grid.blade.php',
        'resources/views/products/detail_product.blade.php',
        'resources/views/products/keranjang_product.blade.php',
    ];

    $filesDenganRumusDiskon = array_values(array_filter(
        $files,
        fn (string $file): bool => str_contains(
            sourceCode($file),
            '$harga * (100 - $diskon) / 100'
        ),
    ));

    expect($filesDenganRumusDiskon)->toHaveCount(1);
});
