<?php

require_once __DIR__.'/InspectionHelpers.php';

// NFR-006 / TC-VALID-1: register dan login memvalidasi masukan pengguna.
it('register dan login memiliki aturan validasi', function () {
    $auth = sourceCode('app/Http/Controllers/AuthController.php');

    expect($auth)->toContain("'email' => 'required|email'")
        ->toContain("'email' => 'required|email|unique:users'")
        ->toContain("'password' => 'required|min:8'");
})->group('NFR-006');

// NFR-007 / TC-VALID-2: jumlah obat dalam keranjang harus minimal satu.
it('penambahan keranjang memvalidasi obat dan jumlah', function () {
    $keranjang = sourceCode('app/Http/Controllers/KeranjangController.php');

    expect($keranjang)->toContain("'obat_id' => 'required|exists:obat,id'")
        ->toContain("'jumlah' => 'required|integer|min:1'");
})->group('NFR-007');

// NFR-008 / TC-VALID-3: checkout dan status pesanan seharusnya divalidasi di server.
it('checkout dan perubahan status memiliki validasi server', function () {
    $checkout = sourceCode('app/Http/Controllers/PesananController.php');
    $statusPesanan = sourceCode('app/Http/Controllers/Admin/PesananController.php');

    $tanpaValidasi = [];

    if (! str_contains($checkout, '$request->validate(')) {
        $tanpaValidasi[] = 'PesananController::simpan()';
    }

    if (! str_contains($statusPesanan, '$request->validate(')) {
        $tanpaValidasi[] = 'Admin\PesananController::updateStatus()';
    }

    expect($tanpaValidasi)->toBeEmpty();
});
