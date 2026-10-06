<?php

uses(Tests\TestCase::class);

function methodTanpaDocblock(array $daftar = []): array
{
    $tanpaDoc = [];

    foreach (glob(app_path('Http/Controllers/*.php')) as $file) {
        $class = 'App\\Http\\Controllers\\'.basename($file, '.php');

        foreach ((new ReflectionClass($class))->getMethods(ReflectionMethod::IS_PUBLIC) as $m) {
            if ($m->class !== $class || $m->isConstructor()) {
                continue;
            }
            if ($daftar && ! in_array("{$class}::{$m->name}", $daftar, true)) {
                continue;
            }
            if (! $m->getDocComment()) {
                $tanpaDoc[] = "{$class}::{$m->name}";
            }
        }
    }

    return $tanpaDoc;
}

// NFR-011 / TC-DOC-1: semua public method controller punya docblock
it('semua public method controller memiliki docblock', function () {
    expect(methodTanpaDocblock())->toBe([]);
})->group('NFR-011');

// NFR-011 / TC-DOC-2: fungsi utama (login, register, keranjang, checkout) punya docblock
it('fungsi utama memiliki komentar penjelasan singkat', function () {
    $utama = [
        'App\\Http\\Controllers\\AuthController::login',
        'App\\Http\\Controllers\\AuthController::register',
        'App\\Http\\Controllers\\KeranjangController::hapus',
        'App\\Http\\Controllers\\PesananController::simpan',
    ];

    expect(methodTanpaDocblock($utama))->toBe([]);
})->group('NFR-011');


