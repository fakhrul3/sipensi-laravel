{{-- 1. Tambahkan CSS Slick di paling atas file --}}
@push('styles')
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css"/>
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css"/>
  <style>
    /* Biar gambar nggak numpuk pas loading */
    .slider-for:not(.slick-initialized), .slider-nav:not(.slick-initialized) {
      display: none;
    }
    .slider-for img { 
      width: 100%; 
      max-height: 450px; 
      object-fit: contain; 
    }
    .slick-prev:before, .slick-next:before {
      font-size: 30px;
      color: #000; /* Atau warna kontras lainnya */
      opacity: 0.7;
    }
    .slider-for {
        width: 100%;
        display: block;
    }

    /* Biar div pembungkus gambar di dalam slick jadi center */
    .slider-for .slick-slide {
        display: flex !important;
        flex-direction: column !important;
        justify-content: center !important;
        align-items: center !important;
        outline: none;
    }
    .slider-for p {
        margin-top: 10px;
        font-weight: bold;
    }
  </style>
@endpush

<div class="modal fade" id="modalSaranaPrasarana" tabindex="-1" aria-labelledby="modalSopLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalSopLabel">Sarana Prasarana</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        @php
          $toArr = function ($val) {
            if (is_array($val)) return $val;
            if (is_string($val)) {
              $decoded = json_decode($val, true);
              return is_array($decoded) ? $decoded : [];
            }
            return [];
          };

          $kantorFiles         = $toArr($inkubator->path_kantor ?? []);
          $ruangUsahaFiles      = $toArr($inkubator->path_ruang_usaha ?? []);
          $ruangRapatFiles      = $toArr($inkubator->path_ruang_rapat ?? []);
          $ruangPelatihanFiles  = $toArr($inkubator->path_ruang_pelatihan ?? []);
          $ruangKomunikasiFiles = $toArr($inkubator->path_ruang_komunikasi ?? []);
          
          $totalFiles = count($kantorFiles) + count($ruangUsahaFiles) + count($ruangRapatFiles) + count($ruangPelatihanFiles) + count($ruangKomunikasiFiles);
        @endphp

        @if($totalFiles > 0)
          {{-- SLIDER UTAMA --}}
          <div class="slider-for mb-3">
            @foreach($kantorFiles as $kantor)
              <div class="text-center"><img src="{{ \Storage::url($kantor) }}" onerror="this.src='{{ asset('theme/images/default.png') }}'"><p>Gedung</p></div>
            @endforeach
            @foreach($ruangUsahaFiles as $ruang_usaha)
              <div class="text-center"><img src="{{ \Storage::url($ruang_usaha) }}" onerror="this.src='{{ asset('theme/images/default.png') }}'"><p>Ruang Usaha</p></div>
            @endforeach
            @foreach($ruangRapatFiles as $ruang_rapat)
              <div class="text-center"><img src="{{ \Storage::url($ruang_rapat) }}" onerror="this.src='{{ asset('theme/images/default.png') }}'"><p>Ruang Rapat</p></div>
            @endforeach
            @foreach($ruangPelatihanFiles as $ruang_pelatihan)
              <div class="text-center"><img src="{{ \Storage::url($ruang_pelatihan) }}" onerror="this.src='{{ asset('theme/images/default.png') }}'"><p>Ruang Pelatihan</p></div>
            @endforeach
            @foreach($ruangKomunikasiFiles as $ruang_komunikasi)
              <div class="text-center"><img src="{{ \Storage::url($ruang_komunikasi) }}" onerror="this.src='{{ asset('theme/images/default.png') }}'"><p>Ruang Komunikasi</p></div>
            @endforeach
          </div>

          {{-- SLIDER NAVIGATION --}}
          <div class="slider-nav text-center mt-5">
            @foreach($kantorFiles as $kantor)
              <img src="{{ \Storage::url($kantor) }}" class="p-1" style="width:100px;height:120px;object-fit:contain;" onerror="this.src='{{ asset('theme/images/default.png') }}'">
            @endforeach
            @foreach($ruangUsahaFiles as $ruang_usaha)
              <img src="{{ \Storage::url($ruang_usaha) }}" class="p-1" style="width:100px;height:120px;object-fit:contain;" onerror="this.src='{{ asset('theme/images/default.png') }}'">
            @endforeach
            @foreach($ruangRapatFiles as $ruang_rapat)
              <img src="{{ \Storage::url($ruang_rapat) }}" class="p-1" style="width:100px;height:120px;object-fit:contain;" onerror="this.src='{{ asset('theme/images/default.png') }}'">
            @endforeach
            @foreach($ruangPelatihanFiles as $ruang_pelatihan)
              <img src="{{ \Storage::url($ruang_pelatihan) }}" class="p-1" style="width:100px;height:120px;object-fit:contain;" onerror="this.src='{{ asset('theme/images/default.png') }}'">
            @endforeach
            @foreach($ruangKomunikasiFiles as $ruang_komunikasi)
              <img src="{{ \Storage::url($ruang_komunikasi) }}" class="p-1" style="width:100px;height:120px;object-fit:contain;" onerror="this.src='{{ asset('theme/images/default.png') }}'">
            @endforeach
          </div>
        @else
          <p class="text-center">Data sarana prasarana belum tersedia.</p>
        @endif
      </div>
    </div>
  </div>
</div>

@push('scripts')
  {{-- LOAD JQUERY & SLICK JS DARI CDN --}}
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>

  <script type="text/javascript">
    $(document).ready(function() {
      var modalSaranaPrasarana = document.getElementById('modalSaranaPrasarana');
      
      modalSaranaPrasarana.addEventListener('shown.bs.modal', function () {
        // Slider Utama (Foto Gede)
        $('.slider-for').not('.slick-initialized').slick({
          slidesToShow: 1,
          slidesToScroll: 1,
          arrows: true,
          fade: true,
          asNavFor: '.slider-nav'
        });

        $('.slider-nav').not('.slick-initialized').slick({
          slidesToShow: 5,
          slidesToScroll: 1,
          asNavFor: '.slider-for',
          dots: false,
          arrows : false,
          centerMode: true,
          focusOnSelect: true,
          autoplay: true
        });

        $('.slider-for').slick('setPosition');
        $('.slider-nav').slick('setPosition');
      });

      modalSaranaPrasarana.addEventListener('hidden.bs.modal', function () {
        if ($('.slider-for').hasClass('slick-initialized')) {
          $('.slider-for').slick('unslick');
          $('.slider-nav').slick('unslick');
        }
      });
    });
  </script>
@endpush