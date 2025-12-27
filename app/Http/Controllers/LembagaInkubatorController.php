<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LembagaInkubatorController extends Controller
{
    /**
     * Mock dataset biar index & show konsisten.
     */
    private function mockRows()
    {
        return collect([
            (object)['id'=>1,'nama'=>'Inkubator Bisnis PPNS','jenis'=>3],
            (object)['id'=>2,'nama'=>'INKUBATOR DAN LAYANAN BISNIS INOVATIF ITS','jenis'=>3],
            (object)['id'=>3,'nama'=>'INBIS ASIA MALANG (INC.85 ASIA)','jenis'=>3],
            (object)['id'=>4,'nama'=>'INKUBATOR BISNIS UNIVERSITAS HINDU INDONESIA','jenis'=>3],
            (object)['id'=>5,'nama'=>'Direktorat Inkubasi Bisnis Teknologi dan Science Techno Park Unhas','jenis'=>3],
            (object)['id'=>6,'nama'=>'INKUBATOR UNIT BISNIS LPPM UNNES','jenis'=>3],
            (object)['id'=>7,'nama'=>'INKUBATOR BISNIS PRIMAKARA','jenis'=>3],
            (object)['id'=>8,'nama'=>'INKUBATOR BISNIS DAN TEKNOLOGI UPTD KST SOLO TECHNOPARK','jenis'=>2],
            (object)['id'=>9,'nama'=>'Direktorat Pengembangan Usaha','jenis'=>3],
            (object)['id'=>10,'nama'=>'INKUBATOR BISNIS TEKNOLOGI TECHNOPARK','jenis'=>3],
            (object)['id'=>11,'nama'=>'INKUBATOR BISNIS TRILOGI','jenis'=>3],
            (object)['id'=>12,'nama'=>'Inkubator Bisnis Inovasi Produk Kelautan dan Perikanan (Inbis Invapro-KP) BBP3KP','jenis'=>1],
            (object)['id'=>13,'nama'=>'Skystar Ventures','jenis'=>3],
            (object)['id'=>14,'nama'=>'Cimahi Technopark','jenis'=>2],
            (object)['id'=>15,'nama'=>'LKST IPB','jenis'=>3],
            (object)['id'=>16,'nama'=>'Link Productive','jenis'=>3],
            (object)['id'=>17,'nama'=>'INKUBATOR BISNIS TEKNOLOGI (INBISTEK) UNIVERSITAS ANDALAS','jenis'=>3],
            (object)['id'=>18,'nama'=>'INKUBATOR BISNIS UIN BANDUNG','jenis'=>3],
            (object)['id'=>19,'nama'=>'ACARYABHIRAMA','jenis'=>5],
            (object)['id'=>20,'nama'=>'LEMBAGA INKUBATOR BISNIS LASINRANG','jenis'=>2],
        ]);
    }

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
        /**
         * ==========================================================
         * SLOT QUERY (aktifkan kalau tabel sudah siap)
         * ==========================================================
         */
        /*
        $rows = DB::table('lembaga_inkubator')
            ->select('id','nama','jenis')
            ->when($request->q, fn($q) =>
                $q->where('nama', 'like', '%' . $request->q . '%')
            )
            ->when($request->jenis, fn($q) =>
                $q->where('jenis', (int)$request->jenis)
            )
            ->orderBy('nama')
            ->paginate(15);
        */

        // MOCK DATA
        $rows = $this->mockRows();

        $jenisMap = $this->jenisMap();

        return view('lembaga-inkubator.index', compact('rows','jenisMap'));
    }

    public function show($id)
    {
        /**
         * SLOT QUERY DETAIL (aktifkan nanti kalau DB sudah siap)
         */
        /*
        $row = DB::table('lembaga_inkubator')->where('id', $id)->first();
        abort_if(!$row, 404);
        */

        // MOCK DETAIL: ambil dari dataset supaya nama sesuai
        $rows = $this->mockRows();
        $base = $rows->firstWhere('id', (int)$id);
        abort_if(!$base, 404);

        // gabungkan data detail (yang belum ada di list)
        $row = (object) [
            'id'       => $base->id,
            'nama'     => $base->nama,    // ✅ ini yang kamu mau: nama inkubator
            'jenis'    => $base->jenis,
            'provinsi' => 'Jawa Timur',
            'alamat'   => '-',
            'website'  => null,           // biar blade kamu tampil "-" lewat if
            'kontak'   => null,
        ];

        $jenisMap = $this->jenisMap();

        return view('lembaga-inkubator.show', compact('row','jenisMap'));
    }
}
