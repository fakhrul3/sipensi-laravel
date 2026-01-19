<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\ManajemenGambar;

class ManajemenGambarController extends Controller
{
    /**
     * Menampilkan daftar Manajemen Gambar
     */
    public function index(Request $request)
    {
        try {
            $query = DB::table('manajemen_gambar')
                ->select('id', 'option_gambar', 'path_gambar', 'is_show', 'created_at', 'updated_at')
                ->orderBy('created_at', 'desc');

            // Search functionality
            if ($request->filled('search')) {
                $searchTerm = '%' . $request->search . '%';
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('option_gambar', 'like', $searchTerm);
                });
            }

            $gambars = $query->get();

            return view('manajemen-gambar.index', compact('gambars'));
        } catch (\Exception $e) {
            \Log::error('Manajemen Gambar Index Error: ' . $e->getMessage());
            return view('manajemen-gambar.index', ['gambars' => collect()])->with('error', 'Gagal memuat data gambar: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan detail gambar
     */
    public function show($id)
    {
        try {
            $gambar = DB::table('manajemen_gambar')->where('id', $id)->first();

            if (!$gambar) {
                return response()->json(['success' => false, 'message' => 'Gambar tidak ditemukan'], 404);
            }

            return response()->json(['success' => true, 'data' => $gambar]);
        } catch (\Exception $e) {
            \Log::error('Manajemen Gambar Show Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data gambar'], 500);
        }
    }

    /**
     * Menyimpan gambar baru
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'option_gambar' => 'required|string|max:255',
                'path_gambar' => 'nullable|string|max:500',
                'is_show' => 'boolean',
            ]);

            // Handle file upload jika ada
            if ($request->hasFile('gambar')) {
                $file = $request->file('gambar');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('public/manajemen_gambar', $filename);
                $validated['path_gambar'] = str_replace('public/', '', $path);
            }

            $validated['is_show'] = $request->has('is_show') ? 1 : 0;

            $id = DB::table('manajemen_gambar')->insertGetId($validated);

            // Clear cache
            cache()->forget('carousel_data');

            return response()->json([
                'success' => true,
                'message' => 'Gambar berhasil ditambahkan',
                'id' => $id
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Manajemen Gambar Store Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan gambar: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update gambar
     */
    public function update(Request $request, $id)
    {
        try {
            $gambar = DB::table('manajemen_gambar')->where('id', $id)->first();

            if (!$gambar) {
                return response()->json(['success' => false, 'message' => 'Gambar tidak ditemukan'], 404);
            }

            $validated = $request->validate([
                'option_gambar' => 'required|string|max:255',
                'path_gambar' => 'nullable|string|max:500',
                'is_show' => 'boolean',
            ]);

            // Handle file upload jika ada
            if ($request->hasFile('gambar')) {
                // Hapus file lama jika ada
                if ($gambar->path_gambar) {
                    $oldPath = str_replace('public/', 'storage/', $gambar->path_gambar);
                    if (Storage::disk('public')->exists($oldPath)) {
                        Storage::disk('public')->delete($oldPath);
                    }
                }

                $file = $request->file('gambar');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('public/manajemen_gambar', $filename);
                $validated['path_gambar'] = str_replace('public/', '', $path);
            }

            $validated['is_show'] = $request->has('is_show') ? 1 : 0;

            DB::table('manajemen_gambar')->where('id', $id)->update($validated);

            // Clear cache
            cache()->forget('carousel_data');

            return response()->json([
                'success' => true,
                'message' => 'Gambar berhasil diupdate'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Manajemen Gambar Update Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate gambar: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hapus gambar
     */
    public function destroy($id)
    {
        try {
            $gambar = DB::table('manajemen_gambar')->where('id', $id)->first();

            if (!$gambar) {
                return response()->json(['success' => false, 'message' => 'Gambar tidak ditemukan'], 404);
            }

            // Hapus file jika ada
            if ($gambar->path_gambar) {
                $filePath = str_replace('public/', 'storage/', $gambar->path_gambar);
                if (Storage::disk('public')->exists($filePath)) {
                    Storage::disk('public')->delete($filePath);
                }
            }

            DB::table('manajemen_gambar')->where('id', $id)->delete();

            // Clear cache
            cache()->forget('carousel_data');

            return response()->json([
                'success' => true,
                'message' => 'Gambar berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            \Log::error('Manajemen Gambar Destroy Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus gambar: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle publish/unpublish
     */
    public function togglePublish($id)
    {
        try {
            $gambar = DB::table('manajemen_gambar')->where('id', $id)->first();

            if (!$gambar) {
                return response()->json(['success' => false, 'message' => 'Gambar tidak ditemukan'], 404);
            }

            $newStatus = $gambar->is_show == 1 ? 0 : 1;

            DB::table('manajemen_gambar')->where('id', $id)->update(['is_show' => $newStatus]);

            // Clear cache
            cache()->forget('carousel_data');

            return response()->json([
                'success' => true,
                'message' => $newStatus == 1 ? 'Gambar berhasil dipublish' : 'Gambar berhasil diunpublish',
                'is_show' => $newStatus
            ]);
        } catch (\Exception $e) {
            \Log::error('Manajemen Gambar Toggle Publish Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status publish: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download gambar
     */
    public function download($id)
    {
        try {
            $gambar = DB::table('manajemen_gambar')->where('id', $id)->first();

            if (!$gambar || !$gambar->path_gambar) {
                return back()->with('error', 'File gambar tidak ditemukan.');
            }

            $filePath = ltrim(str_replace('public/', '', $gambar->path_gambar), '/');
            $fullPath = public_path($filePath);

            if (file_exists($fullPath)) {
                return response()->download($fullPath, basename($filePath));
            }

            // Coba dari storage
            $storagePath = str_replace('public/', 'storage/', $gambar->path_gambar);
            if (Storage::disk('public')->exists($storagePath)) {
                return Storage::disk('public')->download($storagePath, basename($gambar->path_gambar));
            }

            return back()->with('error', 'File gambar tidak ditemukan atau tidak dapat diakses.');
        } catch (\Exception $e) {
            \Log::error('Error downloading gambar: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat mengunduh gambar.');
        }
    }
}
