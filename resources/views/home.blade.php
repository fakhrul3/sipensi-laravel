@extends('layouts.app')

@section('title', 'SIPENSI - Beranda')

{{-- ================= STYLES ================= --}}
@push('styles')
  {{-- Preconnect untuk Leaflet --}}
  <link rel="dns-prefetch" href="https://unpkg.com">
  <link rel="preconnect" href="https://unpkg.com" crossorigin>

  {{-- Leaflet (Map) - Defer loading karena tidak critical --}}
  <link rel="preload" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
  <noscript><link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"></noscript>

  {{-- CSS Map - Defer loading --}}
  <link rel="preload" href="{{ asset('css/sebaran-inkubator.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
  <noscript><link rel="stylesheet" href="{{ asset('css/sebaran-inkubator.css') }}"></noscript>

  {{-- CSS Galeri - Defer loading --}}
  <link rel="preload" href="{{ asset('css/galeri.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
  <noscript><link rel="stylesheet" href="{{ asset('css/galeri.css') }}"></noscript>
@endpush


@section('content')

{{-- ================= HERO ================= --}}
<section class="hero">
  <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
    <div class="carousel-inner">
      @foreach ($carousel as $i => $item)
        <div class="carousel-item {{ $i === 0 ? 'active' : '' }} position-relative">
        <div class="hero-bg"
          style="background-image:url('{{ asset($item->path_gambar) }}');">
        </div>
          <div class="hero-overlay"></div>
          <div class="hero-content">
            <div class="wrap">
              <div class="hero-title-logo mb-3 reveal">
                <img src="{{ asset('img/logo/sipensi_white_nobg.png') }}"
                     alt="SIPENSI"
                     class="hero-logo">
              </div>
              <p class="fs-6 mb-4 text-white reveal d-1">
                Akses Informasi Inkubator dan Usaha Rintisan Indonesia
              </p>
              <a href="{{ url('/lembaga-inkubator') }}"
                 class="btn btn-selengkapnya reveal d-2">
                Selengkapnya
              </a>
            </div>
          </div>
        </div>
      @endforeach
    </div>
    <button class="carousel-control-prev"
            type="button"
            data-bs-target="#heroCarousel"
            data-bs-slide="prev">
      <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next"
            type="button"
            data-bs-target="#heroCarousel"
            data-bs-slide="next">
      <span class="carousel-control-next-icon"></span>
    </button>
  </div>
</section>


{{-- ================= INCUBATOR ================= --}}
<section class="incubator-section">
  <div class="container">

    <div class="incubator-head text-center">
      <div class="incubator-title-wrap">
        <h3 class="incubator-title dotted-title reveal">
          Menghubungkan Inkubator<br>Menguatkan Wirausaha
        </h3>
      </div>
    </div>

<div class="container">
  <div class="row g-4 justify-content-center incubator-cards">

    <div class="col-12 col-md-6 col-lg-5 reveal reveal-left d-1">
      <div class="incubator-card incubator-card--teal">
        <div class="incubator-number stat-number counter"
             data-target="{{ (int)($totalLembaga ?? 732) }}"
             data-duration="1200">0</div>
        <div class="incubator-card-foot">Inkubator Terdaftar</div>
      </div>
    </div>

    <div class="col-12 col-md-6 col-lg-5 reveal reveal-right d-2">
      <div class="incubator-card incubator-card--gold">
        <div class="incubator-number stat-number counter"
             data-target="{{ (int)($totalTenant ?? 6165) }}"
             data-format="dot"
             data-duration="1400">0</div>
        <div class="incubator-card-foot">Usaha Rintisan Terinkubasi</div>
      </div>
    </div>

  </div>
</div>


    <div class="text-center mt-4 reveal d-3">
      <a href="{{ url('/lembaga-inkubator') }}" class="btn btn-inkubator">
        Inkubator Terdaftar
      </a>
    </div>

  </div>
</section>

{{-- ================= SEBARAN MAP ================= --}}
@include('partials.sebaran-inkubator')

{{-- ================= GALERI ================= --}}
@include('partials.galeri', ['galleryItems' => $galleryItems ?? []])

{{-- ================= BERITA ================= --}}
@include('partials.berita', ['berita' => $berita ?? collect()])




@endsection


{{-- ================= SCRIPTS ================= --}}
@push('scripts')
  {{-- Preload Leaflet untuk performa lebih baik --}}
  <link rel="preload" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" as="script">

  {{-- CONFIG DATA UNTUK MAP - Inline karena kecil dan diperlukan segera --}}
  <script>
    window.SEBARAN_INKUBATOR_DATA = @json($sebaranInkubator ?? []);
    window.SIPENSI = window.SIPENSI || {};
    window.SIPENSI.lembagaUrl = "{{ route('lembaga.index') }}";
  </script>

  {{-- Leaflet (Map) - Defer karena tidak critical untuk first paint --}}
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" defer></script>

  {{-- JS Map - Defer, bergantung pada Leaflet --}}
  <script src="{{ asset('js/sebaran-inkubator.js') }}" defer></script>

  {{-- JS Home - Defer --}}
  <script src="{{ asset('js/home.js') }}" defer></script>

  {{-- JS Galeri - Defer --}}
  <script src="{{ asset('js/galeri.js') }}" defer></script>

  {{-- JS counter scroll - Defer --}}
  <script src="{{ asset('js/counter-scroll.js') }}" defer></script>
@endpush
