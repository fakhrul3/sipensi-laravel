@extends('layouts.admin')

@section('title', 'Data Manajemen Gambar')
@section('page-title', 'Data Manajemen Gambar')

@section('breadcrumb')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">Data Gambar Header</li>
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

    .admin-btn-add {
        background: linear-gradient(135deg, #6c7ae0 0%, #5a6ad8 100%);
        color: #fff;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 2px 6px rgba(108, 122, 224, 0.2);
    }

    .admin-btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(108, 122, 224, 0.3);
        color: #fff;
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
        text-align: center;
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

    .btn-action-edit {
        background: linear-gradient(135deg, #6c7ae0 0%, #5a6ad8 100%);
        color: #fff;
    }

    .btn-action-edit:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(108, 122, 224, 0.3);
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

    .btn-download {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: #fff;
        border: none;
        cursor: pointer;
        box-shadow: 0 2px 6px rgba(16, 185, 129, 0.2);
    }

    .btn-download:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        color: #fff;
    }

    .publish-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .publish-badge.published {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: #fff;
    }

    .publish-badge.unpublished {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: #fff;
    }

    .publish-badge:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
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

    .preview-image {
        max-width: 100%;
        max-height: 200px;
        border-radius: 8px;
        margin-top: 10px;
        object-fit: cover;
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
        <h5 class="admin-table-title">Daftar Gambar</h5>
        <div class="admin-table-actions">
            <button type="button" class="admin-btn-add" data-bs-toggle="modal" data-bs-target="#gambarModal" onclick="openGambarModal()">
                <i class="fas fa-plus"></i>
                Tambah
            </button>
        </div>
    </div>

    <table id="gambarTable" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>NO</th>
                <th>JUDUL</th>
                <th>GAMBAR</th>
                <th>PUBLISH</th>
                <th>AKSI</th>
            </tr>
        </thead>
        <tbody>
            @forelse($gambars as $index => $gambar)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $gambar->option_gambar ?? '-' }}</td>
                    <td>
                        @if($gambar->path_gambar)
                            <a href="{{ route('manajemen-gambar.download', $gambar->id) }}" class="btn-download" title="Download Gambar">
                                <i class="fas fa-download"></i> Gambar
                            </a>
                        @else
                            <span class="text-muted">Tidak ada gambar</span>
                        @endif
                    </td>
                    <td>
                        <span class="publish-badge {{ $gambar->is_show == 1 ? 'published' : 'unpublished' }}" 
                              onclick="togglePublish({{ $gambar->id }}, {{ $gambar->is_show }})">
                            @if($gambar->is_show == 1)
                                <i class="fas fa-check"></i> Published
                            @else
                                <i class="fas fa-times"></i> Unpublished
                            @endif
                        </span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <button type="button" class="btn-action btn-action-edit" title="Edit" onclick="editGambar({{ $gambar->id }})">
                                <i class="fas fa-pencil-alt"></i>
                            </button>
                            <button type="button" class="btn-action btn-action-delete" title="Delete" onclick="deleteGambar({{ $gambar->id }})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Tidak ada data gambar.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Modal Form Gambar --}}
<div class="modal fade" id="gambarModal" tabindex="-1" aria-labelledby="gambarModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="gambarModalLabel">Tambah Gambar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="closeGambarModal()"></button>
            </div>
            <form id="gambarForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" id="gambar_id" name="id">
                    
                    <div class="mb-3">
                        <label for="option_gambar" class="form-label">Judul/Option Gambar <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="option_gambar" name="option_gambar" required placeholder="Contoh: carousel_1, tentang_1">
                        <small class="text-muted">Contoh: carousel_1, carousel_2, tentang_1, dll</small>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <label for="gambar" class="form-label">Upload Gambar</label>
                        <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*" onchange="previewImage(this)">
                        <div class="invalid-feedback"></div>
                        <img id="preview" class="preview-image" style="display: none;" alt="Preview">
                    </div>

                    <div class="mb-3">
                        <label for="path_gambar" class="form-label">Path Gambar (opsional)</label>
                        <input type="text" class="form-control" id="path_gambar" name="path_gambar" placeholder="Contoh: img/carousel/image.jpg">
                        <small class="text-muted">Jika tidak upload file, isi path gambar yang sudah ada</small>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_show" name="is_show" value="1" checked>
                            <label class="form-check-label" for="is_show">
                                Tampilkan (Publish)
                            </label>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="closeGambarModal()">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
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
$(document).ready(function() {
    $('#gambarTable').DataTable({
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

    // Handle form submit
    $('#gambarForm').on('submit', function(e) {
        e.preventDefault();
        saveGambar();
    });

    // Cleanup modal backdrop when modal is hidden
    $('#gambarModal').on('hidden.bs.modal', function() {
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open');
        $('body').css('overflow', '');
        $('body').css('padding-right', '');
    });
});

// Preview image
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            $('#preview').attr('src', e.target.result).show();
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Close modal dan cleanup
function closeGambarModal() {
    setTimeout(function() {
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open');
        $('body').css('overflow', '');
        $('body').css('padding-right', '');
    }, 300);
}

// Open modal untuk tambah
function openGambarModal() {
    $('.modal-backdrop').remove();
    $('body').removeClass('modal-open');
    $('body').css('overflow', '');
    $('body').css('padding-right', '');
    
    $('#gambarForm')[0].reset();
    $('#gambar_id').val('');
    $('#preview').hide();
    $('#gambarModalLabel').text('Tambah Gambar');
    $('#is_show').prop('checked', true);
    $('.invalid-feedback').text('').hide();
    $('.form-control').removeClass('is-invalid');
    
    const modalElement = document.getElementById('gambarModal');
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

// Edit gambar
function editGambar(id) {
    $.ajax({
        url: '{{ route("manajemen-gambar.show", ":id") }}'.replace(':id', id),
        method: 'GET',
        success: function(response) {
            if (response.success) {
                $('#gambar_id').val(response.data.id);
                $('#option_gambar').val(response.data.option_gambar);
                $('#path_gambar').val(response.data.path_gambar);
                $('#is_show').prop('checked', response.data.is_show == 1);
                $('#preview').hide();
                $('#gambarModalLabel').text('Edit Gambar');
                $('.invalid-feedback').text('').hide();
                $('.form-control').removeClass('is-invalid');
                
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open');
                $('body').css('overflow', '');
                $('body').css('padding-right', '');
                
                const modalElement = document.getElementById('gambarModal');
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
            alert('Gagal memuat data gambar');
        }
    });
}

// Save gambar (create/update)
function saveGambar() {
    const formData = new FormData();
    formData.append('option_gambar', $('#option_gambar').val());
    formData.append('path_gambar', $('#path_gambar').val());
    formData.append('is_show', $('#is_show').is(':checked') ? 1 : 0);
    
    if ($('#gambar')[0].files.length > 0) {
        formData.append('gambar', $('#gambar')[0].files[0]);
    }

    const id = $('#gambar_id').val();
    const url = id 
        ? '{{ route("manajemen-gambar.update", ":id") }}'.replace(':id', id)
        : '{{ route("manajemen-gambar.store") }}';
    const method = id ? 'PUT' : 'POST';

    if (method === 'PUT') {
        formData.append('_method', 'PUT');
    }

    $.ajax({
        url: url,
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                const modalElement = document.getElementById('gambarModal');
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
                alert(response.message || 'Data berhasil disimpan');
                location.reload();
            }
        },
        error: function(xhr) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;
                Object.keys(errors).forEach(function(key) {
                    const input = $('#' + key);
                    input.addClass('is-invalid');
                    input.siblings('.invalid-feedback').text(errors[key][0]).show();
                });
            } else {
                alert(xhr.responseJSON?.message || 'Gagal menyimpan data');
            }
        }
    });
}

// Delete gambar
function deleteGambar(id) {
    if (!confirm('Apakah Anda yakin ingin menghapus gambar ini?')) {
        return;
    }

    $.ajax({
        url: '{{ route("manajemen-gambar.destroy", ":id") }}'.replace(':id', id),
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                alert(response.message || 'Gambar berhasil dihapus');
                location.reload();
            }
        },
        error: function(xhr) {
            alert(xhr.responseJSON?.message || 'Gagal menghapus data');
        }
    });
}

// Toggle publish/unpublish
function togglePublish(id, currentStatus) {
    $.ajax({
        url: '{{ route("manajemen-gambar.toggle-publish", ":id") }}'.replace(':id', id),
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                alert(response.message || 'Status berhasil diubah');
                location.reload();
            }
        },
        error: function(xhr) {
            alert(xhr.responseJSON?.message || 'Gagal mengubah status');
        }
    });
}
</script>
@endpush
