<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class BeritaController extends Controller
{
    // LIST BERITA (Public)
    public function index()
    {
        try {
            // Ambil data berita dengan select hanya kolom yang diperlukan
            $berita = Berita::select('id', 'judul', 'isi', 'path_gambar', 'tgl_tayang', 'is_highlight')
                ->where('is_publikasi', 1)
            ->where(function ($q) {
                $q->whereNull('tgl_akhir')
                ->orWhere('tgl_akhir', '>=', now()->toDateString());
            })
            ->orderByDesc('is_highlight')
            ->orderByDesc('tgl_tayang')
                ->limit(20) // Limit untuk performa
            ->get();
        } catch (\Exception $e) {
            // Return empty collection jika database error
            $berita = collect();
        }

        // Kirim variabel $berita ke view home
        return view('home', compact('berita')); 
    }

    // DETAIL BERITA (Public)
    public function show($slug)
    {
        try {
            // Cari berita dengan slug yang match
            // Slug di blade menggunakan Str::slug(), jadi kita perlu match dengan cara yang sama
        $berita = Berita::where('is_publikasi', 1)
                ->get()
                ->first(function ($item) use ($slug) {
                    return Str::slug($item->judul) === $slug;
                });

            if (!$berita) {
                abort(404, 'Berita tidak ditemukan');
            }
        } catch (\Exception $e) {
            abort(404, 'Berita tidak ditemukan: ' . $e->getMessage());
        }

        return view('berita.detail', compact('berita'));
    }

    // ========== ADMIN CRUD BERITA ==========

    /**
     * Menampilkan daftar Berita (Admin)
     */
    public function adminIndex(Request $request)
    {
        try {
            $query = DB::table('berita')
                ->select('id', 'judul', 'tgl_tayang', 'tgl_akhir', 'is_publikasi', 'is_highlight', 'created_at', 'updated_at')
                ->orderBy('tgl_tayang', 'desc');

            // Search functionality
            if ($request->filled('search')) {
                $searchTerm = '%' . $request->search . '%';
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('judul', 'like', $searchTerm);
                });
            }

            $beritas = $query->get();

            return view('admin.berita.index', compact('beritas'));
        } catch (\Exception $e) {
            \Log::error('Berita Admin Index Error: ' . $e->getMessage());
            return view('admin.berita.index', ['beritas' => collect()])->with('error', 'Gagal memuat data berita: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan detail berita
     */
    public function adminShow($id)
    {
        try {
            $berita = DB::table('berita')->where('id', $id)->first();

            if (!$berita) {
                return response()->json(['success' => false, 'message' => 'Berita tidak ditemukan'], 404);
            }

            return response()->json(['success' => true, 'data' => $berita]);
        } catch (\Exception $e) {
            \Log::error('Berita Admin Show Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data berita'], 500);
        }
    }

    /**
     * Menyimpan berita baru
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'judul' => 'required|string|max:255',
                'isi' => 'required|string',
                'tgl_tayang' => 'required|date',
                'tgl_akhir' => 'nullable|date|after_or_equal:tgl_tayang',
                'is_publikasi' => 'boolean',
                'is_highlight' => 'boolean',
                'path_gambar' => 'nullable|string|max:500',
            ]);

            // Handle file upload jika ada
            if ($request->hasFile('gambar')) {
                $file = $request->file('gambar');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('public/berita', $filename);
                $validated['path_gambar'] = str_replace('public/', '', $path);
            }

            $validated['user_id'] = Auth::id() ?? 1;
            $validated['is_publikasi'] = $request->has('is_publikasi') ? 1 : 0;
            $validated['is_highlight'] = $request->has('is_highlight') ? 1 : 0;

            $id = DB::table('berita')->insertGetId($validated);

            // Clear cache
            cache()->forget('berita_home');

            return response()->json([
                'success' => true,
                'message' => 'Berita berhasil ditambahkan',
                'id' => $id
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Berita Store Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan berita: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update berita
     */
    public function update(Request $request, $id)
    {
        try {
            $berita = DB::table('berita')->where('id', $id)->first();

            if (!$berita) {
                return response()->json(['success' => false, 'message' => 'Berita tidak ditemukan'], 404);
            }

            $validated = $request->validate([
                'judul' => 'required|string|max:255',
                'isi' => 'required|string',
                'tgl_tayang' => 'required|date',
                'tgl_akhir' => 'nullable|date|after_or_equal:tgl_tayang',
                'is_publikasi' => 'boolean',
                'is_highlight' => 'boolean',
                'path_gambar' => 'nullable|string|max:500',
            ]);

            // Handle file upload jika ada
            if ($request->hasFile('gambar')) {
                // Hapus file lama jika ada
                if ($berita->path_gambar) {
                    $oldPath = str_replace('public/', 'storage/', $berita->path_gambar);
                    if (Storage::disk('public')->exists($oldPath)) {
                        Storage::disk('public')->delete($oldPath);
                    }
                }

                $file = $request->file('gambar');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('public/berita', $filename);
                $validated['path_gambar'] = str_replace('public/', '', $path);
            }

            $validated['is_publikasi'] = $request->has('is_publikasi') ? 1 : 0;
            $validated['is_highlight'] = $request->has('is_highlight') ? 1 : 0;

            DB::table('berita')->where('id', $id)->update($validated);

            // Clear cache
            cache()->forget('berita_home');

            return response()->json([
                'success' => true,
                'message' => 'Berita berhasil diupdate'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Berita Update Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate berita: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hapus berita
     */
    public function destroy($id)
    {
        try {
            $berita = DB::table('berita')->where('id', $id)->first();

            if (!$berita) {
                return response()->json(['success' => false, 'message' => 'Berita tidak ditemukan'], 404);
            }

            // Hapus file jika ada
            if ($berita->path_gambar) {
                $filePath = str_replace('public/', 'storage/', $berita->path_gambar);
                if (Storage::disk('public')->exists($filePath)) {
                    Storage::disk('public')->delete($filePath);
                }
            }

            DB::table('berita')->where('id', $id)->delete();

            // Clear cache
            cache()->forget('berita_home');

            return response()->json([
                'success' => true,
                'message' => 'Berita berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            \Log::error('Berita Destroy Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus berita: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Copy/Duplicate berita
     */
    public function copy($id)
    {
        try {
            $berita = DB::table('berita')->where('id', $id)->first();

            if (!$berita) {
                return response()->json(['success' => false, 'message' => 'Berita tidak ditemukan'], 404);
            }

            // Copy data tanpa id
            $newData = (array) $berita;
            unset($newData['id']);
            $newData['judul'] = $berita->judul . ' (Copy)';
            $newData['is_publikasi'] = 0; // Set unpublish untuk copy
            $newData['created_at'] = now();
            $newData['updated_at'] = now();

            $newId = DB::table('berita')->insertGetId($newData);

            // Clear cache
            cache()->forget('berita_home');

            return response()->json([
                'success' => true,
                'message' => 'Berita berhasil diduplikasi',
                'id' => $newId
            ]);
        } catch (\Exception $e) {
            \Log::error('Berita Copy Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menduplikasi berita: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle publish/unpublish
     */
    public function togglePublish($id)
    {
        try {
            $berita = DB::table('berita')->where('id', $id)->first();

            if (!$berita) {
                return response()->json(['success' => false, 'message' => 'Berita tidak ditemukan'], 404);
            }

            $newStatus = $berita->is_publikasi == 1 ? 0 : 1;

            DB::table('berita')->where('id', $id)->update(['is_publikasi' => $newStatus]);

            // Clear cache
            cache()->forget('berita_home');

            return response()->json([
                'success' => true,
                'message' => $newStatus == 1 ? 'Berita berhasil dipublish' : 'Berita berhasil diunpublish',
                'is_publikasi' => $newStatus
            ]);
        } catch (\Exception $e) {
            \Log::error('Berita Toggle Publish Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status publish: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle highlight
     */
    public function toggleHighlight($id)
    {
        try {
            $berita = DB::table('berita')->where('id', $id)->first();

            if (!$berita) {
                return response()->json(['success' => false, 'message' => 'Berita tidak ditemukan'], 404);
            }

            $newStatus = $berita->is_highlight == 1 ? 0 : 1;

            DB::table('berita')->where('id', $id)->update(['is_highlight' => $newStatus]);

            // Clear cache
            cache()->forget('berita_home');

            return response()->json([
                'success' => true,
                'message' => $newStatus == 1 ? 'Berita berhasil di-highlight' : 'Berita berhasil di-unhighlight',
                'is_highlight' => $newStatus
            ]);
        } catch (\Exception $e) {
            \Log::error('Berita Toggle Highlight Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status highlight: ' . $e->getMessage()
            ], 500);
        }
    }
}
