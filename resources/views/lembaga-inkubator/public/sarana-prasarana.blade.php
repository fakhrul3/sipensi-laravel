{{-- 1. Tambahkan CSS Slick di paling atas file --}}
@push('styles')
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css"/>
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css"/>
  <style>
    .slider-for:not(.slick-initialized), .slider-nav:not(.slick-initialized) {
      display: none;
    }
    .slider-for img { 
      width: 100%; 
      max-height: 400px; 
      object-fit: contain; 
      border-radius: 12px;
      background: #f8f9fa;
    }
    .slider-nav img {
      width: 100px;
      height: 80px;
      object-fit: cover;
      cursor: pointer;
      margin: 5px;
      border-radius: 8px;
      border: 2px solid transparent;
      transition: all 0.3s;
    }
    .slider-nav .slick-current img {
      border-color: #2f8f9d;
    }
    .slick-prev:before, .slick-next:before {
      font-size: 30px;
      color: #22466C;
    }
  </style>
@endpush

<div class="modal fade" id="modalSaranaPrasarana" tabindex="-1" aria-labelledby="modalSopLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalSopLabel">Sarana & Prasarana Lembaga</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        @php
          // Fungsi Helper buat beresin path dan JSON
          $getCleanPaths = function ($val) {
            if (is_string($val)) {
              $decoded = json_decode($val, true);
              $files = is_array($decoded) ? $decoded : [$val];
            } else {
              $files = is_array($val) ? $val : [];
            }
            
            // Bersihkan 'public/' dari setiap item
            return array_map(function($path) {
                return str_replace('public/', '', $path);
            }, $files);
          };

          // Ambil semua file dengan path yang sudah bersih
          $allMedia = [
            'Gedung / Kantor'  => $getCleanPaths($inkubator->path_kantor ?? []),
            'Ruang Usaha'      => $getCleanPaths($inkubator->path_ruang_usaha ?? []),
            'Ruang Rapat'      => $getCleanPaths($inkubator->path_ruang_rapat ?? []),
            'Ruang Pelatihan'  => $getCleanPaths($inkubator->path_ruang_pelatihan ?? []),
            'Ruang Komunikasi' => $getCleanPaths($inkubator->path_ruang_komunikasi ?? []),
          ];

          $hasFiles = false;
          foreach($allMedia as $files) { if(count($files) > 0) $hasFiles = true; }
        @endphp

        @if($hasFiles)
          {{-- SLIDER UTAMA (Foto Gede) --}}
          <div class="slider-for mb-3">
            @foreach($allMedia as $label => $files)
              @foreach($files as $file)
                <div class="text-center px-2">
                  <img src="{{ asset('storage/' . $file) }}" onerror="this.src='{{ asset('theme/images/default.png') }}'">
                  <p class="mt-2 fw-bold text-primary">{{ $label }}</p>
                </div>
              @endforeach
            @endforeach
          </div>

          {{-- SLIDER NAVIGATION (Thumbnail) --}}
          <div class="slider-nav mt-4">
            @foreach($allMedia as $label => $files)
              @foreach($files as $file)
                <div class="px-1">
                  <img src="{{ asset('storage/' . $file) }}" onerror="this.src='{{ asset('theme/images/default.png') }}'">
                </div>
              @endforeach
            @endforeach
          </div>
        @else
          <div class="text-center py-5 text-muted">
            <i class="fa-regular fa-images d-block mb-3" style="font-size: 3rem;"></i>
            <p>Data foto sarana prasarana belum tersedia.</p>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>

@push('scripts')
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>

  <script type="text/javascript">
    $(document).ready(function() {
      var modalSaranaPrasarana = document.getElementById('modalSaranaPrasarana');
      
      modalSaranaPrasarana.addEventListener('shown.bs.modal', function () {
        $('.slider-for').not('.slick-initialized').slick({
          slidesToShow: 1,
          slidesToScroll: 1,
          arrows: true,
          fade: true,
          adaptiveHeight: true,
          asNavFor: '.slider-nav'
        });

        $('.slider-nav').not('.slick-initialized').slick({
          slidesToShow: 5,
          slidesToScroll: 1,
          asNavFor: '.slider-for',
          dots: false,
          arrows: false,
          centerMode: true,
          focusOnSelect: true,
          responsive: [
            { breakpoint: 768, settings: { slidesToShow: 3 } }
          ]
        });

        // Paksa refresh posisi slick agar tidak gepeng
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