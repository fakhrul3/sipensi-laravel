<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KontakController;
use App\Http\Controllers\TentangController;
use App\Http\Controllers\MitraController;
use App\Http\Controllers\LembagaInkubatorController;
use App\Http\Controllers\TenantController; // ✅ tambah ini

// ROUTE UTAMA (Wajib Paling Atas)
Route::get('/', [HomeController::class, 'index'])->name('home');

// INFO
Route::get('/tentang', [TentangController::class, 'index'])->name('tentang');
Route::get('/kontak', [KontakController::class, 'index'])->name('kontak');
Route::post('/kontak', [KontakController::class, 'store'])->name('kontak.store');

// BERITA
Route::get('/berita', [BeritaController::class, 'index'])->name('berita.index');
Route::get('/berita/{slug}', [BeritaController::class, 'show'])->name('berita.detail');

// LAINNYA
Route::get('/mitra-kolaborator', [MitraController::class, 'index'])->name('mitra.index');

// LIST INKUBATOR
Route::get('/lembaga-inkubator', [LembagaInkubatorController::class, 'index'])->name('lembaga.index');

// DETAIL INKUBATOR
Route::get('/lembaga-inkubator/{id}', [LembagaInkubatorController::class, 'show'])->name('lembaga.show');

// ✅ TAMBAHAN: search tenant di halaman detail inkubator (dipakai di show.blade.php versi live)
Route::get('/lembaga-inkubator/{id}/cari-tenant', [LembagaInkubatorController::class, 'cariTenantDetail'])
    ->name('inkubators.cari-tenant.detail');

// ✅ TAMBAHAN: detail tenant (dipakai di list tenant)
Route::get('/tenant/{id}', [TenantController::class, 'show'])->name('tenant');

// AUTH
Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLogin')->name('login');
    Route::post('/login', 'login')->name('login.post');
    Route::post('/logout', 'logout')->name('logout');
});
