  @extends('layouts.app')

  @section('title', 'Detail Tenant - ' . ($row->nama_usaha ?? 'Tenant'))
  @section('bg-variant','bg-detail-inkubator')

  @push('styles')
    <link rel="stylesheet" href="{{ asset('css/lembaga-inkubator.css') }}">
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

    // masa inkubasi
    $tglAwal  = $tenant->tgl_awal_inkubasi ?? null;
    $tglLulus = $tenant->tgl_lulus_inkubasi ?? null;

    // relasi (kalau ada)
    $namaInkubator = optional($tenant->inkubator)->nama_inkubator ?? null;

    // ambil dari controller (Query Builder)
    $bidangUsaha = $namaBidangUsaha ?? '-';
    $klasifikasi = $namaKlasifikasiBisnis ?? '-';

    // foto profil
    $fotoPath = $tenant->foto_profil ?? null;

    // format tanggal (aman)
    $fmt = function ($d) {
      if (!$d) return '-';
      try { return \Carbon\Carbon::parse($d)->format('d M Y'); }
      catch (\Exception $e) { return $d; }
    };

    // ====== GALERI PRODUK (GROUPED per produk) ======
    // relasi: $row->galeriProduk (hasMany) - gunakan galeriProduk untuk menghindari konflik dengan kolom 'produk'
    $produkItems = (isset($row) && isset($row->galeriProduk)) ? $row->galeriProduk : collect();

    // helper normalize path -> supaya relative ke /storage
    $normPath = function($path){
      if (empty($path)) return null;

      $path = str_replace('\\', '/', $path);
      $path = preg_replace('~^public/?~', '', $path);
      $path = ltrim($path, '/');

      return $path ?: null;
    };

    /**
    * $produkGaleri: untuk UI + JS (thumbnail per produk)
    * format:
    * [
    *   [
    *     'produk_id' => 123,
    *     'nama'      => 'Nama Produk',
    *     'thumb'     => 'file_foto_produk/xxx.jpg',
    *     'fotos'     => ['file_foto_produk/a.jpg', 'file_foto_produk/b.jpg']
    *   ],
    * ]
    */
    $produkGaleri = [];

    if ($produkItems && $produkItems->count() > 0) {
      foreach ($produkItems as $p) {
        $arr = $p->foto_produk ?? [];

        // antisipasi kalau masih string JSON
        if (is_string($arr)) {
          $decoded = json_decode($arr, true);
          $arr = is_array($decoded) ? $decoded : [];
        }

        $fotos = [];
        if (is_array($arr) && count($arr)) {
          foreach ($arr as $path) {
            $path = $normPath($path);
            if ($path) $fotos[] = $path;
          }
        }

        // hanya masukin produk yang punya foto
        if (!empty($fotos)) {
          $produkGaleri[] = [
            'produk_id' => $p->id ?? null,
            'nama'      => $p->nama_produk ?? 'Produk',
            'thumb'     => $fotos[0], // cover = foto pertama
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

        <a href="{{ url()->previous() }}" class="btn-li-floating">
          ← Kembali
        </a>

        <div class="card ink-card mb-3">

          {{-- HEADER TEAL (ngikut detail inkubator) --}}
          <div class="ink-card__top">
            <div class="ink-logo">
              <img
                src="{{ $fotoPath ? \Storage::url($fotoPath) : '' }}"
                onerror="this.src='{{ asset('assets/images/brand/default-tenant.png') }}'"
                alt="Foto Profil Tenant"
              >
            </div>

            <div class="ink-head">
              <h3 class="ink-title">{{ $namaUsaha }}</h3>

              <div class="ink-badges">
                {{-- Badge optional --}}
                @if($namaInkubator)
                  <span class="ink-badge ink-badge--ok">{{ $namaInkubator }}</span>
                @endif

                {{-- kalau mau tampilkan klasifikasi sebagai badge --}}
                @if($klasifikasi && $klasifikasi !== '-')
                  <span class="ink-badge badge-default">{{ $klasifikasi }}</span>
                @endif
              </div>
            </div>
          </div>

          {{-- BODY --}}
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-borderless ink-table mb-0">

                <tr>
                  <td class="ink-key"><i class="fa-regular fa-building me-2"></i>Nama Tenant</td>
                  <td class="ink-val">{{ $namaUsaha }}</td>
                </tr>

                <tr>
                  <td class="ink-key"><i class="fa-regular fa-user me-2"></i>Pemilik Usaha</td>
                  <td class="ink-val">{{ $pemilikUsaha }}</td>
                </tr>

                <tr>
                  <td class="ink-key"><i class="fa-solid fa-location-dot me-2"></i>Alamat</td>
                  <td class="ink-val">{!! $alamat ?: '-' !!}</td>
                </tr>

                <tr>
                  <td class="ink-key"><i class="fa-solid fa-briefcase me-2"></i>Bidang Usaha</td>
                  <td class="ink-val">{{ $bidangUsaha }}</td>
                </tr>

                <tr>
                  <td class="ink-key"><i class="fa-solid fa-layer-group me-2"></i>Klasifikasi Bisnis</td>
                  <td class="ink-val">{{ $klasifikasi }}</td>
                </tr>

                <tr>
                  <td class="ink-key"><i class="fa-solid fa-box-open me-2"></i>Produk</td>
                  <td class="ink-val">{{ $produk }}</td>
                </tr>

                <tr>
                  <td class="ink-key"><i class="fa-solid fa-sack-dollar me-2"></i>Omset</td>
                  <td class="ink-val">{{ $omset }}</td>
                </tr>

                <tr>
                  <td class="ink-key"><i class="fa-solid fa-bullhorn me-2"></i>Media Promosi</td>
                  <td class="ink-val">{{ $mediaPromosi }}</td>
                </tr>

                <tr>
                  <td class="ink-key"><i class="fa-solid fa-globe me-2"></i>Jangkauan Pasar</td>
                  <td class="ink-val">{{ $jangkauanPasar }}</td>
                </tr>

                <tr>
                  <td class="ink-key"><i class="fa-solid fa-book me-2"></i>Pembukuan</td>
                  <td class="ink-val">{{ $pembukuan }}</td>
                </tr>

                <tr>
                  <td class="ink-key"><i class="fa-regular fa-calendar me-2"></i>Tanggal Awal Inkubasi</td>
                  <td class="ink-val">{{ $fmt($tglAwal) }}</td>
                </tr>

                <tr>
                  <td class="ink-key"><i class="fa-regular fa-calendar-check me-2"></i>Tanggal Lulus Inkubasi</td>
                  <td class="ink-val">{{ $fmt($tglLulus) }}</td>
                </tr>

                <tr>
                  <td class="ink-key"><i class="fa-regular fa-file-lines me-2"></i>Deskripsi</td>
                  <td class="ink-val">{!! $tenant->deskripsi ?: '-' !!}</td>
                </tr>
              </table>
            </div>
          </div>
        </div>
      </div>

      {{-- KOLOM KANAN --}}
      <div class="col-md-6 col-lg-5 li-col-right">
        <div class="card tenant-card">
          <div class="tenant-card__head">
            <h4 class="fw-bold m-0">Galeri Produk</h4>
          </div>

          <div class="card-body tenant-card__body">
            @if(!empty($produkGaleri) && count($produkGaleri) > 0)

              {{-- GRID PER PRODUK (thumbnail cover + nama produk) --}}
              <div class="p-3">
                <div class="row g-3">
                  @foreach($produkGaleri as $pi => $prod)
                    @php
                      $thumbSrc = \Storage::url($prod['thumb']);
                    @endphp

                    <div class="col-6">
                      <a href="#"
                        class="d-block text-decoration-none"
                        data-bs-toggle="modal"
                        data-bs-target="#produkGaleriModal"
                        data-produk-index="{{ $pi }}">
                        <div class="border rounded overflow-hidden bg-white">
                          <img
                            src="{{ $thumbSrc }}"
                            onerror="this.src='{{ asset('assets/images/brand/default-tenant.png') }}'"
                            class="img-fluid"
                            alt="{{ $prod['nama'] }}"
                            style="width:100%; height:150px; object-fit:cover;"
                          >
                          <div class="p-3">
                            <div class="fw-bold text-dark" style="letter-spacing:.2px;">
                              {{ $prod['nama'] }}
                            </div>
                          </div>
                        </div>
                      </a>
                    </div>
                  @endforeach
                </div>
              </div>

              {{-- MODAL DIPINDAH KE BAWAH SECTION (biar gak ketiban modal-backdrop) --}}

            @else
              <div class="p-4 text-center" style="color:#94a3b8; font-weight:600;">
                Belum ada data galeri produk.
              </div>
            @endif
          </div>
        </div>
      </div>

    </div>
  </section>

  {{-- ✅ MODAL + CAROUSEL (isi dinamis sesuai produk yg diklik)
      Dipindah ke luar section supaya z-index & stacking context aman --}}
  <div class="modal fade" id="produkGaleriModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="produkGaleriModalTitle">{{ $namaUsaha }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <div id="produkGaleriCarousel" class="carousel slide" data-bs-ride="false">
            <div class="carousel-inner" id="produkGaleriCarouselInner">
              {{-- diisi via JS --}}
            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#produkGaleriCarousel" data-bs-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Previous</span>
            </button>

            <button class="carousel-control-next" type="button" data-bs-target="#produkGaleriCarousel" data-bs-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Next</span>
            </button>
          </div>
        </div>

      </div>
    </div>
  </div>

  @endsection

  @push('scripts')
    {{-- nav active tanpa jQuery --}}
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        const el = document.getElementById('inkubator');
        if (el) el.classList.add('active');

        // ✅ PENTING: pindahkan modal ke <body> biar gak ketiban .modal-backdrop
        const modalEl = document.getElementById('produkGaleriModal');
        if (modalEl && modalEl.parentElement !== document.body) {
          document.body.appendChild(modalEl);
        }
      });
    </script>

    {{-- Data galeri untuk JS eksternal --}}
    <script>
      window.TENANT_GALERI = @json($produkGaleri);
    </script>
    <script src="{{ asset('js/tenant-galeri.js') }}" defer></script>
  @endpush
