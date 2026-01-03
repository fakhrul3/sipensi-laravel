<section class="tahapan-inkubasi-section">
  <div class="container-fluid px-lg-5">

    <div class="tahapan-inkubasi-title reveal">
      <h1 class="reveal">Tahapan Inkubasi</h1>
      <p class="reveal d-1">
        Proses inkubasi terdiri dari tiga tahapan utama yang dirancang untuk memberikan pendampingan berkelanjutan dari persiapan hingga pengembangan bisnis yang mandiri.
      </p>
    </div>

    <div class="tahapan-tabs reveal d-2" data-tahapan-tabs>
      <button class="tahapan-tab-btn active" data-tahap="pra-inkubasi" type="button">
        <i class="fa-solid fa-file-lines"></i>
        <span>Pra-Inkubasi</span>
      </button>

      <button class="tahapan-tab-btn" data-tahap="inkubasi" type="button">
        <i class="fa-solid fa-house-chimney-user"></i>
        <span>Inkubasi</span>
      </button>

      <button class="tahapan-tab-btn" data-tahap="pasca-inkubasi" type="button">
        <i class="fa-solid fa-chart-simple"></i>
        <span>Pasca-Inkubasi</span>
      </button>
    </div>

    <div class="tahapan-content-wrapper reveal d-3" data-tahapan-wrap>
      {{-- Arrows --}}
      <button class="nav-arrow-left hidden" data-nav="left" type="button" aria-label="Previous">
        <i class="fa-solid fa-chevron-left"></i>
      </button>
      <button class="nav-arrow-right" data-nav="right" type="button" aria-label="Next">
        <i class="fa-solid fa-chevron-right"></i>
      </button>

      {{-- Pra --}}
      <div class="tahapan-content-card tahap-content" id="pra-inkubasi-content" data-tahap-content="pra-inkubasi" style="display:none;">
        <div class="tahap-title">
          <div class="icon-container"><i class="fa-solid fa-file-lines"></i></div>
          <h2>Tahap Pra-Inkubasi</h2>
        </div>
        <p class="tahap-description">
          Tahap persiapan dan seleksi sebelum masuk ke program inkubasi. Meliputi identifikasi potensi bisnis, seleksi peserta, dan persiapan dokumen kontrak.
        </p>
        <div class="aktivitas-utama-section">
          <h3>Aktivitas Utama</h3>
          <ol class="aktivitas-list">
            <li>Penawaran Program Inkubasi</li>
            <li>Seleksi Peserta Inkubasi / Tenant</li>
            <li>Kontrak Tertulis</li>
          </ol>
        </div>
      </div>

      {{-- Inkubasi --}}
      <div class="tahapan-content-card tahap-content" id="inkubasi-content" data-tahap-content="inkubasi" style="display:none;">
        <div class="tahap-title">
          <div class="icon-container"><i class="fa-solid fa-house-chimney-user"></i></div>
          <h2>Tahap Inkubasi</h2>
        </div>
        <p class="tahap-description">
          Tahap pengembangan dan pendampingan aktif selama masa inkubasi. Meliputi pelatihan, bimbingan, konsultasi, dan pendampingan pengembangan usaha.
        </p>
        <div class="aktivitas-utama-section">
          <h3>Aktivitas Utama</h3>
          <ol class="aktivitas-list">
            <li>Perumusan Ide Usaha</li>
            <li>Pelatihan Ide Usaha Tenant</li>
            <li>Pemberian Bimbingan dan Konsultasi Pengembangan Usaha</li>
            <li>Pendampingan</li>
            <li>Pertemuan Mitra Usaha (Business Matching)</li>
          </ol>
        </div>
      </div>

      {{-- Pasca (default tampil) --}}
      <div class="tahapan-content-card tahap-content is-active" id="pasca-inkubasi-content" data-tahap-content="pasca-inkubasi">
        <div class="tahap-title">
          <div class="icon-container"><i class="fa-solid fa-chart-simple"></i></div>
          <h2>Tahap Pasca-Inkubasi</h2>
        </div>
        <p class="tahap-description">
          Tahap pengawasan dan pengembangan lanjutan setelah masa inkubasi. Meliputi monitoring pertumbuhan bisnis, evaluasi keberlanjutan, dan pengembangan jaringan bisnis.
        </p>
        <div class="aktivitas-utama-section">
          <h3>Aktivitas Utama</h3>
          <ol class="aktivitas-list">
            <li>Menyediakan Jejaring Antar Tenant</li>
            <li>Memberi Peluang Partisipasi kepemilikan pada perusahaan tenant</li>
            <li>Melakukan Monitoring dan Evaluasi perkembangan usaha tenant paling singkat 2 tahun</li>
            <li>Memberikan Fasilitas Akses Sumber Pembiayaan</li>
            <li>Mengarahkan para Alumni Lembaga Inkubator membentuk wadah yang legal dalam pengembangan usaha</li>
          </ol>
        </div>
      </div>

    </div>
  </div>
</section>
