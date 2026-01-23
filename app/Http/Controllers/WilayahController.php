<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Provinsi;

class WilayahController extends Controller
{
    /**
     * Menampilkan daftar Provinsi
     */
    public function provinsiIndex(Request $request)
    {
        try {
            // For now, disable cache for testing to see fresh data
            // $provinsis = cache()->remember('provinsi_list', 300, function () {
            //     try {
            //         return DB::table('provinsi')
            //             ->select('id', 'kode_provinsi', 'name', 'latitude', 'longitude', 'created_at', 'updated_at')
            //             ->orderBy('kode_provinsi', 'asc')
            //             ->get();
            //     } catch (\Exception $e) {
            //         \Log::error('Provinsi List Error: ' . $e->getMessage());
            //         return collect();
            //     }
            // });

            // Direct query for now to see fresh data
            $provinsis = DB::table('provinsi')
                ->select('id', 'kode_provinsi', 'name', 'latitude', 'longitude', 'created_at', 'updated_at')
                ->orderBy('kode_provinsi', 'asc')
                ->get();

            \Log::info('Provinsi count: ' . $provinsis->count());

            return view('wilayah.provinsi.index', compact('provinsis'));
        } catch (\Exception $e) {
            \Log::error('Wilayah Provinsi Index Error: ' . $e->getMessage());
            return view('wilayah.provinsi.index', ['provinsis' => collect()]);
        }
    }

    /**
     * Menampilkan daftar Kabupaten
     */
    public function kabupatenIndex(Request $request)
    {
        try {
            // Direct query for now to see fresh data
            $kabupatens = DB::table('kabupaten')
                ->select('id', 'kode_kabupaten', 'provinsi_id', 'name', 'created_at', 'updated_at')
                ->orderBy('kode_kabupaten', 'asc')
                ->get();

            // Ambil list provinsi untuk dropdown
            $provinsis = DB::table('provinsi')->select('id', 'name')->orderBy('name', 'asc')->get();

            \Log::info('Kabupaten count: ' . $kabupatens->count());

            return view('wilayah.kabupaten.index', compact('kabupatens', 'provinsis'));
        } catch (\Exception $e) {
            \Log::error('Wilayah Kabupaten Index Error: ' . $e->getMessage());
            return view('wilayah.kabupaten.index', ['kabupatens' => collect(), 'provinsis' => collect()]);
        }
    }

    /**
     * Menampilkan daftar Kecamatan
     */
    public function kecamatanIndex(Request $request)
    {
        try {
            // Direct query for now to see fresh data
            $kecamatans = DB::table('kecamatan')
                ->select('id', 'kode_kecamatan', 'kabupaten_id', 'name', 'created_at', 'updated_at')
                ->orderBy('kode_kecamatan', 'asc')
                ->get();

            // Ambil list kabupaten untuk dropdown
            $kabupatens = DB::table('kabupaten')->select('id', 'name')->orderBy('name', 'asc')->get();

            \Log::info('Kecamatan count: ' . $kecamatans->count());

            return view('wilayah.kecamatan.index', compact('kecamatans', 'kabupatens'));
        } catch (\Exception $e) {
            \Log::error('Wilayah Kecamatan Index Error: ' . $e->getMessage());
            return view('wilayah.kecamatan.index', ['kecamatans' => collect(), 'kabupatens' => collect()]);
        }
    }

    // ========== CRUD PROVINSI ==========
    
    public function provinsiStore(Request $request)
    {
        try {
            \Log::info('Provinsi Store Request: ', $request->all());
            
            $validated = $request->validate([
                'kode_provinsi' => 'required|string|unique:provinsi,kode_provinsi',
                'name' => 'required|string|max:255',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
            ]);

            \Log::info('Validated Data: ', $validated);

            // Clear empty strings to null for nullable fields
            if (isset($validated['latitude']) && $validated['latitude'] === '') {
                $validated['latitude'] = null;
            }
            if (isset($validated['longitude']) && $validated['longitude'] === '') {
                $validated['longitude'] = null;
            }

            $id = DB::table('provinsi')->insertGetId($validated);
            
            \Log::info('Provinsi created with ID: ' . $id);
            
            // Clear all related caches
            cache()->forget('provinsi_list');
            cache()->forget('sebaran_inkubator');
            cache()->forget('total_lembaga');

            return response()->json([
                'success' => true,
                'message' => 'Provinsi berhasil ditambahkan',
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
            \Log::error('Provinsi Store Error: ' . $e->getMessage());
            \Log::error('Stack Trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan provinsi: ' . $e->getMessage()
            ], 500);
        }
    }

    public function provinsiShow($id)
    {
        try {
            $provinsi = DB::table('provinsi')->where('id', $id)->first();
            if (!$provinsi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Provinsi tidak ditemukan'
                ], 404);
            }
            return response()->json([
                'success' => true,
                'data' => $provinsi
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Provinsi tidak ditemukan'
            ], 404);
        }
    }

    public function provinsiUpdate(Request $request, $id)
    {
        try {
            $provinsi = DB::table('provinsi')->where('id', $id)->first();
            if (!$provinsi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Provinsi tidak ditemukan'
                ], 404);
            }

            $validated = $request->validate([
                'kode_provinsi' => 'required|string|unique:provinsi,kode_provinsi,' . $id,
                'name' => 'required|string|max:255',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
            ]);

            DB::table('provinsi')->where('id', $id)->update($validated);
            
            cache()->forget('provinsi_list');

            return response()->json([
                'success' => true,
                'message' => 'Provinsi berhasil diupdate'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Provinsi Update Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate provinsi: ' . $e->getMessage()
            ], 500);
        }
    }

    public function provinsiDestroy($id)
    {
        try {
            $provinsi = DB::table('provinsi')->where('id', $id)->first();
            if (!$provinsi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Provinsi tidak ditemukan'
                ], 404);
            }

            DB::table('provinsi')->where('id', $id)->delete();
            
            cache()->forget('provinsi_list');

            return response()->json([
                'success' => true,
                'message' => 'Provinsi berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            \Log::error('Provinsi Destroy Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus provinsi: ' . $e->getMessage()
            ], 500);
        }
    }

    // ========== CRUD KABUPATEN ==========
    
    public function kabupatenStore(Request $request)
    {
        try {
            $validated = $request->validate([
                'kode_kabupaten' => 'required|string|unique:kabupaten,kode_kabupaten',
                'provinsi_id' => 'required|integer|exists:provinsi,id',
                'name' => 'required|string|max:255',
            ]);

            $id = DB::table('kabupaten')->insertGetId($validated);
            
            cache()->forget('kabupaten_list');

            return response()->json([
                'success' => true,
                'message' => 'Kabupaten berhasil ditambahkan',
                'id' => $id
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Kabupaten Store Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan kabupaten: ' . $e->getMessage()
            ], 500);
        }
    }

    public function kabupatenShow($id)
    {
        try {
            $kabupaten = DB::table('kabupaten')->where('id', $id)->first();
            if (!$kabupaten) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kabupaten tidak ditemukan'
                ], 404);
            }
            return response()->json([
                'success' => true,
                'data' => $kabupaten
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Kabupaten tidak ditemukan'
            ], 404);
        }
    }

    public function kabupatenUpdate(Request $request, $id)
    {
        try {
            $kabupaten = DB::table('kabupaten')->where('id', $id)->first();
            if (!$kabupaten) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kabupaten tidak ditemukan'
                ], 404);
            }

            $validated = $request->validate([
                'kode_kabupaten' => 'required|string|unique:kabupaten,kode_kabupaten,' . $id,
                'provinsi_id' => 'required|integer|exists:provinsi,id',
                'name' => 'required|string|max:255',
            ]);

            DB::table('kabupaten')->where('id', $id)->update($validated);
            
            cache()->forget('kabupaten_list');

            return response()->json([
                'success' => true,
                'message' => 'Kabupaten berhasil diupdate'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Kabupaten Update Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate kabupaten: ' . $e->getMessage()
            ], 500);
        }
    }

    public function kabupatenDestroy($id)
    {
        try {
            $kabupaten = DB::table('kabupaten')->where('id', $id)->first();
            if (!$kabupaten) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kabupaten tidak ditemukan'
                ], 404);
            }

            DB::table('kabupaten')->where('id', $id)->delete();
            
            cache()->forget('kabupaten_list');

            return response()->json([
                'success' => true,
                'message' => 'Kabupaten berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            \Log::error('Kabupaten Destroy Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus kabupaten: ' . $e->getMessage()
            ], 500);
        }
    }

    // ========== CRUD KECAMATAN ==========
    
    public function kecamatanStore(Request $request)
    {
        try {
            $validated = $request->validate([
                'kode_kecamatan' => 'required|string|unique:kecamatan,kode_kecamatan',
                'kabupaten_id' => 'required|integer|exists:kabupaten,id',
                'name' => 'required|string|max:255',
            ]);

            \Log::info('Kecamatan Store Request: ', $request->all());
            \Log::info('Validated Data: ', $validated);

            $id = DB::table('kecamatan')->insertGetId($validated);
            
            \Log::info('Kecamatan created with ID: ' . $id);
            
            // Clear all related caches
            cache()->forget('kecamatan_list');

            return response()->json([
                'success' => true,
                'message' => 'Kecamatan berhasil ditambahkan',
                'id' => $id
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Kecamatan Store Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan kecamatan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function kecamatanShow($id)
    {
        try {
            $kecamatan = DB::table('kecamatan')->where('id', $id)->first();
            if (!$kecamatan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kecamatan tidak ditemukan'
                ], 404);
            }
            return response()->json([
                'success' => true,
                'data' => $kecamatan
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Kecamatan tidak ditemukan'
            ], 404);
        }
    }

    public function kecamatanUpdate(Request $request, $id)
    {
        try {
            $kecamatan = DB::table('kecamatan')->where('id', $id)->first();
            if (!$kecamatan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kecamatan tidak ditemukan'
                ], 404);
            }

            $validated = $request->validate([
                'kode_kecamatan' => 'required|string|unique:kecamatan,kode_kecamatan,' . $id,
                'kabupaten_id' => 'required|integer|exists:kabupaten,id',
                'name' => 'required|string|max:255',
            ]);

            DB::table('kecamatan')->where('id', $id)->update($validated);
            
            cache()->forget('kecamatan_list');

            return response()->json([
                'success' => true,
                'message' => 'Kecamatan berhasil diupdate'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Kecamatan Update Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate kecamatan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function kecamatanDestroy($id)
    {
        try {
            $kecamatan = DB::table('kecamatan')->where('id', $id)->first();
            if (!$kecamatan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kecamatan tidak ditemukan'
                ], 404);
            }

            DB::table('kecamatan')->where('id', $id)->delete();
            
            cache()->forget('kecamatan_list');

            return response()->json([
                'success' => true,
                'message' => 'Kecamatan berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            \Log::error('Kecamatan Destroy Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus kecamatan: ' . $e->getMessage()
            ], 500);
        }
    }
}
