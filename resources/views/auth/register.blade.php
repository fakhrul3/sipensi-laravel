@extends('layouts.auth')

@section('title', 'Daftar | SIPENSI')
@section('body_class', 'auth-register')

@push('styles')
<style>
    .info-box {
        background-color: #f0f7ff;
        border-left: 4px solid #2563eb;
        padding: 15px;
        border-radius: 8px;
        margin-top: 15px;
    }
    .hr-text {
        display: flex;
        align-items: center;
        text-align: center;
        color: #6c757d;
        font-weight: 600;
        margin: 2rem 0 1rem;
    }
    .hr-text::before, .hr-text::after {
        content: '';
        flex: 1;
        border-bottom: 1px solid #dee2e6;
    }
    .hr-text:not(:empty)::before { margin-right: .5rem; }
    .hr-text:not(:empty)::after { margin-left: .5rem; }
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
                <label class="form-label">No HP / WhatsApp</label>
                <input type="text" name="no_kontak" class="form-control" value="{{ old('no_kontak') }}" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Email Lembaga</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
              </div>
            </div>

            {{-- ALAMAT --}}
            <div class="mt-3">
              <label class="form-label">Alamat Kantor</label>
              <textarea name="alamat_kantor" class="form-control" rows="2">{{ old('alamat_kantor') }}</textarea>
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
            <div class="hr-text">Konfigurasi Akun</div>

            <div class="mt-2">
              <label class="form-label">Username yang Diinginkan</label>
              <input type="text" name="username" class="form-control" value="{{ old('username') }}" placeholder="Contoh: inkubator_maju" required>
            </div>

            {{-- INFORMASI OTOMATISASI PASSWORD --}}
            <div class="info-box shadow-sm">
                <div class="d-flex">
                    <div class="me-3">
                        <i class="bi bi-info-circle-fill text-primary" style="font-size: 1.5rem;"></i>
                    </div>
                    <div>
                        <p class="small mb-0">
                            <strong>Penting:</strong> Anda tidak perlu membuat password sekarang. Sistem akan 
                            <strong>mengirimkan password akses</strong> ke email Anda setelah pendaftaran ini 
                            disetujui oleh Administrator SIPENSI.
                        </p>
                    </div>
                </div>
            </div>

            {{-- ACTION --}}
            <div class="d-flex justify-content-between mt-4">
              <a href="{{ route('login') }}" class="btn btn-outline-secondary px-4">Kembali ke Login</a>
              <button type="submit" class="btn auth-btn px-4 fw-bold">Daftar Sekarang</button>
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
    
    // --- ALERT ---
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

    // --- AJAX WILAYAH ---
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
                    }
                }
            });
        }
    });
});
</script>
@endsection