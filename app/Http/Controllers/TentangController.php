<?php

namespace App\Http\Controllers;

use App\Models\ManajemenGambar;

class TentangController extends Controller
{
    public function index()
    {
        $tentangHero = ManajemenGambar::select('path_gambar')
            ->where('option_gambar', 'tentang_1')
            ->where('is_show', 1)
            ->first();

        return view('tentang.tentang', compact('tentangHero'));
    }
}
