@extends('layouts.auth')

@section('title', 'Daftar | SIPENSI')
@section('body_class', 'auth-register')

@push('styles')
{{-- Link Bootstrap Icons untuk Icon Mata --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
    .password-field {
        position: relative;
    }
    .toggle-password {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #6c757d;
        z-index: 10;
        font-size: 1.2rem;
    }
    .password-field input {
        padding-right: 45px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-0">
  <div class="row g-0 min-vh-100">

    {{-- LEFT - Visual --}}
    <div class="col-lg-5 d-none d-lg-block auth-register-left">
      <div class="left-visual">
        <img src="{{ asset('img/peta_3d_nobg.png') }}" class="register-visual-img">
        <img src="{{ asset('img/net_3d_nobg.png') }}" class="net-banner">
      </div>
    </div>

    {{-- RIGHT - Form Section --}}
    <div class="col-lg-7 d-flex align-items-center justify-content-center">
      <div class="auth-register-wrapper w-100 pe-lg-5 me-lg-4 ps-lg-4 px-3">

        <div class="text-center mb-4">
          <h1 class="auth-title">Pendaftaran Lembaga Inkubator</h1>
          <div class="title-divider"></div>
        </div>

        <div class="auth-card auth-card-modern">
          <form action="{{ route('register.post') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- NAMA --}}
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Nama Lembaga Inkubator</label>
                <input type="text" name="nama_inkubator" class="form-control" value="{{ old('nama_inkubator') }}" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Induk Lembaga Inkubator</label>
                <input type="text" name="induk_inkubator" class="form-control" value="{{ old('induk_inkubator') }}" required>
              </div>
            </div>

            {{-- KONTAK --}}
            <div class="row g-3 mt-2">
              <div class="col-md-6">
                <label class="form-label">No HP</label>
                <input type="text" name="no_kontak" class="form-control" value="{{ old('no_kontak') }}" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
              </div>
            </div>

            {{-- ALAMAT --}}
            <div class="mt-3">
              <label class="form-label">Alamat Kantor</label>
              <textarea name="alamat_kantor" class="form-control" rows="3">{{ old('alamat_kantor') }}</textarea>
            </div>

            {{-- WILAYAH --}}
            <div class="row g-3 mt-2">
              <div class="col-md-6">
                <label class="form-label">Provinsi</label>
                <select id="provinsi" name="provinsi_id" class="form-select" required>
                  <option value="">-- Pilih Provinsi --</option>
                  @foreach($provinsi as $p)
                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                  @endforeach
                </select>
              </div>

              <div class="col-md-6">
                <label class="form-label">Kabupaten / Kota</label>
                <select id="kabupaten" name="kabupaten_id" class="form-select" disabled required>
                  <option value="">Pilih Provinsi dahulu</option>
                </select>
              </div>
            </div>

            {{-- JENIS --}}
            <div class="mt-3">
              <label class="form-label">Jenis Lembaga Inkubator</label>
              <select name="jenis_inkubator" class="form-select" required>
                <option value="">-- Pilih Jenis --</option>
                @foreach($jenis_lembaga as $id => $nama)
                  <option value="{{ $id }}">{{ $nama }}</option>
                @endforeach
              </select>
            </div>

            {{-- FILE --}}
            <div class="mt-3">
              <label class="form-label">Dokumen Legalitas (PDF)</label>
              <input type="file" name="path_legalitas" class="form-control" accept=".pdf">
              <small class="text-muted text-italic">*Format PDF, Maksimal 2MB</small>
            </div>

            {{-- AKUN --}}
            <div class="hr-text mt-4">Informasi Akun</div>

            <div class="mt-2">
              <label class="form-label">Username</label>
              <input type="text" name="username" class="form-control" value="{{ old('username') }}" required>
            </div>

            <div class="row g-3 mt-2">
              {{-- PASSWORD --}}
              <div class="col-md-6">
                <label class="form-label">Password</label>
                <div class="password-field">
                  <input type="password" name="password" id="password" class="form-control" required>
                  <i class="bi bi-eye-slash toggle-password" data-target="#password"></i>
                </div>
              </div>

              {{-- KONFIRMASI PASSWORD --}}
              <div class="col-md-6">
                <label class="form-label">Konfirmasi Password</label>
                <div class="password-field">
                  <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                  <i class="bi bi-eye-slash toggle-password" data-target="#password_confirmation"></i>
                </div>
              </div>
            </div>

            {{-- ACTION --}}
            <div class="d-flex justify-content-between mt-4">
              <a href="{{ route('login') }}" class="btn btn-outline-secondary">Batal</a>
              <button type="submit" class="btn auth-btn px-4">Daftar Sekarang</button>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- JQUERY & SWEETALERT --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function () {
    
    // --- 1. ALERT BERHASIL/GAGAL ---
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            confirmButtonColor: '#3085d6',
        });
    @endif

    @if($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Pendaftaran Gagal',
            html: '<ul style="text-align:left;">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
            confirmButtonColor: '#d33',
        });
    @endif

    // --- 2. FITUR SHOW/HIDE PASSWORD (FIXED BOOTSTRAP ICONS) ---
    $('.toggle-password').on('click', function() {
        let target = $(this).data('target');
        let input = $(target);
        
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            $(this).removeClass('bi-eye-slash').addClass('bi-eye');
        } else {
            input.attr('type', 'password');
            $(this).removeClass('bi-eye').addClass('bi-eye-slash');
        }
    });

    // --- 3. AJAX WILAYAH ---
    $('#provinsi').on('change', function () {
        let provinsiId = $(this).val();
        let kabupatenDropdown = $('#kabupaten');

        kabupatenDropdown.prop('disabled', true).html('<option value="">Sedang memuat...</option>');

        if (provinsiId) {
            let url = "{{ route('get.kabupaten', ':id') }}";
            url = url.replace(':id', provinsiId);

            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    kabupatenDropdown.empty().append('<option value="">-- Pilih Kabupaten/Kota --</option>');
                    if (data.length > 0) {
                        $.each(data, function (key, value) {
                            kabupatenDropdown.append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                        kabupatenDropdown.prop('disabled', false);
                    } else {
                        kabupatenDropdown.html('<option value="">Data tidak ditemukan</option>');
                    }
                },
                error: function (xhr) {
                    kabupatenDropdown.html('<option value="">Gagal mengambil data</option>');
                }
            });
        } else {
            kabupatenDropdown.html('<option value="">Pilih Provinsi dahulu</option>').prop('disabled', true);
        }
    });
});
</script>

@endsection