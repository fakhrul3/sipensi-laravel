@php
  $items = collect([
    [
      'slug' => 'sipensi-perkuat-transparansi-data-inkubasi',
      'type' => 'Rilis Kegiatan',
      'title' => 'Penguatan Ekosistem Inkubasi Nasional melalui SIPENSI',
      'excerpt' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
      'date' => '05 Jan 2026',
      'image' => asset('img/berita/berita_01.jpg'),
    ],
    [
      'slug' => 'program-inkubasi-dorong-umkm-naik-kelas',
      'type' => 'Liputan Acara',
      'title' => 'Program Inkubasi Dorong UMKM Naik Kelas Berbasis Data',
      'excerpt' => 'Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
      'date' => '03 Jan 2026',
      'image' => asset('img/berita/berita_02.jpg'),
    ],
    [
      'slug' => 'kolaborasi-kampus-industri-untuk-startup-impact-driven',
      'type' => 'Kolaborasi',
      'title' => 'Kolaborasi Kampus & Industri Perkuat Startup Impact-Driven',
      'excerpt' => 'Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.',
      'date' => '28 Des 2025',
      'image' => asset('img/berita/berita_03.jpg'),
    ],
    [
      'slug' => 'digitalisasi-layanan-inkubator-lebih-cepat-lebih-rapi',
      'type' => 'Kolaborasi',
      'title' => 'Digitalisasi Layanan Inkubator: Lebih Cepat, Lebih Rapi',
      'excerpt' => 'Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
      'date' => '20 Des 2025',
      'image' => asset('img/berita/berita_04.jpg'),
    ],
    [
      'slug' => 'best-practice-inkubator-daerah-bangun-ekosistem-lokal',
      'type' => 'Kolaborasi',
      'title' => 'Best Practice: Inkubator Daerah Bangun Ekosistem Lokal',
      'excerpt' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
      'date' => '15 Des 2025',
      'image' => asset('img/berita/berita_05.jpg'),
    ],
    [
      'slug' => 'pendampingan-tenant-berbasis-kebutuhan',
      'type' => 'Rilis Kegiatan',
      'title' => 'Pendampingan Tenant Berbasis Kebutuhan dan Tahapan Usaha',
      'excerpt' => 'Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
      'date' => '12 Des 2025',
      'image' => asset('img/berita/berita_06.jpg'),
    ],
    [
      'slug' => 'integrasi-data-inkubator-monitoring-nasional',
      'type' => 'Kolaborasi',
      'title' => 'Integrasi Data Inkubator untuk Monitoring Nasional',
      'excerpt' => 'Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.',
      'date' => '10 Des 2025',
      'image' => asset('img/berita/berita_07.jpg'),
    ],
    [
      'slug' => 'peran-inkubator-dorong-wirausaha-inovatif',
      'type' => 'Liputan Acara',
      'title' => 'Peran Inkubator dalam Mendorong Wirausaha Inovatif',
      'excerpt' => 'Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
      'date' => '08 Des 2025',
      'image' => asset('img/berita/berita_08.jpg'),
    ],
    [
      'slug' => 'sinergi-pemerintah-akademisi-dunia-usaha',
      'type' => 'Kolaborasi',
      'title' => 'Sinergi Pemerintah, Akademisi, dan Dunia Usaha',
      'excerpt' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
      'date' => '05 Des 2025',
      'image' => asset('img/berita/berita_09.jpg'),
    ],
    [
      'slug' => 'evaluasi-kinerja-inkubator-peningkatan-mutu',
      'type' => 'Pengumuman',
      'title' => 'Evaluasi Kinerja Inkubator untuk Peningkatan Mutu',
      'excerpt' => 'Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
      'date' => '02 Des 2025',
      'image' => asset('img/berita/berita_10.jpg'),
    ],
  ]);
@endphp

<section class="sipensi-news" id="berita" data-news-section>
  <div class="sipensi-news-shell">

    <div class="sipensi-news-header">
      <div>
        <h2>Berita</h2>
        <p>Informasi terkini seputar kegiatan dan kolaborasi Wirausaha Nasional.</p>
      </div>

      <div class="sipensi-news-nav">
        <button class="news-nav-btn prev" type="button" aria-label="Sebelumnya">‹</button>
        <button class="news-nav-btn next" type="button" aria-label="Selanjutnya">›</button>
      </div>
    </div>

    <div class="sipensi-news-track" data-news-track>
      @foreach($items as $n)
        <article class="news-card" data-news-card>
          <a href="{{ route('berita.detail', $n['slug']) }}" class="news-card-link">
            <div class="news-card-image">
              <img src="{{ $n['image'] }}" alt="{{ $n['title'] }}" loading="lazy">
              <span class="news-card-badge">{{ $n['type'] }}</span>
            </div>

            <div class="news-card-body">
              <span class="news-card-date">{{ $n['date'] }}</span>
              <h3 class="news-card-title">{{ $n['title'] }}</h3>
              <p class="news-card-excerpt">{{ $n['excerpt'] }}</p>
              <span class="news-card-cta">Baca selengkapnya →</span>
            </div>
          </a>
        </article>
      @endforeach
    </div>

  </div>
</section>
