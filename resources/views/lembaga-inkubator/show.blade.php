@extends('layouts.app')

{{-- Judul TAB (browser) menggunakan nama_inkubator --}}
@section('title', 'Detail - ' . ($row->nama_inkubator ?? 'Lembaga Inkubator'))

{{-- Background variant detail tetap --}}
@section('bg-variant','bg-detail-inkubator')

@push('styles')
  <link rel="stylesheet" href="{{ asset('css/lembaga-inkubator.css') }}">
@endpush

@section('content')
<div class="container li-shell">

  {{-- Tombol Kembali --}}
  <div class="reveal d-1">
    <a href="{{ route('lembaga.index') }}" class="btn btn-outline-secondary btn-sm mb-3">
      ← Kembali
    </a>
  </div>

  @php
    // Sinkronisasi dengan kolom Database
    $idJenis = $row->jenis_lembaga_id; 
    $jm = $jenisMap[$idJenis] ?? ['label' => 'Lainnya', 'badge' => 'badge-default'];
    
    $nama   = trim($row->nama_inkubator ?? '');
    $alamat = trim($row->alamat_kantor ?? '');
    $kontak = trim($row->email ?? ''); // Menggunakan kolom email sesuai screenshot phpMyAdmin
    $web    = trim($row->website ?? '');

    // Ambil nama provinsi jika relasi sudah ada, atau tampilkan kodenya
    $provNama = $row->provinsi->name ?? $row->kode_provinsi ?? '-';

    // Website logic
    $websiteUrl = $web;
    if ($web && !preg_match('~^https?://~i', $web)) {
      $websiteUrl = 'https://' . $web;
    }
  @endphp

  {{-- CARD DETAIL --}}
  <div class="li-detail-card reveal d-2">

    {{-- Judul: NAMA INKUBATOR DARI DB --}}
    <h2 class="li-detail-title reveal d-3">
      {{ $nama !== '' ? $nama : 'Nama Lembaga (belum tersedia)' }}
    </h2>

    {{-- Badge Jenis --}}
    <div class="mb-3 reveal d-4">
      <span class="badge-jenis {{ $jm['badge'] }}">
        {{ $jm['label'] }}
      </span>
    </div>

    {{-- Detail Info --}}
    <div class="row g-3">
      <div class="col-md-6 reveal d-5">
        <div class="li-detail-item">
          <b>Provinsi:</b> {{ $provNama }}
        </div>
        <div class="li-detail-item">
          <b>Alamat:</b> {{ $alamat !== '' ? $alamat : '-' }}
        </div>
      </div>

      <div class="col-md-6 reveal d-6">
        <div class="li-detail-item">
          <b>Website:</b>
          @if($web !== '')
            <a href="{{ $websiteUrl }}" target="_blank" rel="noopener">
              {{ $web }}
            </a>
          @else
            -
          @endif
        </div>

        <div class="li-detail-item">
          <b>Kontak/Email:</b> {{ $kontak !== '' ? $kontak : '-' }}
        </div>
      </div>
    </div>

  </div>
</div>
@endsection