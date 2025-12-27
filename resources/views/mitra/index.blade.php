@extends('layouts.app')

@section('content')
@php
  // Background slides (public/img/slide1.JPG ... slide5.JPG)
  $bgSlides = [
    asset('img/slide1.JPG'),
    asset('img/slide2.JPG'),
    asset('img/slide3.JPG'),
    asset('img/slide4.JPG'),
    asset('img/slide5.JPG'),
  ];
@endphp

<div class="mitra-hero">
  {{-- Background Slideshow --}}
  <div class="mitra-bg" data-slides='@json($bgSlides)'>
    <div class="mitra-bg-layer layer-a"></div>
    <div class="mitra-bg-layer layer-b"></div>
  </div>

  <div class="container py-5">
    <div class="mitra-title-wrap mitra-animate">
      <h1 class="mitra-title">65++ Mitra Kolaborator</h1>
      <!-- <p class="mitra-subtitle">Total logo ter-load: <strong>{{ $total ?? 0 }}</strong></p> -->
    </div>

    {{-- Tabs --}}
    <ul class="nav nav-pills mitra-tabs mitra-animate" id="mitraTabs" role="tablist">
      @php $i = 0; @endphp
      @foreach($groups as $title => $items)
        @php
          $id = 'tab-' . \Illuminate\Support\Str::slug($title);
          $active = $i === 0 ? 'active' : '';
          $selected = $i === 0 ? 'true' : 'false';
          $i++;
        @endphp
        <li class="nav-item" role="presentation">
          <button class="nav-link {{ $active }}" id="{{ $id }}-btn"
                  data-bs-toggle="pill" data-bs-target="#{{ $id }}"
                  type="button" role="tab" aria-controls="{{ $id }}"
                  aria-selected="{{ $selected }}">
            {{ $title }}
            <span class="ms-1 badge rounded-pill">{{ $items->count() }}</span>
          </button>
        </li>
      @endforeach
    </ul>

    {{-- Tab Contents --}}
    <div class="tab-content">
      @php $j = 0; @endphp
      @foreach($groups as $title => $items)
        @php
          $id = 'tab-' . \Illuminate\Support\Str::slug($title);
          $show = $j === 0 ? 'show active' : '';
          $j++;
        @endphp

        <div class="tab-pane fade {{ $show }}" id="{{ $id }}" role="tabpanel" aria-labelledby="{{ $id }}-btn" tabindex="0">
          <div class="mitra-logos-wrap mitra-animate" data-mitra-wrap>

            {{-- Arrows (muncul hanya jika pages>1, di-handle JS) --}}
            <button class="mitra-nav-btn mitra-prev" type="button" aria-label="Previous" data-mitra-prev>&lsaquo;</button>
            <button class="mitra-nav-btn mitra-next" type="button" aria-label="Next" data-mitra-next>&rsaquo;</button>

            <div class="mitra-grid" data-mitra-grid>
              @foreach($items as $logo)
                <div class="mitra-card" data-mitra-item>
                  <img src="{{ asset($logo['path']) }}" alt="{{ $logo['name'] }}" loading="lazy">
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
@endsection

@push('styles')
  <link rel="stylesheet" href="{{ asset('css/mitra-kolaborator.css') }}">
@endpush

@push('scripts')
  <script src="{{ asset('js/mitra-kolaborator.js') }}" defer></script>
@endpush
