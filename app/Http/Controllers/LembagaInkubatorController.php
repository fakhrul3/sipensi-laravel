<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        // ambil param untuk subtitle saja (JS akan handle filter)
        $kodeProv = $request->get('kode_provinsi');

        /**
         * 1) FULL DATASET untuk JS (JANGAN DI-FILTER KODE PROVINSI DI SINI)
         */
        try {
            $allInkubators = Inkubator::select(
                'id',
                'nama_inkubator',
                'jenis_inkubator',
                'kode_provinsi'
            )->get();
        } catch (\Exception $e) {
            $allInkubators = collect();
        }

        /**
         * 2) Variabel $inkubators (opsional) - biar blade kompatibel
         * Kita samakan saja
         */
        $inkubators = $allInkubators;

        /**
         * 3) Nama provinsi untuk subtitle (kalau ada param dari map)
         */
        $namaProvinsi = null;
        if (!empty($kodeProv)) {
            try {
                $namaProvinsi = Provinsi::where('kode_provinsi', $kodeProv)->value('name');
            } catch (\Exception $e) {
                $namaProvinsi = null;
            }
        }

        /**
         * 4) List provinsi untuk dropdown + count
         * NOTE: sekarang pakai query agregasi biar gak N+1
         */
        try {
            $counts = Inkubator::selectRaw('kode_provinsi, COUNT(*) as total')
                ->groupBy('kode_provinsi')
                ->pluck('total', 'kode_provinsi'); // [kode => total]

            $provinsiList = Provinsi::select('kode_provinsi', 'name')
                ->orderBy('name')
                ->get()
                ->map(function ($p) use ($counts) {
                    return [
                        'kode_provinsi' => $p->kode_provinsi,
                        'name' => $p->name,
                        'count' => (int) ($counts[$p->kode_provinsi] ?? 0),
                    ];
                });
        } catch (\Exception $e) {
            $provinsiList = collect();
        }

        $jenisMap = $this->jenisMap();

        // penting: kirim allInkubators supaya blade bisa pakai untuk rows
        return view('lembaga-inkubator.index', compact(
            'allInkubators',
            'inkubators',
            'jenisMap',
            'namaProvinsi',
            'provinsiList'
        ));
    }

    public function show($id)
    {
        $row = Inkubator::findOrFail($id);
        $jenisMap = $this->jenisMap();

        return view('lembaga-inkubator.show', compact('row', 'jenisMap'));
    }
}
