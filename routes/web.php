<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\SearchController;
// Import Controller Admin di sini
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ObatController as AdminObatController;
use App\Http\Controllers\Admin\PesananController as AdminPesananController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Route Autentikasi
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Route Lupa & Reset Password
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

Route::get('/obat/{id}', [ObatController::class, 'show'])->name('obat.show');
Route::get('/search', [SearchController::class, 'index'])->name('search');

Route::middleware('jwt.auth')->group(function () {
    Route::get('/keranjang', [KeranjangController::class, 'index'])->name('products.keranjang_product');
    Route::get('/pembayaran/{id}', [PesananController::class, 'struk'])->name('pesanan.struk');
    Route::get('/pesanan', [PesananController::class, 'index']);
    Route::get('/pesanan/{id}', [PesananController::class, 'show']);
});

Route::prefix('admin')->middleware(['jwt.auth'])->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::resource('obat', AdminObatController::class);
    Route::get('/pesanan', [AdminPesananController::class, 'index'])->name('admin.pesanan.index');
    Route::get('pesanan/{id}', [AdminPesananController::class, 'show'])->name('admin.pesanan.show');
    Route::put('/pesanan/{id}/status', [AdminPesananController::class, 'updateStatus'])->name('admin.pesanan.updateStatus');
});
