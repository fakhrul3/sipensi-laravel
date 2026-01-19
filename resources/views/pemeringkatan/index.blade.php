@extends('layouts.admin')

@section('title', 'Data Pemeringkatan')
@section('page-title', 'Data Pemeringkatan')

@section('breadcrumb')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">Data Pemeringkatan</li>
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
        text-align: center;
        border-bottom: 2px solid rgba(226, 232, 240, 0.6);
        font-size: 15px;
    }

    /* Lebarkan kolom NAMA LEMBAGA INKUBATOR (kolom ke-3) */
    table.dataTable thead th:nth-child(3),
    table.dataTable tbody td:nth-child(3) {
        min-width: 300px;
        width: 300px;
        max-width: 300px;
    }

    table.dataTable tbody td:nth-child(3) {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        line-height: 1.5;
        word-wrap: break-word;
    }

    /* Lebarkan kolom FILE */
    table.dataTable thead th:nth-child(4),
    table.dataTable tbody td:nth-child(4) {
        min-width: 400px;
        width: 400px;
    }

    /* Lebarkan kolom PERINGKAT (kolom ke-5) */
    table.dataTable thead th:nth-child(5),
    table.dataTable tbody td:nth-child(5) {
        min-width: 250px;
        width: 250px;
        text-align: center;
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

    /* Center align untuk kolom tertentu */
    table.dataTable tbody td:nth-child(1),
    table.dataTable tbody td:nth-child(5),
    table.dataTable tbody td:nth-child(6),
    table.dataTable tbody td:nth-child(7),
    table.dataTable tbody td:nth-child(8),
    table.dataTable tbody td:nth-child(9),
    table.dataTable tbody td:nth-child(10) {
        text-align: center;
    }

    table.dataTable tbody tr:hover {
        background-color: rgba(108, 122, 224, 0.03);
    }

    /* File Icons */
    .file-icons {
        display: flex;
        flex-wrap: nowrap;
        gap: 12px;
        align-items: center;
        justify-content: flex-start;
    }

    .file-icon-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 4px;
        min-width: 70px;
        flex-shrink: 0;
    }

    .file-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        color: #fff;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
    }

    .file-icon:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
    }

    .file-icon-purple {
        background: linear-gradient(135deg, #6c7ae0 0%, #5a6ad8 100%);
    }

    .file-icon-green {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }

    .file-icon-orange {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    }

    .file-label {
        font-size: 11px;
        color: #64748b;
        text-align: center;
        max-width: 80px;
        line-height: 1.2;
        white-space: normal;
        word-wrap: break-word;
    }

    /* Grade Badge */
    .grade-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        font-size: 18px;
        font-weight: 700;
        color: #fff;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }

    .grade-A {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }

    .grade-B {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    }

    .grade-C {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    }

    .grade-none {
        background: linear-gradient(135deg, #94a3b8 0%, #64748b 100%);
        width: auto;
        min-width: 200px;
        padding: 10px 20px;
        border-radius: 25px;
        font-size: 14px;
        white-space: nowrap;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    /* Download SK Button */
    .btn-download-sk {
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
        background: linear-gradient(135deg, #6c7ae0 0%, #5a6ad8 100%);
        color: #fff;
    }

    .btn-download-sk:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(108, 122, 224, 0.3);
        color: #fff;
    }

    /* Action Button */
    .btn-action-edit {
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

    .btn-action-edit:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(108, 122, 224, 0.3);
        color: #fff;
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

    .form-label {
        font-weight: 600;
        color: #64748b;
        margin-bottom: 5px;
    }

    .form-control {
        border: 1px solid rgba(226, 232, 240, 0.8);
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 15px;
    }

    .form-control:focus {
        border-color: #6c7ae0;
        box-shadow: 0 0 0 3px rgba(108, 122, 224, 0.1);
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
        <h5 class="admin-table-title">Daftar Pemeringkatan</h5>
    </div>

    <table id="pemeringkatanTable" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>NO</th>
                <th>NO TANDA DAFTAR <i class="fas fa-sort"></i></th>
                <th>NAMA LEMBAGA INKUBATOR <i class="fas fa-sort"></i></th>
                <th>FILE <i class="fas fa-sort"></i></th>
                <th>PERINGKAT <i class="fas fa-sort"></i></th>
                <th>TANGGAL SK <i class="fas fa-sort"></i></th>
                <th>MASA BERLAKU SK <i class="fas fa-sort"></i></th>
                <th>TANGGAL HABIS SK <i class="fas fa-sort"></i></th>
                <th>FILE SK</th>
                <th>AKSI</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pemeringkatans as $index => $pemeringkatan)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td style="font-weight: 500; color: #334155;">{{ $pemeringkatan->no_tanda_daftar ?? '-' }}</td>
                    <td style="font-weight: 500; color: #334155;">{{ $pemeringkatan->nama_inkubator ?? '-' }}</td>
                    <td>
                        <div class="file-icons">
                            @if($pemeringkatan->file_pemeringkatan)
                                <div class="file-icon-item">
                                    <a href="{{ route('pemeringkatan.download-file', ['id' => $pemeringkatan->id, 'type' => 'pemeringkatan']) }}" 
                                       class="file-icon file-icon-purple" 
                                       title="Permohonan">
                                        <i class="fas fa-cloud"></i>
                                    </a>
                                    <span class="file-label">Permohonan</span>
                                </div>
                            @endif
                            @if($pemeringkatan->file_pengelola)
                                <div class="file-icon-item">
                                    <a href="{{ route('pemeringkatan.download-file', ['id' => $pemeringkatan->id, 'type' => 'pengelola']) }}" 
                                       class="file-icon file-icon-green" 
                                       title="Pengelola">
                                        <i class="fas fa-cloud"></i>
                                    </a>
                                    <span class="file-label">Pengelola</span>
                                </div>
                            @endif
                            @if($pemeringkatan->file_profil_lembaga)
                                <div class="file-icon-item">
                                    <a href="{{ route('pemeringkatan.download-file', ['id' => $pemeringkatan->id, 'type' => 'profil-lembaga']) }}" 
                                       class="file-icon file-icon-orange" 
                                       title="Profil Lembaga">
                                        <i class="fas fa-cloud"></i>
                                    </a>
                                    <span class="file-label">Profil Lembaga</span>
                                </div>
                            @endif
                            @if($pemeringkatan->file_sarana_prasarana)
                                <div class="file-icon-item">
                                    <a href="{{ route('pemeringkatan.download-file', ['id' => $pemeringkatan->id, 'type' => 'sarana-prasarana']) }}" 
                                       class="file-icon file-icon-purple" 
                                       title="Sarana dan Prasarana">
                                        <i class="fas fa-cloud"></i>
                                    </a>
                                    <span class="file-label">Sarana dan Prasarana</span>
                                </div>
                            @endif
                            @if($pemeringkatan->file_bisnis_model)
                                <div class="file-icon-item">
                                    <a href="{{ route('pemeringkatan.download-file', ['id' => $pemeringkatan->id, 'type' => 'bisnis-model']) }}" 
                                       class="file-icon file-icon-green" 
                                       title="Binis Model">
                                        <i class="fas fa-cloud"></i>
                                    </a>
                                    <span class="file-label">Binis Model</span>
                                </div>
                            @endif
                            @if(!$pemeringkatan->file_pemeringkatan && !$pemeringkatan->file_pengelola && !$pemeringkatan->file_profil_lembaga && !$pemeringkatan->file_sarana_prasarana && !$pemeringkatan->file_bisnis_model)
                                <span style="color: #94a3b8; font-size: 13px;">-</span>
                            @endif
                        </div>
                    </td>
                    <td>
                        @if($pemeringkatan->grade)
                            <span class="grade-badge grade-{{ $pemeringkatan->grade }}">
                                {{ $pemeringkatan->grade }}
                            </span>
                        @else
                            <span class="grade-badge grade-none">Belum Dilakukan Pemeringkatan</span>
                        @endif
                    </td>
                    <td>
                        {{ $pemeringkatan->tanggal_sk ? \Carbon\Carbon::parse($pemeringkatan->tanggal_sk)->format('d-m-Y') : '-' }}
                    </td>
                    <td>
                        {{ $pemeringkatan->masa_berlaku_sk ? $pemeringkatan->masa_berlaku_sk . ' Tahun' : '-' }}
                    </td>
                    <td>
                        {{ $pemeringkatan->tanggal_habis_sk ? \Carbon\Carbon::parse($pemeringkatan->tanggal_habis_sk)->format('d-m-Y') : '-' }}
                    </td>
                    <td>
                        @if($pemeringkatan->file_sk_pemeringkatan)
                            <a href="{{ route('pemeringkatan.download-file', ['id' => $pemeringkatan->id, 'type' => 'sk']) }}" 
                               class="btn-download-sk" 
                               title="Download File SK">
                                <i class="fas fa-download"></i>
                                <span>Download</span>
                            </a>
                        @else
                            <span style="color: #94a3b8; font-size: 13px;">-</span>
                        @endif
                    </td>
                    <td>
                        <button type="button" class="btn-action-edit" onclick="showDetail({{ $pemeringkatan->id }})" title="Edit">
                            <i class="fas fa-pencil-alt"></i>
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center" style="padding: 40px; color: #94a3b8;">
                        Tidak ada data pemeringkatan
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Modal Detail & Approve --}}
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel">Detail Pemeringkatan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="pemeringkatanForm">
                <div class="modal-body" id="detailModalBody">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn-reject" id="btnReject" onclick="rejectPemeringkatan()" style="display: none;">Tolak</button>
                    <button type="submit" class="btn-approve" id="btnApprove" style="display: none;">Setujui</button>
                </div>
            </form>
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
    $('#pemeringkatanTable').DataTable({
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        order: [[0, 'asc']],
        paging: true,
        searching: true,
        scrollX: true,
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

    // Handle form submit
    $('#pemeringkatanForm').on('submit', function(e) {
        e.preventDefault();
        approvePemeringkatan();
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
        url: '{{ route("pemeringkatan.show", ":id") }}'.replace(':id', id),
        method: 'GET',
        success: function(response) {
            if (response.success) {
                const data = response.data;
                let html = '';
                
                html += '<div class="mb-3">';
                html += '<label class="form-label">No Tanda Daftar:</label>';
                html += '<div style="color: #334155; font-weight: 500;">' + (data.no_tanda_daftar || '-') + '</div>';
                html += '</div>';
                
                html += '<div class="mb-3">';
                html += '<label class="form-label">Nama Lembaga Inkubator:</label>';
                html += '<div style="color: #334155; font-weight: 500;">' + (data.nama_inkubator || '-') + '</div>';
                html += '</div>';
                
                html += '<div class="mb-3">';
                html += '<label class="form-label">Peringkat (Grade) <span class="text-danger">*</span></label>';
                html += '<select class="form-control" id="grade" name="grade" required>';
                html += '<option value="">Pilih Peringkat</option>';
                html += '<option value="A"' + (data.grade == 'A' ? ' selected' : '') + '>A</option>';
                html += '<option value="B"' + (data.grade == 'B' ? ' selected' : '') + '>B</option>';
                html += '<option value="C"' + (data.grade == 'C' ? ' selected' : '') + '>C</option>';
                html += '</select>';
                html += '</div>';
                
                html += '<div class="mb-3">';
                html += '<label class="form-label">Tanggal SK <span class="text-danger">*</span></label>';
                html += '<input type="date" class="form-control" id="tanggal_sk" name="tanggal_sk" value="' + (data.tanggal_sk || '') + '" required>';
                html += '</div>';
                
                html += '<div class="mb-3">';
                html += '<label class="form-label">Masa Berlaku SK (Tahun) <span class="text-danger">*</span></label>';
                html += '<input type="number" class="form-control" id="masa_berlaku_sk" name="masa_berlaku_sk" value="' + (data.masa_berlaku_sk || '') + '" min="1" required>';
                html += '</div>';
                
                html += '<div class="mb-3">';
                html += '<label class="form-label">Tanggal Habis SK <span class="text-danger">*</span></label>';
                html += '<input type="date" class="form-control" id="tanggal_habis_sk" name="tanggal_habis_sk" value="' + (data.tanggal_habis_sk || '') + '" required>';
                html += '</div>';
                
                // File info
                html += '<div class="mb-3">';
                html += '<label class="form-label">File yang Diupload:</label>';
                html += '<div style="color: #64748b; font-size: 14px;">';
                if (data.file_pemeringkatan) html += '<div>✓ Permohonan</div>';
                if (data.file_pengelola) html += '<div>✓ Pengelola</div>';
                if (data.file_profil_lembaga) html += '<div>✓ Profil Lembaga</div>';
                if (data.file_sarana_prasarana) html += '<div>✓ Sarana dan Prasarana</div>';
                if (data.file_bisnis_model) html += '<div>✓ Binis Model</div>';
                if (data.file_sk_pemeringkatan) html += '<div>✓ File SK</div>';
                html += '</div>';
                html += '</div>';
                
                $('#detailModalBody').html(html);
                
                // Show approve/reject buttons if pending
                if (data.status == 0 || !data.status) {
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

function approvePemeringkatan() {
    if (!currentId) return;
    
    const formData = {
        grade: $('#grade').val(),
        tanggal_sk: $('#tanggal_sk').val(),
        masa_berlaku_sk: $('#masa_berlaku_sk').val(),
        tanggal_habis_sk: $('#tanggal_habis_sk').val(),
    };

    if (!formData.grade || !formData.tanggal_sk || !formData.masa_berlaku_sk || !formData.tanggal_habis_sk) {
        alert('Harap lengkapi semua field yang wajib diisi');
        return;
    }
    
    if (!confirm('Apakah Anda yakin ingin menyetujui pemeringkatan ini?')) {
        return;
    }
    
    $.ajax({
        url: '{{ route("pemeringkatan.approve", ":id") }}'.replace(':id', currentId),
        method: 'POST',
        data: formData,
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
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;
                let errorMsg = 'Validasi gagal:\n';
                Object.keys(errors).forEach(function(key) {
                    errorMsg += errors[key][0] + '\n';
                });
                alert(errorMsg);
            } else {
                alert(xhr.responseJSON?.message || 'Gagal menyetujui pemeringkatan');
            }
        }
    });
}

function rejectPemeringkatan() {
    if (!currentId) return;
    
    if (!confirm('Apakah Anda yakin ingin menolak pemeringkatan ini?')) {
        return;
    }
    
    $.ajax({
        url: '{{ route("pemeringkatan.reject", ":id") }}'.replace(':id', currentId),
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
            alert(xhr.responseJSON?.message || 'Gagal menolak pemeringkatan');
        }
    });
}

// Auto calculate tanggal_habis_sk based on tanggal_sk and masa_berlaku_sk
$(document).on('change', '#tanggal_sk, #masa_berlaku_sk', function() {
    const tanggalSk = $('#tanggal_sk').val();
    const masaBerlaku = parseInt($('#masa_berlaku_sk').val());
    
    if (tanggalSk && masaBerlaku) {
        const date = new Date(tanggalSk);
        date.setFullYear(date.getFullYear() + masaBerlaku);
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        $('#tanggal_habis_sk').val(year + '-' + month + '-' + day);
    }
});
</script>
@endpush
