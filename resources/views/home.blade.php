@extends('layouts.app')

@section('title', 'SIPENSI - Beranda')

{{-- ================= STYLES ================= --}}
@push('styles')
  {{-- Leaflet (Map) --}}
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">

  {{-- CSS Map --}}
  <link rel="stylesheet" href="{{ asset('css/sebaran-inkubator.css') }}">

  {{-- CSS Galeri --}}
  <link rel="stylesheet" href="{{ asset('css/galeri.css') }}">
@endpush


@section('content')

{{-- ================= HERO ================= --}}
<section class="hero">
  <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
    <div class="carousel-inner">

      @for ($i = 1; $i <= 5; $i++)
      <div class="carousel-item {{ $i === 1 ? 'active' : '' }} position-relative">
        <div class="hero-bg" style="background-image:url('{{ asset("img/slide$i.jpg") }}');"></div>
        <div class="hero-overlay"></div>

        <div class="hero-content">
          <div class="wrap">
            <div class="hero-title-logo mb-3 reveal">
              <img src="{{ asset('img/logo/sipensi_white_nobg.png') }}" alt="SIPENSI" class="hero-logo">
            </div>
            <p class="fs-6 mb-4 text-white reveal d-1">
              Akses informasi Inkubator dan Usaha Rintisan Indonesia
            </p>
            <a href="{{ url('/lembaga-inkubator') }}" class="btn btn-selengkapnya reveal d-2">
              Selengkapnya
            </a>
          </div>
        </div>
      </div>
      @endfor

    </div>

    <button class="carousel-control-prev" type="button"
            data-bs-target="#heroCarousel" data-bs-slide="prev">
      <span class="carousel-control-prev-icon"></span>
    </button>

    <button class="carousel-control-next" type="button"
            data-bs-target="#heroCarousel" data-bs-slide="next">
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

    <div class="row g-4 justify-content-center incubator-cards">
      <div class="col-12 col-md-6 col-lg-5 reveal reveal-left d-1">
        <div class="incubator-card incubator-card--teal">
          <div class="incubator-number stat-number"
               data-target="{{ (int)($totalLembaga ?? 732) }}">0</div>
          <div class="incubator-card-foot">Inkubator Terdaftar</div>
        </div>
      </div>

      <div class="col-12 col-md-6 col-lg-5 reveal reveal-right d-2">
        <div class="incubator-card incubator-card--gold">
          <div class="incubator-number stat-number"
               data-target="{{ (int)($totalTenant ?? 6165) }}">0</div>
          <div class="incubator-card-foot">Usaha Rintisan Terinkubasi</div>
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



@endsection


{{-- ================= SCRIPTS ================= --}}
@push('scripts')
  {{-- Leaflet (Map) --}}
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

  {{-- CONFIG DATA UNTUK MAP --}}
  <script>
    window.SEBARAN_INKUBATOR_DATA = @json($sebaranInkubator ?? []);
  </script>

  {{-- JS Map --}}
  <script src="{{ asset('js/sebaran-inkubator.js') }}"></script>

  {{-- JS Home (punya lu) --}}
  <script src="{{ asset('js/home.js') }}"></script>

  {{-- JS Galeri --}}
  <script src="{{ asset('js/galeri.js') }}"></script>

  
@endpush
