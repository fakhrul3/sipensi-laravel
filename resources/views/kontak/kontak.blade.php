@extends('layouts.app')

@section('title', 'SIPENSI - Kontak Kami')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/kontak.css') }}">
@endpush

@section('content')

<main class="page-kontak">

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
                    {{-- kalau mau tampil error validation, bisa pakai:
                    @error('g-recaptcha-response')
                      <div class="contact-error">{{ $message }}</div>
                    @enderror
                    --}}
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
