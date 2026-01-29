@extends('layouts.app')

@section('title', 'Detail Tenant - ' . ($row->nama_usaha ?? 'Tenant'))
@section('bg-variant','bg-detail-inkubator')

@push('styles')
  <link rel="stylesheet" href="{{ asset('css/lembaga-inkubator.css') }}">
  <style>
    /* Agar foto profil tenant konsisten */
    .ink-logo { background: #fff; display: flex; align-items: center; justify-content: center; overflow: hidden; }
    .ink-logo img { width: 100%; height: 100%; object-fit: cover; }
    /* Styling galeri agar rapi */
    .galeri-item-box { transition: transform 0.2s; border: 1px solid #eee; }
    .galeri-item-box:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
  </style>
@endpush

@section('content')
@php
  // ====== DATA TENANT ======
  $tenant = $row;

  $namaUsaha     = trim($tenant->nama_usaha ?? '') ?: '-';
  $pemilikUsaha  = $tenant->pemilik_usaha ?? '-';
  $alamat        = $tenant->alamat ?? '-';
  $produk        = $tenant->produk ?? '-';
  $omset         = $tenant->omset ?? '-';
  $mediaPromosi  = $tenant->media_promosi ?? '-';
  $jangkauanPasar= $tenant->jangkauan_pasar ?? '-';
  $pembukuan     = $tenant->pembukuan ?? '-';
  $deskripsi     = $tenant->deskripsi ?? '-';

  $tglAwal  = $tenant->tgl_awal_inkubasi ?? null;
  $tglLulus = $tenant->tgl_lulus_inkubasi ?? null;

  $namaInkubator = optional($tenant->inkubator)->nama_inkubator ?? null;

  $bidangUsaha = $namaBidangUsaha ?? '-';
  $klasifikasi = $namaKlasifikasiBisnis ?? '-';

  // FIX PATH FOTO PROFIL TENANT
  $fotoPathRaw = $tenant->foto_profil ?? null;
  $cleanFotoProfil = $fotoPathRaw ? asset('storage/' . str_replace('public/', '', $fotoPathRaw)) : asset('assets/images/brand/default-tenant.png');

  // format tanggal
  $fmt = function ($d) {
    if (!$d) return '-';
    try { return \Carbon\Carbon::parse($d)->format('d M Y'); }
    catch (\Exception $e) { return $d; }
  };

  // ====== GALERI PRODUK ======
  $produkItems = (isset($row) && isset($row->galeriProduk)) ? $row->galeriProduk : collect();

  $produkGaleri = [];
  if ($produkItems->count() > 0) {
    foreach ($produkItems as $p) {
      $arr = $p->foto_produk ?? [];
      if (is_string($arr)) {
        $decoded = json_decode($arr, true);
        $arr = is_array($decoded) ? $decoded : [];
      }

      $fotos = [];
      if (is_array($arr)) {
        foreach ($arr as $path) {
          // Clean path untuk setiap foto di dalam array
          $cPath = str_replace(['public/', '\\'], ['', '/'], $path);
          $fotos[] = asset('storage/' . ltrim($cPath, '/'));
        }
      }

      if (!empty($fotos)) {
        $produkGaleri[] = [
          'produk_id' => $p->id ?? null,
          'nama'      => $p->nama_produk ?? 'Produk',
          'thumb'     => $fotos[0], 
          'fotos'     => $fotos,
        ];
      }
    }
  }
@endphp

<section class="container-fluid li-detail-wrap mb-5 px-md-5">
  <div class="row g-4">

    {{-- KOLOM KIRI --}}
    <div class="col-md-6 col-lg-7 li-col-left">
      <a href="{{ url()->previous() }}" class="btn-li-floating">← Kembali</a>

      <div class="card ink-card mb-3">
        <div class="ink-card__top">
          <div class="ink-logo">
            <img src="{{ $cleanFotoProfil }}" onerror="this.src='{{ asset('assets/images/brand/default-tenant.png') }}'" alt="Foto Profil Tenant">
          </div>

          <div class="ink-head">
            <h3 class="ink-title">{{ $namaUsaha }}</h3>
            <div class="ink-badges">
              @if($namaInkubator)
                <span class="ink-badge ink-badge--ok">{{ $namaInkubator }}</span>
              @endif
              @if($klasifikasi && $klasifikasi !== '-')
                <span class="ink-badge badge-default">{{ $klasifikasi }}</span>
              @endif
            </div>
          </div>
        </div>

        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-borderless ink-table mb-0">
              <tr><td class="ink-key"><i class="fa-regular fa-building me-2"></i>Nama Tenant</td><td class="ink-val">{{ $namaUsaha }}</td></tr>
              <tr><td class="ink-key"><i class="fa-regular fa-user me-2"></i>Pemilik Usaha</td><td class="ink-val">{{ $pemilikUsaha }}</td></tr>
              <tr><td class="ink-key"><i class="fa-solid fa-location-dot me-2"></i>Alamat</td><td class="ink-val">{!! $alamat ?: '-' !!}</td></tr>
              <tr><td class="ink-key"><i class="fa-solid fa-briefcase me-2"></i>Bidang Usaha</td><td class="ink-val">{{ $bidangUsaha }}</td></tr>
              <tr><td class="ink-key"><i class="fa-solid fa-layer-group me-2"></i>Klasifikasi Bisnis</td><td class="ink-val">{{ $klasifikasi }}</td></tr>
              <tr><td class="ink-key"><i class="fa-solid fa-box-open me-2"></i>Produk Utama</td><td class="ink-val">{{ $produk }}</td></tr>
              <tr><td class="ink-key"><i class="fa-solid fa-sack-dollar me-2"></i>Omset</td><td class="ink-val">{{ $omset }}</td></tr>
              <tr><td class="ink-key"><i class="fa-solid fa-bullhorn me-2"></i>Media Promosi</td><td class="ink-val">{{ $mediaPromosi }}</td></tr>
              <tr><td class="ink-key"><i class="fa-solid fa-globe me-2"></i>Jangkauan Pasar</td><td class="ink-val">{{ $jangkauanPasar }}</td></tr>
              <tr><td class="ink-key"><i class="fa-solid fa-book me-2"></i>Pembukuan</td><td class="ink-val">{{ $pembukuan }}</td></tr>
              <tr><td class="ink-key"><i class="fa-regular fa-calendar me-2"></i>Awal Inkubasi</td><td class="ink-val">{{ $fmt($tglAwal) }}</td></tr>
              <tr><td class="ink-key"><i class="fa-regular fa-calendar-check me-2"></i>Tanggal Lulus</td><td class="ink-val">{{ $fmt($tglLulus) }}</td></tr>
              <tr><td class="ink-key"><i class="fa-regular fa-file-lines me-2"></i>Deskripsi</td><td class="ink-val">{!! $deskripsi ?: '-' !!}</td></tr>
            </table>
          </div>
        </div>
      </div>
    </div>

    {{-- KOLOM KANAN - GALERI PRODUK --}}
    <div class="col-md-6 col-lg-5 li-col-right">
      <div class="card tenant-card">
        <div class="tenant-card__head">
          <h4 class="fw-bold m-0">Galeri Produk</h4>
        </div>
        <div class="card-body tenant-card__body">
          @if(count($produkGaleri) > 0)
            <div class="p-3">
              <div class="row g-3">
                @foreach($produkGaleri as $pi => $prod)
                  <div class="col-6">
                    <a href="#" class="d-block text-decoration-none galeri-item-box rounded overflow-hidden bg-white" 
                       data-bs-toggle="modal" data-bs-target="#produkGaleriModal" data-produk-index="{{ $pi }}">
                      <img src="{{ $prod['thumb'] }}" onerror="this.src='{{ asset('assets/images/brand/default-tenant.png') }}'"
                           class="img-fluid" alt="{{ $prod['nama'] }}" style="width:100%; height:150px; object-fit:cover;">
                      <div class="p-2 text-center">
                        <small class="fw-bold text-dark text-truncate d-block">{{ $prod['nama'] }}</small>
                      </div>
                    </a>
                  </div>
                @endforeach
              </div>
            </div>
          @else
            <div class="p-5 text-center text-muted">
              <i class="fa-solid fa-image fa-3x mb-3 opacity-25"></i>
              <p>Belum ada data galeri produk.</p>
            </div>
          @endif
        </div>
      </div>
    </div>

  </div>
</section>

{{-- MODAL CAROUSEL --}}
<div class="modal fade" id="produkGaleriModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="produkGaleriModalTitle">Galeri Produk</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body bg-light">
        <div id="produkGaleriCarousel" class="carousel slide" data-bs-ride="false">
          <div class="carousel-inner" id="produkGaleriCarouselInner">
             </div>
          <button class="carousel-control-prev" type="button" data-bs-target="#produkGaleriCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon bg-dark rounded-circle" aria-hidden="true"></span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#produkGaleriCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon bg-dark rounded-circle" aria-hidden="true"></span>
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      // Set nav active
      const el = document.getElementById('inkubator');
      if (el) el.classList.add('active');

      // Pindahkan modal ke body
      const modalEl = document.getElementById('produkGaleriModal');
      if (modalEl) document.body.appendChild(modalEl);

      // Logic Carousel Dinamis
      const galeriData = @json($produkGaleri);
      const modalTitle = document.getElementById('produkGaleriModalTitle');
      const carouselInner = document.getElementById('produkGaleriCarouselInner');

      document.querySelectorAll('[data-produk-index]').forEach(anchor => {
        anchor.addEventListener('click', function() {
          const idx = this.getAttribute('data-produk-index');
          const item = galeriData[idx];
          
          modalTitle.innerText = item.nama;
          carouselInner.innerHTML = '';

          item.fotos.forEach((foto, fIdx) => {
            const div = document.createElement('div');
            div.className = 'carousel-item ' + (fIdx === 0 ? 'active' : '');
            div.innerHTML = `<img src="${foto}" class="d-block w-100" style="max-height:500px; object-fit:contain;">`;
            carouselInner.appendChild(div);
          });
        });
      });
    });
  </script>
@endpush