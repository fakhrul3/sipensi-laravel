<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MitraController extends Controller
{
    /**
     * Halaman Mitra Kolaborator.
     *
     * - Auto-load semua logo dari public/img/mitra (rekursif).
     * - Grouping pakai keyword sederhana (folder name / file name).
     * - Sisa logo otomatis masuk tab "Business Matching" agar tidak ada yang ketinggalan.
     */
    public function index()
    {
        $baseDir = public_path('img/mitra');

        // kalau folder belum ada, tetap render page tanpa error
        if (!File::exists($baseDir)) {
            return view('mitra.index', [
                'groups' => collect(),
                'total'  => 0,
            ]);
        }

        $files = File::allFiles($baseDir);

        $allLogos = collect($files)
            ->filter(fn ($f) => in_array(strtolower($f->getExtension()), ['png','jpg','jpeg','webp','svg']))
            ->map(function ($f) {
                $relative = str_replace(public_path() . DIRECTORY_SEPARATOR, '', $f->getPathname());
                $relative = str_replace('\\', '/', $relative); // windows
                return [
                    'path' => $relative,
                    'name' => Str::of($f->getFilename())->beforeLast('.')->replace(['_','-'], ' ')->title(),
                    'meta' => strtolower($relative),
                ];
            })
            ->values();

        // urutan tab (biar konsisten)
        $rules = [
            'Digital Corner' => ['setc', 'komdigi', 'shopee', 'grab', 'lazada', 'instellar', 'smesco', 'smes',
                'eka', 'tjipta', 'foundation', 'unikl', 'block71', 'the_local_enablers', 'kode_creative_hub', 'wesolve',
                'djki_kumham', 'ydba', 'podomoro',],
            'FuturePreneur' => ['future', 'preneur', 'grab', 'wadhawani', 'bank_bri', 'pnm', 'impala', 'univ sumatera', 'soedirman', 'lppm', 'pnm',
                'inbistek_andalas', 'setc',],
            'Entrepreneur Scale Up Day' => ['pemprov bali', 'scale', 'up', 'angin', 'huawei', 'lalamove', 'binus', 'idb', 'bali', 'tsinghua', 'block71', 'braja biru', 'nexmedis',
                'ciputra entrepreneurs', 'maxi', 'lionparcel', 'agrindo', 'panda', 'inbis stikom', 'udayana', 'balai diklat denpasar', 
                'akar kita', 'apindo', 'podomoro'],
            'Intensive Lab & Founder Assessment' => [  'intensive', 'lab', 'founder', 'assessment', 'inovasi', 'katamistry', 'dikst', 'stp_univ_hassanudin', 'inbistek_andalas',
                'dinkop_ntt', 'dinkop_diy', 'inbis_stikom', 'inovasi_ugm', 'dikst unbraw', 'diskuk_jabar', 'the_local_enablers', 
                'ibisma_uii', 'dinkop_sumbar',],
            'Coaching & Mentoring' => ['coach', 'coaching', 'mentor', 'mentoring', 'candra', 'lewis', 'du_anyam', 'paragon',
                'bni', 'mandiri', 'bni_ventures', 'capital', 'apindo', 'tbf', 'efunding', 'candra', 'kode',
                'SYNNOVAC', 'du_anyam', 'braja biru', 'dibimbing', 'invst', 'dibimbing', 'angin', 
                'rahmania', 'east_ventures',],
        ];

        $groups = collect();
        $usedPaths = collect();

        foreach ($rules as $title => $keywords) {
            $items = $allLogos->filter(function ($logo) use ($keywords) {
                $hay = $logo['meta'];
                foreach ($keywords as $kw) {
                    if (str_contains($hay, $kw)) return true;
                }
                return false;
            })->values();

            if ($items->count()) {
                $groups->put($title, $items->map(fn ($x) => ['path' => $x['path'], 'name' => $x['name']]));
                $usedPaths = $usedPaths->merge($items->pluck('path'));
            }
        }

        $usedPaths = $usedPaths->unique();
        $remaining = $allLogos
            ->filter(fn ($logo) => !$usedPaths->contains($logo['path']))
            ->values()
            ->map(fn ($x) => ['path' => $x['path'], 'name' => $x['name']]);

        $groups->put('Business Matching', $remaining);

        // Pastikan $groups jadi array/collection yang iterable di blade
        return view('mitra.index', [
            'groups' => $groups,
            'total'  => $allLogos->count(),
        ]);
    }
}
