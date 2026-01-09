<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InkubatorController;
use App\Http\Controllers\MitraController;
use App\Http\Controllers\LembagaInkubatorController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\TentangController;
use App\Http\Controllers\KontakController;




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
Route::get('/berita/{slug}', [BeritaController::class, 'show'])->name('berita.detail');


