<section class="sebaran-section" id="sebaran-inkubator" data-reveal-once>
  <div class="sebaran-shell">
    <h2 class="sebaran-title">Sebaran Inkubator</h2>
    <p class="sebaran-subtitle">
      Peta sebaran lembaga inkubator yang terdaftar pada SIPENSI.
    </p>

    <div class="sebaran-map-wrap">
      <div id="mapSebaran"></div>
    </div>
  </div>
</section>

{{-- Data sudah di-set di home.blade.php, tidak perlu duplikasi --}}
{{-- Hanya set jika belum ada --}}
<script>
  if (!window.SIPENSI) {
    window.SIPENSI = {
      lembagaUrl: "{{ url('/inkubator') }}" 
    };
  }
  if (!window.SEBARAN_INKUBATOR_DATA) {
    window.SEBARAN_INKUBATOR_DATA = @json($sebaranInkubator ?? []);
  }
</script>