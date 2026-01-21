<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KontakController;
use App\Http\Controllers\TentangController;
use App\Http\Controllers\MitraController;
use App\Http\Controllers\LembagaInkubatorController;
use App\Http\Controllers\Auth\NewVerifyController;


// Halaman tunggu/resend setelah register
Route::get('/verify-resend/{username}', [AuthController::class, 'showResendPage'])->name('resend.verify');
Route::get('/verify-resend-mail/{username}', [AuthController::class, 'resendEmail'])->name('resend.mail');
Route::get('/verify-email/{token}/{username}/{expired}', [NewVerifyController::class, 'verify'])->name('user.verify');
/*
|--------------------------------------------------------------------------
| ROUTE UTAMA
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

/*
|--------------------------------------------------------------------------
| INFORMASI
|--------------------------------------------------------------------------
*/
Route::get('/tentang', [TentangController::class, 'index'])->name('tentang');

Route::get('/kontak', [KontakController::class, 'index'])->name('kontak');
Route::post('/kontak', [KontakController::class, 'store'])->name('kontak.store');

/*
|--------------------------------------------------------------------------
| BERITA
|--------------------------------------------------------------------------
*/
Route::get('/berita', [BeritaController::class, 'index'])->name('berita.index');
Route::get('/berita/{slug}', [BeritaController::class, 'show'])->name('berita.detail');

/*
|--------------------------------------------------------------------------
| LAINNYA
|--------------------------------------------------------------------------
*/
Route::get('/mitra-kolaborator', [MitraController::class, 'index'])->name('mitra.index');

Route::get('/lembaga-inkubator', [LembagaInkubatorController::class, 'index'])->name('lembaga.index');
Route::get('/lembaga-inkubator/{id}', [LembagaInkubatorController::class, 'show'])->name('lembaga.show');
/*
|--------------------------------------------------------------------------
| AUTH & REGISTER ROUTES
|--------------------------------------------------------------------------
*/
Route::controller(AuthController::class)->group(function () {
    // Auth
    Route::get('/login', 'showLogin')->name('login');
    Route::post('/login', 'login')->name('login.post');
    Route::post('/logout', 'logout')->name('logout');

    // Register
    Route::get('/register', 'showRegister')->name('register');
    Route::post('/register', 'register')->name('register.post');

    // AJAX Wilayah - Pastikan NAME route ini diingat untuk dipakai di View
    Route::get('/get-kabupaten/{provinsi_id}', 'getKabupaten')->name('get.kabupaten');

    // Contoh jika menggunakan controller baru atau AuthController
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
});
