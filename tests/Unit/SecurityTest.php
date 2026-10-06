<?php

require_once __DIR__.'/InspectionHelpers.php';

// NFR-004 / TC-SEC-1: password tidak boleh disimpan sebagai teks biasa.
it('password pendaftaran disimpan menggunakan hash', function () {
    $auth = sourceCode('app/Http/Controllers/AuthController.php');

    expect($auth)->toContain('Hash::make($request->password)')
        ->not->toContain("'password' => \$request->password,");
})->group('NFR-004');

// NFR-005 / TC-SEC-2: query detail pesanan membatasi data berdasarkan pemiliknya.
it('detail pesanan memeriksa pemilik pesanan', function () {
    $pesanan = sourceCode('app/Http/Controllers/PesananController.php');

    expect($pesanan)->toContain("->where('user_id', \$user->id)")
        ->toContain('firstOrFail()');
})->group('NFR-005');

