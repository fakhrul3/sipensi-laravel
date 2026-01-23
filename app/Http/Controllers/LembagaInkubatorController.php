<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inkubator;
use App\Models\Provinsi;

// ✅ Tambahin model Tenant (sesuaikan nama model di project lu)
use App\Models\Tenant;

// ✅ Tambahin model Laporan
use App\Models\Laporan;

// ✅ TAMBAHAN: Import model Aktifitas agar bisa dipanggil
use App\Models\Aktifitas;

// ✅ TAMBAHAN: Import model Pemeringkatan
use App\Models\Pemeringkatan;

class LembagaInkubatorController extends Controller
{
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
        $kodeProv = $request->get('kode_provinsi');

        try {
            $allInkubators = Inkubator::select(
                'id',
                'logo',
                'nama_inkubator',
                'jenis_inkubator',
                'kode_provinsi'
            )->get();
        } catch (\Exception $e) {
            $allInkubators = collect();
        }

        $inkubators = $allInkubators;

        $namaProvinsi = null;
        if (!empty($kodeProv)) {
            try {
                $namaProvinsi = Provinsi::where('kode_provinsi', $kodeProv)->value('name');
            } catch (\Exception $e) {
                $namaProvinsi = null;
            }
        }

        try {
            $counts = Inkubator::selectRaw('kode_provinsi, COUNT(*) as total')
                ->groupBy('kode_provinsi')
                ->pluck('total', 'kode_provinsi');

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
        $jenisMap = $this->jenisMap();
        $keyword = null;

        // ✅ TAMBAHAN: Load relasi 'aktifitas' juga di sini
        $row = Inkubator::with(['provinsi', 'kabupaten', 'kecamatan', 'aktifitas'])->findOrFail($id);

        // ✅ ambil tenant by inkubator_id (sesuaikan kolom FK kalau beda)
        $tenant = Tenant::where('inkubator_id', $id)
            ->latest()
            ->get();

        // ✅ ambil laporan dari tabel laporan (pakai Model)
        $laporan = Laporan::where('inkubator_id', $id)
            ->latest()
            ->get();

        /**
         * ✅ Normalisasi supaya siap dipakai viewer (array string path)
         * DB bisa simpan JSON string di kolom path_laporan
         */
        $laporanFiles = [];
        foreach ($laporan as $lp) {
            $val = $lp->path_laporan;

            // kalau path_laporan sudah dicasts (jadi array di model), ini akan langsung array
            if (is_array($val)) {
                foreach ($val as $f) {
                    $laporanFiles[] = preg_replace('#^public[\\\\/]#', '', (string) $f);
                }
                continue;
            }

            // kalau masih string JSON
            if (is_string($val)) {
                $decoded = json_decode($val, true);
                if (is_array($decoded)) {
                    foreach ($decoded as $f) {
                        $laporanFiles[] = preg_replace('#^public[\\\\/]#', '', (string) $f);
                    }
                }
            }
        }

        // ✅ REVISI: ambil pemeringkatan terakhir dari tabel pemeringkatan (tanpa filter grade)
        $grade_terakhir = Pemeringkatan::where('inkubator_id', $id)
            ->orderByDesc('tanggal_sk')   // pakai tanggal_sk biar masuk akal
            ->first();

        return view('lembaga-inkubator.show', compact(
            'row',
            'jenisMap',
            'tenant',
            'keyword',
            'grade_terakhir',
            'laporan',
            'laporanFiles'
        ));
    }

    // ✅ route: inkubators.cari-tenant.detail
    public function cariTenantDetail(Request $request, $id)
    {
        $jenisMap = $this->jenisMap();

        // ✅ TAMBAHAN: Load relasi 'aktifitas' agar galeri tidak hilang saat search tenant
        $row = Inkubator::with(['provinsi', 'kabupaten', 'kecamatan', 'aktifitas'])->findOrFail($id);

        $keyword = trim((string) $request->get('keyword', ''));

        $tenantQuery = Tenant::where('inkubator_id', $id);

        if ($keyword !== '') {
            $tenantQuery->where(function ($q) use ($keyword) {
                $q->where('nama_usaha', 'like', "%{$keyword}%")
                  ->orWhere('alamat', 'like', "%{$keyword}%");
            });
        }

        $tenant = $tenantQuery->latest()->get();

        // ✅ ambil laporan juga biar modal laporan tetap bisa tampil saat search tenant
        $laporan = Laporan::where('inkubator_id', $id)
            ->latest()
            ->get();

        $laporanFiles = [];
        foreach ($laporan as $lp) {
            $val = $lp->path_laporan;

            if (is_array($val)) {
                foreach ($val as $f) {
                    $laporanFiles[] = preg_replace('#^public[\\\\/]#', '', (string) $f);
                }
                continue;
            }

            if (is_string($val)) {
                $decoded = json_decode($val, true);
                if (is_array($decoded)) {
                    foreach ($decoded as $f) {
                        $laporanFiles[] = preg_replace('#^public[\\\\/]#', '', (string) $f);
                    }
                }
            }
        }

        // ✅ REVISI: ambil pemeringkatan terakhir juga saat search tenant (tanpa filter grade)
        $grade_terakhir = Pemeringkatan::where('inkubator_id', $id)
            ->orderByDesc('tanggal_sk')
            ->first();

        return view('lembaga-inkubator.show', compact(
            'row',
            'jenisMap',
            'tenant',
            'keyword',
            'grade_terakhir',
            'laporan',
            'laporanFiles'
        ));
    }
}
