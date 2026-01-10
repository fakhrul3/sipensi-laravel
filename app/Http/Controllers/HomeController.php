<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Galeri;
use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public function index()
    {
        try {
            // 1. Berita (Highlight di atas)
            $berita = Berita::where('is_publikasi', 1)
                ->orderBy('is_highlight', 'desc') 
                ->orderBy('tgl_tayang', 'desc')
                ->get();

            // 2. Statistik
            $totalLembaga = DB::table('inkubator')->count() ?: 0;
            $totalTenant  = DB::table('tenant')->count() ?: 0;

            // 3. Carousel (Cegah error jika data kosong)
            $carousel = DB::table('manajemen_gambar')
                ->where('is_show', 1)
                ->whereIn('option_gambar', ['carousel_1','carousel_2','carousel_3','carousel_4','carousel_5'])
                ->get() ?: collect();

            // 4. Sebaran Map - Query dari database
            $sebaranInkubator = $this->getSebaranInkubator();

            // 5. Galeri - Map ke format array yang diharapkan view
            $galleryItems = $this->getGalleryItems();

            return view('home', compact(
                'berita', 'totalLembaga', 'totalTenant', 'sebaranInkubator', 'carousel', 'galleryItems'
            ));
            
        } catch (\Exception $e) {
            // Jika ada error apa pun, kirim data kosong agar halaman tetap bisa diakses
            return view('home', [
                'berita' => collect(),
                'carousel' => collect(),
                'totalLembaga' => 0,
                'totalTenant' => 0,
                'sebaranInkubator' => [],
                'galleryItems' => []
            ]);
        }
    }

    /**
     * Ambil data galeri dan map ke format array yang diharapkan view
     */
    private function getGalleryItems(): array
    {
        $rows = Galeri::where('is_show', 1)
            ->where(function ($q) {
                $q->whereNull('published_at')
                  ->orWhere('published_at', '<=', now());
            })
            ->orderBy('sort_order', 'asc')
            ->orderByRaw('tanggal_kegiatan IS NULL ASC')
            ->orderBy('tanggal_kegiatan', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        return $rows->map(function (Galeri $g) {
            $path = trim($g->path_gambar ?? '');
            $path = ltrim($path, '/');
            
            // Handle path yang sudah full URL
            if (Str::startsWith($path, ['http://', 'https://'])) {
                $src = $path;
            } else {
                $src = asset($path);
            }

            return [
                'id'       => $g->id,
                'src'      => $src,
                'full'     => $src,
                'title'    => $g->judul ?? '',
                'category' => $g->kategori ?? 'kegiatan',
            ];
        })->all();
    }

    /**
     * Ambil data sebaran inkubator dari database
     * Query jumlah inkubator per provinsi dan gabungkan dengan koordinat
     */
    private function getSebaranInkubator(): array
    {
        try {
            // Mapping koordinat provinsi (berdasarkan data di sebaran-inkubator.js)
            $provinsiCoords = [
                'Aceh' => ['name' => 'Nanggroe Aceh Darussalam', 'latitude' => 4.6951, 'longitude' => 96.7494],
                'Nanggroe Aceh Darussalam' => ['name' => 'Nanggroe Aceh Darussalam', 'latitude' => 4.6951, 'longitude' => 96.7494],
                'Sumatera Utara' => ['name' => 'Sumatera Utara', 'latitude' => 2.1154, 'longitude' => 99.5451],
                'Sumatera Barat' => ['name' => 'Sumatera Barat', 'latitude' => -0.9492, 'longitude' => 100.3543],
                'Riau' => ['name' => 'Riau', 'latitude' => 0.5071, 'longitude' => 101.4478],
                'Jambi' => ['name' => 'Jambi', 'latitude' => -1.4852, 'longitude' => 102.438],
                'Sumatera Selatan' => ['name' => 'Sumatera Selatan', 'latitude' => -3.3194, 'longitude' => 103.9144],
                'Bengkulu' => ['name' => 'Bengkulu', 'latitude' => -3.7928, 'longitude' => 102.2608],
                'Lampung' => ['name' => 'Lampung', 'latitude' => -5.428, 'longitude' => 105.2619],
                'Kepulauan Bangka Belitung' => ['name' => 'Kepulauan Bangka Belitung', 'latitude' => -2.0961, 'longitude' => 106.1443],
                'Kepulauan Riau' => ['name' => 'Kepulauan Riau', 'latitude' => 0.9186, 'longitude' => 104.4554],
                'DKI Jakarta' => ['name' => 'DKI Jakarta', 'latitude' => -6.2088, 'longitude' => 106.8456],
                'Jawa Barat' => ['name' => 'Jawa Barat', 'latitude' => -6.9175, 'longitude' => 107.6191],
                'Jawa Tengah' => ['name' => 'Jawa Tengah', 'latitude' => -7.0253, 'longitude' => 110.3769],
                'DI Yogyakarta' => ['name' => 'DI Yogyakarta', 'latitude' => -7.7956, 'longitude' => 110.3695],
                'Jawa Timur' => ['name' => 'Jawa Timur', 'latitude' => -7.2575, 'longitude' => 112.7521],
                'Banten' => ['name' => 'Banten', 'latitude' => -6.4058, 'longitude' => 106.064],
                'Bali' => ['name' => 'Bali', 'latitude' => -8.6705, 'longitude' => 115.2126],
                'Nusa Tenggara Barat' => ['name' => 'Nusa Tenggara Barat', 'latitude' => -8.5833, 'longitude' => 116.1167],
                'Nusa Tenggara Timur' => ['name' => 'Nusa Tenggara Timur', 'latitude' => -8.6574, 'longitude' => 121.0794],
                'Kalimantan Barat' => ['name' => 'Kalimantan Barat', 'latitude' => -0.0263, 'longitude' => 109.3425],
                'Kalimantan Tengah' => ['name' => 'Kalimantan Tengah', 'latitude' => -2.2102, 'longitude' => 113.92],
                'Kalimantan Selatan' => ['name' => 'Kalimantan Selatan', 'latitude' => -3.3194, 'longitude' => 114.5908],
                'Kalimantan Timur' => ['name' => 'Kalimantan Timur', 'latitude' => -0.5021, 'longitude' => 117.1536],
                'Kalimantan Utara' => ['name' => 'Kalimantan Utara', 'latitude' => 3.0738, 'longitude' => 116.0414],
                'Sulawesi Utara' => ['name' => 'Sulawesi Utara', 'latitude' => 1.4748, 'longitude' => 124.8426],
                'Sulawesi Tengah' => ['name' => 'Sulawesi Tengah', 'latitude' => -1.43, 'longitude' => 121.4456],
                'Sulawesi Selatan' => ['name' => 'Sulawesi Selatan', 'latitude' => -5.1477, 'longitude' => 119.4327],
                'Sulawesi Tenggara' => ['name' => 'Sulawesi Tenggara', 'latitude' => -3.9678, 'longitude' => 122.5947],
                'Gorontalo' => ['name' => 'Gorontalo', 'latitude' => 0.6999, 'longitude' => 122.4467],
                'Sulawesi Barat' => ['name' => 'Sulawesi Barat', 'latitude' => -2.8441, 'longitude' => 119.2321],
                'Maluku' => ['name' => 'Maluku', 'latitude' => -3.2385, 'longitude' => 130.1453],
                'Maluku Utara' => ['name' => 'Maluku Utara', 'latitude' => 0.7306, 'longitude' => 127.5699],
                'Papua Barat' => ['name' => 'Papua Barat', 'latitude' => -0.8615, 'longitude' => 134.062],
                'Papua' => ['name' => 'Papua', 'latitude' => -4.2699, 'longitude' => 138.0804],
            ];

            // Query jumlah inkubator per provinsi dari database
            // Join dengan tabel provinsi menggunakan provinsi_id
            $inkubatorByProvinsi = [];
            
            try {
                // Coba query dengan join ke tabel provinsi jika ada
                $results = DB::table('inkubator')
                    ->leftJoin('provinsi', 'inkubator.provinsi_id', '=', 'provinsi.id')
                    ->select('provinsi.nama as provinsi', DB::raw('COUNT(*) as total'))
                    ->whereNotNull('inkubator.provinsi_id')
                    ->groupBy('provinsi.id', 'provinsi.nama')
                    ->get();

                foreach ($results as $row) {
                    $provinsiKey = trim($row->provinsi ?? '');
                    if (!empty($provinsiKey)) {
                        $inkubatorByProvinsi[$provinsiKey] = (int)($row->total ?? 0);
                    }
                }
            } catch (\Exception $e) {
                // Jika tidak ada kolom provinsi_id atau join gagal, coba langsung dari kolom provinsi
                try {
                    $results = DB::table('inkubator')
                        ->select('provinsi', DB::raw('COUNT(*) as total'))
                        ->whereNotNull('provinsi')
                        ->where('provinsi', '!=', '')
                        ->groupBy('provinsi')
                        ->get();

                    foreach ($results as $row) {
                        $provinsiKey = trim($row->provinsi ?? '');
                        if (!empty($provinsiKey)) {
                            $inkubatorByProvinsi[$provinsiKey] = (int)($row->total ?? 0);
                        }
                    }
                } catch (\Exception $e2) {
                    // Jika masih gagal, tetap kosong dan akan pakai fallback
                }
            }

            // Jika tidak ada data dari query, gunakan data default dari JSON atau 0
            if (empty($inkubatorByProvinsi)) {
                // Fallback: gunakan data total lembaga untuk Indonesia
                $totalLembaga = DB::table('inkubator')->count() ?: 0;
                return [['name' => 'Indonesia', 'latitude' => -2.5489, 'longitude' => 118.0149, 'total' => $totalLembaga]];
            }

            // Gabungkan data jumlah dengan koordinat
            $sebaran = [];
            foreach ($inkubatorByProvinsi as $provinsi => $total) {
                // Normalisasi nama provinsi (coba berbagai variasi)
                $coordKey = $provinsi;
                if (!isset($provinsiCoords[$coordKey])) {
                    // Coba cari di mapping dengan case insensitive
                    foreach ($provinsiCoords as $key => $coord) {
                        if (strcasecmp($key, $provinsi) === 0 || strcasecmp($coord['name'], $provinsi) === 0) {
                            $coordKey = $key;
                            break;
                        }
                    }
                }

                if (isset($provinsiCoords[$coordKey])) {
                    $sebaran[] = [
                        'name' => $provinsiCoords[$coordKey]['name'],
                        'latitude' => $provinsiCoords[$coordKey]['latitude'],
                        'longitude' => $provinsiCoords[$coordKey]['longitude'],
                        'total' => $total,
                    ];
                }
            }

            // Sort by total descending
            usort($sebaran, function($a, $b) {
                return $b['total'] <=> $a['total'];
            });

            return $sebaran;

        } catch (\Exception $e) {
            // Fallback jika error
            $totalLembaga = DB::table('inkubator')->count() ?: 0;
            return [['name' => 'Indonesia', 'latitude' => -2.5489, 'longitude' => 118.0149, 'total' => $totalLembaga]];
        }
    }
}