<div class="modal fade" id="modalRencanaStrategis" tabindex="-1" aria-labelledby="modalRencanaStrategisLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-fullscreen">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="modalRencanaStrategisLabel">Rencana Strategis</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        @php
          /**
           * path_rencana_strategis WAJIB berisi:
           * [
           *   "files_rencana_strategis/file1.pdf",
           *   "files_rencana_strategis/file2.pdf"
           * ]
           */

          $rencanaFiles = $inkubator->path_rencana_strategis ?? [];

          // kalau dari DB bentuknya JSON string
          if (is_string($rencanaFiles)) {
            $decoded = json_decode($rencanaFiles, true);
            $rencanaFiles = is_array($decoded) ? $decoded : [];
          }

          // jaga-jaga kalau bukan array
          if (!is_array($rencanaFiles)) {
            $rencanaFiles = [];
          }

          // filter data kosong / null
          $rencanaFiles = array_values(array_filter($rencanaFiles, function ($v) {
            return is_string($v) && trim($v) !== '';
          }));
        @endphp

        @if(count($rencanaFiles) === 0)
          <div class="d-flex align-items-center justify-content-center h-100">
            <div class="text-center">
              <b>Data tidak ditemukan</b><br>
              File rencana strategis belum diunggah
            </div>
          </div>
        @else
          <div class="row h-100">

            {{-- LIST DOKUMEN --}}
            <div class="col-3 border-end">
              <div class="list-group">
                @foreach($rencanaFiles as $index => $file)
                  <a href="#"
                     class="list-group-item view-rencana doc-item"
                     data-id="{{ $index }}">
                    <i class="fa-solid fa-file-pdf"></i>
                    <span>Dokumen {{ $index + 1 }}</span>
                  </a>
                @endforeach
              </div>
            </div>

            {{-- PREVIEW PDF --}}
            <div class="col-9">
              @foreach($rencanaFiles as $index => $file)
                @php
                  // buang prefix "public/" atau "public\"
                  $cleanPath = preg_replace('#^public[\\\\/]#', '', $file);
                @endphp

                <div class="preview-rencana"
                     data-id="{{ $index }}"
                     style="display:none;height:100%;">
                  <iframe
                    src="{{ \Storage::url($cleanPath) }}"
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

  const modal = document.getElementById('modalRencanaStrategis');

  modal.addEventListener('shown.bs.modal', function () {
    const firstBtn = modal.querySelector('.view-rencana');
    const firstPreview = modal.querySelector('.preview-rencana');

    if (firstBtn && firstPreview) {
      firstBtn.classList.add('active');
      firstPreview.style.display = 'block';
    }
  });

  modal.addEventListener('hidden.bs.modal', function () {
    modal.querySelectorAll('.view-rencana').forEach(el =>
      el.classList.remove('active')
    );
    modal.querySelectorAll('.preview-rencana').forEach(el =>
      el.style.display = 'none'
    );
  });

  modal.addEventListener('click', function (e) {
    const btn = e.target.closest('.view-rencana');
    if (!btn) return;

    e.preventDefault();

    modal.querySelectorAll('.view-rencana').forEach(el =>
      el.classList.remove('active')
    );
    modal.querySelectorAll('.preview-rencana').forEach(el =>
      el.style.display = 'none'
    );

    const id = btn.getAttribute('data-id');
    btn.classList.add('active');

    const preview = modal.querySelector('.preview-rencana[data-id="'+id+'"]');
    if (preview) preview.style.display = 'block';
  });

});
</script>
@endpush

@push('styles')
<style>
/* ===== LIST DOKUMEN STYLE (MENYERUPAI LIVE) ===== */
.doc-item{
  background:#2f8f9d;
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
.doc-item:hover{
  background:#257a85;
  color:#fff;
}
.doc-item.active{
  background:#1f6972;
  box-shadow: inset 0 0 0 2px rgba(255,255,255,.25);
}
</style>
@endpush
