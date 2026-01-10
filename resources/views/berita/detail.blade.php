@extends('layouts.app')

@section('title', $berita->judul . ' | SIPENSI')

@section('content')
<section class="berita-detail page-enter">

  <img src="{{ asset('img/bunga.png') }}" class="berita-ornament" alt="">

  <div class="berita-detail-shell">

    <a href="{{ route('berita.index') }}" class="berita-back">
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
      <img src="{{ asset($berita->path_gambar) }}" alt="{{ $berita->judul }}">
    </div>

    <article class="berita-content">
      {!! nl2br(e($berita->isi)) !!}
    </article>

  </div>
</section>
@endsection
