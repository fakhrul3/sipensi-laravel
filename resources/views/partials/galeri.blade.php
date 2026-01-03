<section class="sipensi-gallery" id="sipensiGallery">
  <div class="container">

    <div class="gallery-head">
      <h2 class="gallery-title">Galeri Kegiatan</h2>
      <p class="gallery-subtitle">
        Dokumentasi kegiatan, pelatihan, kolaborasi, dan pendampingan.
      </p>
    </div>

    {{-- Filter --}}
    <div class="gallery-filter" role="tablist" aria-label="Filter galeri">
      <button class="gallery-filter-btn active" data-filter="all" type="button">Semua</button>
      <button class="gallery-filter-btn" data-filter="kegiatan" type="button">Kegiatan</button>
      <button class="gallery-filter-btn" data-filter="pelatihan" type="button">Pelatihan</button>
      <button class="gallery-filter-btn" data-filter="kolaborasi" type="button">Kolaborasi</button>
      <button class="gallery-filter-btn" data-filter="konsultasi" type="button">Konsultasi</button>
    </div>

    <div class="gallery-stage">
      {{-- Arrow Prev --}}
      <button class="gallery-arrow gallery-arrow-prev" id="galleryPrevPage" type="button" aria-label="Sebelumnya">
        <svg viewBox="0 0 24 24" aria-hidden="true">
          <path d="M15 6l-6 6 6 6"></path>
        </svg>
      </button>

      {{-- Arrow Next --}}
      <button class="gallery-arrow gallery-arrow-next" id="galleryNextPage" type="button" aria-label="Berikutnya">
        <svg viewBox="0 0 24 24" aria-hidden="true">
          <path d="M9 6l6 6-6 6"></path>
        </svg>
      </button>

      {{-- GRID --}}
      <div id="sipensiGalleryGrid" class="gallery-grid">
        @php
          // Aman: kalau controller belum ngirim, jangan bikin page blank
          $galleryItems = $galleryItems ?? [];
        @endphp

        @if (count($galleryItems) === 0)
          <div class="gallery-empty">
            <strong>Galeri belum terbaca.</strong><br>
            Pastikan controller mengirim <code>$galleryItems</code> ke view.
          </div>
        @else
          @foreach ($galleryItems as $item)
            @php
              $cat = $item['category'] ?? 'kegiatan';
              $src = $item['src'] ?? '';
            @endphp

            <div class="gallery-item" data-category="{{ $cat }}">
              <button class="gallery-card" type="button" data-src="{{ $src }}">
                <div class="gallery-thumb">
                  <img src="{{ $src }}" alt="Galeri {{ $cat }}" loading="lazy">
                </div>
              </button>
            </div>
          @endforeach
        @endif
      </div>
    </div>

  </div>
</section>

@push('styles')
  <link rel="stylesheet" href="{{ asset('css/galeri.css') }}">
@endpush

@push('scripts')
  <script src="{{ asset('js/galeri.js') }}"></script>
@endpush
