<?php

namespace App\Http\Controllers;

use App\Models\ManajemenGambar;

class KontakController extends Controller
{
    public function index()
    {
          $kontakBg = ManajemenGambar::select('path_gambar')
        ->where('option_gambar', 'kontak_2')
        ->where('is_show', 1)
        ->first();

        return view('kontak.kontak', compact('kontakBg'));
    }
}
