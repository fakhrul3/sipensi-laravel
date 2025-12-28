<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>@yield('title', 'SIPENSI')</title>

  {{-- Bootstrap --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  {{-- Font --}}
  <link href="https://fonts.googleapis.com/css2?family=KoHo:wght@300;400;600;700&display=swap" rel="stylesheet">

  {{-- Auth CSS (ini harus terakhir) --}}
  <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>

<body class="auth-page page-loading">
  <header class="auth-topbar">
    <div class="container-fluid px-4">
      <div class="d-flex align-items-center justify-content-between py-3">
        <div class="d-flex align-items-center gap-3">
          {{-- GANTI PATH LOGO SESUAI PUNYA KAMU --}}
          <img src="{{ asset('img/logo/logo_umkm_tanpabg.png') }}" class="auth-logo" alt="KemenUMKM">
          <img src="{{ asset('img/logo/logo_incubase_tanpabg.png') }}" class="auth-logo" alt="Ehub Incubase">
          <img src="{{ asset('img/logo/logo_sipensi_hd.png') }}" class="auth-logo" alt="sipensi">
        </div>

        <nav class="auth-nav d-flex align-items-center gap-3">
          <a href="#" class="auth-nav-link">Beranda</a>
          <a href="#" class="auth-nav-link active">Login</a>
          <a href="#" class="auth-nav-link">Register</a>
        </nav>
      </div>
    </div>
  </header>

  <main class="auth-main">
    @yield('content')
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
  window.addEventListener('load', () => {
    document.body.classList.remove('page-loading');
    document.body.classList.add('page-loaded');
  });
</script>

</body>
</html>
