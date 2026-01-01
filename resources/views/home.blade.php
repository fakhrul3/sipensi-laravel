@extends('layouts.app')

@section('title', 'SIPENSI - Beranda')

@section('content')

<section class="hero">
  <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
    <div class="carousel-inner">

      {{-- SLIDE 1 --}}
      <div class="carousel-item active position-relative">
        <div class="hero-bg" style="background-image:url('{{ asset('img/slide1.jpg') }}');"></div>
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

      {{-- SLIDE 2 --}}
      <div class="carousel-item position-relative">
        <div class="hero-bg" style="background-image:url('{{ asset('img/slide2.jpg') }}'); }}"></div>
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

      {{-- SLIDE 3 --}}
      <div class="carousel-item position-relative">
        <div class="hero-bg" style="background-image:url('{{ asset('img/slide3.jpg') }}'); }}"></div>
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

      {{-- SLIDE 4 --}}
      <div class="carousel-item position-relative">
        <div class="hero-bg" style="background-image:url('{{ asset('img/slide4.jpg') }}'); }}"></div>
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

      {{-- SLIDE 5 --}}
      <div class="carousel-item position-relative">
        <div class="hero-bg" style="background-image:url('{{ asset('img/slide5.jpg') }}'); }}"></div>
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

    </div>

    {{-- prev/next --}}
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
      {{-- CARD 1 --}}
      <div class="col-12 col-md-6 col-lg-5 reveal reveal-left d-1">
        <div class="incubator-card incubator-card--teal">
          <div class="incubator-number stat-number"
               data-target="{{ (int)($totalLembaga ?? 732) }}">0</div>
          <div class="incubator-card-foot">Inkubator Terdaftar</div>
        </div>
      </div>

      {{-- CARD 2 --}}
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

{{-- konten bawah --}}
<div class="container my-5">
  <div class="row g-4">
    <div class="col-md-4">
      <div class="card shadow-sm h-100">
        <div class="card-body">
          <h5 class="fw-bold">Database Inkubator</h5>
          <p class="text-muted mb-0">Akses profil inkubator dan detail program inkubasinya.</p>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card shadow-sm h-100">
        <div class="card-body">
          <h5 class="fw-bold">Sebaran Wilayah</h5>
          <p class="text-muted mb-0">Lihat sebaran inkubator per provinsi melalui peta.</p>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card shadow-sm h-100">
        <div class="card-body">
          <h5 class="fw-bold">Skema & Tahapan</h5>
          <p class="text-muted mb-0">Pelajari skema inkubasi, tahapan, dan mekanisme pembinaan.</p>
        </div>
      </div>
    </div>
  </div>
</div>

@push('scripts')
  <script src="{{ asset('js/home.js') }}"></script>
@endpush

@endsection
