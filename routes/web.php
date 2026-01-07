<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MitraController;
use App\Http\Controllers\LembagaInkubatorController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| PUBLIC PAGES
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/mitra-kolaborator', [MitraController::class, 'index'])->name('mitra.index');

Route::get('/lembaga-inkubator', [LembagaInkubatorController::class, 'index'])->name('lembaga.index');
Route::get('/lembaga-inkubator/{id}', [LembagaInkubatorController::class, 'show'])->name('lembaga.show');

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

});
