@extends('layouts.auth')
@section('title', 'Lupa Password | SIPENSI')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-9 col-lg-6 col-xl-5">

      <div class="text-center mb-4">
        <h1 class="auth-title auth-title-sm stg stg-1">Lupa Password</h1>
        <p class="auth-subtitle stg stg-2">
          Masukan Email yang terdaftar <br> Kami akan mengirimkan tautan untuk reset password
        </p>
      </div>

      <div class="auth-card auth-card-modern stg stg-3">
        <form action="#" method="POST" onsubmit="return false;">

          <div class="mb-3 stg stg-4">
            <label class="form-label">Email</label>
            <input type="email" class="form-control form-control-lg" placeholder="contoh@domain.go.id">
            <div class="form-hint mt-2">
              Pastikan email sesuai saat registrasi.
            </div>
          </div>

          <button type="button" class="btn btn-primary w-100 btn-lg auth-btn stg stg-5" id="btnSendLinkMock">
            Kirim Tautan
          </button>

          <div class="d-flex justify-content-between align-items-center mt-4 stg stg-6">
            <a href="{{ url('/login') }}" class="auth-link">← Kembali ke Login</a>

            {{-- FIX: trigger modal --}}
            <a href="javascript:void(0)" class="auth-link" data-bs-toggle="modal" data-bs-target="#helpModal">
              Butuh bantuan?
            </a>
          </div>

        </form>
      </div>

    </div>
  </div>
</div>
@endsection

@push('modals')
<div class="modal fade" id="helpModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content help-modal">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold">Butuh bantuan?</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body pt-2">
        <p class="text-muted mb-3">
          Silakan hubungi kami melalui kanal dibawah ini
        </p>

        <div class="d-grid gap-2">
          <a class="btn btn-outline-primary help-btn"
             href="https://wa.me/62811380280" target="_blank" rel="noopener">
            WhatsApp Helpdesk
          </a>

          <a class="btn btn-outline-primary help-btn"
             href="mailto:halo.sipensi@umkm.go.id?subject=Bantuan%20Lupa%20Password%20SIPENSI">
            Email Support
          </a>

          <a class="btn btn-outline-primary help-btn" href="javascript:void(0)">
            Panduan / FAQ
          </a>
        </div>

        <div class="help-note mt-3">
          Jam layanan: Senin–Jumat, 08.00–16.00 WIB (Hari Kerja)
        </div>
      </div>
    </div>
  </div>
</div>
@endpush

@push('scripts')
<script>
  document.getElementById('btnSendLinkMock')?.addEventListener('click', () => {
    alert('Mockup: tautan reset password belum terhubung ke sistem.');
  });
</script>
@endpush
