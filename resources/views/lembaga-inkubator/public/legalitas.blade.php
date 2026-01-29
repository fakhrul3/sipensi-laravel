<div class="modal fade" id="modalLegalitas" tabindex="-1" aria-labelledby="modalLegalitasLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-fullscreen">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="modalLegalitasLabel">Legalitas Lembaga</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        @php
          // Ambil data dari object inkubator
          $legalitasFiles = $inkubator->path_legalitas ?? [];

          // Jika data di DB berupa JSON string (seperti ["public/file.pdf"]), kita decode jadi array
          if (is_string($legalitasFiles)) {
            $decoded = json_decode($legalitasFiles, true);
            $legalitasFiles = is_array($decoded) ? $decoded : [];
          }

          // Pastikan variabel selalu array agar tidak error saat count() atau foreach
          if (!is_array($legalitasFiles)) {
            $legalitasFiles = [];
          }
        @endphp

        @if(count($legalitasFiles) === 0)
          <div class="d-flex align-items-center justify-content-center h-100">
            <div class="text-center">
              <i class="fa-solid fa-file-circle-xmark mb-3" style="font-size: 3rem; color: #ccc;"></i><br>
              <b>Data tidak ditemukan</b><br>
              <span class="text-muted">File legalitas belum diunggah oleh lembaga ini.</span>
            </div>
          </div>
        @else
          <div class="row h-100">

            {{-- LIST DOKUMEN (Kiri) --}}
            <div class="col-3 border-end bg-light p-3">
              <div class="list-group">
                @foreach($legalitasFiles as $index => $file)
                  <a href="#"
                     class="list-group-item list-group-item-action view-legalitas doc-item"
                     data-id="{{ $index }}">
                    <i class="fa-solid fa-file-pdf"></i>
                    <span>Dokumen {{ $index + 1 }}</span>
                  </a>
                @endforeach
              </div>
              <div class="mt-3 small text-muted text-center">
                <i class="fa-solid fa-circle-info"></i> Pilih dokumen untuk melihat pratinjau
              </div>
            </div>

            {{-- PREVIEW PDF (Kanan) --}}
            <div class="col-9 p-0">
              @foreach($legalitasFiles as $index => $file)
                @php
                    // Membersihkan 'public/' karena asset('storage/...') lari ke storage/app/public
                    $cleanPath = str_replace('public/', '', $file);
                @endphp
                <div class="preview-legalitas"
                     data-id="{{ $index }}"
                     style="display:none; height:100%;">
                  <iframe
                    src="{{ asset('storage/' . $cleanPath) }}"
                    style="width:100%; height:calc(100vh - 70px); border:0;">
                  </iframe>
                </div>
              @endforeach
            </div>

          </div>
        @endif
      </div>

    </div>
  </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const modal = document.getElementById('modalLegalitas');

  // Logic saat modal dibuka: tampilkan dokumen pertama otomatis
  modal.addEventListener('shown.bs.modal', function () {
    const firstBtn = document.querySelector('.view-legalitas');
    const firstPreview = document.querySelector('.preview-legalitas');

    if (firstBtn && firstPreview) {
      // Reset semua dulu biar gak double
      document.querySelectorAll('.view-legalitas').forEach(el => el.classList.remove('active'));
      document.querySelectorAll('.preview-legalitas').forEach(el => el.style.display = 'none');
      
      // Aktifkan yang pertama
      firstBtn.classList.add('active');
      firstPreview.style.display = 'block';
    }
  });

  // Event Click List Dokumen
  document.addEventListener('click', function (e) {
    const btn = e.target.closest('.view-legalitas');
    if (!btn) return;

    e.preventDefault();

    // Sembunyikan semua preview & hilangkan class active
    document.querySelectorAll('.view-legalitas').forEach(el => el.classList.remove('active'));
    document.querySelectorAll('.preview-legalitas').forEach(el => el.style.display = 'none');

    // Tampilkan yang dipilih
    const id = btn.getAttribute('data-id');
    btn.classList.add('active');
    const preview = document.querySelector('.preview-legalitas[data-id="'+id+'"]');
    if (preview) preview.style.display = 'block';
  });
});
</script>
@endpush

@push('styles')
<style>
/* Style tombol dokumen agar lebih 'Inkubator' banget */
.doc-item {
    background: #fff;
    color: #444;
    border: 1px solid #dee2e6 !important;
    border-radius: 8px !important;
    margin-bottom: 10px;
    padding: 15px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 12px;
    transition: all .2s ease;
    cursor: pointer;
}

.doc-item i {
    color: #e74c3c; /* Warna icon PDF merah */
    font-size: 20px;
}

.doc-item:hover {
    background: #f8f9fa;
    border-color: #2f8f9d !important;
    transform: translateX(5px);
}

.doc-item.active {
    background: #22466C !important;
    color: #fff !important;
    border-color: #22466C !important;
}

.doc-item.active i {
    color: #fff;
}

.modal-fullscreen .modal-body {
    padding: 0;
    overflow: hidden;
}
</style>
@endpush