<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>@yield('title', 'SIPENSI')</title>

  {{-- Bootstrap CSS --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  {{-- Font --}}
  <link href="https://fonts.googleapis.com/css2?family=KoHo:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">




  {{-- Auth CSS --}}
  <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>

<body class="auth-page page-loading @yield('body_class')">
  <header class="auth-topbar">
    <div class="container-fluid px-4">
      <div class="d-flex align-items-center justify-content-between py-3">

        {{-- LOGO GROUP (KLIK BALIK KE DASHBOARD DEPAN) --}}
        <a href="{{ url('/') }}" class="auth-logos-link" aria-label="Kembali ke Beranda">
          <div class="d-flex align-items-center gap-3 auth-logos">
            <!-- <img src="{{ asset('img/logo/logo_umkm_tanpabg.png') }}" class="auth-logo" alt="KemenUMKM"> -->
            <img src="{{ asset('img/logo/logo_incubase_nobg.png') }}" class="auth-logo" alt="Ehub Incubase">
            <!-- <img src="{{ asset('img/logo/logo_sipensi_hd.png') }}" class="auth-logo auth-logo-sipensi" alt="SIPENSI"> -->
          </div>
        </a>

        <nav class="auth-nav d-flex align-items-center gap-3">
          <a href="{{ url('/login') }}" class="auth-nav-link {{ request()->is('login') ? 'active' : '' }}">Masuk</a>
          <a href="{{ url('/register') }}" class="auth-nav-link {{ request()->is('register') ? 'active' : '' }}">Daftar</a>
        </nav>

      </div>
    </div>
  </header>

  <main class="auth-main">
    @yield('content')
  </main>

  {{-- MODAL AREA --}}
  @stack('modals')

  {{-- Bootstrap JS --}}
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  {{-- PAGE SCRIPTS --}}
  @stack('scripts')

  {{-- PAGE LOADER --}}
  <script>
    window.addEventListener('load', () => {
      document.body.classList.remove('page-loading');
      document.body.classList.add('page-loaded');
    });
  </script>
</body>
</html>
