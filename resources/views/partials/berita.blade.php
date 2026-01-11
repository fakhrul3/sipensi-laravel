<section class="sipensi-news" id="berita" data-news-section>
  <div class="sipensi-news-shell">

    <div class="sipensi-news-header">
      <div>
        <h2>Berita</h2>
        <p>Informasi terkini seputar kegiatan dan kolaborasi Wirausaha Nasional.</p>
      </div>

      <div class="sipensi-news-nav">
        <button class="news-nav-btn prev">‹</button>
        <button class="news-nav-btn next">›</button>
      </div>
    </div>

    <div class="sipensi-news-track" data-news-track>
      @php $berita = $berita ?? collect(); @endphp
      @if(count($berita) > 0)
        @foreach ($berita as $item)
          <article class="news-card" data-news-card>
            <a href="{{ route('berita.detail', Str::slug($item->judul)) }}" class="news-card-link">

              <div class="news-card-image">
                @php
                  // Normalize path - hapus 'public/' jika ada
                  $imagePath = ltrim(str_replace('public/', '', $item->path_gambar ?? ''), '/');
                  $imageUrl = asset('img/placeholder-news.png'); // Default placeholder
                  
                  // Cek apakah file ada dan valid
                  if ($imagePath) {
                    $fullPath = public_path($imagePath);
                    if (file_exists($fullPath)) {
                      // Cek apakah file adalah SVG dengan extension PNG
                      $content = file_get_contents($fullPath);
                      if (strpos($content, '<svg') === false) {
                        // File valid, bukan SVG
                        $imageUrl = asset($imagePath);
                      }
                      // Jika SVG, tetap pakai placeholder
                    }
                  }
                @endphp
                <img src="{{ $imageUrl }}" alt="{{ $item->judul }}" loading="lazy" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.src='{{ asset('img/placeholder-news.png') }}'">
                @if($item->is_highlight)
                  <span class="news-card-badge">Highlight</span>
                @endif
              </div>

              <div class="news-card-body">
                <div class="news-card-date">
                  {{ $item->tgl_tayang?->translatedFormat('d M Y') }}
                </div>

                <h3 class="news-card-title">
                  {{ $item->judul }}
                </h3>

                <p class="news-card-excerpt">
                  {{ Str::limit(strip_tags($item->isi), 110) }}
                </p>

                <div class="news-card-cta">
                  Baca selengkapnya →
                </div>
              </div>

            </a>
          </article>
        @endforeach
      @else
        <div style="text-align: center; padding: 40px; color: #64748b;">
          Belum ada berita yang tersedia.
        </div>
      @endif
    </div>

  </div>
</section>

@push('styles')
<link rel="stylesheet" href="{{ asset('css/berita.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/berita.js') }}" defer></script>
@endpush
