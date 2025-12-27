<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InkubatorController;
use App\Http\Controllers\MitraController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/lembaga-inkubator', [InkubatorController::class, 'index'])->name('inkubator.index');
Route::get('/mitra-kolaborator', [MitraController::class, 'index'])->name('mitra.index');
