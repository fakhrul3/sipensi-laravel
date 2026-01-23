<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class LaporanController extends Controller
{
    /**
     * Menampilkan daftar Laporan Lembaga Inkubator
     */
    public function index(Request $request)
    {
        try {
            $search = $request->get('search', '');
            
            $query = DB::table('laporan')
                ->join('inkubator', 'laporan.inkubator_id', '=', 'inkubator.id')
                ->select(
                    'laporan.id',
                    'laporan.inkubator_id',
                    'laporan.path_laporan',
                    'laporan.nama_laporan',
                    'laporan.path_lampiran',
                    'laporan.tgl_laporan',
                    'laporan.bulan_laporan',
                    'laporan.created_at',
                    'laporan.updated_at',
                    'inkubator.nama_inkubator'
                )
                ->orderBy('laporan.tgl_laporan', 'desc')
                ->orderBy('laporan.created_at', 'desc');

            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('inkubator.nama_inkubator', 'like', "%{$search}%")
                      ->orWhere('laporan.nama_laporan', 'like', "%{$search}%")
                      ->orWhere('laporan.bulan_laporan', 'like', "%{$search}%");
                });
            }

            $laporans = $query->get();

            \Log::info('Laporan count: ' . $laporans->count());

            return view('laporan.index', compact('laporans', 'search'));
        } catch (\Exception $e) {
            \Log::error('Laporan Index Error: ' . $e->getMessage());
            return view('laporan.index', ['laporans' => collect(), 'search' => '']);
        }
    }

    /**
     * Download file laporan
     */
    public function downloadLaporan($id)
    {
        try {
            $laporan = DB::table('laporan')->where('id', $id)->first();
            
            if (!$laporan || !$laporan->path_laporan) {
                return redirect()->back()->with('error', 'File laporan tidak ditemukan');
            }

            $filePath = $this->getFilePath($laporan->path_laporan);
            
            if (!file_exists($filePath)) {
                return redirect()->back()->with('error', 'File tidak ditemukan di server');
            }

            return Response::download($filePath, $laporan->nama_laporan . '.pdf');
        } catch (\Exception $e) {
            \Log::error('Download Laporan Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengunduh file');
        }
    }

    /**
     * Download file lampiran
     */
    public function downloadLampiran($id)
    {
        try {
            $laporan = DB::table('laporan')->where('id', $id)->first();
            
            if (!$laporan || !$laporan->path_lampiran) {
                return redirect()->back()->with('error', 'File lampiran tidak ditemukan');
            }

            $filePath = $this->getFilePath($laporan->path_lampiran);
            
            if (!file_exists($filePath)) {
                return redirect()->back()->with('error', 'File tidak ditemukan di server');
            }

            return Response::download($filePath, 'Lampiran_' . $laporan->nama_laporan . '.pdf');
        } catch (\Exception $e) {
            \Log::error('Download Lampiran Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengunduh file');
        }
    }

    /**
     * Helper untuk mendapatkan path file
     */
    private function getFilePath($path)
    {
        // Normalisasi path - hapus 'public/' jika ada
        $cleanPath = ltrim(str_replace('public/', '', $path), '/');
        
        // Cek berbagai kemungkinan lokasi file
        $possiblePaths = [
            public_path('laporan/' . basename($cleanPath)),
            public_path('storage/laporan/' . basename($cleanPath)),
            storage_path('app/public/laporan/' . basename($cleanPath)),
            storage_path('app/laporan/' . basename($cleanPath)),
            public_path($cleanPath),
            storage_path('app/public/' . basename($cleanPath)),
            storage_path('app/' . basename($cleanPath)),
        ];

        foreach ($possiblePaths as $possiblePath) {
            if (file_exists($possiblePath)) {
                \Log::info('File found at: ' . $possiblePath);
                return $possiblePath;
            }
        }

        \Log::warning('File not found for path: ' . $path);
        // Jika tidak ditemukan, return path pertama sebagai fallback
        return $possiblePaths[0];
    }
}
