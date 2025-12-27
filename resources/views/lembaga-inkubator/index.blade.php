@extends('layouts.app')
@section('title','Lembaga Inkubator')
@section('bg-variant','bg-gold')


@push('styles')
  <link rel="stylesheet" href="{{ asset('css/lembaga-inkubator.css') }}">
@endpush

@section('content')
<div class="container li-shell">
  <div class="li-head text-center">
    <h1 class="li-title">Lembaga Inkubator</h1>
    <p class="li-subtitle">Daftar Lembaga Inkubator terdaftar di SIPENSI</p>
  </div>

  <div class="li-toolbar">
    <div class="li-search">
      <input id="liSearch" type="text" class="form-control" placeholder="Cari nama lembaga...">
    </div>

    <div class="li-filter">
      <select id="liJenis" class="form-select">
        <option value="">Semua Jenis</option>
        <option value="1">Pemerintah Pusat</option>
        <option value="2">Pemerintah Daerah</option>
        <option value="3">Lembaga Pendidikan</option>
        <option value="4">Badan Usaha</option>
        <option value="5">Masyarakat</option>
      </select>
    </div>
  </div>

  <div class="li-table-wrap">
    <table class="table li-table mb-0">
      <thead>
        <tr>
          <th style="width:90px;">NO</th>
          <th>Lembaga Inkubator</th>
          <th class="text-end" style="width:280px;">Jenis Lembaga Inkubator</th>
        </tr>
      </thead>
      <tbody id="liTbody"></tbody>
    </table>
  </div>

  <div class="li-pagination">
    <button id="liPrev" class="btn btn-outline-secondary btn-sm" disabled>← Sebelumnya</button>
    <span id="liPageInfo" class="li-page-info">Halaman 1</span>
    <button id="liNext" class="btn btn-outline-secondary btn-sm">Berikutnya →</button>
  </div>
</div>
@endsection

@push('scripts')
<script>
  window.LI_CONFIG = {
    baseUrl: "{{ url('') }}",
    rows: @json($rows),
    jenisMap: @json($jenisMap)
  };
</script>
<script src="{{ asset('js/lembaga-inkubator.js') }}"></script>
@endpush
