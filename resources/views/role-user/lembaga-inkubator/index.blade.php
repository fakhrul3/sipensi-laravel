@extends('layouts.admin')

@section('title', 'Daftar Inkubator')
@section('page-title', 'Daftar Inkubator')

@section('breadcrumb')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">Daftar Inkubator</li>
</ol>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<style>
    .admin-table-container {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0px 10px 20px rgba(200, 208, 216, 0.3);
        padding: 20px;
    }

    .admin-table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .admin-table-title {
        font-size: 18px;
        font-weight: 700;
        color: #2c3e50;
        margin: 0;
    }

    .admin-table-actions {
        display: flex;
        gap: 15px;
        align-items: center;
    }

    .admin-btn-export {
        background-color: #17a2b8;
        color: #fff;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .admin-btn-export:hover {
        background-color: #138496;
        color: #fff;
    }

    .admin-btn-export-xlsx {
        background-color: #28a745;
    }

    .admin-btn-export-xlsx:hover {
        background-color: #218838;
    }

    table.dataTable {
        width: 100% !important;
        border-collapse: collapse;
    }

    table.dataTable thead th {
        background-color: #f8f9fa;
        color: #495057;
        font-weight: 600;
        padding: 12px;
        text-align: left;
        border-bottom: 2px solid #dee2e6;
        font-size: 14px;
    }

    table.dataTable tbody td {
        padding: 12px;
        border-bottom: 1px solid #e9ecef;
        font-size: 14px;
        color: #495057;
    }

    table.dataTable tbody tr:hover {
        background-color: #f8f9fa;
    }

    .password-masked {
        font-family: monospace;
        letter-spacing: 2px;
    }

    .verifikasi-check {
        color: #28a745;
        font-size: 18px;
    }

    .dataTables_length {
        margin-bottom: 15px;
    }

    .dataTables_length select {
        padding: 5px 10px;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        margin: 0 5px;
    }

    .dataTables_filter {
        margin-bottom: 15px;
    }

    /* Horizontal Scroll untuk tabel */
    .table-wrapper {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    #inkubatorTable {
        min-width: 1400px; /* Minimum width untuk semua kolom */
    }

    table.dataTable {
        width: 100% !important;
    }

    /* Badge Status Styles (Pill-shaped buttons) */
    .badge-status {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
        white-space: nowrap;
    }

    .badge-status-verified {
        background-color: #28a745;
        color: #fff;
    }

    .badge-status-pending {
        background-color: #ffc107;
        color: #000;
    }

    .badge-status-complete {
        background-color: #28a745;
        color: #fff;
    }

    .badge-status-incomplete {
        background-color: #ffc107;
        color: #000;
    }

    /* Badge Ranking Styles */
    .badge-ranking {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
        white-space: nowrap;
    }

    .badge-ranking-grade {
        background-color: #ffc107;
        color: #000;
        min-width: 30px;
        text-align: center;
    }

    .badge-ranking-orange {
        background-color: #ffc107;
        color: #000;
    }

    .badge-ranking-blue {
        background-color: #007bff;
        color: #fff;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 5px;
        align-items: center;
    }

    .btn-action {
        background-color: #6c757d;
        color: #fff;
        border: none;
        width: 32px;
        height: 32px;
        border-radius: 4px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background-color 0.2s ease;
        font-size: 14px;
    }

    .btn-action:hover {
        background-color: #5a6268;
        color: #fff;
    }

    .btn-action-view {
        background-color: #6c757d;
    }

    .btn-action-approve {
        background-color: #6c757d;
    }

    .btn-action-edit {
        background-color: #6c757d;
    }

    .btn-action-delete {
        background-color: #6c757d;
    }

    /* Sorting arrows in header */
    table.dataTable thead th {
        position: relative;
    }

    table.dataTable thead th .fas.fa-sort {
        margin-left: 5px;
        opacity: 0.5;
        font-size: 10px;
    }
</style>
@endpush

@section('content')
<div class="admin-table-container">
    <div class="admin-table-header">
        <h5 class="admin-table-title">Daftar Inkubator</h5>
        <div class="admin-table-actions">
            <a href="{{ route('lembaga-inkubator.export', ['format' => 'csv']) }}" class="admin-btn-export admin-btn-export-csv">
                <i class="fas fa-file-csv"></i>
                Export CSV
            </a>
            <a href="{{ route('lembaga-inkubator.export', ['format' => 'xlsx']) }}" class="admin-btn-export admin-btn-export-xlsx">
                <i class="fas fa-file-excel"></i>
                Export XLSX
            </a>
        </div>
    </div>

    <div class="table-wrapper">
        <table id="inkubatorTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>NO <i class="fas fa-sort"></i></th>
                    <th>NO TANDA DAFTAR <i class="fas fa-sort"></i></th>
                    <th>JENIS LEMBAGA INKUBATOR <i class="fas fa-sort"></i></th>
                    <th>INDUK LEMBAGA INKUBATOR <i class="fas fa-sort"></i></th>
                    <th>NAMA PIMPINAN <i class="fas fa-sort"></i></th>
                    <th>NO KONTAK <i class="fas fa-sort"></i></th>
                    <th>EMAIL</th>
                    <th>STATUS <i class="fas fa-sort"></i></th>
                    <th>STATUS LEGAL DOKUMEN <i class="fas fa-sort"></i></th>
                    <th>PERINGKAT <i class="fas fa-sort"></i></th>
                    <th>AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($inkubators as $index => $inkubator)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $inkubator->no_tanda_daftar ?? '-' }}</td>
                        <td>
                            @php
                                $jenisMap = [
                                    1 => 'Pemerintah Pusat',
                                    2 => 'Pemerintah Daerah',
                                    3 => 'Lembaga Pendidikan',
                                    4 => 'Badan Usaha',
                                    5 => 'Masyarakat'
                                ];
                                $jenisLabel = $jenisMap[$inkubator->jenis_inkubator ?? 0] ?? '-';
                            @endphp
                            {{ $jenisLabel }}
                        </td>
                        <td>{{ $inkubator->induk_inkubator ?? '-' }}</td>
                        <td>{{ $inkubator->nama_pimpinan ?? '-' }}</td>
                        <td>{{ $inkubator->no_kontak ?? '-' }}</td>
                        <td>{{ $inkubator->email ?? '-' }}</td>
                        <td>
                            @php
                                $isVerified = ($inkubator->is_verify == 1 || $inkubator->is_verify == 2);
                            @endphp
                            @if($isVerified)
                                <span class="badge-status badge-status-verified">Terverifikasi</span>
                            @else
                                <span class="badge-status badge-status-pending">Belum Terverifikasi</span>
                            @endif
                        </td>
                        <td>
                            @php
                                // Cek semua path yang diperlukan
                                $requiredPaths = [
                                    'path_kantor',
                                    'path_ruang_usaha',
                                    'path_ruang_rapat',
                                    'path_ruang_pelatihan',
                                    'path_ruang_komunikasi',
                                    'path_legalitas',
                                    'path_spesialisasi_inkubasi',
                                    'path_model_inkubasi',
                                    'path_rencana_strategis'
                                ];
                                $isLegalComplete = true;
                                foreach ($requiredPaths as $path) {
                                    $value = $inkubator->$path ?? null;
                                    if (empty($value)) {
                                        $isLegalComplete = false;
                                        break;
                                    }
                                    // Jika JSON array, cek apakah ada isi
                                    if (strpos($value, '[') === 0) {
                                        $paths = json_decode($value, true);
                                        if (!is_array($paths) || empty($paths)) {
                                            $isLegalComplete = false;
                                            break;
                                        }
                                    }
                                }
                            @endphp
                            @if($isLegalComplete)
                                <span class="badge-status badge-status-complete">Lengkap</span>
                            @else
                                <span class="badge-status badge-status-incomplete">Belum Lengkap</span>
                            @endif
                        </td>
                        <td>
                            @php
                                // Prioritas: peringkat dari tabel pemeringkatan, jika tidak ada gunakan pemeringkatan_rank dari inkubator
                                $peringkat = $inkubator->peringkat ?? $inkubator->pemeringkatan_rank ?? null;
                                $pemeringkatanStatus = $inkubator->pemeringkatan_status ?? null;
                            @endphp
                            @if(!empty($peringkat) && in_array(strtoupper($peringkat), ['A', 'B', 'C', 'D']))
                                <span class="badge-ranking badge-ranking-grade">{{ strtoupper($peringkat) }}</span>
                            @elseif($pemeringkatanStatus == 1)
                                <span class="badge-ranking badge-ranking-blue">Belum Dilakukan Pemeringkatan</span>
                            @else
                                <span class="badge-ranking badge-ranking-orange">Belum Mengajukan Pemeringkatan</span>
                            @endif
                        </td>
                        <td>
                            <div class="action-buttons">
                                @php
                                    $isVerified = ($inkubator->is_verify == 1 || $inkubator->is_verify == 2);
                                    $requiredPaths = [
                                        'path_kantor',
                                        'path_ruang_usaha',
                                        'path_ruang_rapat',
                                        'path_ruang_pelatihan',
                                        'path_ruang_komunikasi',
                                        'path_legalitas',
                                        'path_spesialisasi_inkubasi',
                                        'path_model_inkubasi',
                                        'path_rencana_strategis'
                                    ];
                                    $isLegalComplete = true;
                                    foreach ($requiredPaths as $path) {
                                        $value = $inkubator->$path ?? null;
                                        if (empty($value)) {
                                            $isLegalComplete = false;
                                            break;
                                        }
                                        if (strpos($value, '[') === 0) {
                                            $paths = json_decode($value, true);
                                            if (!is_array($paths) || empty($paths)) {
                                                $isLegalComplete = false;
                                                break;
                                            }
                                        }
                                    }
                                    $canDownload = $isVerified && $isLegalComplete;
                                @endphp
                                
                                @if($canDownload)
                                    <a href="{{ route('lembaga-inkubator.download-sertifikat', $inkubator->id) }}" 
                                       class="btn-action btn-action-view" 
                                       title="Download Sertifikat"
                                       target="_blank"
                                       download>
                                        <i class="fas fa-download"></i>
                                    </a>
                                @else
                                    <button type="button" 
                                            class="btn-action btn-action-approve" 
                                            title="Verifikasi Inkubator"
                                            onclick="approveInkubator({{ $inkubator->id }})">
                                        <i class="fas fa-thumbs-up"></i>
                                    </button>
                                @endif
                                
                                <a href="{{ route('lembaga-inkubator.show', $inkubator->id) }}" 
                                   class="btn-action btn-action-edit" 
                                   title="Detail Lembaga Inkubator">
                                    <i class="fas fa-file-alt"></i>
                                </a>
                                <button type="button" 
                                        class="btn-action btn-action-delete" 
                                        title="Hapus Inkubator"
                                        onclick="deleteInkubator({{ $inkubator->id }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="text-center">Tidak ada data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/fixedcolumns/4.2.2/js/dataTables.fixedColumns.min.js"></script>
<script>
$(document).ready(function() {
    $('#inkubatorTable').DataTable({
        pageLength: 10,
        lengthMenu: [[10], [10]], // Hanya 10 entries, tidak ada opsi All
        order: [[0, 'asc']],
        paging: true, // Pastikan pagination aktif
        searching: true,
        scrollX: true, // Enable horizontal scroll
        scrollCollapse: true,
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
            infoFiltered: "(difilter dari _MAX_ total data)",
            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "Selanjutnya",
                previous: "Sebelumnya"
            },
            infoPostFix: "",
            emptyTable: "Tidak ada data",
            zeroRecords: "Tidak ada data yang cocok"
        }
    });
});

// Function untuk approve inkubator
function approveInkubator(id) {
    if (!confirm('Apakah Anda yakin ingin memverifikasi inkubator ini?')) {
        return;
    }

    $.ajax({
        url: '{{ url("admin/lembaga-inkubator/approve") }}/' + id,
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                alert('Inkubator berhasil diverifikasi');
                location.reload();
            } else {
                alert('Gagal memverifikasi: ' + (response.message || 'Terjadi kesalahan'));
            }
        },
        error: function(xhr) {
            var message = 'Gagal memverifikasi';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            }
            alert(message);
        }
    });
}

// Function untuk delete inkubator
function deleteInkubator(id) {
    if (!confirm('Apakah Anda yakin ingin menghapus data inkubator ini?')) {
        return;
    }

    $.ajax({
        url: '{{ url("admin/lembaga-inkubator") }}/' + id,
        type: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                alert('Data inkubator berhasil dihapus');
                location.reload();
            } else {
                alert('Gagal menghapus data: ' + (response.message || 'Terjadi kesalahan'));
            }
        },
        error: function(xhr) {
            var message = 'Gagal menghapus data';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            }
            alert(message);
        }
    });
}
</script>
@endpush
