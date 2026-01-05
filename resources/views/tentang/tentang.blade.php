@extends('layouts.app')

@push('styles')
  <link rel="stylesheet" href="{{ asset('css/tentang.css') }}">
  <link rel="stylesheet" href="{{ asset('css/tahapan-inkubasi.css') }}">

@endpush

@section('content')

{{-- WRAPPER: page enter khusus Tentang --}}
<div id="tentangRoot" class="page-enter">

  {{-- HERO TENTANG --}}
  <section class="tentang-hero" style="background-image: url('{{ asset('/img/bg_tentang.jpg') }}');">
    <div class="tentang-hero__overlay"></div>

    <div class="tentang-container tentang-hero__content">
      <p class="tentang-hero__kicker reveal d-1">SIPENSI KEMENTERIAN UMKM</p>
      <h1 class="tentang-hero__title reveal d-2">Tentang SIPENSI</h1>
      <p class="tentang-hero__subtitle reveal d-3">
        Platform digital strategis Kementerian UMKM dalam memantau dan memperkuat ekosistem inkubasi nasional guna mencetak wirausaha baru yang inovatif dan berdaya saing global.
      </p>
    </div>
  </section>

  {{-- KONTEN UTAMA --}}
 <section class="tentang-section tentang-section--white">
    <div class="tentang-content-card">
      <div class="tentang-content-wrap">
        <p class="tentang-text reveal">
          <strong> SIPENSI </strong> adalah <strong>platform informasi nasional yang menghubungkan lembaga inkubator dan usaha rintisan</strong> dalam satu ekosistem inkubasi. Dikelola oleh Kementerian UMKM, SIPENSI menjadi <strong>sistem resmi untuk pendaftaran dan pemutakhiran data lembaga inkubator di Indonesia</strong>, sekaligus sumber informasi terbuka bagi usaha rintisan yang ingin menemukan inkubator yang sesuai.
        </p>

        <p class="tentang-text reveal d-1">
          Lembaga inkubator yang tercatat di SIPENSI dapat diselenggarakan oleh berbagai pihak, termasuk Pemerintah Pusat, Pemerintah Daerah, lembaga pendidikan, badan usaha, maupun masyarakat. Melalui <strong>satu sistem terintegrasi</strong>, SIPENSI menghimpun informasi inkubator secara nasional agar <strong>mudah diakses, ditelusuri, dan dimanfaatkan oleh ekosistem inkubasi</strong>.
          Selain sebagai basis data resmi, SIPENSI juga mendukung <strong>pemantauan dan evaluasi penyelenggaraan inkubasi secara berkala</strong>, sehingga tata kelola inkubasi dapat berjalan lebih <strong>transparan, terhubung, dan berkelanjutan</strong>.
        </p>

        <p class="tentang-text tentang-text--last reveal d-2">
          <small> Pengelolaan SIPENSI mengacu pada <strong>Peraturan Pemerintah Republik Indonesia Nomor 7 Tahun 2021</strong>. </small> 
        </p>
      </div>
    </div>
  </section>

  {{-- SKEMA --}}
  <section class="tentang-section tentang-section--soft">
    <div class="tentang-container">
      <h2 class="tentang-title reveal">Skema Inkubasi</h2>
      <p class="tentang-subtitle reveal d-1">
       Skema inkubasi dirancang sebagai kerangka pendampingan untuk mendukung UMKM dan usaha rintisan berkembang secara bertahap, terarah, dan berkelanjutan.
<small> Skema ini disusun mengacu pada <strong>Peraturan Pemerintah Republik Indonesia Nomor 7 Tahun 2021. </strong></small></p>

      <div class="row g-4">
        <div class="col-md-6 col-lg-4 reveal d-1">
          <div class="tentang-card">
            <h3 class="tentang-card__title">Tujuan Inkubasi</h3>
            <p class="tentang-card__desc">
              Meningkatkan kemampuan, nilai tambah, dan daya saing UMKM melalui pendampingan intensif.
            </p>
          </div>
        </div>

        <div class="col-md-6 col-lg-4 reveal d-2">
          <div class="tentang-card">
            <h3 class="tentang-card__title">Penyelenggara</h3>
            <p class="tentang-card__desc">
              Lembaga yang memenuhi kualifikasi teknis untuk mengelola proses inkubasi tenant secara profesional.
            </p>
          </div>
        </div>

        <div class="col-md-6 col-lg-4 reveal d-3">
          <div class="tentang-card">
            <h3 class="tentang-card__title">Aktivitas Utama</h3>
            <p class="tentang-card__desc">
              Meliputi seleksi tenant, pelatihan manajemen, hingga fasilitasi akses pasar dan permodalan.
            </p>
          </div>
        </div>

        <div class="col-md-6 col-lg-4 reveal d-1">
          <div class="tentang-card">
            <h3 class="tentang-card__title">Aspek Layanan</h3>
            <p class="tentang-card__desc">
              Penyediaan ruang kantor, sarana produksi, konsultasi hukum, hingga bimbingan teknis produk.
            </p>
          </div>
        </div>

        <div class="col-md-6 col-lg-4 reveal d-2">
          <div class="tentang-card">
            <h3 class="tentang-card__title">Kerja Sama</h3>
            <p class="tentang-card__desc">
              Membangun jejaring antara pemerintah, akademisi, dunia usaha, dan komunitas (Hexa-helix).
            </p>
          </div>
        </div>

      </div>
    </div>
  </section>

</div>

{{-- TAHAPAN INKUBASI --}}
@include('partials.tahapan-inkubasi')

@endsection

@push('scripts')
<script>
  // Optional: kasih class active ke menu "Tentang" TANPA jQuery
  // Pastikan di navbar ada id="tentang" pada link Tentang
  document.addEventListener('DOMContentLoaded', () => {
    const el = document.getElementById('tentang');
    if (el) el.classList.add('active');
  });
</script>

<script src="{{ asset('js/scroll-reveal.js') }}"></script>
<script src="{{ asset('js/tahapan-inkubasi.js') }}"></script>

@endpush
