<!doctype html>
<html lang="id" class="no-js">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Dashboard') - SIPENSI Admin</title>

  {{-- ✅ HARD LOCK: cegah 1st paint kedip --}}
  <style>
    html.no-js body{ opacity:0; }
  </style>

  {{-- switch no-js -> js secepat mungkin (anti kedip first paint) --}}
  <script>
    document.documentElement.classList.remove('no-js');
    document.documentElement.classList.add('js');
  </script>

  {{-- Critical CSS: Bootstrap --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">

  {{-- Admin CSS --}}
  <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

  {{-- Icons --}}
  <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
  <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"></noscript>

  @stack('styles')
</head>
<body class="admin-body">
  <div class="admin-wrapper">
    {{-- SIDEBAR --}}
    @include('partials.admin-sidebar')

    {{-- MAIN CONTENT --}}
    <div class="admin-main">
      {{-- HEADER --}}
      <header class="admin-header">
        <div class="admin-header-content">
          <div class="admin-header-left">
            <h1 class="admin-page-title">@yield('page-title', 'Dashboard')</h1>
            @hasSection('breadcrumb')
              <nav aria-label="breadcrumb" class="admin-breadcrumb">
                @yield('breadcrumb')
              </nav>
            @endif
          </div>
          <div class="admin-header-right">
            <div class="admin-user-dropdown">
              <button class="admin-user-btn" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                {{ Auth::user()->username ?? 'User' }}
                <i class="fas fa-chevron-down ms-2"></i>
              </button>
              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i> Profile</a></li>
                <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i> Settings</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                  <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="dropdown-item"><i class="fas fa-sign-out-alt me-2"></i> Logout</button>
                  </form>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </header>

      {{-- CONTENT --}}
      <main class="admin-content">
        @yield('content')
      </main>
    </div>
  </div>

  {{-- Bootstrap JS --}}
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>

  {{-- Admin JS --}}
  <script src="{{ asset('js/admin.js') }}" defer></script>

  @stack('scripts')
</body>
</html>
