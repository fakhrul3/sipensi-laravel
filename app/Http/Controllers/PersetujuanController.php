<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PersetujuanController extends Controller
{
    /**
     * Menampilkan daftar Persetujuan
     */
    public function index(Request $request)
    {
        try {
            $search = $request->get('search', '');
            
            $query = DB::table('inkubator')
                ->select(
                    'inkubator.id',
                    'inkubator.no_tanda_daftar',
                    'inkubator.nama_inkubator',
                    'inkubator.ganti_nama',
                    'inkubator.ganti_email',
                    'inkubator.email',
                    'inkubator.is_ganti',
                    'inkubator.created_at',
                    'inkubator.updated_at'
                )
                ->whereNotNull('inkubator.no_tanda_daftar')
                // Tampilkan semua yang memiliki pengajuan perubahan atau sudah pernah ada perubahan
                ->where(function($q) {
                    $q->where('inkubator.is_ganti', 1)
                      ->orWhereNotNull('inkubator.ganti_nama')
                      ->orWhereNotNull('inkubator.ganti_email');
                })
                ->orderBy('inkubator.updated_at', 'desc');

            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('inkubator.no_tanda_daftar', 'like', "%{$search}%")
                      ->orWhere('inkubator.nama_inkubator', 'like', "%{$search}%")
                      ->orWhere('inkubator.ganti_nama', 'like', "%{$search}%")
                      ->orWhere('inkubator.email', 'like', "%{$search}%");
                });
            }

            $persetujuans = $query->get();

            \Log::info('Persetujuan count: ' . $persetujuans->count());

            return view('persetujuan.index', compact('persetujuans', 'search'));
        } catch (\Exception $e) {
            \Log::error('Persetujuan Index Error: ' . $e->getMessage());
            return view('persetujuan.index', ['persetujuans' => collect(), 'search' => '']);
        }
    }

    /**
     * Approve perubahan data inkubator
     */
    public function approve($id)
    {
        try {
            $inkubator = DB::table('inkubator')->where('id', $id)->first();
            
            if (!$inkubator) {
                return redirect()->back()->with('error', 'Data inkubator tidak ditemukan');
            }

            $updateData = [];
            
            // Jika ada ganti_nama, update nama_inkubator
            if ($inkubator->ganti_nama) {
                $updateData['nama_inkubator'] = $inkubator->ganti_nama;
                $updateData['ganti_nama'] = null;
            }
            
            // Jika ada ganti_email, update email
            if ($inkubator->ganti_email) {
                $updateData['email'] = $inkubator->ganti_email;
                $updateData['ganti_email'] = null;
            }
            
            // Set is_ganti = 0 (sudah disetujui)
            $updateData['is_ganti'] = 0;
            
            DB::table('inkubator')->where('id', $id)->update($updateData);
            
            \Log::info('Persetujuan approved for inkubator ID: ' . $id);

            return redirect()->back()->with('success', 'Perubahan data berhasil disetujui');
        } catch (\Exception $e) {
            \Log::error('Persetujuan Approve Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menyetujui perubahan data');
        }
    }

    /**
     * Reject perubahan data inkubator
     */
    public function reject($id)
    {
        try {
            $inkubator = DB::table('inkubator')->where('id', $id)->first();
            
            if (!$inkubator) {
                return redirect()->back()->with('error', 'Data inkubator tidak ditemukan');
            }

            // Hapus ganti_nama dan ganti_email, set is_ganti = 0
            DB::table('inkubator')->where('id', $id)->update([
                'ganti_nama' => null,
                'ganti_email' => null,
                'is_ganti' => 0
            ]);
            
            \Log::info('Persetujuan rejected for inkubator ID: ' . $id);

            return redirect()->back()->with('success', 'Perubahan data ditolak');
        } catch (\Exception $e) {
            \Log::error('Persetujuan Reject Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menolak perubahan data');
        }
    }

    /**
     * Show detail pengajuan
     */
    public function show($id)
    {
        try {
            $inkubator = DB::table('inkubator')->where('id', $id)->first();
            
            if (!$inkubator) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $inkubator
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data'
            ], 500);
        }
    }
}
