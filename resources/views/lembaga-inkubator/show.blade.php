@extends('layouts.app')

@section('title', 'Detail - ' . ($row->nama_inkubator ?? 'Lembaga Inkubator'))
@section('bg-variant','bg-detail-inkubator')

@push('styles')
  <link rel="stylesheet" href="{{ asset('css/lembaga-inkubator.css') }}">
  <link rel="stylesheet" type="text/css" media="all" href="{{ asset('theme/plugins/slick/slick.css') }}">
  <link rel="stylesheet" type="text/css" media="all" href="{{ asset('theme/plugins/slick/slick-theme.css') }}">
  <style>
    /* Styling tambahan agar tampilan foto konsisten */
    .ink-logo { background: #fff; display: flex; align-items: center; justify-content: center; overflow: hidden; }
    .ink-logo img { width: 100%; height: 100%; object-fit: contain; }
    .tenant-avatar { width: 50px; height: 50px; object-fit: cover; border-radius: 50%; border: 1px solid #eee; margin-right: 12px; }
    .tenant-info { display: flex; align-items: center; text-decoration: none; }
  </style>
@endpush

@section('content')
@php
  $inkubator = $row;

  $idJenis = $inkubator->jenis_inkubator ?? $inkubator->jenis_lembaga_id ?? null;
  $jm = $jenisMap[$idJenis] ?? ['label' => 'Lainnya', 'badge' => 'badge-default'];

  $nama     = trim($inkubator->nama_inkubator ?? '');
  $alamat   = $inkubator->alamat_kantor ?? '';
  $induk    = $inkubator->induk_inkubator ?? '-';
  $pimpinan = $inkubator->nama_pimpinan ?? '-';
  $email    = $inkubator->email ?? '-';
  $telp     = $inkubator->no_kontak ?? '-';
  $web      = $inkubator->website ?? '';

  $kec  = optional($inkubator->kecamatan)->name ?? '-';
  $kab  = optional($inkubator->kabupaten)->name ?? '-';
  $prov = optional($inkubator->provinsi)->name ?? ($inkubator->kode_provinsi ?? '-');

  $facebook  = $inkubator->facebook ?? '-';
  $instagram = $inkubator->instagram ?? '-';
  $tiktok    = $inkubator->tiktok ?? '-';

  // FIX PATH LOGO LEMBAGA (Hapus 'public/' agar bisa diakses lewat symlink storage)
  $logoPathRaw = $inkubator->logo ?? $inkubator->logo_inkubator ?? null;
  $cleanLogo = $logoPathRaw ? asset('storage/' . str_replace('public/', '', $logoPathRaw)) : asset('assets/images/brand/default-inkubator.png');

  $websiteUrl = $web;
  if ($web && !preg_match('~^https?://~i', $web)) $websiteUrl = 'https://' . $web;
@endphp

<section class="container-fluid li-detail-wrap mb-5 px-md-5">
  <div class="row g-4">

    {{-- KOLOM KIRI --}}
    <div class="col-md-6 col-lg-7 li-col-left">

      <a href="{{ route('lembaga.index') }}" class="btn-li-floating">
        ← Kembali
      </a>

      <div class="card ink-card mb-3">
        <div class="ink-card__top">
          <div class="ink-logo">
            <img
              src="{{ $cleanLogo }}"
              onerror="this.src='{{ asset('assets/images/brand/default-inkubator.png') }}'"
              alt="Logo Inkubator"
            >
          </div>

          <div class="ink-head">
            <h3 class="ink-title">{{ $nama !== '' ? $nama : '-' }}</h3>

            <div class="ink-badges">
              <span class="ink-badge ink-badge--ok">Terverifikasi</span>
              <span class="badge-jenis {{ $jm['badge'] }}">{{ $jm['label'] }}</span>
              @if($grade_terakhir)
                  @if(!empty($grade_terakhir->grade))
                    @if(($grade_terakhir->tanggal_habis_sk ?? null) && ($grade_terakhir->tanggal_habis_sk > date('Y-m-d')))
                      <span class="ink-badge ink-badge--grade">{{ $grade_terakhir->grade }}</span>
                    @else
                      <span class="ink-badge ink-badge--warn">{{ $grade_terakhir->grade }} (Expired)</span>
                    @endif
                  @else
                    <span class="ink-badge ink-badge--info">Belum Dilakukan Pemeringkatan</span>
                  @endif
                @else
                  <span class="ink-badge ink-badge--warn">Belum Mengajukan Pemeringkatan</span>
              @endif
            </div>
          </div>
        </div>

        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-borderless ink-table mb-0">
              <tr>
                <td class="ink-key"><i class="fa-regular fa-building me-2"></i>Nama Lembaga Inkubator</td>
                <td class="ink-val">{{ $nama !== '' ? $nama : '-' }}</td>
              </tr>
              <tr>
                <td class="ink-key"><i class="fa-solid fa-suitcase me-2"></i>Induk Lembaga Inkubator</td>
                <td class="ink-val">{{ $induk }}</td>
              </tr>
              <tr>
                <td class="ink-key"><i class="fa-regular fa-user me-2"></i>Nama Pimpinan</td>
                <td class="ink-val">{{ $pimpinan }}</td>
              </tr>
              <tr>
                <td class="ink-key"><i class="fa-regular fa-envelope me-2"></i>Email</td>
                <td class="ink-val">{{ $email }}</td>
              </tr>
              <tr>
                <td class="ink-key"><i class="fa-solid fa-phone me-2"></i>Nomor Telepon</td>
                <td class="ink-val">{{ $telp }}</td>
              </tr>
              <tr>
                <td class="ink-key"><i class="fa-solid fa-location-dot me-2"></i>Alamat Kantor</td>
                <td class="ink-val">{!! $alamat ?: '-' !!}</td>
              </tr>
              <tr>
                <td class="ink-key"><i class="fa-solid fa-location me-2"></i>Kecamatan</td>
                <td class="ink-val">{{ $kec }}</td>
              </tr>
              <tr>
                <td class="ink-key"><i class="fa-solid fa-map-pin me-2"></i>Kabupaten</td>
                <td class="ink-val">{{ $kab }}</td>
              </tr>
              <tr>
                <td class="ink-key"><i class="fa-solid fa-map-marker me-2"></i>Provinsi</td>
                <td class="ink-val">{{ $prov }}</td>
              </tr>
              <tr>
                <td class="ink-key"><i class="fa-solid fa-globe me-2"></i>Website</td>
                <td class="ink-val">
                  @if($web)
                    <a href="{{ $websiteUrl }}" target="_blank" rel="noopener">{{ $web }}</a>
                  @else
                    -
                  @endif
                </td>
              </tr>
              <tr>
                <td class="ink-key"><i class="fa-brands fa-facebook me-2"></i>Facebook</td>
                <td class="ink-val">{{ $facebook }}</td>
              </tr>
              <tr>
                <td class="ink-key"><i class="fa-brands fa-instagram me-2"></i>Instagram</td>
                <td class="ink-val">{{ $instagram }}</td>
              </tr>
              <tr>
                <td class="ink-key"><i class="fa-brands fa-tiktok me-2"></i>Tiktok</td>
                <td class="ink-val">{{ $tiktok }}</td>
              </tr>
            </table>
          </div>
        </div>
      </div>

      <div class="d-flex flex-wrap gap-2 mt-3 ink-modal-tabs">
        <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalLegalitas">Legalitas</button>
        <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalLaporan">Laporan</button>
        <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalSaranaPrasarana">Sarana Prasarana</button>
        <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalSpesialisasi">Spesialisasi Bidang Usaha</button>
        <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalInkubasi">Model Inkubasi</button>
        <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalRencanaStrategis">Rencana Strategis</button>
        <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalGaleri">Galeri Kegiatan</button>
      </div>
    </div>

    {{-- KOLOM KANAN --}}
    <div class="col-md-6 col-lg-5 li-col-right">
      <div class="card tenant-card">
        <div class="tenant-card__head">
          <h4 class="fw-bold m-0">List Tenant (<span>{{ isset($tenant) ? $tenant->count() : 0 }}</span>)</h4>
          <form action="{{ route('inkubators.cari-tenant.detail',[$inkubator->id]) }}" method="GET" class="tenant-search" id="tenantSearchForm">
            <div class="input-group">
              <input
                type="text"
                class="form-control"
                placeholder="Cari"
                name="keyword"
                id="tenantSearch"
                autocomplete="off"
                value="{{ $keyword ?? old('keyword') }}"
              >
              <button class="btn btn-outline-dark" type="submit" aria-label="Cari">
                <i class="fa-solid fa-search"></i>
              </button>
            </div>
          </form>
        </div>
        <div class="card-body tenant-card__body">
          @if(isset($tenant) && $tenant->count() > 0)
            <ul class="tenant-lists" id="tenantList">
            @foreach($tenant as $item)
                @php
                  // FIX PATH FOTO PROFIL TENANT (Hapus 'public/')
                  $pPath = $item->foto_profil;
                  $cleanTPath = $pPath ? asset('storage/' . str_replace('public/', '', $pPath)) : asset('assets/images/brand/default-tenant.png');
                @endphp
                <li class="tenant-item">
                  <a href="{{ route('tenant', $item->id) }}" class="tenant-info">
                    <img
                      class="tenant-avatar"
                      src="{{ $cleanTPath }}"
                      onerror="this.src='{{ asset('assets/images/brand/default-tenant.png') }}'"
                      alt="Tenant"
                    >
                    <div>
                      <div class="tenant-name">{{ $item->nama_usaha }}</div>
                      <div class="tenant-addr">{!! strip_tags($item->alamat) ?: '-' !!}</div>
                    </div>
                  </a>
                </li>
              @endforeach
            </ul>
          @else
            <div class="p-5 text-center">
              <p class="tenant-empty text-center m-0 py-4" id="tenantEmpty">
                Tidak ada Tenant dari lembaga inkubator.
              </p>
            </div>
          @endif
        </div>
      </div>
    </div>

  </div>
</section>

@include('lembaga-inkubator.public.legalitas')
@include('lembaga-inkubator.public.laporan')
@include('lembaga-inkubator.public.sarana-prasarana')
@include('lembaga-inkubator.public.spesialisasi')
@include('lembaga-inkubator.public.inkubasi')
@include('lembaga-inkubator.public.rencana-strategis')
@include('lembaga-inkubator.public.galeri')

@endsection

@push('scripts')
  <script src="{{ asset('theme/plugins/slick/slick.js') }}"></script>
  <script> $("#inkubator").addClass('active'); </script>
  <script>
    (function () {
      const input = document.getElementById('tenantSearch');
      const list  = document.getElementById('tenantList');
      const empty = document.getElementById('tenantEmpty');
      const form  = document.getElementById('tenantSearchForm');

      if (!input || !list) return;

      if (form) {
        form.addEventListener('submit', function (e) {
          e.preventDefault();
        });
      }

      const items = Array.from(list.querySelectorAll('.tenant-item'));
      const norm = (s) => (s || '').toString().toLowerCase().trim();

      function applyFilter() {
        const q = norm(input.value);
        let shown = 0;
        items.forEach(li => {
          const name = norm(li.querySelector('.tenant-name')?.textContent);
          const addr = norm(li.querySelector('.tenant-addr')?.textContent);
          const ok = !q || name.includes(q) || addr.includes(q);
          li.style.display = ok ? '' : 'none';
          if (ok) shown++;
        });
        if (empty) empty.style.display = (shown === 0) ? '' : 'none';
      }

      input.addEventListener('input', applyFilter);
      applyFilter();
    })();
  </script>
  <script>
  document.addEventListener('DOMContentLoaded', function () {
    const ids = ['modalLegalitas','modalLaporan','modalSaranaPrasarana','modalSpesialisasi','modalInkubasi','modalRencanaStrategis','modalGaleri'];
    ids.forEach(function (id) {
      const el = document.getElementById(id);
      if (el && el.parentElement !== document.body) {
        document.body.appendChild(el);
      }
    });
  });
</script>
@endpush