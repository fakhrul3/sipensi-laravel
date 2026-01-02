@extends('layouts.app')

@section('title', 'SIPENSI - Kontak Kami')

@push('styles')
  <link rel="stylesheet" href="{{ asset('css/kontak.css') }}">
@endpush

@section('content')


<main class="page-kontak">

  {{-- =========================
      FAQ SECTION (SUDAH BENER)
  ========================== --}}
  <section class="faq-section">
    <div class="faq-container">
      <div class="faq-header">
        <h2>Pertanyaan yang Sering Diajukan</h2>
        <p>Temukan jawaban untuk pertanyaan umum tentang SIPENSI</p>
      </div>

      <div class="faq-content-wrapper">
        {{-- FAQ Image --}}
        <div class="faq-image-container">
          {{-- ganti nama file kalau beda --}}
          <img src="{{ asset('img/image9.jpg') }}" alt="FAQ SIPENSI">
        </div>

        {{-- FAQ Accordion --}}
        <div class="faq-accordion">

          {{-- 1 --}}
          <div class="faq-item">
            <div class="faq-question" onclick="toggleFaq(this)">
              <span>APA ITU SIPENSI?</span>
              <i class="fa fa-chevron-down faq-icon"></i>
            </div>
            <div class="faq-answer">
              <div class="faq-answer-content">
                SIPENSI adalah Sistem Pendaftaran Informasi dan Evaluasi Inkubasi yang dikembangkan oleh
                Deputi Bidang Kewirausahaan, Kementerian Usaha Mikro, Kecil, dan Menengah Republik Indonesia.
                Sistem ini dirancang untuk mendaftarkan dan mengevaluasi lembaga inkubator di seluruh Indonesia.
              </div>
            </div>
          </div>

          {{-- 2 --}}
          <div class="faq-item">
            <div class="faq-question" onclick="toggleFaq(this)">
              <span>SIAPA SAJA YANG DAPAT MENDAFTAR PADA SITUS SIPENSI?</span>
              <i class="fa fa-chevron-down faq-icon"></i>
            </div>
            <div class="faq-answer">
              <div class="faq-answer-content">
                Lembaga Inkubator dari berbagai kategori dapat mendaftar di SIPENSI, yaitu:
                <ul>
                  <li>Pemerintah Pusat</li>
                  <li>Pemerintah Daerah</li>
                  <li>Lembaga Pendidikan</li>
                  <li>Badan Usaha</li>
                  <li>Masyarakat</li>
                </ul>
              </div>
            </div>
          </div>

          {{-- 3 --}}
          <div class="faq-item">
            <div class="faq-question" onclick="toggleFaq(this)">
              <span>APAKAH LEMBAGA PELATIHAN/PENDAMPINGAN BISA MENDAFTAR DI SIPENSI?</span>
              <i class="fa fa-chevron-down faq-icon"></i>
            </div>
            <div class="faq-answer">
              <div class="faq-answer-content">
                Bisa. Lembaga pelatihan/pendampingan dapat mendaftar di SIPENSI dengan syarat lembaga tersebut
                berfungsi sebagai Lembaga Inkubator yang melakukan kegiatan pra-inkubasi, inkubasi, dan pasca-inkubasi
                sesuai dengan peraturan yang berlaku.
              </div>
            </div>
          </div>

          {{-- 4 --}}
          <div class="faq-item">
            <div class="faq-question" onclick="toggleFaq(this)">
              <span>BAGAIMANA CARA MENDAFTAR DI SIPENSI?</span>
              <i class="fa fa-chevron-down faq-icon"></i>
            </div>
            <div class="faq-answer">
              <div class="faq-answer-content">
                <strong>Langkah Pertama:</strong> Kunjungi situs SIPENSI di sipensi.umkm.go.id, kemudian klik tombol Register
                untuk mendaftarkan akun Lembaga Inkubator.<br><br>
                <strong>Langkah Kedua:</strong> Lengkapi semua form pendaftaran dan unggah dokumen yang diperlukan.
                Setelah itu, lakukan verifikasi melalui tautan yang dikirim ke email Anda. Jika persyaratan terpenuhi,
                akun Anda akan diverifikasi oleh administrator.
              </div>
            </div>
          </div>

          {{-- 5 --}}
          <div class="faq-item">
            <div class="faq-question" onclick="toggleFaq(this)">
              <span>APAKAH SIPENSI DAPAT DIAKSES MELALUI PERANGKAT MOBILE?</span>
              <i class="fa fa-chevron-down faq-icon"></i>
            </div>
            <div class="faq-answer">
              <div class="faq-answer-content">
                Saat ini, SIPENSI dapat diakses melalui browser pada perangkat mobile. Pengembangan aplikasi mobile khusus
                untuk SIPENSI masih dalam tahap penyempurnaan dan akan segera diluncurkan.
              </div>
            </div>
          </div>

        </div>
      </div>

    </div>
  </section>


  {{-- =========================
      CONTACT SECTION (PAKAI PUNYA KAMU)
      NOTE: MASIH MOCKUP (BELUM BACKEND)
  ========================== --}}
  <section class="contact-top">
    <div class="container">

      <div class="contact-top-grid">

        {{-- LEFT COLUMN --}}
        <div class="contact-top-left reveal">
          <h1 class="contact-title">Kontak Kami</h1>

          <p class="contact-subtitle contact-subtitle--org">
            Asisten Deputi Inkubasi dan Digitalisasi Wirausaha<br>
            Deputi Bidang Kewirausahaan<br>
            Kementerian Usaha Mikro, Kecil, dan Menengah
          </p>

          {{-- MAPS --}}
          <div class="contact-map contact-map--col reveal d-1">
            <iframe
              src="https://www.google.com/maps?q=SME%20Tower%20Kementerian%20UMKM&output=embed"
              loading="lazy"
              allowfullscreen
              referrerpolicy="no-referrer-when-downgrade">
            </iframe>

            <div class="contact-map-caption">
              SME Tower, Jl. Gatot Subroto Kav. 94, Pancoran, Jakarta Selatan
            </div>
          </div>
        </div>

        {{-- RIGHT COLUMN : FORM --}}
        <div class="contact-top-right reveal d-1">

          <div class="contact-form-wrap contact-form-wrap--blue">
            {{-- overlay biar ada efek gelap transparan --}}
            <div class="contact-form-overlay contact-form-overlay--blue"></div>

            <form class="contact-form contact-form--onblue" action="#" method="POST">
              @csrf

              <div class="row g-3">

                <div class="col-md-6">
                  <input
                    type="text"
                    name="name"
                    class="form-control contact-input contact-input--onblue"
                    placeholder="Nama"
                    required>
                </div>

                <div class="col-md-6">
                  <input
                    type="email"
                    name="email"
                    class="form-control contact-input contact-input--onblue"
                    placeholder="Email"
                    required>
                </div>

                <div class="col-12">
                  <textarea
                    name="message"
                    class="form-control contact-textarea contact-input--onblue"
                    rows="10"
                    placeholder="Deskripsi"
                    required></textarea>
                </div>

                {{-- CAPTCHA --}}
                <div class="col-12">
                  <div class="contact-captcha">
                    <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
                  </div>
                </div>

                <div class="col-12">
                  <button type="submit" class="btn contact-submit contact-submit--onblue">
                    KIRIM
                  </button>
                </div>

              </div>
            </form>

          </div>

        </div>

      </div>
    </div>
  </section>

</main>

{{-- reCAPTCHA script --}}
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

@endsection

@push('scripts')
  <script src="{{ asset('js/kontak.js') }}"></script>
@endpush
