<?php

namespace App\Http\Controllers;

use App\Models\Galeri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class GaleriController extends Controller
{
    /**
     * Dipanggil dari Home / Beranda
     * Return array biar tetep cocok sama blade galeri lu (yang baca $item['src'], dst)
     */
    public function forHome(int $limit = 200): array
    {
        return $this->fetchGalleryItems($limit);
    }

    /**
     * Halaman Galeri (route /galeri)
     * Bisa filter kategori via query param: /galeri?kategori=kegiatan
     */
    public function index(Request $request)
    {
        $limit = (int) $request->query('limit', 300);
        $kategori = $request->query('kategori'); // optional

        $galleryItems = $this->fetchGalleryItems($limit, $kategori);

        return view('partials.galeri', compact('galleryItems'));

    }

    /**
     * ===============================
     * CORE: Ambil dari DB (tabel galeri)
     * Output disamakan dengan format blade:
     * [
     *   'id' => ...,
     *   'src' => asset('storage/...') or asset('img/...'),
     *   'full' => same as src,
     *   'title' => ...,
     *   'category' => ...
     * ]
     * ===============================
     */
    private function fetchGalleryItems(int $limit = 200, ?string $kategori = null): array
    {
        try {
            $q = Galeri::select('id', 'path_gambar', 'judul', 'kategori')
            ->where('is_show', 1)
            ->where(function ($x) {
                $x->whereNull('published_at')
                  ->orWhere('published_at', '<=', now());
            })
            ->orderBy('sort_order', 'asc')
            ->orderByRaw('tanggal_kegiatan IS NULL ASC')
            ->orderBy('tanggal_kegiatan', 'desc')
            ->orderBy('created_at', 'desc');

        if ($kategori && $kategori !== 'all' && $kategori !== 'semua') {
            $q->where('kategori', $kategori);
        }

        $rows = $q->limit($limit)->get();

        // Map ke format blade yang sudah ada (tanpa ubah blade/CSS)
        return $rows->map(function (Galeri $g) {
            $src = $this->publicAssetFromPath($g->path_gambar);

            return [
                'id'       => $g->id,
                'src'      => $src,
                'full'     => $src,
                'title'    => $g->judul ?? '',
                'category' => $g->kategori ?? 'kegiatan',
                'filename' => basename($g->path_gambar ?? ''),
            ];
        })->all();
        } catch (\Exception $e) {
            // Return empty array jika database error
            return [];
        }
    }

    /**
     * path_gambar yang disimpan di DB bisa seperti:
     * - img/galeri/kegiatan/xxx.jpg  (public)
     * - /img/galeri/kegiatan/xxx.jpg (public)
     * - storage/galeri/xxx.jpg       (storage)
     */
    private function publicAssetFromPath(?string $path): string
    {
        $path = trim((string) $path);
        if ($path === '') return '';

        $path = ltrim($path, '/');

        // kalau sudah full url
        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        return asset($path);
    }

    /**
     * ===============================
     * OPTIONAL: sync/import gambar dari public/img/galeri ke DB
     * Jalankan via route khusus sementara, atau bikin command (lihat di bawah)
     * ===============================
     */
    public function syncFromFolder(Request $request)
    {
        // optional: proteksi sederhana (biar gak kepanggil publik)
        // abort_unless(auth()->check() && auth()->user()->is_admin, 403);

        $limit = (int) $request->query('limit', 10000);

        $result = $this->importImagesFromPublicFolder($limit);

        return response()->json($result);
    }

    private function importImagesFromPublicFolder(int $limit = 10000): array
    {
        $baseDir = public_path('img/galeri');
        if (!File::exists($baseDir)) {
            return ['ok' => false, 'message' => "Folder tidak ditemukan: public/img/galeri", 'inserted' => 0, 'skipped' => 0];
        }

        $allowedExt = ['jpg', 'jpeg', 'png', 'webp'];

        // kategori fleksibel: ambil dari subfolder apa pun
        $inserted = 0;
        $skipped = 0;

        // 1) scan subfolder dulu (kategori = nama folder)
        $folders = File::directories($baseDir);

        foreach ($folders as $folderPath) {
            $kategori = basename($folderPath);

            foreach (File::files($folderPath) as $file) {
                if (($inserted + $skipped) >= $limit) break 2;

                $ext = strtolower($file->getExtension());
                if (!in_array($ext, $allowedExt)) continue;

                $filename = $file->getFilename();
                $relativePath = "img/galeri/{$kategori}/{$filename}";

                // skip kalau sudah ada (path sama)
                $exists = Galeri::where('path_gambar', $relativePath)->exists();
                if ($exists) {
                    $skipped++;
                    continue;
                }

                $judul = $this->titleFromFilename($filename);
                $slug  = Str::slug($judul);

                Galeri::create([
                    'user_id' => auth()->id() ?? 1,               // kalau mau isi user login, ganti auth()->id()
                    'path_gambar' => $relativePath,  // public path
                    'judul' => $judul,
                    'slug' => $this->uniqueSlug($slug),
                    'kategori' => $kategori,
                    'is_show' => 1,
                    'alt_text' => $judul,
                    'excerpt' => null,
                    'deskripsi' => null,
                    'sort_order' => 9999,
                    'tanggal_kegiatan' => null,
                    'published_at' => now(),
                ]);

                $inserted++;
            }
        }

        // 2) kalau ada file langsung di img/galeri (tanpa folder), masuk kategori "kegiatan"
        foreach (File::files($baseDir) as $file) {
            if (($inserted + $skipped) >= $limit) break;

            $ext = strtolower($file->getExtension());
            if (!in_array($ext, $allowedExt)) continue;

            $filename = $file->getFilename();
            $relativePath = "img/galeri/{$filename}";

            $exists = Galeri::where('path_gambar', $relativePath)->exists();
            if ($exists) {
                $skipped++;
                continue;
            }

            $judul = $this->titleFromFilename($filename);
            $slug  = Str::slug($judul);

            Galeri::create([
                'user_id' => null,
                'path_gambar' => $relativePath,
                'judul' => $judul,
                'slug' => $this->uniqueSlug($slug),
                'kategori' => 'kegiatan',
                'is_show' => 1,
                'alt_text' => $judul,
                'excerpt' => null,
                'deskripsi' => null,
                'sort_order' => 9999,
                'tanggal_kegiatan' => null,
                'published_at' => now(),
            ]);

            $inserted++;
        }

        return [
            'ok' => true,
            'message' => 'Sync selesai',
            'inserted' => $inserted,
            'skipped' => $skipped,
        ];
    }

    private function titleFromFilename(string $filename): string
    {
        $name = pathinfo($filename, PATHINFO_FILENAME);
        $name = str_replace(['_', '-'], ' ', $name);
        $name = preg_replace('/\s+/', ' ', $name);
        return trim(Str::title($name));
    }

    private function uniqueSlug(string $baseSlug): string
    {
        $slug = $baseSlug ?: Str::random(8);

        $i = 1;
        while (Galeri::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $i;
            $i++;
        }

        return $slug;
    }
}
