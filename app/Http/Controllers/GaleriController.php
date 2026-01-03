<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;

class GaleriController extends Controller
{
    /**
     * Dipanggil dari Home / Beranda
     */
    public function forHome(int $limit = 200): array
    {
        return $this->buildGalleryItems($limit);
    }

    /**
     * Halaman Galeri (kalau kamu punya route /galeri)
     */
    public function index()
    {
        $galleryItems = $this->buildGalleryItems(300);
        return view('galeri', compact('galleryItems'));
    }

    /**
     * ===============================
     * CORE: Build gallery dari public/img/galeri
     * ===============================
     * Struktur yang DIDUKUNG:
     *
     * public/img/galeri/kegiatan/*.jpg
     * public/img/galeri/pelatihan/*.jpg
     * public/img/galeri/kolaborasi/*.jpg
     * public/img/galeri/konsultasi/*.jpg
     *
     * (Kalau tanpa subfolder: akan dibagi rata round-robin)
     */
    private function buildGalleryItems(int $limit = 200): array
    {
        $baseDir = public_path('img/galeri');
        if (!File::exists($baseDir)) return [];

        $allowedExt = ['jpg', 'jpeg', 'png', 'webp'];

        // slug HARUS match data-filter di blade/js: kegiatan|pelatihan|kolaborasi|konsultasi
        $categories = ['kegiatan', 'pelatihan', 'kolaborasi', 'konsultasi'];

        $items = [];
        $id = 1;

        // ===============================
        // MODE 1: ADA SUBFOLDER KATEGORI
        // ===============================
        $foundCategoryFolder = false;

        foreach ($categories as $slug) {
            $dir = $baseDir . DIRECTORY_SEPARATOR . $slug;
            if (!File::exists($dir)) continue;

            $foundCategoryFolder = true;

            foreach (File::files($dir) as $file) {
                if (count($items) >= $limit) break 2;

                $ext = strtolower($file->getExtension());
                if (!in_array($ext, $allowedExt)) continue;

                $filename = $file->getFilename();

                $items[] = [
                    'id'       => $id++,
                    'src'      => asset("img/galeri/{$slug}/{$filename}"), // ✅ IMPORTANT: asset()
                    'title'    => $this->titleFromFilename($filename),
                    'category' => $slug,
                    'filename' => $filename,
                ];
            }
        }

        // ===============================
        // MODE 2: TANPA SUBFOLDER
        // ===============================
        if (!$foundCategoryFolder) {
            $files = File::files($baseDir);
            $catIndex = 0;

            foreach ($files as $file) {
                if (count($items) >= $limit) break;

                $ext = strtolower($file->getExtension());
                if (!in_array($ext, $allowedExt)) continue;

                $filename = $file->getFilename();
                $slug = $categories[$catIndex % count($categories)];
                $catIndex++;

                $items[] = [
                    'id'       => $id++,
                    'src'      => asset("img/galeri/{$filename}"), // fallback
                    'title'    => $this->titleFromFilename($filename),
                    'category' => $slug,
                    'filename' => $filename,
                ];
            }
        }

        return $items;
    }

    private function titleFromFilename(string $filename): string
    {
        $name = pathinfo($filename, PATHINFO_FILENAME);
        $name = str_replace(['_', '-'], ' ', $name);
        $name = preg_replace('/\s+/', ' ', $name);
        return ucwords(trim($name));
    }
}
