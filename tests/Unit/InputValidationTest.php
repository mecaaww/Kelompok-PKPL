<?php

require_once __DIR__.'/InspectionHelpers.php';

// NFR-006 / TC-VALID-1: register dan login memvalidasi masukan pengguna.
it('register dan login memiliki aturan validasi', function () {
    $auth = sourceCode('app/Http/Controllers/AuthController.php');

    expect($auth)->toContain("'email' => 'required|email'")
        ->toContain("'email' => 'required|email|unique:users'")
        ->toContain("'password' => 'required|min:8'");
})->group('NFR-006');
