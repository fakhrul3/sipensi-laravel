<!doctype html>
<html lang="id">
<head>


  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" crossorigin=""/>
  <link rel="stylesheet" href="{{ asset('css/sebaran-inkubator.css') }}">



  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', config('app.name','SIPENSI')) - {{ config('app.name','SIPENSI') }}</title>


  {{-- Bootstrap --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  {{-- Base CSS (dashboard) --}}
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <link rel="stylesheet" href="{{ asset('css/sipensi.css') }}">
  <link rel="stylesheet" href="{{ asset('css/page-transition.css') }}">
  <link rel="stylesheet" href="{{ asset('css/chatbot.css') }}">
  <link rel="stylesheet" href="{{ asset('css/tentang.css') }}">


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

  {{-- Bootstrap JS --}}
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>

  {{-- Global JS --}}
  <script src="{{ asset('js/page-transition.js') }}" defer></script>
  <script src="{{ asset('js/navbar.js') }}" defer></script>

  {{-- Home JS only (optional) --}}
  @if (request()->routeIs('home'))
    <script src="{{ asset('js/home.js') }}" defer></script>
  @endif

  {{-- Reveal JS --}}
  <script src="{{ asset('js/reveal.js') }}" defer></script>

  {{-- CHATBOT SIPENSI (GLOBAL) --}}
  <div class="sipensi-chatbot" id="sipensiChatbot">
    <button class="chatbot-fab" id="chatbotToggle" aria-label="Tanya SIPENSI">
      <svg class="chatbot-icon" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
        <!-- bubble -->
        <path
          d="M10 10h28a6 6 0 0 1 6 6v14a6 6 0 0 1-6 6H22l-8 6v-6h-4a6 6 0 0 1-6-6V16a6 6 0 0 1 6-6z"
          fill="none"
          stroke="currentColor"
          stroke-width="3"
          stroke-linejoin="round"
        />
        <!-- dots -->
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
          Halo! Apa yang bisa aku bantu? <br> Kamu bisa ikuti format berikut ini ya <b>“Jumlah inkubator di Jawa Barat”</b> atau <b>“Inkubator di DKI Jakarta ada berapa?”</b>
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

  {{-- panggil js chatbot --}}
  <script src="{{ asset('js/chatbot.js') }}"></script>



  @stack('scripts')

  
</body>

</html>
