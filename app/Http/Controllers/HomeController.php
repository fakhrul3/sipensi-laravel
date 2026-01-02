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

        // 38 Provinsi Indonesia (lat/long titik tengah provinsi - perkiraan untuk marker)
        // total = dummy (silakan kamu ubah nanti)
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
            ['name' => 'Nusa Tenggara Barat', 'latitude' => -8.652933, 'longitude' => 117.361647, 'total' => 12],
            ['name' => 'Nusa Tenggara Timur', 'latitude' => -8.657381, 'longitude' => 121.079370, 'total' => 11],
            ['name' => 'Kalimantan Barat', 'latitude' => -0.278781, 'longitude' => 111.475285, 'total' => 10],
            ['name' => 'Kalimantan Tengah', 'latitude' => -1.681488, 'longitude' => 113.382354, 'total' => 9],
            ['name' => 'Kalimantan Selatan', 'latitude' => -3.092642, 'longitude' => 115.283759, 'total' => 11],
            ['name' => 'Kalimantan Timur', 'latitude' => 0.538659, 'longitude' => 116.419389, 'total' => 13],
            ['name' => 'Kalimantan Utara', 'latitude' => 2.725940, 'longitude' => 116.911000, 'total' => 6],
            ['name' => 'Sulawesi Utara', 'latitude' => 0.624693, 'longitude' => 123.975001, 'total' => 9],
            ['name' => 'Gorontalo', 'latitude' => 0.699937, 'longitude' => 122.446723, 'total' => 5],
            ['name' => 'Sulawesi Tengah', 'latitude' => -1.430025, 'longitude' => 121.445618, 'total' => 8],
            ['name' => 'Sulawesi Barat', 'latitude' => -2.844137, 'longitude' => 119.232078, 'total' => 4],
            ['name' => 'Sulawesi Selatan', 'latitude' => -3.668799, 'longitude' => 119.974053, 'total' => 14],
            ['name' => 'Sulawesi Tenggara', 'latitude' => -4.144910, 'longitude' => 122.174605, 'total' => 7],
            ['name' => 'Maluku', 'latitude' => -3.238462, 'longitude' => 130.145273, 'total' => 6],
            ['name' => 'Maluku Utara', 'latitude' => 1.570999, 'longitude' => 127.808769, 'total' => 5],
            ['name' => 'Papua Barat', 'latitude' => -1.336115, 'longitude' => 133.174716, 'total' => 4],
            ['name' => 'Papua Barat Daya', 'latitude' => -0.876163, 'longitude' => 131.255828, 'total' => 3],
            ['name' => 'Papua', 'latitude' => -4.269928, 'longitude' => 138.080352, 'total' => 6],
            ['name' => 'Papua Selatan', 'latitude' => -7.090911, 'longitude' => 139.548454, 'total' => 2],
            ['name' => 'Papua Tengah', 'latitude' => -3.363417, 'longitude' => 136.708803, 'total' => 2],
            ['name' => 'Papua Pegunungan', 'latitude' => -4.083000, 'longitude' => 139.083000, 'total' => 1],
        ];

        return view('home', compact('totalLembaga', 'totalTenant', 'sebaranInkubator'));
    }
}
