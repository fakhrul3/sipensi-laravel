<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Http\Request;

class BeritaController extends Controller
{
    // LIST BERITA
    public function index()
    {
        // Ambil data berita yang sama dengan logika di BeritaController
        $berita = Berita::where('is_publikasi', 1)
            ->where(function ($q) {
                $q->whereNull('tgl_akhir')
                ->orWhere('tgl_akhir', '>=', now()->toDateString());
            })
            ->orderByDesc('is_highlight')
            ->orderByDesc('tgl_tayang')
            ->get();

        // Kirim variabel $berita ke view home
        return view('home', compact('berita')); 
    }

    // DETAIL BERITA
    public function show($slug)
    {
        $berita = Berita::where('is_publikasi', 1)
            ->whereRaw("LOWER(REPLACE(judul,' ','-')) = ?", [$slug])
            ->firstOrFail();

        return view('detail', compact('berita'));
    }
}
