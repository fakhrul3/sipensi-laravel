@extends('layouts.admin')

@section('title', 'Data Kabupaten/Kota')
@section('page-title', 'Data Kabupaten/Kota')

@section('breadcrumb')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">Data Kabupaten/Kota</li>
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
        background-color: #6f42c1;
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

    .admin-btn-add:hover {
        background-color: #5a32a3;
        color: #fff;
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
        position: relative;
    }

    table.dataTable thead th .fas.fa-sort {
        margin-left: 5px;
        opacity: 0.5;
        font-size: 10px;
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

    .btn-action-edit {
        background-color: #6c757d;
    }

    .btn-action-delete {
        background-color: #6c757d;
    }

    /* Modal Styles */
    .modal-content {
        border-radius: 10px;
        border: none;
        box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.2);
    }

    .modal-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        border-radius: 10px 10px 0 0;
    }

    .modal-title {
        font-weight: 600;
        color: #2c3e50;
    }

    .form-label {
        font-weight: 500;
        color: #495057;
        margin-bottom: 5px;
    }

    .form-control, .form-select {
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 8px 12px;
    }

    .form-control:focus, .form-select:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
    }

    .btn-primary {
        background-color: #007bff;
        border: none;
        padding: 8px 20px;
        border-radius: 6px;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    .btn-secondary {
        background-color: #6c757d;
        border: none;
        padding: 8px 20px;
        border-radius: 6px;
    }

    .invalid-feedback {
        display: none;
        font-size: 12px;
        color: #dc3545;
        margin-top: 5px;
    }

    .form-control.is-invalid, .form-select.is-invalid {
        border-color: #dc3545;
    }

    .form-control.is-invalid + .invalid-feedback, .form-select.is-invalid + .invalid-feedback {
        display: block;
    }
</style>
@endpush

@section('content')
<div class="admin-table-container">
    <div class="admin-table-header">
        <h5 class="admin-table-title">Daftar Kabupaten/Kota</h5>
        <div class="admin-table-actions">
            <button type="button" class="admin-btn-add" data-bs-toggle="modal" data-bs-target="#kabupatenModal" onclick="openKabupatenModal()">
                <i class="fas fa-plus"></i>
                Tambah
            </button>
        </div>
    </div>

    <table id="kabupatenTable" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>NO</th>
                <th>NAMA KABUPATEN/KOTA <i class="fas fa-sort"></i></th>
                <th>KODE KABUPATEN/KOTA <i class="fas fa-sort"></i></th>
                <th>PROVINSI ID <i class="fas fa-sort"></i></th>
                <th>AKSI</th>
            </tr>
        </thead>
        <tbody>
            @forelse($kabupatens as $index => $kabupaten)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $kabupaten->name ?? '-' }}</td>
                    <td>{{ $kabupaten->kode_kabupaten ?? '-' }}</td>
                    <td>{{ $kabupaten->provinsi_id ?? '-' }}</td>
                    <td>
                        <div class="action-buttons">
                            <button type="button" class="btn-action btn-action-edit" title="Edit" onclick="editKabupaten({{ $kabupaten->id }})">
                                <i class="fas fa-pencil-alt"></i>
                            </button>
                            <button type="button" class="btn-action btn-action-delete" title="Delete" onclick="deleteKabupaten({{ $kabupaten->id }})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Modal Form Kabupaten --}}
<div class="modal fade" id="kabupatenModal" tabindex="-1" aria-labelledby="kabupatenModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="kabupatenModalLabel">Tambah Kabupaten/Kota</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="closeKabupatenModal()"></button>
            </div>
            <form id="kabupatenForm">
                <div class="modal-body">
                    <input type="hidden" id="kabupaten_id" name="id">
                    
                    <div class="mb-3">
                        <label for="kode_kabupaten" class="form-label">Kode Kabupaten/Kota <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="kode_kabupaten" name="kode_kabupaten" required>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <label for="provinsi_id" class="form-label">Provinsi <span class="text-danger">*</span></label>
                        <select class="form-select" id="provinsi_id" name="provinsi_id" required>
                            <option value="">-- Pilih Provinsi --</option>
                            @foreach($provinsis as $provinsi)
                                <option value="{{ $provinsi->id }}">{{ $provinsi->name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Kabupaten/Kota <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" required>
                        <div class="invalid-feedback"></div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="closeKabupatenModal()">Batal</button>
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
    $('#kabupatenTable').DataTable({
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        order: [[1, 'asc']],
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
    $('#kabupatenForm').on('submit', function(e) {
        e.preventDefault();
        saveKabupaten();
    });

    // Cleanup modal backdrop when modal is hidden
    $('#kabupatenModal').on('hidden.bs.modal', function() {
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open');
        $('body').css('overflow', '');
        $('body').css('padding-right', '');
    });
});

// Close modal dan cleanup
function closeKabupatenModal() {
    setTimeout(function() {
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open');
        $('body').css('overflow', '');
        $('body').css('padding-right', '');
    }, 300);
}

// Open modal untuk tambah
function openKabupatenModal() {
    // Remove any existing modal backdrop first
    $('.modal-backdrop').remove();
    $('body').removeClass('modal-open');
    $('body').css('overflow', '');
    $('body').css('padding-right', '');
    
    $('#kabupatenForm')[0].reset();
    $('#kabupaten_id').val('');
    $('#kabupatenModalLabel').text('Tambah Kabupaten/Kota');
    $('.invalid-feedback').text('').hide();
    $('.form-control, .form-select').removeClass('is-invalid');
    
    const modalElement = document.getElementById('kabupatenModal');
    const modal = new bootstrap.Modal(modalElement, {
        backdrop: true,
        keyboard: true
    });
    
    // Clean up on hide
    modalElement.addEventListener('hidden.bs.modal', function() {
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open');
        $('body').css('overflow', '');
        $('body').css('padding-right', '');
    }, { once: true });
    
    modal.show();
}

// Edit kabupaten
function editKabupaten(id) {
    $.ajax({
        url: '{{ route("wilayah.kabupaten.show", ":id") }}'.replace(':id', id),
        method: 'GET',
        success: function(response) {
            if (response.success) {
                $('#kabupaten_id').val(response.data.id);
                $('#kode_kabupaten').val(response.data.kode_kabupaten);
                $('#provinsi_id').val(response.data.provinsi_id);
                $('#name').val(response.data.name);
                $('#kabupatenModalLabel').text('Edit Kabupaten/Kota');
                $('.invalid-feedback').text('').hide();
                $('.form-control, .form-select').removeClass('is-invalid');
                
                // Remove any existing modal backdrop first
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open');
                $('body').css('overflow', '');
                $('body').css('padding-right', '');
                
                const modalElement = document.getElementById('kabupatenModal');
                const modal = new bootstrap.Modal(modalElement, {
                    backdrop: true,
                    keyboard: true
                });
                
                // Clean up on hide
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
            alert('Gagal memuat data kabupaten');
        }
    });
}

// Save kabupaten (create/update)
function saveKabupaten() {
    const formData = {
        kode_kabupaten: $('#kode_kabupaten').val(),
        provinsi_id: $('#provinsi_id').val(),
        name: $('#name').val(),
    };

    console.log('Form Data to Send:', formData);

    const id = $('#kabupaten_id').val();
    const url = id 
        ? '{{ route("wilayah.kabupaten.update", ":id") }}'.replace(':id', id)
        : '{{ route("wilayah.kabupaten.store") }}';
    const method = id ? 'PUT' : 'POST';

    if (method === 'PUT') {
        formData._method = 'PUT';
    }

    console.log('URL:', url);
    console.log('Method:', method);

    $.ajax({
        url: url,
        method: 'POST',
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            console.log('Success Response:', response);
            if (response.success) {
                const modalElement = document.getElementById('kabupatenModal');
                const modal = bootstrap.Modal.getInstance(modalElement);
                if (modal) {
                    modal.hide();
                }
                // Cleanup overlay
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
            console.error('Error Response:', xhr);
            console.error('Response JSON:', xhr.responseJSON);
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;
                Object.keys(errors).forEach(function(key) {
                    const input = $('#' + key);
                    input.addClass('is-invalid');
                    input.siblings('.invalid-feedback').text(errors[key][0]).show();
                });
            } else {
                alert(xhr.responseJSON?.message || 'Gagal menyimpan data. Cek console untuk detail.');
            }
        }
    });
}

// Delete kabupaten
function deleteKabupaten(id) {
    if (!confirm('Apakah Anda yakin ingin menghapus kabupaten/kota ini?')) {
        return;
    }

    $.ajax({
        url: '{{ route("wilayah.kabupaten.destroy", ":id") }}'.replace(':id', id),
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                alert(response.message || 'Kabupaten/Kota berhasil dihapus');
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
