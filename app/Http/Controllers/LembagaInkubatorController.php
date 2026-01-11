<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// Pastikan Import Model Inkubator kamu, asumsi namanya 'Inkubator'
use App\Models\Inkubator; 
use App\Models\Provinsi;

class LembagaInkubatorController extends Controller
{
    /**
     * Mapping badge untuk UI
     */
    private function jenisMap()
    {
        return [
            1 => ['label' => 'Pemerintah Pusat',   'badge' => 'badge-pusat'],
            2 => ['label' => 'Pemerintah Daerah',  'badge' => 'badge-pemda'],
            3 => ['label' => 'Lembaga Pendidikan', 'badge' => 'badge-pendidikan'],
            4 => ['label' => 'Badan Usaha',        'badge' => 'badge-usaha'],
            5 => ['label' => 'Masyarakat',         'badge' => 'badge-masyarakat'],
        ];
    }

    public function index(Request $request)
    {
        try {
            // Select hanya kolom yang diperlukan untuk performa
            $query = Inkubator::select(
                'id', 'nama_inkubator', 'jenis_inkubator', 'kode_provinsi', 
                'provinsi_id', 'logo', 'alamat_kantor', 'no_kontak', 'email', 'website'
            );

            // Filter berdasarkan kode_provinsi
            if ($request->filled('kode_provinsi')) {
                $query->where('kode_provinsi', $request->kode_provinsi);
            }

            // Ambil semua data (tidak pakai pagination di server, karena pagination di client-side)
            $inkubators = $query->get();

            // TAMBAHKAN INI: Ambil nama provinsi kalau ada filternya
            $namaProvinsi = null;
            if ($request->filled('kode_provinsi')) {
                try {
                    $namaProvinsi = Provinsi::where('kode_provinsi', $request->kode_provinsi)
                        ->value('name');
                } catch (\Exception $e) {
                    $namaProvinsi = null;
                }
            }
        } catch (\Exception $e) {
            $inkubators = collect();
            $namaProvinsi = null;
        }

        // Ambil daftar provinsi untuk dropdown
        try {
            $provinsiList = Provinsi::select('kode_provinsi', 'name')
                ->orderBy('name')
                ->get()
                ->map(function ($p) {
                    $count = Inkubator::where('kode_provinsi', $p->kode_provinsi)->count();
                    return [
                        'kode_provinsi' => $p->kode_provinsi,
                        'name' => $p->name,
                        'count' => $count
                    ];
                });
        } catch (\Exception $e) {
            $provinsiList = collect();
        }

        $jenisMap = $this->jenisMap();

        // Masukkan $namaProvinsi dan $provinsiList ke compact
        return view('lembaga-inkubator.index', compact('inkubators', 'jenisMap', 'namaProvinsi', 'provinsiList'));
    }

    public function show($id)
    {
        // Ambil data asli dari database berdasarkan ID
        $row = Inkubator::findOrFail($id);

        $jenisMap = $this->jenisMap();

        return view('lembaga-inkubator.show', compact('row', 'jenisMap'));
    }
}