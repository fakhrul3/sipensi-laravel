@extends('layouts.admin')

@section('title', 'Data Klasifikasi Bisnis')
@section('page-title', 'Data Klasifikasi Bisnis')

@section('breadcrumb')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">Data Klasifikasi Bisnis</li>
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

    .form-control {
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 8px 12px;
    }

    .form-control:focus {
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

    .form-control.is-invalid {
        border-color: #dc3545;
    }

    .form-control.is-invalid + .invalid-feedback {
        display: block;
    }
</style>
@endpush

@section('content')
<div class="admin-table-container">
    <div class="admin-table-header">
        <h5 class="admin-table-title">Daftar Klasifikasi Bisnis Tenant</h5>
        <div class="admin-table-actions">
            <button type="button" class="admin-btn-add" data-bs-toggle="modal" data-bs-target="#klasifikasiBisnisModal" onclick="openKlasifikasiBisnisModal()">
                <i class="fas fa-plus"></i>
                Tambah
            </button>
        </div>
    </div>

    <table id="klasifikasiBisnisTable" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>NO</th>
                <th>NAMA KLASIFIKASI BISNIS <i class="fas fa-sort"></i></th>
                <th>AKSI</th>
            </tr>
        </thead>
        <tbody>
            @forelse($klasifikasiBisniss as $index => $klasifikasiBisnis)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $klasifikasiBisnis->name ?? '-' }}</td>
                    <td>
                        <div class="action-buttons">
                            <button type="button" class="btn-action btn-action-edit" title="Edit" onclick="editKlasifikasiBisnis({{ $klasifikasiBisnis->id }})">
                                <i class="fas fa-pencil-alt"></i>
                            </button>
                            <button type="button" class="btn-action btn-action-delete" title="Delete" onclick="deleteKlasifikasiBisnis({{ $klasifikasiBisnis->id }})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Modal Form Klasifikasi Bisnis --}}
<div class="modal fade" id="klasifikasiBisnisModal" tabindex="-1" aria-labelledby="klasifikasiBisnisModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="klasifikasiBisnisModalLabel">Tambah Klasifikasi Bisnis</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="closeKlasifikasiBisnisModal()"></button>
            </div>
            <form id="klasifikasiBisnisForm">
                <div class="modal-body">
                    <input type="hidden" id="klasifikasi_bisnis_id" name="id">
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Klasifikasi Bisnis <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" required>
                        <div class="invalid-feedback"></div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="closeKlasifikasiBisnisModal()">Batal</button>
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
    $('#klasifikasiBisnisTable').DataTable({
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
    $('#klasifikasiBisnisForm').on('submit', function(e) {
        e.preventDefault();
        saveKlasifikasiBisnis();
    });

    // Cleanup modal backdrop when modal is hidden
    $('#klasifikasiBisnisModal').on('hidden.bs.modal', function() {
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open');
        $('body').css('overflow', '');
        $('body').css('padding-right', '');
    });
});

// Close modal dan cleanup
function closeKlasifikasiBisnisModal() {
    setTimeout(function() {
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open');
        $('body').css('overflow', '');
        $('body').css('padding-right', '');
    }, 300);
}

// Open modal untuk tambah
function openKlasifikasiBisnisModal() {
    // Remove any existing modal backdrop first
    $('.modal-backdrop').remove();
    $('body').removeClass('modal-open');
    $('body').css('overflow', '');
    $('body').css('padding-right', '');
    
    $('#klasifikasiBisnisForm')[0].reset();
    $('#klasifikasi_bisnis_id').val('');
    $('#klasifikasiBisnisModalLabel').text('Tambah Klasifikasi Bisnis');
    $('.invalid-feedback').text('').hide();
    $('.form-control').removeClass('is-invalid');
    
    const modalElement = document.getElementById('klasifikasiBisnisModal');
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

// Edit klasifikasi bisnis
function editKlasifikasiBisnis(id) {
    $.ajax({
        url: '{{ route("klasifikasi-bisnis.show", ":id") }}'.replace(':id', id),
        method: 'GET',
        success: function(response) {
            if (response.success) {
                $('#klasifikasi_bisnis_id').val(response.data.id);
                $('#name').val(response.data.name);
                $('#klasifikasiBisnisModalLabel').text('Edit Klasifikasi Bisnis');
                $('.invalid-feedback').text('').hide();
                $('.form-control').removeClass('is-invalid');
                
                // Remove any existing modal backdrop first
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open');
                $('body').css('overflow', '');
                $('body').css('padding-right', '');
                
                const modalElement = document.getElementById('klasifikasiBisnisModal');
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
            alert('Gagal memuat data klasifikasi bisnis');
        }
    });
}

// Save klasifikasi bisnis (create/update)
function saveKlasifikasiBisnis() {
    const formData = {
        name: $('#name').val(),
    };

    console.log('Form Data to Send:', formData);

    const id = $('#klasifikasi_bisnis_id').val();
    const url = id 
        ? '{{ route("klasifikasi-bisnis.update", ":id") }}'.replace(':id', id)
        : '{{ route("klasifikasi-bisnis.store") }}';
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
                const modalElement = document.getElementById('klasifikasiBisnisModal');
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

// Delete klasifikasi bisnis
function deleteKlasifikasiBisnis(id) {
    if (!confirm('Apakah Anda yakin ingin menghapus klasifikasi bisnis ini?')) {
        return;
    }

    $.ajax({
        url: '{{ route("klasifikasi-bisnis.destroy", ":id") }}'.replace(':id', id),
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                alert(response.message || 'Klasifikasi bisnis berhasil dihapus');
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
