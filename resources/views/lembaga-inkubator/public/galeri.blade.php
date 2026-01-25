{{-- CSS Khusus Galeri --}}
@push('styles')
<style>
    /* Cegah gambar numpuk sebelum slick jalan */
    #modalGaleri .slider-for:not(.slick-initialized), 
    #modalGaleri .slider-nav:not(.slick-initialized) {
        display: none;
    }

    /* Container Slider Utama */
    #modalGaleri .slider-for .slick-slide {
        display: flex !important;
        flex-direction: column !important;
        justify-content: center !important;
        align-items: center !important;
        outline: none;
        background: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
    }

    #modalGaleri .slider-for img {
        width: 100%;
        max-height: 450px;
        object-fit: contain;
        margin-bottom: 15px;
    }

    #modalGaleri .slider-for p {
        font-weight: 600;
        font-size: 1.1rem;
        color: #333;
        margin: 0;
    }

    /* Thumbnail bawah */
    #modalGaleri .slider-nav .thumb-item {
        padding: 5px;
        outline: none;
        cursor: pointer;
    }

    #modalGaleri .slider-nav img {
        width: 100%;
        height: 80px;
        object-fit: cover;
        border-radius: 4px;
        opacity: 0.5;
        transition: 0.3s;
    }

    #modalGaleri .slider-nav .slick-current img {
        opacity: 1;
        border: 2px solid #0d6efd;
    }

    /* Arrow Style */
    #modalGaleri .slick-prev:before, 
    #modalGaleri .slick-next:before {
        color: #000 !important;
        font-size: 24px;
    }
</style>
@endpush

<div class="modal fade" id="modalGaleri" tabindex="-1" aria-labelledby="modalGaleriLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalGaleriLabel">Galeri Kegiatan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-md-5">
                @php
                    // Ambil data aktifitas dari $row (Inkubator)
                    $aktifitas = $row->aktifitas ?? collect();
                    
                    // Cek apakah ada foto di dalam koleksi aktifitas
                    $hasFoto = false;
                    foreach ($aktifitas as $act) {
                        $pics = json_decode($act->path_photo, true);
                        if (is_array($pics) && count($pics) > 0) {
                            $hasFoto = true;
                            break;
                        }
                    }
                @endphp

                @if(!$hasFoto)
                    <div class="text-center py-5">
                        <i class="fa-regular fa-images fa-3x mb-3" style="color: #dee2e6;"></i>
                        <p style="color:#94a3b8; font-weight:600;">Belum ada dokumentasi kegiatan.</p>
                    </div>
                @else
                    {{-- Slider Utama --}}
                    <div class="slider-for mb-3">
                        @foreach ($aktifitas as $item)
                            @php $photos = json_decode($item->path_photo, true) ?: []; @endphp
                            @foreach ($photos as $img)
                                <div>
                                    <img src="{{ \Storage::url($img) }}" onerror="this.src='{{ asset('theme/images/default.png') }}'">
                                    <p>{{ $item->nama_kegiatan ?? 'Kegiatan' }}</p>
                                </div>
                            @endforeach
                        @endforeach
                    </div>

                    {{-- Slider Navigasi --}}
                    <div class="slider-nav mt-3">
                        @foreach ($aktifitas as $item)
                            @php $photos = json_decode($item->path_photo, true) ?: []; @endphp
                            @foreach ($photos as $img)
                                <div class="thumb-item">
                                    <img src="{{ \Storage::url($img) }}" onerror="this.src='{{ asset('theme/images/default.png') }}'">
                                </div>
                            @endforeach
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- JS sudah di-handle di show.blade.php melalui push scripts, 
     tapi kita butuh inisialisasi Slick saat modal terbuka --}}
@push('scripts')
<script>
$(document).ready(function() {
    const modalGaleri = document.getElementById('modalGaleri');
    
    modalGaleri.addEventListener('shown.bs.modal', function () {
        // Init Slider Utama
        $('#modalGaleri .slider-for').not('.slick-initialized').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: true,
            fade: true,
            asNavFor: '#modalGaleri .slider-nav'
        });

        // Init Slider Navigasi
        $('#modalGaleri .slider-nav').not('.slick-initialized').slick({
            slidesToShow: 5,
            slidesToScroll: 1,
            asNavFor: '#modalGaleri .slider-for',
            dots: false,
            arrows: false,
            centerMode: true,
            focusOnSelect: true,
            responsive: [
                { breakpoint: 768, settings: { slidesToShow: 3 } }
            ]
        });

        // Refresh posisi
        $('#modalGaleri .slider-for').slick('setPosition');
        $('#modalGaleri .slider-nav').slick('setPosition');
    });

    // Hancurkan slick saat modal ditutup agar tidak berat/error saat dibuka lagi
    modalGaleri.addEventListener('hidden.bs.modal', function () {
        if ($('#modalGaleri .slider-for').hasClass('slick-initialized')) {
            $('#modalGaleri .slider-for').slick('unslick');
            $('#modalGaleri .slider-nav').slick('unslick');
        }
    });
});
</script>
@endpush