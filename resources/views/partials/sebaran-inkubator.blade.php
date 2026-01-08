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

<script>
  window.SIPENSI = {
    // Route ini harus sesuai dengan route daftar lembaga kamu
    lembagaUrl: "{{ url('/inkubator') }}" 
  };
  
  // Mengambil data $sebaranInkubator dari HomeController
  window.SEBARAN_INKUBATOR_DATA = @json($sebaranInkubator);
</script>