@extends('layouts.auth')
@section('title', 'Masuk | SIPENSI')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-9 col-lg-6 col-xl-5">

      <div class="text-center mb-4">
        <div class="auth-title-logo">
          <img src="{{ asset('img/logo/sipensi_white_nobg.png') }}" alt="SIPENSI3D">
        </div>
      </div>

      <div class="auth-card">
        <form action="#" method="GET" onsubmit="return false;">
          <div class="mb-3">
            <label class="form-label">Username</label>
            <input class="form-control form-control-lg" type="text" placeholder="Masukkan Username Anda">
          </div>

          <div class="mb-3">
            <label class="form-label">Password</label>
            <input class="form-control form-control-lg" type="password" placeholder="Masukkan Password Anda">
          </div>

          <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="remember">
              <label class="form-check-label" for="remember">Ingat Saya</label>
            </div>

            {{-- FIX: LINK BENER --}}
            <a href="{{ url('/forgot-password') }}" class="auth-link">Lupa Password?</a>
          </div>

          <button type="button" class="btn btn-primary w-100 btn-lg auth-btn">
            Masuk
          </button>
        </form>
      </div>

    </div>
  </div>
</div>
@endsection
