<?php

require_once __DIR__.'/InspectionHelpers.php';

// NFR-004 / TC-SEC-1: password tidak boleh disimpan sebagai teks biasa.
it('password pendaftaran disimpan menggunakan hash', function () {
    $auth = sourceCode('app/Http/Controllers/AuthController.php');

    expect($auth)->toContain('Hash::make($request->password)')
        ->not->toContain("'password' => \$request->password,");
})->group('NFR-004');

