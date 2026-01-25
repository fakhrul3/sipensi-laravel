<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Support\Facades\DB;

class TenantController extends Controller
{
    /**
     * Detail tenant (public)
     * Route: /tenant/{id}
     * Name : tenant
     */
    public function show($id)
    {
        // Kalau relasi sudah dibuat di Model Tenant, kita eager load biar data lengkap
        // Jika relasi belum ada, fallback ke findOrFail biasa (biar tidak error)
        $with = [];

        // cek method relasi ada atau tidak
        if (method_exists(Tenant::class, 'inkubator')) {
            $with[] = 'inkubator';
        }
        if (method_exists(Tenant::class, 'bidangUsaha')) {
            $with[] = 'bidangUsaha';
        }
        if (method_exists(Tenant::class, 'klasifikasiBisnis')) {
            $with[] = 'klasifikasiBisnis';
        }

        // ✅ tambah relasi galeriProduk (galeri produk) dari tabel `produk`
        // Gunakan galeriProduk() untuk menghindari konflik dengan kolom 'produk' di tabel tenant
        if (method_exists(Tenant::class, 'galeriProduk')) {
            $with[] = 'galeriProduk';
        }

        $row = !empty($with)
            ? Tenant::with($with)->findOrFail($id)
            : Tenant::findOrFail($id);

        // =========================
        //  (Query Builder):
        // mapping angka → nama (tanpa bikin model baru)
        // =========================
        $namaBidangUsaha = null;
        if (!empty($row->bidang_usaha_id)) {
            $namaBidangUsaha = DB::table('bidang_usaha')
                ->where('id', $row->bidang_usaha_id)
                ->value('name');
        }

        $namaKlasifikasiBisnis = null;
        if (!empty($row->klasifikasi_bisnis_id)) {
            $namaKlasifikasiBisnis = DB::table('klasifikasi_bisnis')
                ->where('id', $row->klasifikasi_bisnis_id)
                ->value('name');
        }

        // ✅ ambil galeri produk (flatten semua foto dari tabel produk)
        // Normalisasi path: hapus prefix "public/" atau "public\" dan ubah backslash jadi slash
        $galeriProduk = [];
        if ($row && method_exists($row, 'galeriProduk')) {
            foreach ($row->galeriProduk ?? [] as $p) {
                // foto_produk di-cast array di Model Produk
                $arr = $p->foto_produk ?? [];
                
                // Handle jika masih string JSON
                if (is_string($arr)) {
                    $decoded = json_decode($arr, true);
                    $arr = is_array($decoded) ? $decoded : [];
                }
                
                if (is_array($arr)) {
                    foreach ($arr as $path) {
                        if (empty($path)) continue;
                        
                        // Normalisasi path: ubah backslash jadi slash
                        $path = str_replace('\\', '/', $path);
                        
                        // Hapus prefix "public/" atau "public\"
                        $path = preg_replace('~^public/?~', '', $path);
                        
                        // Hapus leading slash
                        $path = ltrim($path, '/');
                        
                        if (!empty($path)) {
                            $galeriProduk[] = $path;
                        }
                    }
                }
            }
        }

        return view('tenant.show', compact('row', 'namaBidangUsaha', 'namaKlasifikasiBisnis', 'galeriProduk'));
    }
}
