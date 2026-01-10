<section class="sipensi-news" data-news-section>
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
                <img src="{{ asset($item->path_gambar) }}" alt="{{ $item->judul }}">
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
<script src="{{ asset('js/berita.js') }}"></script>
@endpush
