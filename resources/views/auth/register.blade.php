@extends('layouts.auth')
@section('title', 'Daftar | SIPENSI')
@section('body_class', 'auth-register')

@section('content')
<div class="container-fluid px-0">
  <div class="row g-0 min-vh-100">

    {{-- LEFT : VISUAL (DESKTOP ONLY) --}}
    <div class="col-lg-5 d-none d-lg-block auth-register-left stg stg-1 stg-left">
      <div class="left-visual">
        {{-- MAP UTAMA --}}
        <img
          src="{{ asset('img/peta_3d_nobg.png') }}"
          alt="Peta Indonesia"
          class="register-visual-img"
        />

        {{-- ORNAMEN BAWAH (SUBTLE) --}}
        <img
          src="{{ asset('img/net_3d_nobg.png') }}"
          alt="Network Ornament"
          class="net-banner"
        />
      </div>
    </div>

    {{-- RIGHT : FORM --}}
    <div class="col-lg-7 register-form-col d-flex align-items-center justify-content-center">
      <div class="auth-register-wrapper w-100">

        <div class="text-center mb-4 register-heading">
          <h1 class="auth-title mb-2 stg stg-2">Pendaftaran Lembaga Inkubator</h1>
          <div class="title-divider stg stg-3"></div>
        </div>

        <div class="auth-card auth-card-modern stg stg-4 stg-right">
          <form onsubmit="return false">

            {{-- ROW 1 --}}
            <div class="row g-3 stg stg-5">
              <div class="col-md-6">
                <label class="form-label">Nama Lembaga Inkubator</label>
                <input type="text" class="form-control" placeholder="Nama Inkubator">
              </div>
              <div class="col-md-6">
                <label class="form-label">Induk Lembaga Inkubator</label>
                <input type="text" class="form-control" placeholder="Induk Inkubator">
              </div>
            </div>

            {{-- ROW 2 --}}
            <div class="row g-3 mt-1 stg stg-6">
              <div class="col-md-6">
                <label class="form-label">No Kontak / HP</label>
                <input type="text" class="form-control" placeholder="08xxxxxxxx">
                <small class="form-hint">Pastikan terhubung WhatsApp</small>
              </div>
              <div class="col-md-6">
                <label class="form-label">Email Lembaga</label>
                <input type="email" class="form-control" placeholder="email@domain.go.id">
              </div>
            </div>

            {{-- ALAMAT --}}
            <div class="mt-3 stg stg-7">
              <label class="form-label">Alamat Kantor / Sekretariat</label>
              <textarea class="form-control" rows="3" placeholder="Alamat lengkap"></textarea>
            </div>

            {{-- ROW 3 --}}
            <div class="row g-3 mt-1 stg stg-8">
              <div class="col-md-6">
                <label class="form-label">Provinsi</label>
                <select class="form-select">
                  <option>DKI Jakarta</option>
                  <option>Jawa Barat</option>
                  <option>Jawa Tengah</option>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label">Kabupaten / Kota</label>
                <select class="form-select">
                  <option>DKI Jakarta</option>
                  <option>Bandung</option>
                </select>
              </div>
            </div>

            {{-- JENIS --}}
            <div class="mt-3 stg stg-9">
              <label class="form-label">Jenis Lembaga Inkubator</label>
              <select class="form-select">
                <option>Pemerintah Pusat</option>
                <option>Pemerintah Daerah</option>
                <option>Lembaga Pendidikan</option>
                <option>Badan Usaha</option>
              </select>
            </div>

            {{-- FILE --}}
            <div class="mt-3 stg stg-10">
              <label class="form-label">Dokumen Legalitas</label>
              <input type="file" class="form-control">
              <small class="form-hint">PDF, maks. 10 MB</small>
            </div>

            {{-- AKUN --}}
            <div class="row g-3 mt-2 stg stg-11">
              <div class="col-md-6">
                <label class="form-label">Email / Username</label>
                <input type="text" class="form-control is-invalid">
                <div class="invalid-feedback">
                  Email / Username tidak sesuai
                </div>
              </div>
            </div>

            <div class="row g-3 mt-1 stg stg-12">
              <div class="col-md-6">
                <label class="form-label">Password</label>
                <input type="password" class="form-control">
                <small class="form-hint">4–12 karakter</small>
              </div>
              <div class="col-md-6">
                <label class="form-label">Ketik Ulang Password</label>
                <input type="password" class="form-control">
              </div>
            </div>

            {{-- ACTION --}}
            <div class="d-flex justify-content-between mt-4 stg stg-12">
              <a href="{{ url('/login') }}" class="btn btn-outline-secondary">
                Batal
              </a>
              <button class="btn auth-btn px-5">
                Daftar
              </button>
            </div>

          </form>
        </div>

      </div>
    </div>

  </div>
</div>
@endsection
