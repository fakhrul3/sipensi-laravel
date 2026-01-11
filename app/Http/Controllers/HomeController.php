<?php

namespace App\Http\Controllers;

use App\Models\Provinsi;
use App\Models\Inkubator;
use App\Models\Tenant;
use Illuminate\Http\Request;
// IMPORT GaleriController supaya bisa dipanggil fungsinya
use App\Http\Controllers\GaleriController;
use Illuminate\Support\Facades\DB;
use App\Models\Galeri;
use App\Models\Berita;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    /**
     * Menampilkan halaman beranda dengan statistik dan galeri
     */
    public function index()
    {
        try {
            // Gunakan cache untuk data yang jarang berubah (5 menit)
            $cacheKey = 'home_data_' . date('YmdHi'); // Cache per menit
            
            // 1. AMBIL DATA CAROUSEL (cached)
            $carousel = cache()->remember('carousel_data', 300, function () {
                try {
                    return DB::table('manajemen_gambar')
                        ->select('path_gambar', 'option_gambar')
                        ->where('is_show', 1)
                        ->whereIn('option_gambar', ['carousel_1','carousel_2','carousel_3','carousel_4','carousel_5'])
                        ->orderByRaw("FIELD(option_gambar,'carousel_1','carousel_2','carousel_3','carousel_4','carousel_5')")
                        ->get();
                } catch (\Exception $e) {
                    return collect();
                }
            });

            // 2. AMBIL DATA BERITA (simplified query)
            $berita = cache()->remember('berita_home', 60, function () {
                try {
                    $twoYearsAgo = now()->subYears(2)->toDateString();
                    $today = now()->toDateString();
                    
                    return Berita::select('id', 'judul', 'isi', 'path_gambar', 'tgl_tayang', 'is_highlight')
                        ->where('is_publikasi', 1)
                        ->where(function ($q) use ($twoYearsAgo, $today) {
                            $q->where(function ($subQ) use ($today) {
                                $subQ->whereNull('tgl_akhir')
                                     ->orWhere('tgl_akhir', '>=', $today);
                            })
                            ->orWhere(function ($subQ) use ($twoYearsAgo) {
                                $subQ->whereNotNull('tgl_tayang')
                                     ->where('tgl_tayang', '>=', $twoYearsAgo);
                            });
                        })
                        ->orderByDesc('is_highlight')
                        ->orderByDesc('tgl_tayang')
                        ->limit(10)
                        ->get();
                } catch (\Exception $e) {
                    return collect();
                }
            });

            // 3. AMBIL DATA GALERI
            $galeri = new GaleriController();
            $galleryItems = $galeri->forHome(12);

            // 4. AMBIL DATA SEBARAN (Peta) - hanya ambil kolom yang diperlukan
            $sebaranInkubator = cache()->remember('sebaran_inkubator', 300, function () {
                try {
                    return Provinsi::select('id', 'kode_provinsi', 'name', 'latitude', 'longitude')
                        ->withCount('inkubators')
                        ->get()
                        ->map(function ($prov) {
                            return [
                                'id'            => $prov->id,
                                'kode_provinsi' => (string) $prov->kode_provinsi,
                                'name'          => $prov->name,
                                'latitude'      => (float) ($prov->latitude ?? 0),
                                'longitude'     => (float) ($prov->longitude ?? 0),
                                'total'         => $prov->inkubators_count ?? 0,
                            ];
                        })
                        ->filter(fn($item) => $item['total'] > 0) // Hanya provinsi dengan inkubator
                        ->values()
                        ->toArray();
                } catch (\Exception $e) {
                    return [];
                }
            });
        
            // 5. AMBIL DATA TOTAL (Counter) - gabungkan dalam 1 query jika mungkin
            $totalLembaga = cache()->remember('total_lembaga', 300, function () {
                try {
                    return Inkubator::count();
                } catch (\Exception $e) {
                    return 0;
                }
            });
            
            $totalTenant = cache()->remember('total_tenant', 300, function () {
                try {
                    return Tenant::count();
                } catch (\Exception $e) {
                    return 0;
                }
            });
        
            // 6. KIRIM SEMUA KE VIEW
            return view('home', compact(
                'carousel',
                'berita',
                'totalLembaga', 
                'totalTenant', 
                'sebaranInkubator', 
                'galleryItems'
            ));
        } catch (\Exception $e) {
            // Fallback jika ada error
            return view('home', [
                'carousel' => collect(),
                'berita' => collect(),
                'totalLembaga' => 0,
                'totalTenant' => 0,
                'sebaranInkubator' => [],
                'galleryItems' => []
            ]);
        }
    }

}