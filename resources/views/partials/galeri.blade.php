<section class="sipensi-gallery" id="sipensiGallery">
  <div class="container">

    <div class="gallery-head">
      <h2 class="gallery-title">Galeri Kegiatan</h2>
      <p class="gallery-subtitle">
        Dokumentasi kegiatan, pelatihan, kolaborasi, dan pendampingan.
      </p>
    </div>

    <div class="gallery-filter" role="tablist" aria-label="Filter galeri">
      <button class="gallery-filter-btn active" data-filter="all" type="button">Semua</button>
      <button class="gallery-filter-btn" data-filter="kegiatan" type="button">Kegiatan</button>
      <button class="gallery-filter-btn" data-filter="pelatihan" type="button">Pelatihan</button>
      <button class="gallery-filter-btn" data-filter="kolaborasi" type="button">Kolaborasi</button>
      <button class="gallery-filter-btn" data-filter="konsultasi" type="button">Konsultasi</button>
    </div>

    <div class="gallery-stage">
      <button class="gallery-arrow gallery-arrow-prev" id="galleryPrevPage" type="button" aria-label="Sebelumnya">
        <svg viewBox="0 0 24 24" aria-hidden="true">
          <path d="M15 6l-6 6 6 6"></path>
        </svg>
      </button>

      <button class="gallery-arrow gallery-arrow-next" id="galleryNextPage" type="button" aria-label="Berikutnya">
        <svg viewBox="0 0 24 24" aria-hidden="true">
          <path d="M9 6l6 6-6 6"></path>
        </svg>
      </button>

      <div id="sipensiGalleryGrid" class="gallery-grid">
        @php $galleryItems = $galleryItems ?? []; @endphp

        @if (count($galleryItems) === 0)
          <div class="gallery-empty">
            <strong>Galeri belum terbaca.</strong><br>
            Pastikan controller mengirim <code>$galleryItems</code> ke view.
          </div>
        @else
          @foreach ($galleryItems as $item)
            @php
              $cat  = $item['category'] ?? 'kegiatan';
              $src  = $item['src'] ?? '';
              $full = $item['full'] ?? $src;
            @endphp

            <div class="gallery-item" data-category="{{ $cat }}">
              <button class="gallery-card" type="button" data-full="{{ $full }}" aria-label="Buka gambar {{ $cat }}">
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

{{-- LIGHTBOX --}}
<div class="sipensi-lightbox" id="sipensiLightbox" aria-hidden="true">
  <div class="sipensi-lightbox__backdrop" data-close="1"></div>

  <button class="sipensi-lightbox__close" type="button" aria-label="Tutup (ESC)">
    <svg viewBox="0 0 24 24" aria-hidden="true">
      <path d="M18 6L6 18"></path>
      <path d="M6 6l12 12"></path>
    </svg>
  </button>

  <div class="sipensi-lightbox__panel" role="dialog" aria-modal="true">
    <img class="sipensi-lightbox__img" id="sipensiLightboxImg" src="" alt="">
  </div>
</div>

@push('styles')
  <link rel="stylesheet" href="{{ asset('css/galeri.css') }}">
@endpush

@push('scripts')
  <script src="{{ asset('js/galeri.js') }}"></script>
@endpush
