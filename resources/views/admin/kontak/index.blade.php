@extends('layouts.admin')

@section('title', 'Data Kontak Kami')
@section('page-title', 'Data Kontak Kami')

@section('breadcrumb')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">Data Kontak Kami</li>
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

    table.dataTable {
        width: 100% !important;
        border-collapse: collapse;
    }

    table.dataTable thead th {
        background: linear-gradient(135deg, #f8f9fa 0%, #f1f3f5 100%);
        color: #334155;
        font-weight: 600;
        padding: 14px;
        text-align: center;
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

    table.dataTable tbody td:nth-child(1),
    table.dataTable tbody td:nth-child(3),
    table.dataTable tbody td:nth-child(4) {
        text-align: center;
    }

    table.dataTable tbody td:nth-child(2) {
        text-align: left;
    }

    table.dataTable tbody tr:hover {
        background-color: rgba(108, 122, 224, 0.03);
    }

    .action-buttons {
        display: flex;
        gap: 8px;
        justify-content: center;
        align-items: center;
    }

    .btn-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }

    .btn-action-view {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: #fff;
    }

    .btn-action-view:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        color: #fff;
    }

    .btn-action-delete {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: #fff;
    }

    .btn-action-delete:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        color: #fff;
    }

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

    .detail-item {
        margin-bottom: 20px;
    }

    .detail-label {
        font-weight: 600;
        color: #64748b;
        margin-bottom: 5px;
        font-size: 14px;
    }

    .detail-value {
        color: #334155;
        font-size: 15px;
        padding: 10px;
        background: #f8f9fa;
        border-radius: 8px;
        word-wrap: break-word;
    }

    .detail-value.pesan {
        min-height: 100px;
        white-space: pre-wrap;
    }
</style>
@endpush

@section('content')
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-bottom: 20px; border-radius: 10px;">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" style="margin-bottom: 20px; border-radius: 10px;">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="admin-table-container">
    <div class="admin-table-header">
        <h5 class="admin-table-title">Daftar Kontak Kami</h5>
    </div>

    <table id="kontakTable" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>NO <i class="fas fa-sort"></i></th>
                <th>NAMA LENGKAP <i class="fas fa-sort"></i></th>
                <th>ALAMAT EMAIL <i class="fas fa-sort"></i></th>
                <th>AKSI</th>
            </tr>
        </thead>
        <tbody>
            @forelse($kontaks as $index => $kontak)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $kontak->nama_lengkap ?? '-' }}</td>
                    <td>{{ $kontak->email ?? '-' }}</td>
                    <td>
                        <div class="action-buttons">
                            <button type="button" class="btn-action btn-action-view" title="Lihat Detail" onclick="viewKontak({{ $kontak->id }})">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button type="button" class="btn-action btn-action-delete" title="Delete" onclick="deleteKontak({{ $kontak->id }})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">Tidak ada data kontak.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Modal Detail Kontak --}}
<div class="modal fade" id="kontakDetailModal" tabindex="-1" aria-labelledby="kontakDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="kontakDetailModalLabel">Detail Kontak</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="closeKontakModal()"></button>
            </div>
            <div class="modal-body" id="kontakDetailContent">
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="closeKontakModal()">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
    $('#kontakTable').DataTable({
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        order: [[0, 'desc']],
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
    $('#kontakDetailModal').on('hidden.bs.modal', function() {
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open');
        $('body').css('overflow', '');
        $('body').css('padding-right', '');
    });
});

// Close modal dan cleanup
function closeKontakModal() {
    setTimeout(function() {
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open');
        $('body').css('overflow', '');
        $('body').css('padding-right', '');
    }, 300);
}

// View kontak detail
function viewKontak(id) {
    $.ajax({
        url: '{{ route("admin.kontak.show", ":id") }}'.replace(':id', id),
        method: 'GET',
        success: function(response) {
            if (response.success) {
                const data = response.data;
                const tanggal = new Date(data.created_at).toLocaleDateString('id-ID', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });

                const html = `
                    <div class="detail-item">
                        <div class="detail-label">Nama Lengkap</div>
                        <div class="detail-value">${data.nama_lengkap || '-'}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Alamat Email</div>
                        <div class="detail-value">${data.email || '-'}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Pesan</div>
                        <div class="detail-value pesan">${data.pesan || '-'}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Tanggal Dikirim</div>
                        <div class="detail-value">${tanggal}</div>
                    </div>
                `;

                $('#kontakDetailContent').html(html);
                
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open');
                $('body').css('overflow', '');
                $('body').css('padding-right', '');
                
                const modalElement = document.getElementById('kontakDetailModal');
                const modal = new bootstrap.Modal(modalElement, {
                    backdrop: true,
                    keyboard: true
                });
                
                modalElement.addEventListener('hidden.bs.modal', function() {
                    $('.modal-backdrop').remove();
                    $('body').removeClass('modal-open');
                    $('body').css('overflow', '');
                    $('body').css('padding-right', '');
                }, { once: true });
                
                modal.show();
            }
        },
        error: function() {
            alert('Gagal memuat data kontak');
        }
    });
}

// Delete kontak
function deleteKontak(id) {
    if (!confirm('Apakah Anda yakin ingin menghapus kontak ini?')) {
        return;
    }

    $.ajax({
        url: '{{ route("admin.kontak.destroy", ":id") }}'.replace(':id', id),
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                alert(response.message || 'Kontak berhasil dihapus');
                location.reload();
            }
        },
        error: function(xhr) {
            alert(xhr.responseJSON?.message || 'Gagal menghapus data');
        }
    });
}
</script>
@endpush
