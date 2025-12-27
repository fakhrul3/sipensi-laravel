<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name', 'SIPENSI') }}</title>

  {{-- Bootstrap --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  {{-- Base CSS (dashboard) --}}
  <link rel="stylesheet" href="{{ asset('css/sipensi.css') }}">
  <link rel="stylesheet" href="{{ asset('css/page-transition.css') }}">

  @stack('styles')
</head>
<body class="app-body">
  @include('partials.navbar')

  <main class="app-main">
    @yield('content')
  </main>

  {{-- Bootstrap JS --}}
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>

  {{-- Global JS --}}
  <script src="{{ asset('js/page-transition.js') }}" defer></script>

  {{-- Home JS only (optional) --}}
  @if (request()->routeIs('home'))
    <script src="{{ asset('js/home.js') }}" defer></script>
  @endif

  @stack('scripts')
</body>
</html>
