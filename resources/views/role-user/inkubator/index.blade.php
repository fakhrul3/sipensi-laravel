@extends('layouts.admin')
@section('title', 'Data Inkubator')
@section('page-title', 'Data Inkubator')

@section('breadcrumb')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">Data Inkubator</li>
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

    .admin-search-box {
        position: relative;
        min-width: 200px;
    }

    .admin-search-input {
        width: 100%;
        padding: 8px 35px 8px 12px;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        font-size: 14px;
    }

    .admin-search-input:focus {
        outline: none;
        border-color: #007bff;
        box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
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

    .admin-action-buttons {
        display: flex;
        gap: 8px;
    }

    .admin-btn-action {
        width: 32px;
        height: 32px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        text-decoration: none;
        color: #fff;
    }

    .admin-btn-copy {
        background-color: #17a2b8;
    }

    .admin-btn-copy:hover {
        background-color: #138496;
    }

    .admin-btn-edit {
        background-color: #28a745;
    }

    .admin-btn-edit:hover {
        background-color: #218838;
    }

    .admin-btn-delete {
        background-color: #dc3545;
    }

    .admin-btn-delete:hover {
        background-color: #c82333;
    }

    .password-masked {
        font-family: monospace;
        letter-spacing: 2px;
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
        <h5 class="admin-table-title">Daftar Inkubator</h5>
        <div class="admin-table-actions">
            <a href="{{ route('role-user.inkubator.export', ['format' => 'csv']) }}" class="admin-btn-export admin-btn-export-csv">
                <i class="fas fa-file-csv"></i>
                Export CSV
            </a>
            <a href="{{ route('role-user.inkubator.export', ['format' => 'xlsx']) }}" class="admin-btn-export admin-btn-export-xlsx">
                <i class="fas fa-file-excel"></i>
                Export XLSX
            </a>
        </div>
    </div>

    <table id="inkubatorTable" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>NO</th>
                <th>NO TANDA DAFTAR</th>
                <th>NAMA LEMBAGA INKUBATOR</th>
                <th>EMAIL</th>
                <th>USERNAME</th>
                <th>PASSWORD</th>
                <th>VERIFIKASI</th>
                <th>AKSI</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $index => $user)
                <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $user->no_tanda_daftar ?? '-' }}</td>
                <td>{{ $user->nama_inkubator ?? '-' }}</td>
                <td>{{ $user->inkubator_email ?? '-' }}</td>
                <td>{{ $user->username ?? '-' }}</td>

                <td class="password-masked">********************</td>

                <td>
                    @php $isVerified = ($user->is_verify == 1 || $user->is_verify == 2); @endphp
                    @if($isVerified)
                        <i class="fas fa-check text-success"></i>
                    @else
                        <i class="fas fa-times text-danger"></i>
                    @endif
                </td>
                    <td>
                        <div class="admin-action-buttons">
                            <button type="button" class="admin-btn-action admin-btn-copy" title="Copy Password" onclick="copyPassword('{{ $user->id }}')" data-password="**********************">
                                <i class="fas fa-copy"></i>
                            </button>
                            <button type="button" class="admin-btn-action admin-btn-edit" title="Edit" onclick="editInkubator({{ $user->id }})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button type="button" class="admin-btn-action admin-btn-delete" title="Delete" onclick="deleteInkubatorUser({{ $user->id }})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Modal Form --}}
<div class="modal fade" id="inkubatorModal" tabindex="-1" aria-labelledby="inkubatorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="inkubatorModalLabel">Edit Inkubator</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="inkubatorForm">
                <div class="modal-body">
                    <input type="hidden" id="inkubator_id" name="id">
                    
                    <div class="mb-3">
                        <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="username" name="username" required>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">
                            Password <span class="text-danger">*</span>
                            <small class="text-muted" id="password-hint">(Minimal 6 karakter)</small>
                        </label>
                        <input type="password" class="form-control" id="password" name="password" required>
                        <div class="invalid-feedback"></div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
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
let inkubatorTable;

$(document).ready(function() {
    inkubatorTable = $('#inkubatorTable').DataTable({
        pageLength: 10,
        lengthMenu: [[10], [10]], // Hanya 10 entries, tidak ada opsi All
        order: [[0, 'asc']],
        paging: true, // Pastikan pagination aktif
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
            infoPostFix: "",
            emptyTable: "Tidak ada data",
            zeroRecords: "Tidak ada data yang cocok"
        }
    });

    // Handle form submit (edit-only: tidak boleh create dari halaman ini)
    $('#inkubatorForm').on('submit', function(e) {
        e.preventDefault();
        saveInkubatorUser();
    });
});

// Edit inkubator user
function editInkubator(id) {
    $.ajax({
        url: '{{ route("role-user.inkubator.show", ":id") }}'.replace(':id', id),
        method: 'GET',
        success: function(response) {
            if (response.success) {
                $('#inkubator_id').val(response.data.id);
                $('#username').val(response.data.username);
                $('#password').val('').removeAttr('required');
                $('#password-hint').html('<small class="text-muted">(Kosongkan jika tidak ingin mengubah password)</small>');
                $('#inkubatorModalLabel').text('Edit Inkubator');
                $('.invalid-feedback').text('').hide();
                $('.form-control').removeClass('is-invalid');
                
                // Show modal using Bootstrap 5
                const modal = new bootstrap.Modal(document.getElementById('inkubatorModal'));
                modal.show();
            }
        },
        error: function() {
            alert('Gagal memuat data inkubator');
        }
    });
}

// Save inkubator user (create/update)
function saveInkubatorUser() {
    const formData = {
        username: $('#username').val(),
        password: $('#password').val(),
    };

    const id = $('#inkubator_id').val();
    if (!id) {
        alert('Tidak dapat menambah akun inkubator dari halaman ini. Gunakan proses registrasi.');
        return;
    }

    const url = '{{ route("role-user.inkubator.update", ":id") }}'.replace(':id', id);
    const method = 'PUT';

    // Add _method for PUT
    if (method === 'PUT') {
        formData._method = 'PUT';
    }

    $.ajax({
        url: url,
        method: 'POST',
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                // Close modal using Bootstrap 5
                const modal = bootstrap.Modal.getInstance(document.getElementById('inkubatorModal'));
                if (modal) {
                    modal.hide();
                }
                alert(response.message || 'Data berhasil disimpan');
                location.reload(); // Reload untuk refresh data
            }
        },
        error: function(xhr) {
            if (xhr.status === 422) {
                // Validation errors
                const errors = xhr.responseJSON.errors;
                Object.keys(errors).forEach(function(key) {
                    const input = $('#' + key);
                    input.addClass('is-invalid');
                    input.siblings('.invalid-feedback').text(errors[key][0]).show();
                });
            } else {
                alert(xhr.responseJSON.message || 'Gagal menyimpan data');
            }
        }
    });
}

// Delete inkubator user
function deleteInkubatorUser(id) {
    if (!confirm('Apakah Anda yakin ingin menghapus user inkubator ini?')) {
        return;
    }

    $.ajax({
        url: '{{ route("role-user.inkubator.destroy", ":id") }}'.replace(':id', id),
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                alert(response.message || 'User inkubator berhasil dihapus');
                location.reload();
            }
        },
        error: function(xhr) {
            alert(xhr.responseJSON?.message || 'Gagal menghapus data');
        }
    });
}

// Copy password (placeholder untuk fungsionalitas copy)
function copyPassword(id) {
    // Ini hanya placeholder karena password tidak bisa di-decrypt
    alert('Password tidak dapat dilihat karena di-hash untuk keamanan. Gunakan fitur reset password jika diperlukan.');
}
</script>
@endpush
