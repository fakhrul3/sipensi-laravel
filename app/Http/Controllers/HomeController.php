<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function index()
    {
        // =========================
        // MOCKUP DATA (TANPA DATABASE)
        // =========================
        $totalLembaga = 732;
        $totalTenant  = 6165;

        $sebaranInkubator = [
            ['name' => 'Aceh', 'latitude' => 4.695135, 'longitude' => 96.749397, 'total' => 12],
            ['name' => 'Sumatera Utara', 'latitude' => 2.115354, 'longitude' => 99.545097, 'total' => 28],
            ['name' => 'Sumatera Barat', 'latitude' => -0.739939, 'longitude' => 100.800005, 'total' => 18],
            ['name' => 'Riau', 'latitude' => 0.293347, 'longitude' => 101.706829, 'total' => 22],
            ['name' => 'Kepulauan Riau', 'latitude' => 3.945651, 'longitude' => 108.142866, 'total' => 10],
            ['name' => 'Jambi', 'latitude' => -1.610122, 'longitude' => 103.613121, 'total' => 14],
            ['name' => 'Sumatera Selatan', 'latitude' => -3.319437, 'longitude' => 103.914399, 'total' => 20],
            ['name' => 'Kepulauan Bangka Belitung', 'latitude' => -2.741051, 'longitude' => 106.440587, 'total' => 9],
            ['name' => 'Bengkulu', 'latitude' => -3.577848, 'longitude' => 102.346387, 'total' => 8],
            ['name' => 'Lampung', 'latitude' => -4.558585, 'longitude' => 105.406807, 'total' => 16],
            ['name' => 'DKI Jakarta', 'latitude' => -6.208763, 'longitude' => 106.845599, 'total' => 36],
            ['name' => 'Banten', 'latitude' => -6.405817, 'longitude' => 106.064018, 'total' => 15],
            ['name' => 'Jawa Barat', 'latitude' => -6.917464, 'longitude' => 107.619123, 'total' => 107],
            ['name' => 'Jawa Tengah', 'latitude' => -7.150975, 'longitude' => 110.140259, 'total' => 113],
            ['name' => 'DI Yogyakarta', 'latitude' => -7.875385, 'longitude' => 110.426208, 'total' => 44],
            ['name' => 'Jawa Timur', 'latitude' => -7.536064, 'longitude' => 112.238402, 'total' => 137],
            ['name' => 'Bali', 'latitude' => -8.340539, 'longitude' => 115.091950, 'total' => 19],
            // ...lanjutin data provinsi kamu yang lain (punya kamu sudah ada)
            ['name' => 'Sulawesi Selatan', 'latitude' => -3.668799, 'longitude' => 119.974053, 'total' => 14],
            ['name' => 'Papua Pegunungan', 'latitude' => -4.083000, 'longitude' => 139.083000, 'total' => 1],
        ];

        // =========================
        // GALERI (INI KUNCI BIAR MUNCUL)
        // =========================
        $galleryItems = (new GaleriController())->forHome(200);

        // Pastikan VIEW home kamu memang nge-render galeri.blade (section galeri)
        return view('home', compact(
            'totalLembaga',
            'totalTenant',
            'sebaranInkubator',
            'galleryItems'
        ));
    }
}
