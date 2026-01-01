{{-- DEBUG --}}
<!-- MITRA VIEW AKTIF -->


@extends('layouts.app')
@section('title','Mitra Kolaborator')
@section('bg-variant','')

@push('styles')
  <link rel="stylesheet" href="{{ asset('css/mitra-kolaborator.css') }}">
@endpush

@section('content')
@php
  $bgSlides = [
    asset('img/slide1.JPG'),
    asset('img/slide2.JPG'),
    asset('img/slide3.JPG'),
    asset('img/slide4.JPG'),
    asset('img/slide5.JPG'),
  ];
  $firstKey = array_key_first($tabs);
@endphp

<div class="page-mitra">
  <div class="mitra-hero">

    {{-- Background --}}
    <div class="mitra-bg" data-slides='@json($bgSlides)'>
      <div class="mitra-bg-layer layer-a active"></div>
      <div class="mitra-bg-layer layer-b"></div>
    </div>

    <div class="container py-5">

      <div class="mitra-title-wrap">
        <h1 class="mitra-title">65++ Mitra Kolaborator</h1>
        <p class="mitra-subtitle">Berkolaborasi untuk Wirausaha Indonesia</p>
      </div>

      {{-- Tabs --}}
      <ul class="nav nav-pills mitra-tabs" id="mitraTabs" role="tablist">
        @foreach($tabs as $key => $tab)
          @php
            $id = 'tab-' . $key;
            $active = $key === $firstKey ? 'active' : '';
            $selected = $key === $firstKey ? 'true' : 'false';
          @endphp

          <li class="nav-item" role="presentation">
            <button
              class="nav-link {{ $active }}"
              id="{{ $id }}-btn"
              data-bs-toggle="pill"
              data-bs-target="#{{ $id }}"
              type="button"
              role="tab"
              aria-controls="{{ $id }}"
              aria-selected="{{ $selected }}"
            >
              {{ $tab['label'] }}
              <span class="ms-1 badge rounded-pill">{{ count($tab['files']) }}</span>
            </button>
          </li>
        @endforeach
      </ul>

      {{-- Content --}}
      <div class="tab-content">
        @foreach($tabs as $key => $tab)
          @php
            $id = 'tab-' . $key;
            $show = $key === $firstKey ? 'show active' : '';
          @endphp

          <div
            class="tab-pane fade {{ $show }}"
            id="{{ $id }}"
            role="tabpanel"
            aria-labelledby="{{ $id }}-btn"
            tabindex="0"
          >
            <div class="mitra-logos-wrap" data-mitra-wrap>
              <button class="mitra-nav-btn mitra-prev" type="button" aria-label="Previous" data-mitra-prev>&lsaquo;</button>
              <button class="mitra-nav-btn mitra-next" type="button" aria-label="Next" data-mitra-next>&rsaquo;</button>

              <div class="mitra-grid" data-mitra-grid>
                @foreach($tab['files'] as $file)
                  <div class="mitra-card" data-mitra-item>
                    <img
                      src="{{ asset($mitraBaseUrl . '/' . rawurlencode($file)) }}"
                      alt="{{ pathinfo($file, PATHINFO_FILENAME) }}"
                      loading="lazy"
                    >
                  </div>
                @endforeach
              </div>

              <div class="mitra-page-indicator" data-mitra-indicator></div>
            </div>
          </div>
        @endforeach
      </div>

    </div>
  </div>
</div>
@endsection

@push('scripts')
  {{-- Page-ready + reset tabs (TIDAK sentuh navbar) --}}
  <script>
    window.addEventListener('load', () => {
      document.body.classList.add('page-ready');
      const tabs = document.querySelector('.mitra-tabs');
      if (tabs) tabs.scrollLeft = 0;
    });
  </script>

  <script src="{{ asset('js/mitra-kolaborator.js') }}" defer></script>
@endpush
