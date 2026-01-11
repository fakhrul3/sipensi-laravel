@extends('layouts.app')

@section('title', $berita->judul . ' | SIPENSI')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/berita-detail.css') }}">
@endpush

@section('content')
<section class="berita-detail page-enter">

  <img src="{{ asset('img/bunga.png') }}" class="berita-ornament" alt="">

  <div class="berita-detail-shell">

    <a href="{{ route('home') }}#berita" class="berita-back" onclick="event.preventDefault(); window.location.href='{{ route('home') }}#berita'; setTimeout(() => { document.getElementById('berita')?.scrollIntoView({behavior: 'smooth'}); }, 100);">
      ← Kembali ke Berita
    </a>

    <div class="berita-head">
      @if($berita->is_highlight)
        <span class="berita-badge">Highlight</span>
      @endif

      <h1 class="berita-title">{{ $berita->judul }}</h1>

      <p class="berita-date">
        {{ $berita->tgl_tayang?->translatedFormat('d F Y') }}
      </p>
    </div>

    <div class="berita-hero">
      @php
        // Normalize path - hapus 'public/' jika ada
        $imagePath = ltrim(str_replace('public/', '', $berita->path_gambar ?? ''), '/');
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
      <img src="{{ $imageUrl }}" alt="{{ $berita->judul }}" loading="eager" onerror="this.src='{{ asset('img/placeholder-news.png') }}'">
    </div>

    <article class="berita-content">
      {!! $berita->isi !!}
    </article>

  </div>
</section>
@endsection
