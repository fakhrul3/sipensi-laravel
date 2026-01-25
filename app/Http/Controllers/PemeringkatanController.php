<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class PemeringkatanController extends Controller
{
    /**
     * Menampilkan daftar Pemeringkatan
     */
    public function index(Request $request)
    {
        try {
            $search = $request->get('search', '');
            
            $query = DB::table('pemeringkatan')
                ->join('inkubator', 'pemeringkatan.inkubator_id', '=', 'inkubator.id')
                ->select(
                    'pemeringkatan.id',
                    'pemeringkatan.inkubator_id',
                    'pemeringkatan.grade',
                    'pemeringkatan.tanggal_sk',
                    'pemeringkatan.masa_berlaku_sk',
                    'pemeringkatan.tanggal_habis_sk',
                    'pemeringkatan.file_pemeringkatan',
                    'pemeringkatan.file_pengelola',
                    'pemeringkatan.file_profil_lembaga',
                    'pemeringkatan.file_sk_pemeringkatan',
                    'pemeringkatan.file_bisnis_model',
                    'pemeringkatan.file_sarana_prasarana',
                    'pemeringkatan.status',
                    'pemeringkatan.catatan',
                    'pemeringkatan.created_at',
                    'pemeringkatan.updated_at',
                    'inkubator.no_tanda_daftar',
                    'inkubator.nama_inkubator'
                )
                ->whereNotNull('inkubator.no_tanda_daftar')
                ->orderBy('pemeringkatan.updated_at', 'desc');

            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('inkubator.no_tanda_daftar', 'like', "%{$search}%")
                      ->orWhere('inkubator.nama_inkubator', 'like', "%{$search}%")
                      ->orWhere('pemeringkatan.grade', 'like', "%{$search}%");
                });
            }

            $pemeringkatans = $query->get();

            \Log::info('Pemeringkatan count: ' . $pemeringkatans->count());

            return view('pemeringkatan.index', compact('pemeringkatans', 'search'));
        } catch (\Exception $e) {
            \Log::error('Pemeringkatan Index Error: ' . $e->getMessage());
            return view('pemeringkatan.index', ['pemeringkatans' => collect(), 'search' => '']);
        }
    }

    /**
     * Show detail pemeringkatan
     */
    public function show($id)
    {
        try {
            $pemeringkatan = DB::table('pemeringkatan')
                ->join('inkubator', 'pemeringkatan.inkubator_id', '=', 'inkubator.id')
                ->select(
                    'pemeringkatan.*',
                    'inkubator.no_tanda_daftar',
                    'inkubator.nama_inkubator'
                )
                ->where('pemeringkatan.id', $id)
                ->first();
            
            if (!$pemeringkatan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $pemeringkatan
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data'
            ], 500);
        }
    }

    /**
     * Approve pemeringkatan
     */
    public function approve(Request $request, $id)
    {
        try {
            $pemeringkatan = DB::table('pemeringkatan')->where('id', $id)->first();
            
            if (!$pemeringkatan) {
                return redirect()->back()->with('error', 'Data pemeringkatan tidak ditemukan');
            }

            $validated = $request->validate([
                'grade' => 'required|string|max:10',
                'tanggal_sk' => 'required|date',
                'masa_berlaku_sk' => 'required|integer|min:1',
                'tanggal_habis_sk' => 'required|date|after:tanggal_sk',
            ]);

            // Update pemeringkatan
            DB::table('pemeringkatan')->where('id', $id)->update([
                'grade' => $validated['grade'],
                'tanggal_sk' => $validated['tanggal_sk'],
                'masa_berlaku_sk' => $validated['masa_berlaku_sk'],
                'tanggal_habis_sk' => $validated['tanggal_habis_sk'],
                'status' => 1, // Disetujui
            ]);

            // Update inkubator dengan grade terbaru
            DB::table('inkubator')->where('id', $pemeringkatan->inkubator_id)->update([
                'pemeringkatan_rank' => $validated['grade'],
            ]);
            
            \Log::info('Pemeringkatan approved for ID: ' . $id);

            return redirect()->back()->with('success', 'Pemeringkatan berhasil disetujui');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Pemeringkatan Approve Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menyetujui pemeringkatan');
        }
    }

    /**
     * Reject pemeringkatan
     */
    public function reject($id)
    {
        try {
            $pemeringkatan = DB::table('pemeringkatan')->where('id', $id)->first();
            
            if (!$pemeringkatan) {
                return redirect()->back()->with('error', 'Data pemeringkatan tidak ditemukan');
            }

            // Set status = 0 (ditolak) atau bisa juga dihapus
            DB::table('pemeringkatan')->where('id', $id)->update([
                'status' => 0,
            ]);
            
            \Log::info('Pemeringkatan rejected for ID: ' . $id);

            return redirect()->back()->with('success', 'Pemeringkatan ditolak');
        } catch (\Exception $e) {
            \Log::error('Pemeringkatan Reject Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menolak pemeringkatan');
        }
    }

    /**
     * Download file pemeringkatan
     */
    public function downloadFile($id, $type)
    {
        try {
            $pemeringkatan = DB::table('pemeringkatan')->where('id', $id)->first();
            
            if (!$pemeringkatan) {
                return redirect()->back()->with('error', 'Data tidak ditemukan');
            }

            $fileFieldMap = [
                'pemeringkatan' => 'file_pemeringkatan',
                'pengelola' => 'file_pengelola',
                'profil-lembaga' => 'file_profil_lembaga',
                'sk' => 'file_sk_pemeringkatan',
                'bisnis-model' => 'file_bisnis_model',
                'sarana-prasarana' => 'file_sarana_prasarana',
            ];

            if (!isset($fileFieldMap[$type])) {
                return redirect()->back()->with('error', 'Tipe file tidak valid');
            }

            $fileField = $fileFieldMap[$type];
            $fileName = $pemeringkatan->$fileField;

            if (!$fileName) {
                return redirect()->back()->with('error', 'File tidak ditemukan');
            }

            $filePath = $this->getFilePath($fileName);
            
            if (!file_exists($filePath)) {
                return redirect()->back()->with('error', 'File tidak ditemukan di server');
            }

            return Response::download($filePath, $fileName);
        } catch (\Exception $e) {
            \Log::error('Download File Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengunduh file');
        }
    }

    /**
     * Helper untuk mendapatkan path file
     */
    private function getFilePath($path)
    {
        // Normalisasi path
        $cleanPath = ltrim(str_replace('public/', '', $path), '/');
        
        // Cek berbagai kemungkinan lokasi file
        $possiblePaths = [
            public_path('pemeringkatan/' . basename($cleanPath)),
            public_path('storage/pemeringkatan/' . basename($cleanPath)),
            storage_path('app/public/pemeringkatan/' . basename($cleanPath)),
            storage_path('app/pemeringkatan/' . basename($cleanPath)),
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
        return $possiblePaths[0];
    }
}
