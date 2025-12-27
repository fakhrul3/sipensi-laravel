@php
  use Illuminate\Support\Facades\Route;

  $isHome = request()->routeIs('home');
  $isMitra = request()->routeIs('mitra.*');
  $isInkubator = request()->routeIs('inkubator.*');

  // Login route fallback (some installs don't have auth scaffolding)
  $loginUrl = Route::has('login') ? route('login') : url('/login');
@endphp

<nav id="mainNavbar" class="navbar navbar-expand-lg fixed-top navbar-transparent">
  <div class="container-fluid px-4 navbar-shell">
    <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}" data-page-link aria-label="SIPENSI">
      <img src="{{ asset('img/logo/logo_umkm_tanpabg.png') }}" alt="KemUMKM" class="brand-logo">
      <img src="{{ asset('img/logo/logo_incubase_tanpabg.png') }}" alt="EhubIncubase" class="brand-logo">
      <img src="{{ asset('img/logo/logo_sipensi_public.png') }}" alt="SIPENSI" class="brand-logo">
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbarNav"
            aria-controls="mainNavbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="mainNavbarNav">
      <ul class="navbar-nav mx-auto align-items-lg-center gap-lg-3 menu-center">
      
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('lembaga.*') ? 'active' : '' }}" href="{{ route('lembaga.index') }}" data-page-link>INKUBATOR</a>
        </li>

        

        <li class="nav-item">
          <a class="nav-link {{ $isHome ? '' : '' }}" href="{{ route('home') }}#tentang" data-page-link data-anchor="tentang">TENTANG</a>
        </li>

        <li class="nav-item">
          <a class="nav-link {{ $isMitra ? 'active' : '' }}" href="{{ route('mitra.index') }}" data-page-link>MITRA</a>
        </li>

        <li class="nav-item">
          <a class="nav-link {{ $isHome ? '' : '' }}" href="{{ route('home') }}#kontak" data-page-link data-anchor="kontak">KONTAK</a>
        </li>
      </ul>

      <div class="d-flex align-items-center gap-2">
        <a class="btn btn-login" href="{{ $loginUrl }}">MASUK</a>
      </div>
    </div>
  </div>
</nav>