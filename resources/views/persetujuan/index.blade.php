@extends('layouts.admin')

@section('title', 'Data Persetujuan')
@section('page-title', 'Data Persetujuan')

@section('breadcrumb')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">Data Persetujuan</li>
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

    table.dataTable thead th .fas.fa-sort {
        margin-left: 5px;
        opacity: 0.5;
        font-size: 12px;
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

    /* Status Badge */
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
    }

    .status-approved {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: #fff;
        box-shadow: 0 2px 6px rgba(16, 185, 129, 0.2);
    }

    .status-pending {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: #fff;
        box-shadow: 0 2px 6px rgba(245, 158, 11, 0.2);
    }

    /* Action Button */
    .btn-action-detail {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: linear-gradient(135deg, #6c7ae0 0%, #5a6ad8 100%);
        color: #fff;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 2px 6px rgba(108, 122, 224, 0.2);
        text-decoration: none;
    }

    .btn-action-detail:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(108, 122, 224, 0.3);
        color: #fff;
    }

    .btn-action-detail i {
        font-size: 16px;
    }

    /* Modal Styles */
    .modal-content {
        border-radius: 16px;
        border: none;
        box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.2);
    }

    .modal-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border-bottom: 1px solid rgba(226, 232, 240, 0.6);
        border-radius: 16px 16px 0 0;
        padding: 20px 25px;
    }

    .modal-title {
        font-weight: 700;
        color: #334155;
        font-size: 18px;
    }

    .modal-body {
        padding: 25px;
    }

    .detail-row {
        display: flex;
        padding: 12px 0;
        border-bottom: 1px solid rgba(226, 232, 240, 0.5);
    }

    .detail-row:last-child {
        border-bottom: none;
    }

    .detail-label {
        font-weight: 600;
        color: #64748b;
        width: 180px;
        flex-shrink: 0;
    }

    .detail-value {
        color: #334155;
        flex: 1;
    }

    .btn-approve {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: #fff;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 2px 6px rgba(16, 185, 129, 0.2);
    }

    .btn-approve:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        color: #fff;
    }

    .btn-reject {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: #fff;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 2px 6px rgba(239, 68, 68, 0.2);
    }

    .btn-reject:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        color: #fff;
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
        <h5 class="admin-table-title">Daftar Persetujuan</h5>
    </div>

    <table id="persetujuanTable" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>NO</th>
                <th>NO TANDA DAFTAR <i class="fas fa-sort"></i></th>
                <th>NAMA LEMBAGA INKUBATOR <i class="fas fa-sort"></i></th>
                <th>PENGAJUAN GANTI NAMA <i class="fas fa-sort"></i></th>
                <th>EMAIL <i class="fas fa-sort"></i></th>
                <th>STATUS</th>
                <th>AKSI</th>
            </tr>
        </thead>
        <tbody>
            @forelse($persetujuans as $index => $persetujuan)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td style="font-weight: 500; color: #334155;">{{ $persetujuan->no_tanda_daftar ?? '-' }}</td>
                    <td style="font-weight: 500; color: #334155;">{{ $persetujuan->nama_inkubator ?? '-' }}</td>
                    <td>{{ $persetujuan->ganti_nama ?? '-' }}</td>
                    <td>{{ $persetujuan->email ?? '-' }}</td>
                    <td>
                        @if($persetujuan->is_ganti == 0 && !$persetujuan->ganti_nama && !$persetujuan->ganti_email)
                            <span class="status-badge status-approved">Disetujui</span>
                        @else
                            <span class="status-badge status-pending">Menunggu Persetujuan</span>
                        @endif
                    </td>
                    <td>
                        <button type="button" class="btn-action-detail" onclick="showDetail({{ $persetujuan->id }})" title="Lihat Detail">
                            <i class="fas fa-file-alt"></i>
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding: 40px; color: #94a3b8;">
                        Tidak ada data persetujuan
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Modal Detail --}}
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel">Detail Pengajuan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="detailModalBody">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn-reject" id="btnReject" onclick="rejectPersetujuan()" style="display: none;">Tolak</button>
                <button type="button" class="btn-approve" id="btnApprove" onclick="approvePersetujuan()" style="display: none;">Setujui</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script>
let currentId = null;

$(document).ready(function() {
    $('#persetujuanTable').DataTable({
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        order: [[0, 'asc']],
        paging: true,
        searching: true,
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
            emptyTable: "Tidak ada data",
            zeroRecords: "Tidak ada data yang cocok"
        }
    });

    // Cleanup modal backdrop when modal is hidden
    $('#detailModal').on('hidden.bs.modal', function() {
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open');
        $('body').css('overflow', '');
        $('body').css('padding-right', '');
    });
});

function showDetail(id) {
    currentId = id;
    
    // Reset modal body
    $('#detailModalBody').html('<div class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>');
    $('#btnApprove').hide();
    $('#btnReject').hide();
    
    // Remove any existing modal backdrop first
    $('.modal-backdrop').remove();
    $('body').removeClass('modal-open');
    $('body').css('overflow', '');
    $('body').css('padding-right', '');
    
    $.ajax({
        url: '{{ route("persetujuan.show", ":id") }}'.replace(':id', id),
        method: 'GET',
        success: function(response) {
            if (response.success) {
                const data = response.data;
                let html = '';
                
                html += '<div class="detail-row">';
                html += '<div class="detail-label">No Tanda Daftar:</div>';
                html += '<div class="detail-value">' + (data.no_tanda_daftar || '-') + '</div>';
                html += '</div>';
                
                html += '<div class="detail-row">';
                html += '<div class="detail-label">Nama Lembaga Inkubator:</div>';
                html += '<div class="detail-value"><strong>' + (data.nama_inkubator || '-') + '</strong></div>';
                html += '</div>';
                
                if (data.ganti_nama) {
                    html += '<div class="detail-row">';
                    html += '<div class="detail-label">Pengajuan Ganti Nama:</div>';
                    html += '<div class="detail-value"><span style="color: #10b981; font-weight: 600;">' + data.ganti_nama + '</span></div>';
                    html += '</div>';
                }
                
                html += '<div class="detail-row">';
                html += '<div class="detail-label">Email Saat Ini:</div>';
                html += '<div class="detail-value">' + (data.email || '-') + '</div>';
                html += '</div>';
                
                if (data.ganti_email) {
                    html += '<div class="detail-row">';
                    html += '<div class="detail-label">Pengajuan Ganti Email:</div>';
                    html += '<div class="detail-value"><span style="color: #10b981; font-weight: 600;">' + data.ganti_email + '</span></div>';
                    html += '</div>';
                }
                
                html += '<div class="detail-row">';
                html += '<div class="detail-label">Status:</div>';
                html += '<div class="detail-value">';
                if (data.is_ganti == 1 || data.ganti_nama || data.ganti_email) {
                    html += '<span class="status-badge status-pending">Menunggu Persetujuan</span>';
                } else {
                    html += '<span class="status-badge status-approved">Disetujui</span>';
                }
                html += '</div>';
                html += '</div>';
                
                $('#detailModalBody').html(html);
                
                // Show approve/reject buttons if pending
                if (data.is_ganti == 1 || data.ganti_nama || data.ganti_email) {
                    $('#btnApprove').show();
                    $('#btnReject').show();
                }
                
                const modalElement = document.getElementById('detailModal');
                const modal = new bootstrap.Modal(modalElement, {
                    backdrop: true,
                    keyboard: true
                });
                
                modal.show();
            }
        },
        error: function() {
            $('#detailModalBody').html('<div class="text-center py-4 text-danger">Gagal memuat data</div>');
        }
    });
}

function approvePersetujuan() {
    if (!currentId) return;
    
    if (!confirm('Apakah Anda yakin ingin menyetujui perubahan data ini?')) {
        return;
    }
    
    $.ajax({
        url: '{{ route("persetujuan.approve", ":id") }}'.replace(':id', currentId),
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            const modalElement = document.getElementById('detailModal');
            const modal = bootstrap.Modal.getInstance(modalElement);
            if (modal) {
                modal.hide();
            }
            setTimeout(function() {
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open');
                $('body').css('overflow', '');
                $('body').css('padding-right', '');
            }, 300);
            location.reload();
        },
        error: function(xhr) {
            alert(xhr.responseJSON?.message || 'Gagal menyetujui perubahan data');
        }
    });
}

function rejectPersetujuan() {
    if (!currentId) return;
    
    if (!confirm('Apakah Anda yakin ingin menolak perubahan data ini?')) {
        return;
    }
    
    $.ajax({
        url: '{{ route("persetujuan.reject", ":id") }}'.replace(':id', currentId),
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            const modalElement = document.getElementById('detailModal');
            const modal = bootstrap.Modal.getInstance(modalElement);
            if (modal) {
                modal.hide();
            }
            setTimeout(function() {
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open');
                $('body').css('overflow', '');
                $('body').css('padding-right', '');
            }, 300);
            location.reload();
        },
        error: function(xhr) {
            alert(xhr.responseJSON?.message || 'Gagal menolak perubahan data');
        }
    });
}
</script>
@endpush
