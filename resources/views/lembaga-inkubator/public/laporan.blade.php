

<div class="modal fade" id="modalLaporan" tabindex="-1" aria-labelledby="modalLaporanLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-fullscreen">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="modalLaporanLabel">Laporan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        @php
          /**
           * $laporanFiles dikirim dari controller (hasil normalisasi).
           * Contoh hasil:
           * ["file_laporan/laporan_xxx.pdf"]
           */
          $laporanFiles = $laporanFiles ?? [];

          if ($laporanFiles instanceof \Illuminate\Support\Collection) {
            $laporanFiles = $laporanFiles->all();
          }

          if (!is_array($laporanFiles)) {
            $laporanFiles = [];
          }

          $laporanFiles = array_values(array_filter($laporanFiles, function ($v) {
            return is_string($v) && trim($v) !== '';
          }));
        @endphp

        @if(count($laporanFiles) === 0)
          <div class="d-flex align-items-center justify-content-center h-100">
            <div class="text-center">
              <b>Data tidak ditemukan</b><br>
              File laporan belum diunggah
            </div>
          </div>
        @else
          <div class="row h-100">

            {{-- LIST DOKUMEN --}}
            <div class="col-3 border-end">
              <div class="list-group">
                @foreach($laporanFiles as $index => $file)
                  <a href="#"
                     class="list-group-item view-laporan doc-item"
                     data-id="{{ $index }}">
                    <i class="fa-solid fa-file-pdf"></i>
                    <span>Dokumen {{ $index + 1 }}</span>
                  </a>
                @endforeach
              </div>
            </div>

            {{-- PREVIEW PDF --}}
            <div class="col-9">
              @foreach($laporanFiles as $index => $file)
                @php
                  // jaga-jaga kalau masih ada "public/"
                  $cleanPath = preg_replace('#^public[\\\\/]#', '', $file);
                @endphp

                <div class="preview-laporan"
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

  const modal = document.getElementById('modalLaporan');

  modal.addEventListener('shown.bs.modal', function () {
    const firstBtn = modal.querySelector('.view-laporan');
    const firstPreview = modal.querySelector('.preview-laporan');

    if (firstBtn && firstPreview) {
      firstBtn.classList.add('active');
      firstPreview.style.display = 'block';
    }
  });

  modal.addEventListener('hidden.bs.modal', function () {
    modal.querySelectorAll('.view-laporan').forEach(el => el.classList.remove('active'));
    modal.querySelectorAll('.preview-laporan').forEach(el => el.style.display = 'none');
  });

  modal.addEventListener('click', function (e) {
    const btn = e.target.closest('.view-laporan');
    if (!btn) return;

    e.preventDefault();

    modal.querySelectorAll('.view-laporan').forEach(el => el.classList.remove('active'));
    modal.querySelectorAll('.preview-laporan').forEach(el => el.style.display = 'none');

    const id = btn.getAttribute('data-id');
    btn.classList.add('active');

    const preview = modal.querySelector('.preview-laporan[data-id="'+id+'"]');
    if (preview) preview.style.display = 'block';
  });

});
</script>
@endpush

@push('styles')
<style>
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
