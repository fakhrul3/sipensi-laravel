@extends('layouts.auth')
@section('title', 'Masuk | SIPENSI')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-9 col-lg-6 col-xl-5">

      <div class="text-center mb-4 stg stg-1">
        <div class="auth-title-logo">
          <img src="{{ asset('img/logo/sipensi_white_nobg.png') }}" alt="SIPENSI3D">
        </div>
      </div>

      <div class="auth-card stg stg-2">

        {{-- FIX: form beneran ke endpoint login --}}
        <form action="{{ url('/login') }}" method="POST">
          @csrf

          <div class="mb-3 stg stg-3">
            <label class="form-label">Username</label>
            <input
              name="username"
              class="form-control form-control-lg @error('username') is-invalid @enderror"
              type="text"
              placeholder="Masukkan Username Anda"
              value="{{ old('username') }}"
              autocomplete="username"
              required
            >
            @error('username')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3 stg stg-4">
            <label class="form-label">Password</label>

            <div class="input-group input-group-lg">
              <input
                id="password"
                name="password"
                class="form-control @error('password') is-invalid @enderror"
                type="password"
                placeholder="Masukkan Password Anda"
                autocomplete="current-password"
                required
              >

              <button
                class="btn btn-outline-secondary auth-eye-btn"
                type="button"
                id="togglePassword"
                aria-label="Tampilkan/Sembunyikan Password"
              >
                <!-- icon mata (inline SVG, gak perlu library) -->
                <svg id="eyeIcon" width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                  <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z" stroke="currentColor" stroke-width="2"/>
                  <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" stroke="currentColor" stroke-width="2"/>
                </svg>
              </button>

              @error('password')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>
          </div>


          <div class="d-flex justify-content-between align-items-center mb-4 stg stg-5">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="remember" name="remember">
              <label class="form-check-label" for="remember">Ingat Saya</label>
            </div>

            <a href="{{ url('/forgot-password') }}" class="auth-link">Lupa Password?</a>
          </div>

          {{-- FIX: harus submit --}}
          <button type="submit" class="btn btn-primary w-100 btn-lg auth-btn stg stg-6">
            Masuk
          </button>

        </form>
      </div>

    </div>
  </div>
</div>

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const pwd = document.getElementById('password');
    const btn = document.getElementById('togglePassword');
    const icon = document.getElementById('eyeIcon');

    if (!pwd || !btn) return;

    btn.addEventListener('click', () => {
      const isHidden = pwd.type === 'password';
      pwd.type = isHidden ? 'text' : 'password';

      // ganti icon (mata terbuka vs mata coret)
      icon.innerHTML = isHidden
        ? `
          <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z" stroke="currentColor" stroke-width="2"/>
          <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" stroke="currentColor" stroke-width="2"/>
        `
        : `
          <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z" stroke="currentColor" stroke-width="2"/>
          <path d="M4 4l16 16" stroke="currentColor" stroke-width="2"/>
        `;
    });
  });
</script>
{{-- Pastikan JQuery & SweetAlert sudah di-load di layout atau di sini --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function () {
    // Popup Berhasil (Pesan Success dari Redirect Register)
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            confirmButtonColor: '#3085d6',
        });
    @endif

    // Popup Gagal (Jika ada error login)
    @if($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Gagal Masuk',
            text: "{{ $errors->first() }}",
            confirmButtonColor: '#d33',
        });
    @endif
});
</script>
@endpush


@endsection
  