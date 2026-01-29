@extends('layouts.admin')

@section('title', 'Data Manajemen Gambar')
@section('page-title', 'Data Manajemen Gambar')

@section('breadcrumb')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">Data Gambar Header</li>
</ol>
@endsection

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
        <div class="admin-table-actions"> </div>
    </div>

    <table id="gambarTable" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>NO</th>
                <th>JUDUL</th>
                <th>GAMBAR</th>
                <th>PUBLISH</th>
                <th>TERAKHIR DIUPDATE</th>
                <th>AKSI</th>
            </tr>
        </thead>
        <tbody>
            @forelse($gambars as $index => $gambar)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        @php
                            $key = $gambar->option_gambar ?? '';
                            if (str_starts_with($key, 'carousel_')) {
                            $judul = 'Halaman Dashboard & Mitra';
                            } elseif ($key === 'kontak_2') {
                            $judul = 'Halaman Kontak';
                            } elseif ($key === 'tentang_1') {
                            $judul = 'Halaman Tentang';
                            } else {
                            $judul = $key ?: '-';
                            }
                        @endphp

                        {{ $judul }}
                    </td>
                    <td>
                        @if($gambar->path_gambar)
                            @php
                                // default: DB nyimpen nama file aja (contoh: carousel_1.jpg)
                                $imgUrl = asset('img/manajemen-gambar/' . ltrim($gambar->path_gambar, '/'));

                                // kalau DB ternyata nyimpen path lengkap "img/manajemen-gambar/xxx.jpg" atau "img/xxx.jpg"
                                if (str_starts_with($gambar->path_gambar, 'img/')) {
                                    $imgUrl = asset(ltrim($gambar->path_gambar, '/'));
                                }
                            @endphp
                            <img
                                src="{{ $imgUrl }}"
                                alt="Gambar {{ $gambar->option_gambar }}"
                                width="70"
                                height="70"
                                style="cursor: pointer; object-fit: cover; border-radius: 4px;"
                                onclick="openPreview('{{ $imgUrl }}')"
                            />
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
                        {{ $gambar->updated_at ? $gambar->updated_at->format('H:i - d M Y') : '-' }}
                    </td>
                    <td>
                        <div class="action-buttons">
                            <button type="button" class="btn-action btn-action-edit" title="Edit" onclick="editGambar({{ $gambar->id }})">
                                <i class="fas fa-pencil-alt"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data gambar.</td>
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
        <h5 class="modal-title" id="gambarModalLabel">Edit Gambar</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="closeGambarModal()"></button>
      </div>

      <form id="gambarForm" enctype="multipart/form-data">
        <div class="modal-body">

          <input type="hidden" id="gambar_id" name="id">

          {{-- key slot asli untuk dikirim ke backend --}}
          <input type="hidden" id="option_gambar" name="option_gambar">

          <div class="mb-3">
            <label class="form-label">Judul Halaman</label>
            <input type="text" class="form-control" id="option_gambar_label" disabled>
            <small class="text-muted">Slot ini tidak bisa diubah.</small>
          </div>

          <div class="mb-3">
            <label for="gambar" class="form-label">Upload Gambar</label>
            <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*" onchange="previewImage(this)">
            <div class="invalid-feedback"></div>
            <img id="preview" class="preview-image" style="display:none;" alt="Preview">
          </div>

          <div class="mb-3">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="is_show" name="is_show" value="1" checked>
              <label class="form-check-label" for="is_show">Tampilkan (Publish)</label>
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

{{-- Modal Preview Gambar --}}
<div class="modal fade" id="previewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Preview Gambar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="previewFull" src="" alt="Preview" style="max-width:100%; max-height:70vh; object-fit:contain;">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(document).ready(function() {

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

// ====== helper buat judul halaman di modal (disabled) ======
function getJudulHalaman(key) {
    if (!key) return '-';
    if (key.startsWith('carousel_')) return 'Halaman Dashboard & Mitra';
    if (key === 'kontak_2') return 'Halaman Kontak';
    if (key === 'tentang_1') return 'Halaman Tentang';
    return key;
}

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

// Open preview modal
function openPreview(src) {
    $('#previewFull').attr('src', src);

    $('.modal-backdrop').remove();
    $('body').removeClass('modal-open');
    $('body').css('overflow', '');
    $('body').css('padding-right', '');

    const modalElement = document.getElementById('previewModal');
    const modal = new bootstrap.Modal(modalElement, {
        backdrop: true,
        keyboard: true
    });

    modal.show();
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

// Edit gambar
function editGambar(id) {
    $.ajax({
        url: '{{ route("manajemen-gambar.show", ":id") }}'.replace(':id', id),
        method: 'GET',
        success: function(response) {
            if (response.success) {
                const key = response.data.option_gambar || '';

                $('#gambar_id').val(response.data.id);

                // hidden buat dikirim ke backend
                $('#option_gambar').val(key);

                // disabled buat tampilan judul halaman
                $('#option_gambar_label').val(getJudulHalaman(key));

                $('#is_show').prop('checked', response.data.is_show == 1);

                // reset preview + file input
                $('#preview').hide();
                $('#gambar').val('');

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

// Save gambar (update)
function saveGambar() {
    const formData = new FormData();
    formData.append('option_gambar', $('#option_gambar').val());
    formData.append('is_show', $('#is_show').is(':checked') ? 1 : 0);

    if ($('#gambar')[0].files.length > 0) {
        formData.append('gambar', $('#gambar')[0].files[0]);
    }

    const id = $('#gambar_id').val();
    if (!id) {
        return;
    }

    const url = '{{ route("manajemen-gambar.update", ":id") }}'.replace(':id', id);
    formData.append('_method', 'PUT');

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
