@extends('layouts.admin')

@php
    use Illuminate\Support\Str;
@endphp

@section('title', 'Daftar Berita')
@section('page-title', 'Daftar Berita')

@section('breadcrumb')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">Daftar Berita</li>
</ol>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css">
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
    }

    table.dataTable tbody td:nth-child(1),
    table.dataTable tbody td:nth-child(3),
    table.dataTable tbody td:nth-child(4),
    table.dataTable tbody td:nth-child(5),
    table.dataTable tbody td:nth-child(6),
    table.dataTable tbody td:nth-child(7) {
        text-align: center;
    }

    table.dataTable tbody td:nth-child(2) {
        text-align: left;
        max-width: 400px;
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

    .btn-action-copy {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: #fff;
    }

    .btn-action-copy:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        color: #fff;
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

    .highlight-badge {
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

    .highlight-badge.highlighted {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: #fff;
    }

    .highlight-badge.not-highlighted {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: #fff;
    }

    .highlight-badge:hover {
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

    .note-editor {
        border-radius: 10px;
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
        <h5 class="admin-table-title">Daftar Berita</h5>
        <div class="admin-table-actions">
            <button type="button" class="admin-btn-add" data-bs-toggle="modal" data-bs-target="#beritaModal" onclick="openBeritaModal()">
                <i class="fas fa-plus"></i>
                Tambah
            </button>
        </div>
    </div>

    <table id="beritaTable" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>NO</th>
                <th>JUDUL <i class="fas fa-sort"></i></th>
                <th>TGL TAYANG <i class="fas fa-sort"></i></th>
                <th>TGL AKHIR <i class="fas fa-sort"></i></th>
                <th>PUBLISH</th>
                <th>HIGHLIGHT</th>
                <th>AKSI</th>
            </tr>
        </thead>
        <tbody>
            @forelse($beritas as $index => $berita)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ Str::limit($berita->judul, 70) ?? '-' }}</td>
                    <td>{{ $berita->tgl_tayang ? \Carbon\Carbon::parse($berita->tgl_tayang)->format('d-m-Y') : '-' }}</td>
                    <td>{{ $berita->tgl_akhir ? \Carbon\Carbon::parse($berita->tgl_akhir)->format('d-m-Y') : '-' }}</td>
                    <td>
                        <span class="publish-badge {{ $berita->is_publikasi == 1 ? 'published' : 'unpublished' }}" 
                              onclick="togglePublish({{ $berita->id }}, {{ $berita->is_publikasi }})">
                            @if($berita->is_publikasi == 1)
                                <i class="fas fa-check"></i>
                            @else
                                <i class="fas fa-times"></i>
                            @endif
                        </span>
                    </td>
                    <td>
                        <span class="highlight-badge {{ $berita->is_highlight == 1 ? 'highlighted' : 'not-highlighted' }}" 
                              onclick="toggleHighlight({{ $berita->id }}, {{ $berita->is_highlight }})">
                            @if($berita->is_highlight == 1)
                                <i class="fas fa-check"></i>
                            @else
                                <i class="fas fa-times"></i>
                            @endif
                        </span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <button type="button" class="btn-action btn-action-copy" title="Copy" onclick="copyBerita({{ $berita->id }})">
                                <i class="fas fa-copy"></i>
                            </button>
                            <button type="button" class="btn-action btn-action-edit" title="Edit" onclick="editBerita({{ $berita->id }})">
                                <i class="fas fa-pencil-alt"></i>
                            </button>
                            <button type="button" class="btn-action btn-action-delete" title="Delete" onclick="deleteBerita({{ $berita->id }})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data berita.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Modal Form Berita --}}
<div class="modal fade" id="beritaModal" tabindex="-1" aria-labelledby="beritaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="beritaModalLabel">Tambah Berita</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="closeBeritaModal()"></button>
            </div>
            <form id="beritaForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" id="berita_id" name="id">
                    
                    <div class="mb-3">
                        <label for="judul" class="form-label">Judul Berita <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="judul" name="judul" required>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <label for="isi" class="form-label">Isi Berita <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="isi" name="isi" rows="10" required></textarea>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tgl_tayang" class="form-label">Tanggal Tayang <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="tgl_tayang" name="tgl_tayang" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tgl_akhir" class="form-label">Tanggal Akhir</label>
                                <input type="date" class="form-control" id="tgl_akhir" name="tgl_akhir">
                                <small class="text-muted">Kosongkan jika tidak ada batas waktu</small>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="gambar" class="form-label">Upload Gambar</label>
                        <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*" onchange="previewImage(this)">
                        <div class="invalid-feedback"></div>
                        <img id="preview" class="preview-image" style="display: none;" alt="Preview">
                    </div>

                    <div class="mb-3">
                        <label for="path_gambar" class="form-label">Path Gambar (opsional)</label>
                        <input type="text" class="form-control" id="path_gambar" name="path_gambar" placeholder="Contoh: img/berita/image.jpg">
                        <small class="text-muted">Jika tidak upload file, isi path gambar yang sudah ada</small>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_publikasi" name="is_publikasi" value="1" checked>
                                    <label class="form-check-label" for="is_publikasi">
                                        Publish
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_highlight" name="is_highlight" value="1">
                                    <label class="form-check-label" for="is_highlight">
                                        Highlight
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="closeBeritaModal()">Batal</button>
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
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
<script>
$(document).ready(function() {
    $('#beritaTable').DataTable({
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        order: [[2, 'desc']], // Sort by TGL TAYANG descending
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

    // Initialize Summernote
    $('#isi').summernote({
        height: 300,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'clear']],
            ['fontname', ['fontname']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'video']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ]
    });

    // Handle form submit
    $('#beritaForm').on('submit', function(e) {
        e.preventDefault();
        saveBerita();
    });

    // Cleanup modal backdrop when modal is hidden
    $('#beritaModal').on('hidden.bs.modal', function() {
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
function closeBeritaModal() {
    setTimeout(function() {
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open');
        $('body').css('overflow', '');
        $('body').css('padding-right', '');
    }, 300);
}

// Open modal untuk tambah
function openBeritaModal() {
    $('.modal-backdrop').remove();
    $('body').removeClass('modal-open');
    $('body').css('overflow', '');
    $('body').css('padding-right', '');
    
    $('#beritaForm')[0].reset();
    $('#berita_id').val('');
    $('#preview').hide();
    $('#isi').summernote('code', '');
    $('#beritaModalLabel').text('Tambah Berita');
    $('#is_publikasi').prop('checked', true);
    $('#is_highlight').prop('checked', false);
    $('.invalid-feedback').text('').hide();
    $('.form-control').removeClass('is-invalid');
    
    const modalElement = document.getElementById('beritaModal');
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

// Edit berita
function editBerita(id) {
    $.ajax({
        url: '{{ route("admin.berita.show", ":id") }}'.replace(':id', id),
        method: 'GET',
        success: function(response) {
            if (response.success) {
                $('#berita_id').val(response.data.id);
                $('#judul').val(response.data.judul);
                $('#isi').summernote('code', response.data.isi);
                $('#tgl_tayang').val(response.data.tgl_tayang);
                $('#tgl_akhir').val(response.data.tgl_akhir);
                $('#path_gambar').val(response.data.path_gambar);
                $('#is_publikasi').prop('checked', response.data.is_publikasi == 1);
                $('#is_highlight').prop('checked', response.data.is_highlight == 1);
                $('#preview').hide();
                $('#beritaModalLabel').text('Edit Berita');
                $('.invalid-feedback').text('').hide();
                $('.form-control').removeClass('is-invalid');
                
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open');
                $('body').css('overflow', '');
                $('body').css('padding-right', '');
                
                const modalElement = document.getElementById('beritaModal');
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
            alert('Gagal memuat data berita');
        }
    });
}

// Save berita (create/update)
function saveBerita() {
    const formData = new FormData();
    formData.append('judul', $('#judul').val());
    formData.append('isi', $('#isi').summernote('code'));
    formData.append('tgl_tayang', $('#tgl_tayang').val());
    formData.append('tgl_akhir', $('#tgl_akhir').val() || '');
    formData.append('path_gambar', $('#path_gambar').val() || '');
    formData.append('is_publikasi', $('#is_publikasi').is(':checked') ? 1 : 0);
    formData.append('is_highlight', $('#is_highlight').is(':checked') ? 1 : 0);
    
    if ($('#gambar')[0].files.length > 0) {
        formData.append('gambar', $('#gambar')[0].files[0]);
    }

    const id = $('#berita_id').val();
    const url = id 
        ? '{{ route("admin.berita.update", ":id") }}'.replace(':id', id)
        : '{{ route("admin.berita.store") }}';
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
                const modalElement = document.getElementById('beritaModal');
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

// Delete berita
function deleteBerita(id) {
    if (!confirm('Apakah Anda yakin ingin menghapus berita ini?')) {
        return;
    }

    $.ajax({
        url: '{{ route("admin.berita.destroy", ":id") }}'.replace(':id', id),
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                alert(response.message || 'Berita berhasil dihapus');
                location.reload();
            }
        },
        error: function(xhr) {
            alert(xhr.responseJSON?.message || 'Gagal menghapus data');
        }
    });
}

// Copy berita
function copyBerita(id) {
    if (!confirm('Apakah Anda yakin ingin menduplikasi berita ini?')) {
        return;
    }

    $.ajax({
        url: '{{ route("admin.berita.copy", ":id") }}'.replace(':id', id),
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                alert(response.message || 'Berita berhasil diduplikasi');
                location.reload();
            }
        },
        error: function(xhr) {
            alert(xhr.responseJSON?.message || 'Gagal menduplikasi berita');
        }
    });
}

// Toggle publish/unpublish
function togglePublish(id, currentStatus) {
    $.ajax({
        url: '{{ route("admin.berita.toggle-publish", ":id") }}'.replace(':id', id),
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

// Toggle highlight
function toggleHighlight(id, currentStatus) {
    $.ajax({
        url: '{{ route("admin.berita.toggle-highlight", ":id") }}'.replace(':id', id),
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                alert(response.message || 'Status highlight berhasil diubah');
                location.reload();
            }
        },
        error: function(xhr) {
            alert(xhr.responseJSON?.message || 'Gagal mengubah status highlight');
        }
    });
}
</script>
@endpush
