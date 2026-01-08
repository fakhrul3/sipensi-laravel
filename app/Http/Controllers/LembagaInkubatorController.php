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
    $query = \App\Models\Inkubator::query();

    // Filter berdasarkan kode_provinsi
    if ($request->filled('kode_provinsi')) {
        $query->where('kode_provinsi', $request->kode_provinsi);
    }

    $inkubators = $query->get();

    // TAMBAHKAN INI: Ambil nama provinsi kalau ada filternya
    $namaProvinsi = null;
    if ($request->filled('kode_provinsi')) {
        $namaProvinsi = \App\Models\Provinsi::where('kode_provinsi', $request->kode_provinsi)->value('name');
    }

    $jenisMap = $this->jenisMap();

    // Masukkan $namaProvinsi ke compact
    return view('lembaga-inkubator.index', compact('inkubators', 'jenisMap', 'namaProvinsi'));
}

    public function show($id)
    {
        // Ambil data asli dari database berdasarkan ID
        $row = Inkubator::findOrFail($id);

        $jenisMap = $this->jenisMap();

        return view('lembaga-inkubator.show', compact('row', 'jenisMap'));
    }
}