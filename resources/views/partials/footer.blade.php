<footer class="sipensi-footer">
  <div class="container-fluid sipensi-footer-shell">
    <div class="row gy-4 align-items-start">

      {{-- LEFT: LOGO + ADDRESS --}}
      <div class="col-lg-6">
        <div class="footer-brand">
          <img src="{{ asset('img/logo/logo_ehub_putih.png') }}"
               alt="EHUB Incubase"
               class="footer-brand-logo">

          <p class="footer-address " >
            SME Tower Lt. 14, Jl. Gatot Subroto No. Kav. 94,
            Pancoran, Kota Jakarta Selatan, DKI Jakarta 12780, Indonesia
          </p>
        </div>
      </div>

      {{-- MIDDLE: QUICK LINKS --}}
      <div class="col-lg-3">
        <h6 class="footer-title">Navigasi</h6>
        <ul class="footer-links">
          <li><a href="{{ route('lembaga.index') }}">Inkubator</a></li>
          <li><a href="{{ route('home') }}">Tentang</a></li>
          <li><a href="{{ route('mitra.index') }}">Mitra</a></li>
          <li><a href="{{ route('home') }}">Kontak</a></li>
        </ul>
      </div>

      {{-- RIGHT: CONTACT --}}
      <div class="col-lg-3">
        <h6 class="footer-title">Kontak</h6>
        <ul class="footer-contact">
          <li>
            <i class="fa-regular fa-envelope"></i>
            <a href="mailto:halo.sipensi@umkm.go.id">halo.sipensi@umkm.go.id</a>
          </li>
          <li>
            <i class="fa-brands fa-youtube"></i>
            <a href="https://www.youtube.com/@ehub.incubase" target="_blank">
              @ehub.incubase
            </a>
          </li>
          <li>
            <i class="fa-brands fa-instagram"></i>
            <a href="https://www.instagram.com/ehub.incubase" target="_blank">
              @ehub.incubase
            </a>
          </li>
          <li>
            <i class="fa-brands fa-whatsapp"></i>
            <a href="https://wa.me/62811380280" target="_blank" rel="noopener">
                (+62) 811-380-280
            </a>
          </li>
        </ul>
      </div>

    </div>

    <hr class="footer-divider">

    <div class="footer-bottom">
      © 2025 SIPENSI – Sistem Pendaftaran Informasi dan Evaluasi Inkubasi. All rights reserved.
    </div>
  </div>
</footer>
