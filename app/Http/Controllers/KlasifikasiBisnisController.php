<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KlasifikasiBisnisController extends Controller
{
    /**
     * Menampilkan daftar Klasifikasi Bisnis
     */
    public function index(Request $request)
    {
        try {
            $klasifikasiBisniss = DB::table('klasifikasi_bisnis')
                ->select('id', 'name', 'created_at', 'updated_at')
                ->orderBy('name', 'asc')
                ->get();

            \Log::info('Klasifikasi Bisnis count: ' . $klasifikasiBisniss->count());

            return view('klasifikasi-bisnis.index', compact('klasifikasiBisniss'));
        } catch (\Exception $e) {
            \Log::error('Klasifikasi Bisnis Index Error: ' . $e->getMessage());
            return view('klasifikasi-bisnis.index', ['klasifikasiBisniss' => collect()]);
        }
    }

    // ========== CRUD KLASIFIKASI BISNIS ==========
    
    public function store(Request $request)
    {
        try {
            \Log::info('Klasifikasi Bisnis Store Request: ', $request->all());
            
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:klasifikasi_bisnis,name',
            ]);

            \Log::info('Validated Data: ', $validated);

            $id = DB::table('klasifikasi_bisnis')->insertGetId($validated);
            
            \Log::info('Klasifikasi Bisnis created with ID: ' . $id);

            return response()->json([
                'success' => true,
                'message' => 'Klasifikasi bisnis berhasil ditambahkan',
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
            \Log::error('Klasifikasi Bisnis Store Error: ' . $e->getMessage());
            \Log::error('Stack Trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan klasifikasi bisnis: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $klasifikasiBisnis = DB::table('klasifikasi_bisnis')->where('id', $id)->first();
            if (!$klasifikasiBisnis) {
                return response()->json([
                    'success' => false,
                    'message' => 'Klasifikasi bisnis tidak ditemukan'
                ], 404);
            }
            return response()->json([
                'success' => true,
                'data' => $klasifikasiBisnis
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Klasifikasi bisnis tidak ditemukan'
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $klasifikasiBisnis = DB::table('klasifikasi_bisnis')->where('id', $id)->first();
            if (!$klasifikasiBisnis) {
                return response()->json([
                    'success' => false,
                    'message' => 'Klasifikasi bisnis tidak ditemukan'
                ], 404);
            }

            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:klasifikasi_bisnis,name,' . $id,
            ]);

            DB::table('klasifikasi_bisnis')->where('id', $id)->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Klasifikasi bisnis berhasil diupdate'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Klasifikasi Bisnis Update Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate klasifikasi bisnis: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $klasifikasiBisnis = DB::table('klasifikasi_bisnis')->where('id', $id)->first();
            if (!$klasifikasiBisnis) {
                return response()->json([
                    'success' => false,
                    'message' => 'Klasifikasi bisnis tidak ditemukan'
                ], 404);
            }

            DB::table('klasifikasi_bisnis')->where('id', $id)->delete();

            return response()->json([
                'success' => true,
                'message' => 'Klasifikasi bisnis berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            \Log::error('Klasifikasi Bisnis Destroy Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus klasifikasi bisnis: ' . $e->getMessage()
            ], 500);
        }
    }
}
