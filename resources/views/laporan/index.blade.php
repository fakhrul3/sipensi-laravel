@extends('layouts.admin')

@section('title', 'Laporan')
@section('page-title', 'Laporan')

@section('breadcrumb')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">Laporan</li>
</ol>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<style>
    .admin-table-container {
        background: linear-gradient(135deg, #ffffff 0%, #fafbfc 100%);
        border-radius: 16px;
        box-shadow: 0px 4px 15px rgba(200, 208, 216, 0.15);
        padding: 25px;
    }

    .admin-table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .admin-table-title {
        font-size: 20px;
        font-weight: 700;
        color: #334155;
        margin: 0;
        letter-spacing: -0.2px;
    }

    .search-section {
        display: flex;
        gap: 10px;
        align-items: center;
        margin-bottom: 20px;
    }

    .search-input {
        flex: 1;
        padding: 12px 16px;
        border: 1px solid rgba(226, 232, 240, 0.8);
        border-radius: 10px;
        font-size: 15px;
        transition: all 0.3s ease;
        background: #ffffff;
        color: #334155;
    }

    .search-input:focus {
        outline: none;
        border-color: #6c7ae0;
        box-shadow: 0 0 0 3px rgba(108, 122, 224, 0.1);
    }

    .btn-search {
        background: linear-gradient(135deg, #6c7ae0 0%, #5a6ad8 100%);
        color: #fff;
        border: none;
        padding: 12px 24px;
        border-radius: 10px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(108, 122, 224, 0.2);
    }

    .btn-search:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(108, 122, 224, 0.3);
    }

    table.dataTable {
        width: 100% !important;
        border-collapse: collapse;
    }

    table.dataTable thead th {
        background: linear-gradient(135deg, #f8f9fa 0%, #f1f3f5 100%);
        color: #334155;
        font-weight: 600;
        padding: 14px;
        text-align: left;
        border-bottom: 2px solid rgba(226, 232, 240, 0.6);
        font-size: 15px;
    }

    table.dataTable tbody td {
        padding: 14px;
        border-bottom: 1px solid rgba(226, 232, 240, 0.5);
        font-size: 15px;
        color: #475569;
        vertical-align: middle;
    }

    table.dataTable tbody tr:hover {
        background-color: rgba(108, 122, 224, 0.03);
    }

    /* Download Button Styles */
    .download-buttons {
        display: flex;
        gap: 8px;
        align-items: center;
        flex-wrap: wrap;
    }

    .btn-download {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 14px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }

    .btn-download-laporan {
        background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
        color: #fff;
    }

    .btn-download-laporan:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        color: #fff;
    }

    .btn-download-lampiran {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: #fff;
    }

    .btn-download-lampiran:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        color: #fff;
    }

    .btn-download i {
        font-size: 14px;
    }

    .file-label {
        font-size: 12px;
        color: #64748b;
        margin-top: 4px;
        font-weight: 500;
    }

    .date-format {
        font-size: 14px;
        color: #64748b;
    }
</style>
@endpush

@section('content')
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-bottom: 20px; border-radius: 10px;">
        <i class="fas fa-exclamation-circle me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" style="margin-bottom: 20px; border-radius: 10px;">
        <i class="fas fa-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="admin-table-container">
    <div class="admin-table-header">
        <h5 class="admin-table-title">Daftar Laporan</h5>
    </div>

    <form method="GET" action="{{ route('laporan.index') }}" class="search-section">
        <input 
            type="text" 
            name="search" 
            class="search-input" 
            placeholder="Cari laporan..." 
            value="{{ $search }}"
        >
        <button type="submit" class="btn-search">
            <i class="fas fa-search"></i> Cari
        </button>
    </form>

    <table id="laporanTable" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>NO</th>
                <th>LEMBAGA INKUBATOR</th>
                <th>TANGGAL</th>
                <th>BULAN LAPORAN</th>
                <th>NAMA/JUDUL LAPORAN</th>
                <th>FILE</th>
                <th>#</th>
            </tr>
        </thead>
        <tbody>
            @forelse($laporans as $index => $laporan)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td style="font-weight: 500; color: #334155;">{{ $laporan->nama_inkubator ?? '-' }}</td>
                    <td class="date-format">
                        {{ $laporan->tgl_laporan ? \Carbon\Carbon::parse($laporan->tgl_laporan)->format('d/m/Y') : '-' }}
                    </td>
                    <td>{{ $laporan->bulan_laporan ?? '-' }}</td>
                    <td style="max-width: 400px;">{{ $laporan->nama_laporan ?? '-' }}</td>
                    <td>
                        <div class="download-buttons">
                            @if($laporan->path_laporan)
                                <a href="{{ route('laporan.download-laporan', $laporan->id) }}" 
                                   class="btn-download btn-download-laporan" 
                                   title="Download Laporan">
                                    <i class="fas fa-cloud-download-alt"></i>
                                    <span>Laporan</span>
                                </a>
                            @endif
                            @if($laporan->path_lampiran)
                                <a href="{{ route('laporan.download-lampiran', $laporan->id) }}" 
                                   class="btn-download btn-download-lampiran" 
                                   title="Download Lampiran">
                                    <i class="fas fa-cloud-download-alt"></i>
                                    <span>Lampiran</span>
                                </a>
                            @endif
                            @if(!$laporan->path_laporan && !$laporan->path_lampiran)
                                <span style="color: #94a3b8; font-size: 13px;">-</span>
                            @endif
                        </div>
                    </td>
                    <td>{{ $index + 1 }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding: 40px; color: #94a3b8;">
                        Tidak ada data laporan
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
    $('#laporanTable').DataTable({
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        order: [[2, 'desc']], // Sort by tanggal
        paging: true,
        searching: false, // Disable DataTables search karena kita pakai custom search
        language: {
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
            emptyTable: "Tidak ada data",
            zeroRecords: "Tidak ada data yang cocok"
        }
    });
});
</script>
@endpush
