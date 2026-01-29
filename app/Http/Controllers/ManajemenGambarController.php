<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
            $query = ManajemenGambar::query()
                ->select('id', 'option_gambar', 'path_gambar', 'is_show', 'created_at', 'updated_at')
                ->orderBy('created_at', 'desc');

            // Search functionality
            if ($request->filled('search')) {
                $searchTerm = '%' . $request->search . '%';
                $query->where('option_gambar', 'like', $searchTerm);
            }

            $gambars = $query->get();

            return view('manajemen-gambar.index', compact('gambars'));
        } catch (\Exception $e) {
            \Log::error('Manajemen Gambar Index Error: ' . $e->getMessage());
            return view('manajemen-gambar.index', ['gambars' => collect()])
                ->with('error', 'Gagal memuat data gambar: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan detail gambar
     */
    public function show($id)
    {
        try {
            $gambar = ManajemenGambar::select('id','option_gambar','path_gambar','is_show','created_at','updated_at')
                ->find($id);

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
                'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            ]);

            // Slot yang diizinkan (7 slot fix)
            $allowed = ['carousel_1','carousel_2','carousel_3','carousel_4','carousel_5','kontak_2','tentang_1'];
            if (!in_array($validated['option_gambar'], $allowed)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Slot gambar tidak valid'
                ], 422);
            }

            // Handle file upload jika ada (simpan ke public/img/manajemen-gambar)
            if ($request->hasFile('gambar')) {
                $slot = $validated['option_gambar'];
                $file = $request->file('gambar');
                $ext = strtolower($file->getClientOriginalExtension());

                $targetDir = public_path('img/manajemen-gambar');
                if (!file_exists($targetDir)) {
                    mkdir($targetDir, 0755, true);
                }

                // Hapus file lama apapun ekstensinya (biar tetap 7 file)
                foreach (['jpg','jpeg','png','webp','JPG','JPEG','PNG','WEBP'] as $e) {
                    $old = $targetDir . DIRECTORY_SEPARATOR . $slot . '.' . $e;
                    if (file_exists($old)) {
                        @unlink($old);
                    }
                }

                $filename = $slot . '.' . $ext;
                $file->move($targetDir, $filename);

                // Simpan path ke DB dalam format public path
                $validated['path_gambar'] = 'img/manajemen-gambar/' . $filename;
            }

            $validated['is_show'] = $request->has('is_show') ? 1 : 0;

            $gambar = ManajemenGambar::create($validated);

            cache()->forget('carousel_data');

            return response()->json([
                'success' => true,
                'message' => 'Gambar berhasil ditambahkan',
                'id' => $gambar->id
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
            $gambar = ManajemenGambar::find($id);

            if (!$gambar) {
                return response()->json(['success' => false, 'message' => 'Gambar tidak ditemukan'], 404);
            }

            $validated = $request->validate([
                'option_gambar' => 'required|string|max:255',
                'path_gambar' => 'nullable|string|max:500',
                'is_show' => 'boolean',
                'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            ]);

            // Slot yang diizinkan (7 slot fix)
            $allowed = ['carousel_1','carousel_2','carousel_3','carousel_4','carousel_5','kontak_2','tentang_1'];
            if (!in_array($validated['option_gambar'], $allowed)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Slot gambar tidak valid'
                ], 422);
            }

            // is_show update
            $validated['is_show'] = $request->has('is_show') ? 1 : 0;

            // Handle file upload jika ada => REPLACE di public/img/manajemen-gambar
            if ($request->hasFile('gambar')) {
                $slot = $validated['option_gambar'];
                $file = $request->file('gambar');
                $ext = strtolower($file->getClientOriginalExtension());

                $targetDir = public_path('img/manajemen-gambar');
                if (!file_exists($targetDir)) {
                    mkdir($targetDir, 0755, true);
                }

                // Hapus file lama apapun ekstensinya (biar tidak numpuk & tetap 7 file)
                foreach (['jpg','jpeg','png','webp','JPG','JPEG','PNG','WEBP'] as $e) {
                    $old = $targetDir . DIRECTORY_SEPARATOR . $slot . '.' . $e;
                    if (file_exists($old)) {
                        @unlink($old);
                    }
                }

                // Simpan file baru dengan nama fix sesuai slot
                $filename = $slot . '.' . $ext;
                $file->move($targetDir, $filename);

                // Update path_gambar di DB (public path)
                $validated['path_gambar'] = 'img/manajemen-gambar/' . $filename;
            }

            $gambar->update($validated);

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
            $gambar = ManajemenGambar::find($id);

            if (!$gambar) {
                return response()->json(['success' => false, 'message' => 'Gambar tidak ditemukan'], 404);
            }

            // Hapus file jika ada
            if ($gambar->path_gambar) {
                // Kalau path public (img/...)
                if (str_starts_with($gambar->path_gambar, 'img/')) {
                    $fullPath = public_path(ltrim($gambar->path_gambar, '/'));
                    if (file_exists($fullPath)) {
                        @unlink($fullPath);
                    }
                } else {
                    // Kalau path storage public disk
                    if (Storage::disk('public')->exists($gambar->path_gambar)) {
                        Storage::disk('public')->delete($gambar->path_gambar);
                    }
                }
            }

            $gambar->delete();

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
            $gambar = ManajemenGambar::find($id);

            if (!$gambar) {
                return response()->json(['success' => false, 'message' => 'Gambar tidak ditemukan'], 404);
            }

            $newStatus = $gambar->is_show == 1 ? 0 : 1;

            $gambar->update(['is_show' => $newStatus]);

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
            $gambar = ManajemenGambar::find($id);

            if (!$gambar || !$gambar->path_gambar) {
                return back()->with('error', 'File gambar tidak ditemukan.');
            }

            // Kalau path_gambar = "img/manajemen-gambar/xxx.jpg" (public) => coba public_path
            if (str_starts_with($gambar->path_gambar, 'img/')) {
                $fullPath = public_path(ltrim($gambar->path_gambar, '/'));
                if (file_exists($fullPath)) {
                    return response()->download($fullPath, basename($fullPath));
                }
            }

            // Kalau path_gambar = "manajemen_gambar/xxx.jpg" (storage public disk)
            if (Storage::disk('public')->exists($gambar->path_gambar)) {
                return Storage::disk('public')->download($gambar->path_gambar, basename($gambar->path_gambar));
            }

            return back()->with('error', 'File gambar tidak ditemukan atau tidak dapat diakses.');
        } catch (\Exception $e) {
            \Log::error('Error downloading gambar: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat mengunduh gambar.');
        }
    }
}
