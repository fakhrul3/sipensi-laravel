<?php

namespace App\Http\Controllers;

class BeritaController extends Controller
{
    private function data()
    {
        return collect([
            [
                'slug' => 'sipensi-perkuat-transparansi-data-inkubasi',
                'type' => 'Rilis Kegiatan',
                'title' => 'Penguatan Ekosistem Inkubasi Nasional melalui SIPENSI',
                'excerpt' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                'date' => '05 Jan 2026',
                'image' => asset('img/berita/berita_01.jpg'),
                'content' => '
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                    <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                    <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
                '
            ],
            [
                'slug' => 'program-inkubasi-dorong-umkm-naik-kelas',
                'type' => 'Liputan Acara',
                'title' => 'Program Inkubasi Dorong UMKM Naik Kelas Berbasis Data',
                'excerpt' => 'Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.',
                'date' => '03 Jan 2026',
                'image' => asset('img/berita/berita_02.jpg'),
                'content' => '
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                    <p>Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                    <p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                '
            ],
            [
                'slug' => 'kolaborasi-kampus-industri-untuk-startup-impact-driven',
                'type' => 'Kolaborasi',
                'title' => 'Kolaborasi Kampus & Industri Perkuat Startup Impact-Driven',
                'excerpt' => 'Duis aute irure dolor in reprehenderit in voluptate velit esse cillum.',
                'date' => '28 Des 2025',
                'image' => asset('img/berita/berita_03.jpg'),
                'content' => '
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                    <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                    <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
                '
            ],
            [
                'slug' => 'digitalisasi-layanan-inkubator-lebih-cepat-lebih-rapi',
                'type' => 'Update Sistem',
                'title' => 'Digitalisasi Layanan Inkubator: Lebih Cepat, Lebih Rapi',
                'excerpt' => 'Excepteur sint occaecat cupidatat non proident, sunt in culpa.',
                'date' => '20 Des 2025',
                'image' => asset('img/berita/berita_04.jpg'),
                'content' => '
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                    <p>Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                    <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                '
            ],
            [
                'slug' => 'best-practice-inkubator-daerah-bangun-ekosistem-lokal',
                'type' => 'Best Practice',
                'title' => 'Best Practice: Inkubator Daerah Bangun Ekosistem Lokal',
                'excerpt' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                'date' => '15 Des 2025',
                'image' => asset('img/berita/berita_05.jpg'),
                'content' => '
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                    <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
                    <p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                '
            ],
            [
                'slug' => 'pendampingan-tenant-berbasis-kebutuhan',
                'type' => 'Rilis Kegiatan',
                'title' => 'Pendampingan Tenant Berbasis Kebutuhan dan Tahapan Usaha',
                'excerpt' => 'Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.',
                'date' => '12 Des 2025',
                'image' => asset('img/berita/berita_06.jpg'),
                'content' => '
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                    <p>Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                    <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                '
            ],
            [
                'slug' => 'integrasi-data-inkubator-monitoring-nasional',
                'type' => 'Update Sistem',
                'title' => 'Integrasi Data Inkubator untuk Monitoring Nasional',
                'excerpt' => 'Duis aute irure dolor in reprehenderit in voluptate velit esse cillum.',
                'date' => '10 Des 2025',
                'image' => asset('img/berita/berita_07.jpg'),
                'content' => '
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                    <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
                    <p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                '
            ],
            [
                'slug' => 'peran-inkubator-dorong-wirausaha-inovatif',
                'type' => 'Liputan Acara',
                'title' => 'Peran Inkubator dalam Mendorong Wirausaha Inovatif',
                'excerpt' => 'Excepteur sint occaecat cupidatat non proident, sunt in culpa.',
                'date' => '08 Des 2025',
                'image' => asset('img/berita/berita_08.jpg'),
                'content' => '
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                    <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                    <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
                '
            ],
            [
                'slug' => 'sinergi-pemerintah-akademisi-dunia-usaha',
                'type' => 'Kolaborasi',
                'title' => 'Sinergi Pemerintah, Akademisi, dan Dunia Usaha',
                'excerpt' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                'date' => '05 Des 2025',
                'image' => asset('img/berita/berita_09.jpg'),
                'content' => '
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                    <p>Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                    <p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                '
            ],
            [
                'slug' => 'evaluasi-kinerja-inkubator-peningkatan-mutu',
                'type' => 'Pengumuman',
                'title' => 'Evaluasi Kinerja Inkubator untuk Peningkatan Mutu',
                'excerpt' => 'Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.',
                'date' => '02 Des 2025',
                'image' => asset('img/berita/berita_10.jpg'),
                'content' => '
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                    <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                    <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
                '
            ],
        ]);
    }

    public function show($slug)
    {
        $berita = $this->data()->firstWhere('slug', $slug);
        abort_if(!$berita, 404);

        return view('berita.detail', compact('berita'));
    }
}
