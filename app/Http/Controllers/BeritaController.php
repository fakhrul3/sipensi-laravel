<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BeritaController extends Controller
{
    // LIST BERITA
    public function index()
    {
        try {
            // Ambil data berita dengan select hanya kolom yang diperlukan
            $berita = Berita::select('id', 'judul', 'isi', 'path_gambar', 'tgl_tayang', 'is_highlight')
                ->where('is_publikasi', 1)
            ->where(function ($q) {
                $q->whereNull('tgl_akhir')
                ->orWhere('tgl_akhir', '>=', now()->toDateString());
            })
            ->orderByDesc('is_highlight')
            ->orderByDesc('tgl_tayang')
                ->limit(20) // Limit untuk performa
            ->get();
        } catch (\Exception $e) {
            // Return empty collection jika database error
            $berita = collect();
        }

        // Kirim variabel $berita ke view home
        return view('home', compact('berita')); 
    }

    // DETAIL BERITA
    public function show($slug)
    {
        try {
            // Cari berita dengan slug yang match
            // Slug di blade menggunakan Str::slug(), jadi kita perlu match dengan cara yang sama
        $berita = Berita::where('is_publikasi', 1)
                ->get()
                ->first(function ($item) use ($slug) {
                    return Str::slug($item->judul) === $slug;
                });

            if (!$berita) {
                abort(404, 'Berita tidak ditemukan');
            }
        } catch (\Exception $e) {
            abort(404, 'Berita tidak ditemukan: ' . $e->getMessage());
        }

        return view('berita.detail', compact('berita'));
    }
}
