<div class="modal fade" id="modalLegalitas" tabindex="-1" aria-labelledby="modalLegalitasLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-fullscreen">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="modalLegalitasLabel">Legalitas</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        @php
          /**
           * path_legalitas WAJIB berisi:
           * [
           *   "file_legalitas/file1.pdf",
           *   "file_legalitas/file2.pdf"
           * ]
           */

          $legalitasFiles = $inkubator->path_legalitas ?? [];

          // kalau dari DB bentuknya JSON string
          if (is_string($legalitasFiles)) {
            $decoded = json_decode($legalitasFiles, true);
            $legalitasFiles = is_array($decoded) ? $decoded : [];
          }

          // jaga-jaga kalau bukan array
          if (!is_array($legalitasFiles)) {
            $legalitasFiles = [];
          }
        @endphp

        @if(count($legalitasFiles) === 0)
          <div class="d-flex align-items-center justify-content-center h-100">
            <div class="text-center">
              <b>Data tidak ditemukan</b><br>
              File legalitas belum diunggah
            </div>
          </div>
        @else
          <div class="row h-100">

            {{-- LIST DOKUMEN --}}
            <div class="col-3 border-end">
              <div class="list-group">
                @foreach($legalitasFiles as $index => $file)
                  <a href="#"
                     class="list-group-item view-legalitas doc-item"
                     data-id="{{ $index }}">
                    <i class="fa-solid fa-file-pdf"></i>
                    <span>Dokumen {{ $index + 1 }}</span>
                  </a>
                @endforeach
              </div>
            </div>

            {{-- PREVIEW PDF --}}
            <div class="col-9">
              @foreach($legalitasFiles as $index => $file)
                <div class="preview-legalitas"
                     data-id="{{ $index }}"
                     style="display:none;height:100%;">
                  <iframe
                    src="{{ \Storage::url($file) }}"
                    style="width:100%;height:calc(100vh - 120px);border:0;">
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

  modal.addEventListener('shown.bs.modal', function () {
    const firstBtn = document.querySelector('.view-legalitas');
    const firstPreview = document.querySelector('.preview-legalitas');

    if (firstBtn && firstPreview) {
      firstBtn.classList.add('active');
      firstPreview.style.display = 'block';
    }
  });

  document.addEventListener('click', function (e) {
    if (!e.target.closest('.view-legalitas')) return;

    e.preventDefault();

    document.querySelectorAll('.view-legalitas').forEach(el =>
      el.classList.remove('active')
    );
    document.querySelectorAll('.preview-legalitas').forEach(el =>
      el.style.display = 'none'
    );

    const btn = e.target.closest('.view-legalitas');
    const id  = btn.getAttribute('data-id');

    btn.classList.add('active');

    const preview = document.querySelector('.preview-legalitas[data-id="'+id+'"]');
    if (preview) preview.style.display = 'block';
  });

});
</script>
@endpush

@push('styles')
<style>
/* ===== LIST DOKUMEN STYLE (MENYERUPAI LIVE) ===== */
.doc-item{
  background:#2f8f9d; /* warna teal */
  color:#fff;
  border:0;
  border-radius:6px;
  margin-bottom:8px;
  padding:12px 14px;
  font-weight:600;
  display:flex;
  align-items:center;
  gap:10px;
  transition:all .2s ease;
}
.doc-item i{
  color:#fff;
  font-size:16px;
}
/* hover */
.doc-item:hover{
  background:#257a85;
  color:#fff;
}
/* aktif / dipilih */
.doc-item.active{
  background:#1f6972;
  box-shadow: inset 0 0 0 2px rgba(255,255,255,.25);
}
</style>
@endpush
