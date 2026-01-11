<!doctype html>
<html lang="id" class="no-js">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', config('app.name','SIPENSI')) - {{ config('app.name','SIPENSI') }}</title>

  {{-- ✅ HARD LOCK: cegah 1st paint kedip --}}
  <style>
    html.no-js body{ opacity:0; }
  </style>

  {{-- switch no-js -> js secepat mungkin (anti kedip first paint) --}}
  <script>
    document.documentElement.classList.remove('no-js');
    document.documentElement.classList.add('js');
  </script>

  {{-- Polyfill untuk preload CSS (fallback untuk browser lama) --}}
  <script>
    !function(e){"use strict";var t=function(t,n,o){var i,r=e.document,a=r.createElement("link");if(n)i=n;else{var l=(r.body||r.getElementsByTagName("head")[0]).childNodes;i=l[l.length-1]}var d=r.styleSheets;a.rel="stylesheet",a.href=t,a.media="only x",function e(t){if(r.body)return t();setTimeout(function(){e(t)})}(function(){i.parentNode.insertBefore(a,n?i:i.nextSibling)});var f=function(e){for(var t=a.href,n=d.length;n--;)if(d[n].href===t)return e();setTimeout(function(){f(e)})};return a.addEventListener&&a.addEventListener("load",function(){this.media=o||"all"}),a.onloadcssdefined=f,f(function(){a.media!==o&&(a.media=o||"all")}),a};"undefined"!=typeof exports?exports.loadCSS=t:e.loadCSS=t}("undefined"!=typeof global?global:this);
  </script>

  {{-- DNS Prefetch untuk external resources --}}
  <link rel="dns-prefetch" href="https://fonts.googleapis.com">
  <link rel="dns-prefetch" href="https://fonts.gstatic.com">
  <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">
  <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">
  <link rel="dns-prefetch" href="https://unpkg.com">

  {{-- Preconnect untuk critical resources --}}
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
  <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
  <link rel="preconnect" href="https://unpkg.com" crossorigin>

  {{-- Critical CSS: Bootstrap (harus load pertama) --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">

  {{-- Critical CSS: Base styles (load inline atau early) --}}
  <link rel="stylesheet" href="{{ asset('css/sipensi.css') }}">
  <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">

  {{-- Non-critical CSS: Load dengan defer/async --}}
  <link rel="preload" href="{{ asset('css/page-transition.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
  <noscript><link rel="stylesheet" href="{{ asset('css/page-transition.css') }}"></noscript>

  <link rel="preload" href="{{ asset('css/footer.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
  <noscript><link rel="stylesheet" href="{{ asset('css/footer.css') }}"></noscript>

  <link rel="preload" href="{{ asset('css/chatbot.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
  <noscript><link rel="stylesheet" href="{{ asset('css/chatbot.css') }}"></noscript>

  <link rel="preload" href="{{ asset('css/berita.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
  <noscript><link rel="stylesheet" href="{{ asset('css/berita.css') }}"></noscript>

  {{-- Fonts: Optimize dengan font-display swap --}}
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
  <noscript><link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"></noscript>

  {{-- Icons: Defer loading --}}
  <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
  <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"></noscript>

  {{-- Page-specific CSS --}}
  @if (request()->routeIs('home'))
    <link rel="stylesheet" href="{{ asset('css/home-incubator.css') }}">
  @endif

  @if (request()->routeIs('tentang'))
    <link rel="stylesheet" href="{{ asset('css/tentang.css') }}">
  @endif

  @stack('styles')
</head>

<body class="app-body">
  @include('partials.navbar')

  <div class="app-bg @yield('bg-variant')">
    <main class="app-main">
      @yield('content')
    </main>
  </div>

  @include('partials.footer')

  {{-- CHATBOT SIPENSI (GLOBAL) --}}
  <div class="sipensi-chatbot" id="sipensiChatbot">
    <button class="chatbot-fab" id="chatbotToggle" aria-label="Tanya SIPENSI">
      <svg class="chatbot-icon" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
        <path
          d="M10 10h28a6 6 0 0 1 6 6v14a6 6 0 0 1-6 6H22l-8 6v-6h-4a6 6 0 0 1-6-6V16a6 6 0 0 1 6-6z"
          fill="none"
          stroke="currentColor"
          stroke-width="3"
          stroke-linejoin="round"
        />
        <circle cx="18" cy="24" r="2.2" fill="currentColor"/>
        <circle cx="24" cy="24" r="2.2" fill="currentColor"/>
        <circle cx="30" cy="24" r="2.2" fill="currentColor"/>
      </svg>
    </button>

    <div class="chatbot-window" id="chatbotWindow" aria-hidden="true">
      <div class="chatbot-header">
        <div class="chatbot-title">
          <strong>Tanya SIPENSI</strong>
        </div>
        <button class="chatbot-close" id="chatbotClose" aria-label="Tutup">✕</button>
      </div>

      <div class="chatbot-body" id="chatbotBody">
        <div class="bot-msg">
          Halo! Apa yang bisa aku bantu? <br>
          Kamu bisa ikuti format berikut ini ya <b>“Jumlah inkubator di Jawa Barat”</b>
          atau <b>“Inkubator di DKI Jakarta ada berapa?”</b>
        </div>
      </div>

      <form class="chatbot-footer" id="chatbotForm">
        <input
          type="text"
          id="chatbotInput"
          class="chatbot-input"
          placeholder="Tulis pertanyaan..."
          autocomplete="off"
        />
        <button type="submit" class="chatbot-send">Kirim</button>
      </form>
    </div>
  </div>

  {{-- Bootstrap JS - Preload untuk performa lebih baik --}}
  <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" as="script">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>

  {{-- Global JS - Semua di-defer untuk tidak blocking render --}}
  <script src="{{ asset('js/page-transition.js') }}" defer></script>
  <script src="{{ asset('js/navbar.js') }}" defer></script>
  <script src="{{ asset('js/reveal.js') }}" defer></script>
  <script src="{{ asset('js/berita.js') }}" defer></script>

  {{-- Chatbot JS - Defer karena tidak critical --}}
  <script src="{{ asset('js/chatbot.js') }}" defer></script>

  {{-- scroll-reveal.js - Hanya load jika diperlukan (bisa dihapus jika reveal.js sudah cukup) --}}
  {{-- <script src="{{ asset('js/scroll-reveal.js') }}" defer></script> --}}

  @stack('scripts')
</body>
</html>
