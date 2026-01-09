<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MitraController;
use App\Http\Controllers\LembagaInkubatorController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\TentangController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GaleriController;
use App\Http\Controllers\KontakController;

/*
|--------------------------------------------------------------------------
| PUBLIC PAGES
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/mitra-kolaborator', [MitraController::class, 'index'])->name('mitra.index');

Route::get('/lembaga-inkubator', [LembagaInkubatorController::class, 'index'])->name('lembaga.index');
Route::get('/lembaga-inkubator/{id}', [LembagaInkubatorController::class, 'show'])->name('lembaga.show');
Route::get('/kontak', [KontakController::class, 'index'])->name('kontak');
Route::get('/login', function () {return view('auth.login');});
Route::get('/forgot-password', function () {return view('auth.forgot-password');});
Route::view('/login', 'auth.login')->name('login.mock');
Route::view('/forgot-password', 'auth.forgot-password')->name('password.request.mock');
Route::view('/register', 'auth.register')->name('register.mock');
Route::get('/tentang', [TentangController::class, 'index'])->name('tentang');

Route::view('/kontak', 'kontak.kontak')->name('kontak');
Route::view('/tentang', 'tentang.tentang')->name('tentang');

Route::get('/berita/{slug}', [BeritaController::class, 'show'])->name('berita.detail');


Route::controller(AuthController::class)->group(function () {
Route::get('/login', 'showLogin')->name('login');
Route::post('/login', 'login')->name('login.post');
Route::post('/logout', 'logout')->name('logout');

    // halaman doang (mock) – belum ada proses reset password
Route::view('/forgot-password', 'auth.forgot-password')->name('password.request');

    // kalau register cuma tampilan dulu
Route::view('/register', 'auth.register')->name('register');





Route::get('/kontak', [KontakController::class, 'index'])->name('kontak');
Route::post('/kontak', [KontakController::class, 'store'])->name('kontak.store');


// Route::get('/galeri', [GaleriController::class, 'index'])->name('galeri.index');

// // sementara untuk import dari folder ke DB (hapus/lock setelah beres)
// Route::get('/galeri/sync', [GaleriController::class, 'syncFromFolder'])->name('galeri.sync');


});
