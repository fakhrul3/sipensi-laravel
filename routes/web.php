<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KontakController;
use App\Http\Controllers\TentangController;
use App\Http\Controllers\MitraController;
use App\Http\Controllers\LembagaInkubatorController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoleUserController;
use App\Http\Controllers\WilayahController;




// ROUTE UTAMA (Wajib Paling Atas)
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/api/berita', [HomeController::class, 'getBerita'])->name('api.berita');
Route::get('/api/galeri', [HomeController::class, 'getGaleri'])->name('api.galeri');
Route::get('/api/sebaran-inkubator', [HomeController::class, 'getSebaranInkubator'])->name('api.sebaran-inkubator');

// INFO
Route::get('/tentang', [TentangController::class, 'index'])->name('tentang');
Route::get('/kontak', [KontakController::class, 'index'])->name('kontak');
Route::post('/kontak', [KontakController::class, 'store'])->name('kontak.store');

// BERITA
Route::get('/berita', [BeritaController::class, 'index'])->name('berita.index');
Route::get('/berita/{slug}', [BeritaController::class, 'show'])->name('berita.detail');

// LAINNYA
Route::get('/mitra-kolaborator', [MitraController::class, 'index'])->name('mitra.index');
// Route public lembaga-inkubator (harus sebelum route protected dengan prefix yang sama)
Route::get('/lembaga-inkubator', [LembagaInkubatorController::class, 'index'])->name('lembaga.index');
Route::get('/lembaga-inkubator/{id}', [LembagaInkubatorController::class, 'show'])->name('lembaga.show');

// AUTH
Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLogin')->name('login');
    Route::post('/login', 'login')->name('login.post');
    Route::post('/logout', 'logout')->name('logout');
});

// DASHBOARD (Protected)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/top-inkubator', [DashboardController::class, 'getTopInkubator'])->name('dashboard.top-inkubator');
    Route::get('/dashboard/top-tenant', [DashboardController::class, 'getTopTenant'])->name('dashboard.top-tenant');
    
    // ROLE USER
    Route::prefix('role-user')->name('role-user.')->group(function () {
        // Admin CRUD
        Route::get('/admin', [RoleUserController::class, 'adminIndex'])->name('admin.index');
        Route::post('/admin', [RoleUserController::class, 'adminStore'])->name('admin.store');
        Route::get('/admin/{id}', [RoleUserController::class, 'adminShow'])->name('admin.show');
        Route::put('/admin/{id}', [RoleUserController::class, 'adminUpdate'])->name('admin.update');
        Route::delete('/admin/{id}', [RoleUserController::class, 'adminDestroy'])->name('admin.destroy');
        Route::get('/admin/export/{format}', [RoleUserController::class, 'adminExport'])->name('admin.export');
    });
    
    // LEMBAGA INKUBATOR (Admin) - menggunakan /admin/lembaga-inkubator untuk menghindari konflik dengan route public
    Route::prefix('admin/lembaga-inkubator')->name('lembaga-inkubator.')->group(function () {
        Route::get('/', [RoleUserController::class, 'lembagaInkubatorIndex'])->name('index');
        Route::get('/export/{format}', [RoleUserController::class, 'lembagaInkubatorExport'])->name('export');
    });
    
    // WILAYAH (Admin)
    Route::prefix('wilayah')->name('wilayah.')->group(function () {
        // Provinsi CRUD
        Route::get('/provinsi', [WilayahController::class, 'provinsiIndex'])->name('provinsi.index');
        Route::post('/provinsi', [WilayahController::class, 'provinsiStore'])->name('provinsi.store');
        Route::get('/provinsi/{id}', [WilayahController::class, 'provinsiShow'])->name('provinsi.show');
        Route::put('/provinsi/{id}', [WilayahController::class, 'provinsiUpdate'])->name('provinsi.update');
        Route::delete('/provinsi/{id}', [WilayahController::class, 'provinsiDestroy'])->name('provinsi.destroy');
        
        // Kabupaten CRUD
        Route::get('/kabupaten', [WilayahController::class, 'kabupatenIndex'])->name('kabupaten.index');
        Route::post('/kabupaten', [WilayahController::class, 'kabupatenStore'])->name('kabupaten.store');
        Route::get('/kabupaten/{id}', [WilayahController::class, 'kabupatenShow'])->name('kabupaten.show');
        Route::put('/kabupaten/{id}', [WilayahController::class, 'kabupatenUpdate'])->name('kabupaten.update');
        Route::delete('/kabupaten/{id}', [WilayahController::class, 'kabupatenDestroy'])->name('kabupaten.destroy');
        
        // Kecamatan CRUD
        Route::get('/kecamatan', [WilayahController::class, 'kecamatanIndex'])->name('kecamatan.index');
        Route::post('/kecamatan', [WilayahController::class, 'kecamatanStore'])->name('kecamatan.store');
        Route::get('/kecamatan/{id}', [WilayahController::class, 'kecamatanShow'])->name('kecamatan.show');
        Route::put('/kecamatan/{id}', [WilayahController::class, 'kecamatanUpdate'])->name('kecamatan.update');
        Route::delete('/kecamatan/{id}', [WilayahController::class, 'kecamatanDestroy'])->name('kecamatan.destroy');
    });
});