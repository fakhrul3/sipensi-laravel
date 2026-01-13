@extends('layouts.app')
@section('title','Lembaga Inkubator')

@section('bg-variant','bg-li')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/lembaga-inkubator.css') }}">
@endpush

@section('content')
<div class="container li-shell"> {{-- JANGAN SAMPAI TERHAPUS --}}

    {{-- ================= HEADER ================= --}}
    <div class="li-head text-center">
        <h1 class="li-title">Lembaga Inkubator</h1>

        @if(!empty($namaProvinsi))
            <p class="li-subtitle" style="color:#000; font-weight:700;">
                Daftar Lembaga Inkubator di Provinsi
                <strong>{{ $namaProvinsi }}</strong>
            </p>
        @else
            <p class="li-subtitle">
                Daftar Lembaga Inkubator terdaftar di SIPENSI
            </p>
        @endif
    </div>

    {{-- ================= TOOLBAR ================= --}}
    <div class="li-toolbar">
        <div class="li-search">
            <input
                id="liSearch"
                type="text"
                class="form-control"
                placeholder="Cari nama lembaga..."
            >
        </div>

        {{-- FILTER PROVINSI --}}
        <div class="li-filter">
            <select id="liProvinsi" class="form-select">
                <option value="">Semua Provinsi</option>
                @foreach($provinsiList ?? [] as $prov)
                    <option
                        value="{{ $prov['kode_provinsi'] }}"
                        {{ request('kode_provinsi') == $prov['kode_provinsi'] ? 'selected' : '' }}
                    >
                        {{ $prov['name'] }} ({{ $prov['count'] }})
                    </option>
                @endforeach
            </select>
        </div>

        {{-- FILTER JENIS --}}
        <div class="li-filter">
            <select id="liJenis" class="form-select">
                <option value="">Semua Jenis</option>
                <option value="1" {{ request('jenis')=='1' ? 'selected' : '' }}>Pemerintah Pusat</option>
                <option value="2" {{ request('jenis')=='2' ? 'selected' : '' }}>Pemerintah Daerah</option>
                <option value="3" {{ request('jenis')=='3' ? 'selected' : '' }}>Lembaga Pendidikan</option>
                <option value="4" {{ request('jenis')=='4' ? 'selected' : '' }}>Badan Usaha</option>
                <option value="5" {{ request('jenis')=='5' ? 'selected' : '' }}>Masyarakat</option>
            </select>
        </div>
    </div>

    {{-- ================= TABLE ================= --}}
    <div class="li-table-wrap">
        <table class="table li-table mb-0">
            <thead>
                <tr>
                    <th style="width:90px;">NO</th>
                    <th>Lembaga Inkubator</th>
                    <th class="text-end" style="width:280px;">
                        Jenis Lembaga Inkubator
                    </th>
                </tr>
            </thead>
            <tbody id="liTbody">
                {{-- DIRENDER VIA JS --}}
            </tbody>
        </table>
    </div>

    {{-- ================= PAGINATION ================= --}}
    <div class="li-pagination">
        <button id="liPrev" class="btn btn-outline-secondary btn-sm" disabled>
            ← Sebelumnya
        </button>
        <span id="liPageInfo" class="li-page-info">Halaman 1</span>
        <button id="liNext" class="btn btn-outline-secondary btn-sm">
            Berikutnya →
        </button>
    </div>

</div>
@endsection

@push('scripts')
<script>
    /**
     * PENTING:
     * - rows HARUS FULL DATASET
     * - filter awal dikontrol oleh JS (currentProvinsi)
     */
    window.LI_CONFIG = {
    baseUrl: "{{ url('') }}",

    // ✅ INI PENTING: base URL untuk halaman detail
    // hasilnya nanti: /lembaga-inkubator/{id}
    detailBase: "{{ url('/lembaga-inkubator') }}",

    rows: @json(($allInkubators ?? $inkubators)->toArray()),
    currentProvinsi: "{{ request('kode_provinsi') ?? '' }}"
};

</script>

<script src="{{ asset('js/lembaga-inkubator.js') }}" defer></script>
@endpush
