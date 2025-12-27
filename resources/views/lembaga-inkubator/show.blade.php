@extends('layouts.app')

{{-- Judul TAB (browser) --}}
@section('title', 'Detail - ' . ($row->nama ?? 'Lembaga Inkubator'))

{{-- Background variant --}}
@section('bg-variant','bg-detail-inkubator')

@push('styles')
  <link rel="stylesheet" href="{{ asset('css/lembaga-inkubator.css') }}">
@endpush

@section('content')
<div class="container li-shell">

  {{-- tombol kembali --}}
  <div class="reveal d-1">
    <a href="{{ route('lembaga.index') }}" class="btn btn-outline-secondary btn-sm mb-3">
      ← Kembali
    </a>
  </div>

  @php
    $jm = $jenisMap[$row->jenis] ?? ['label'=>'-','badge'=>''];
    $nama = trim($row->nama ?? '');
    $prov = trim($row->provinsi ?? '');
    $alamat = trim($row->alamat ?? '');
    $kontak = trim($row->kontak ?? '');
    $websiteRaw = trim($row->website ?? '');

    // website: kalau user isi "www..." tanpa http, otomatis tambahin https://
    $websiteUrl = $websiteRaw;
    if ($websiteRaw && !preg_match('~^https?://~i', $websiteRaw)) {
      $websiteUrl = 'https://' . $websiteRaw;
    }
  @endphp

  {{-- CARD DETAIL --}}
  <div class="li-detail-card reveal d-2">

    {{-- judul: PASTI NAMA INKUBATOR --}}
    <h2 class="li-detail-title reveal d-3">
      {{ $nama !== '' ? $nama : 'Nama Lembaga (belum tersedia)' }}
    </h2>

    {{-- badge jenis --}}
    <div class="mb-3 reveal d-4">
      <span class="badge-jenis {{ $jm['badge'] }}">
        {{ $jm['label'] }}
      </span>
    </div>

    {{-- detail info --}}
    <div class="row g-3">
      <div class="col-md-6 reveal d-5">
        <div class="li-detail-item">
          <b>Provinsi:</b> {{ $prov !== '' ? $prov : '-' }}
        </div>
        <div class="li-detail-item">
          <b>Alamat:</b> {{ $alamat !== '' ? $alamat : '-' }}
        </div>
      </div>

      <div class="col-md-6 reveal d-6">
        <div class="li-detail-item">
          <b>Website:</b>
          @if($websiteRaw !== '')
            <a href="{{ $websiteUrl }}" target="_blank" rel="noopener">
              {{ $websiteRaw }}
            </a>
          @else
            -
          @endif
        </div>

        <div class="li-detail-item">
          <b>Kontak:</b> {{ $kontak !== '' ? $kontak : '-' }}
        </div>
      </div>
    </div>

  </div>
</div>
@endsection
