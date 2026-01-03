@php
  use Illuminate\Support\Facades\Route;

  $isHome = request()->routeIs('home');
  $isMitra = request()->routeIs('mitra.*');
  $isInkubator = request()->routeIs('inkubator.*');

  // Login route fallback
  $loginUrl = Route::has('login') ? route('login') : url('/login');
@endphp

<nav id="mainNavbar" class="navbar navbar-expand-lg fixed-top navbar-transparent">
  <div class="container-fluid px-4 navbar-shell">

    {{-- LOGO --}}
    <a
      href="{{ route('home') }}"
      class="navbar-brand navbar-logo-link d-flex align-items-center"
      data-page-link
      aria-label="SIPENSI"
    >
      <span class="navbar-logo-wrap">
        <img
          src="{{ asset('img/logo/logo_incubase_nobg.png') }}"
          alt="EHUB Incubase"
          class="navbar-logo-img"
        >
      </span>
    </a>

    {{-- TOGGLER --}}
    <button
      class="navbar-toggler"
      type="button"
      data-bs-toggle="collapse"
      data-bs-target="#mainNavbarNav"
      aria-controls="mainNavbarNav"
      aria-expanded="false"
      aria-label="Toggle navigation"
    >
      <span class="navbar-toggler-icon"></span>
    </button>

    {{-- MENU --}}
    <div class="collapse navbar-collapse" id="mainNavbarNav">
      <ul class="navbar-nav mx-auto align-items-lg-center gap-lg-3 menu-center">

        <li class="nav-item">
          <a
            class="nav-link {{ request()->routeIs('lembaga.*') ? 'active' : '' }}"
            href="{{ route('lembaga.index') }}"
            data-page-link
          >
            INKUBATOR
          </a>
        </li>

        <li class="nav-item">
          <a
            class="nav-link {{ request()->routeIs('tentang') ? 'active' : '' }}"
            href="{{ route('tentang') }}"
            data-page-link
          >
            TENTANG
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('mitra.*') ? 'active' : '' }}"
            href="{{ route('mitra.index') }}"
            >
            MITRA
          </a>
        </li>

        <li class="nav-item">
          <a
            class="nav-link {{ request()->routeIs('kontak') ? 'active' : '' }}"
            href="{{ route('kontak') }}"
            data-page-link
          >
            KONTAK
          </a>
        </li>

      </ul>

      {{-- CTA --}}
      <div class="d-flex align-items-center gap-2">
        <a class="btn btn-login" href="{{ $loginUrl }}">
          MASUK
        </a>
      </div>
    </div>
  </div>
</nav>
