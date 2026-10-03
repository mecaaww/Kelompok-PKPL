<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\PesananController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('jwt.auth')->group(function () {
    Route::post('/keranjang/tambah', [KeranjangController::class, 'tambah']);
    Route::post('/keranjang/update', [KeranjangController::class, 'update']);
    Route::delete('/keranjang/hapus/{id}', [KeranjangController::class, 'hapus']);
    Route::post('/pesanan/simpan', [PesananController::class, 'simpan']);
});
