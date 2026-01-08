<?php

namespace App\Http\Controllers;

use App\Models\Provinsi;
use App\Models\Inkubator;
use App\Models\Tenant;
use Illuminate\Http\Request;
// IMPORT GaleriController supaya bisa dipanggil fungsinya
use App\Http\Controllers\GaleriController;

class HomeController extends Controller
{
    /**
     * Menampilkan halaman beranda dengan statistik dan galeri
     */
    public function index()
    {
        // 1. AMBIL DATA GALERI
        // Kita panggil class GaleriController yang sudah lu buat sebelumnya
        $galeri = new GaleriController();
        $galleryItems = $galeri->forHome(12); // Mengambil 12 foto terbaru

        // 2. AMBIL DATA SEBARAN (Peta)
        $sebaranInkubator = Provinsi::withCount('inkubators')
            ->get()
            ->map(function ($prov) {
                return [
                    'id'            => $prov->id,
                    'kode_provinsi' => (string) $prov->kode_provinsi,
                    'name'          => $prov->name,
                    'latitude'      => (float) $prov->latitude,
                    'longitude'     => (float) $prov->longitude,
                    'total'         => $prov->inkubators_count,
                ];
            });
    
        // 3. AMBIL DATA TOTAL (Counter)
        $totalLembaga = Inkubator::count();
        $totalTenant  = Tenant::count();
    
        // 4. KIRIM SEMUA KE VIEW
        // Pastikan variabel 'galleryItems' masuk ke dalam compact()
        return view('home', compact(
            'totalLembaga', 
            'totalTenant', 
            'sebaranInkubator', 
            'galleryItems'
        ));
    }
}