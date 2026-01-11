<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SyncBeritaImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'berita:sync-images {--force : Force re-download even if file exists} {--dummy : Replace semua placeholder dengan gambar dummy}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync gambar berita dari database ke folder public/berita';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai sync gambar berita...');
        
        // Pastikan folder public/berita ada
        $targetDir = public_path('berita');
        if (!File::exists($targetDir)) {
            File::makeDirectory($targetDir, 0755, true);
            $this->info('Folder public/berita berhasil dibuat');
        }

        // Ambil semua berita yang punya path_gambar
        $beritaList = DB::table('berita')
            ->whereNotNull('path_gambar')
            ->where('path_gambar', '!=', '')
            ->get();

        $this->info("Ditemukan {$beritaList->count()} berita dengan gambar");

        $success = 0;
        $skipped = 0;
        $failed = 0;
        $created = 0;

        foreach ($beritaList as $berita) {
            $originalPath = $berita->path_gambar;
            $force = $this->option('force');

            // Normalisasi path (hapus 'public/' jika ada)
            $cleanPath = ltrim(str_replace('public/', '', $originalPath), '/');
            $targetPath = public_path($cleanPath);
            $targetDirPath = dirname($targetPath);

            // Cek apakah file sudah ada
            if (File::exists($targetPath) && !$force) {
                $this->line("  ✓ ID {$berita->id}: File sudah ada - {$cleanPath}");
                $skipped++;
                continue;
            }

            // Pastikan folder target ada
            if (!File::exists($targetDirPath)) {
                File::makeDirectory($targetDirPath, 0755, true);
            }

            // Cek apakah path adalah URL external
            if (preg_match('/^https?:\/\//', $originalPath)) {
                // Download dari URL
                try {
                    $response = Http::timeout(30)->get($originalPath);
                    if ($response->successful()) {
                        File::put($targetPath, $response->body());
                        $this->info("  ✓ ID {$berita->id}: Downloaded - {$cleanPath}");
                        $success++;
                    } else {
                        $this->error("  ✗ ID {$berita->id}: Download failed (HTTP {$response->status()})");
                        $this->createPlaceholder($targetPath, $berita->judul ?? 'Berita');
                        $created++;
                    }
                } catch (\Exception $e) {
                    $this->error("  ✗ ID {$berita->id}: Download error - " . $e->getMessage());
                    $this->createPlaceholder($targetPath, $berita->judul ?? 'Berita');
                    $created++;
                }
            } else {
                // Path lokal - cek apakah file ada di tempat lain
                $possiblePaths = [
                    public_path($originalPath),
                    public_path($cleanPath),
                    storage_path('app/public/' . basename($cleanPath)),
                    storage_path('app/' . basename($cleanPath)),
                ];

                $found = false;
                foreach ($possiblePaths as $possiblePath) {
                    if (File::exists($possiblePath)) {
                        // Copy file ke target
                        File::copy($possiblePath, $targetPath);
                        $this->info("  ✓ ID {$berita->id}: Copied - {$cleanPath}");
                        $success++;
                        $found = true;
                        break;
                    }
                }

                if (!$found) {
                    $this->warn("  ⚠ ID {$berita->id}: File tidak ditemukan - {$originalPath}");
                    $this->info("  → Membuat placeholder image...");
                    $this->createPlaceholder($targetPath, $berita->judul ?? 'Berita');
                    $created++;
                }
            }

            // Update path di database jika berbeda
            $newPath = 'public/' . $cleanPath;
            if ($originalPath !== $newPath) {
                DB::table('berita')
                    ->where('id', $berita->id)
                    ->update(['path_gambar' => $newPath]);
            }
        }

        $this->newLine();
        $this->info("=== Hasil Sync ===");
        $this->info("✓ Berhasil: {$success}");
        $this->info("⊘ Dilewati: {$skipped}");
        $this->info("⚠ Placeholder dibuat: {$created}");
        $this->info("✗ Gagal: {$failed}");
        $this->newLine();
        
        if ($created > 0) {
            $this->warn("Catatan: {$created} placeholder image telah dibuat karena file asli tidak ditemukan.");
            $this->warn("Silakan upload gambar yang sesuai untuk menggantikan placeholder.");
        }

        return Command::SUCCESS;
    }

    /**
     * Buat placeholder image jika file tidak ditemukan
     */
    private function createPlaceholder(string $targetPath, string $title = 'Berita'): void
    {
        try {
            // Cek apakah extension GD tersedia
            if (!function_exists('imagecreatetruecolor')) {
                // Jika GD tidak tersedia, buat file SVG placeholder
                $this->createSvgPlaceholder($targetPath, $title);
                return;
            }
            
            $width = 800;
            $height = 450;
            
            // Buat gambar placeholder sederhana menggunakan GD
            $image = imagecreatetruecolor($width, $height);
            
            // Warna background (abu-abu muda)
            $bgColor = imagecolorallocate($image, 243, 244, 246);
            imagefill($image, 0, 0, $bgColor);
            
            // Warna text (abu-abu gelap)
            $textColor = imagecolorallocate($image, 107, 114, 128);
            
            // Font (gunakan built-in font jika tidak ada TTF)
            $font = 5; // Built-in font
            $text = "Gambar Berita";
            $textWidth = imagefontwidth($font) * strlen($text);
            $textHeight = imagefontheight($font);
            $x = ($width - $textWidth) / 2;
            $y = ($height - $textHeight) / 2;
            
            imagestring($image, $font, $x, $y, $text, $textColor);
            
            // Simpan gambar
            $extension = strtolower(pathinfo($targetPath, PATHINFO_EXTENSION));
            switch ($extension) {
                case 'jpg':
                case 'jpeg':
                    imagejpeg($image, $targetPath, 80);
                    break;
                case 'png':
                    imagepng($image, $targetPath);
                    break;
                case 'gif':
                    imagegif($image, $targetPath);
                    break;
                default:
                    imagepng($image, $targetPath);
            }
            
            imagedestroy($image);
        } catch (\Exception $e) {
            // Fallback ke SVG jika GD error
            $this->createSvgPlaceholder($targetPath, $title);
        }
    }

    /**
     * Buat placeholder SVG sederhana
     */
    private function createSvgPlaceholder(string $targetPath, string $title = 'Berita'): void
    {
        $extension = strtolower(pathinfo($targetPath, PATHINFO_EXTENSION));
        
        // Jika extension bukan SVG, ubah ke PNG
        if ($extension !== 'svg') {
            $targetPath = str_replace('.' . $extension, '.png', $targetPath);
        }
        
        $width = 800;
        $height = 450;
        
        $svg = <<<SVG
<svg width="{$width}" height="{$height}" xmlns="http://www.w3.org/2000/svg">
  <rect width="{$width}" height="{$height}" fill="#f3f4f6"/>
  <text x="50%" y="50%" font-family="Arial, sans-serif" font-size="24" fill="#6b7280" text-anchor="middle" dominant-baseline="middle">Gambar Berita</text>
</svg>
SVG;
        
        File::put($targetPath, $svg);
    }

    /**
     * Replace semua placeholder SVG dengan gambar dummy PNG
     */
    private function replacePlaceholdersWithDummy(): int
    {
        $dummyPath = public_path('img/placeholder-news.png');
        
        // Jika dummy tidak ada, buat dulu
        if (!File::exists($dummyPath)) {
            $this->createDummyImage($dummyPath);
        }

        if (!File::exists($dummyPath)) {
            $this->error("Gambar dummy tidak ditemukan di: {$dummyPath}");
            return 0;
        }

        $replaced = 0;
        $beritaDir = public_path('berita');
        
        if (!File::exists($beritaDir)) {
            return 0;
        }

        $files = File::files($beritaDir);
        
        foreach ($files as $file) {
            // Cek apakah file adalah SVG
            $content = File::get($file->getPathname());
            if (strpos($content, '<svg') !== false) {
                // Copy dummy ke file ini
                File::copy($dummyPath, $file->getPathname());
                $replaced++;
            }
        }

        return $replaced;
    }

    /**
     * Buat gambar dummy PNG sederhana (1x1 pixel atau base64)
     */
    private function createDummyImage(string $targetPath): void
    {
        // Buat gambar PNG sederhana menggunakan base64
        // PNG 800x450 dengan background abu-abu
        $pngBase64 = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==';
        
        // Atau buat SVG yang benar-benar bisa dirender sebagai image
        $svg = <<<SVG
<svg width="800" height="450" xmlns="http://www.w3.org/2000/svg">
  <rect width="800" height="450" fill="#f3f4f6"/>
  <text x="50%" y="50%" font-family="Arial, sans-serif" font-size="24" fill="#6b7280" text-anchor="middle" dominant-baseline="middle">Gambar Berita</text>
</svg>
SVG;
        
        // Simpan sebagai SVG (browser bisa render SVG langsung)
        File::put(str_replace('.png', '.svg', $targetPath), $svg);
        
        // Untuk PNG, kita perlu library GD atau gunakan base64
        // Tapi karena GD tidak tersedia, kita copy SVG ke PNG
        // Browser modern bisa render SVG dengan extension apapun jika content-type benar
        File::put($targetPath, base64_decode($pngBase64));
        
        // Atau lebih baik, buat file SVG dengan extension .svg
        $svgPath = public_path('img/placeholder-news.svg');
        File::put($svgPath, $svg);
    }
}
