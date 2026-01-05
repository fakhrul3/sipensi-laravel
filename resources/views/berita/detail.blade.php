@extends('layouts.app')

@section('title', $berita['title'])

@push('styles')
  <link rel="stylesheet" href="{{ asset('css/berita-detail.css') }}">
@endpush

@section('content')

<div class="page-enter">
  <section class="berita-detail">

    {{-- ORNAMEN BUNGA (POSISI DIATUR DARI CSS VARIABLE) --}}
    <img
      src="{{ asset('img/bunga_kemenumkm.png') }}"
      alt=""
      class="berita-ornament is-bunga"
      aria-hidden="true"
    >

    <div class="berita-detail-shell">

      <a class="berita-back" href="{{ url('/#berita') }}">← Kembali</a>

      <div class="berita-head">
        <span class="berita-badge">{{ $berita['type'] ?? 'Rilis Kegiatan' }}</span>
        <h1 class="berita-title">{{ $berita['title'] }}</h1>
        <p class="berita-date">{{ $berita['date'] ?? '' }}</p>
      </div>

      <div class="berita-hero">
        <img src="{{ $berita['image'] }}" alt="{{ $berita['title'] }}">
      </div>

      <article class="berita-content">
        {!! $berita['content'] !!}
      </article>

    </div>
  </section>
</div>

@endsection
