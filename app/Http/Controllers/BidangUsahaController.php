<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BidangUsahaController extends Controller
{
    /**
     * Menampilkan daftar Bidang Usaha
     */
    public function index(Request $request)
    {
        try {
            $bidangUsahas = DB::table('bidang_usaha')
                ->select('id', 'name', 'created_at', 'updated_at')
                ->orderBy('name', 'asc')
                ->get();

            \Log::info('Bidang Usaha count: ' . $bidangUsahas->count());

            return view('bidang-usaha.index', compact('bidangUsahas'));
        } catch (\Exception $e) {
            \Log::error('Bidang Usaha Index Error: ' . $e->getMessage());
            return view('bidang-usaha.index', ['bidangUsahas' => collect()]);
        }
    }

    // ========== CRUD BIDANG USAHA ==========
    
    public function store(Request $request)
    {
        try {
            \Log::info('Bidang Usaha Store Request: ', $request->all());
            
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:bidang_usaha,name',
            ]);

            \Log::info('Validated Data: ', $validated);

            $id = DB::table('bidang_usaha')->insertGetId($validated);
            
            \Log::info('Bidang Usaha created with ID: ' . $id);

            return response()->json([
                'success' => true,
                'message' => 'Bidang usaha berhasil ditambahkan',
                'id' => $id
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation Error: ', $e->errors());
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Bidang Usaha Store Error: ' . $e->getMessage());
            \Log::error('Stack Trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan bidang usaha: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $bidangUsaha = DB::table('bidang_usaha')->where('id', $id)->first();
            if (!$bidangUsaha) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bidang usaha tidak ditemukan'
                ], 404);
            }
            return response()->json([
                'success' => true,
                'data' => $bidangUsaha
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bidang usaha tidak ditemukan'
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $bidangUsaha = DB::table('bidang_usaha')->where('id', $id)->first();
            if (!$bidangUsaha) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bidang usaha tidak ditemukan'
                ], 404);
            }

            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:bidang_usaha,name,' . $id,
            ]);

            DB::table('bidang_usaha')->where('id', $id)->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Bidang usaha berhasil diupdate'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Bidang Usaha Update Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate bidang usaha: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $bidangUsaha = DB::table('bidang_usaha')->where('id', $id)->first();
            if (!$bidangUsaha) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bidang usaha tidak ditemukan'
                ], 404);
            }

            DB::table('bidang_usaha')->where('id', $id)->delete();

            return response()->json([
                'success' => true,
                'message' => 'Bidang usaha berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            \Log::error('Bidang Usaha Destroy Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus bidang usaha: ' . $e->getMessage()
            ], 500);
        }
    }
}
