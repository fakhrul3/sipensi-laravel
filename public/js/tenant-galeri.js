// Galeri Tenant - Vanilla JS (tanpa jQuery)
// Meng-handle grid produk + modal + Bootstrap Carousel

(function () {
  // Data dikirim dari Blade: window.TENANT_GALERI = @json($produkGaleri);
  if (!Array.isArray(window.TENANT_GALERI) || window.TENANT_GALERI.length === 0) {
    return;
  }

  var produkGaleri = window.TENANT_GALERI;

  // Elemen utama (gunakan ID yang spesifik untuk produk galeri)
  var modalEl = document.getElementById('produkGaleriModal');
  var modalTitleEl = document.getElementById('produkGaleriModalTitle');
  var carouselInnerEl = document.getElementById('produkGaleriCarouselInner');
  var carouselEl = document.getElementById('produkGaleriCarousel');

  if (!modalEl || !carouselInnerEl || !carouselEl) return;

  var selectedProdukIndex = 0;

  // Klik card produk -> simpan index produk yang diklik
  // (tetap keep ini, tapi nanti saat show modal kita pakai e.relatedTarget biar ga ketuker)
  document.addEventListener('click', function (e) {
    var trigger = e.target.closest('[data-bs-target="#produkGaleriModal"][data-produk-index]');
    if (!trigger) return;

    // optional: cegah lompat ke atas karena href="#"
    if (trigger.tagName === 'A') e.preventDefault();

    var idx = trigger.getAttribute('data-produk-index');
    selectedProdukIndex = parseInt(idx || '0', 10) || 0;
  });

  // Helper: buat satu slide carousel
  function makeSlide(src, isActive, altText) {
    var item = document.createElement('div');
    item.className = 'carousel-item' + (isActive ? ' active' : '');

    var wrapper = document.createElement('div');
    wrapper.className = 'd-flex justify-content-center align-items-center';
    wrapper.style.minHeight = '70vh';

    var img = document.createElement('img');
    img.src = src;
    img.className = 'img-fluid';
    img.alt = altText || 'Foto';
    img.style.maxHeight = '70vh';

    wrapper.appendChild(img);
    item.appendChild(wrapper);

    return item;
  }

  // Jika bootstrap tidak ada, jangan lanjut (hindari error console)
  if (typeof window.bootstrap === 'undefined') {
    return;
  }

  // Build isi carousel ketika modal akan ditampilkan
  // NOTE: pakai e.relatedTarget biar index sesuai element yg bener-bener memicu modal
  modalEl.addEventListener('show.bs.modal', function (e) {
    // ✅ ambil index dari trigger modal (paling akurat)
    var triggerEl = e && e.relatedTarget ? e.relatedTarget : null;
    if (triggerEl) {
      var idxAttr = triggerEl.getAttribute('data-produk-index');
      selectedProdukIndex = parseInt(idxAttr || '0', 10) || 0;
    }

    // ✅ dispose instance carousel sebelumnya SEBELUM ganti isi
    var oldInstance = window.bootstrap.Carousel.getInstance(carouselEl);
    if (oldInstance) {
      oldInstance.dispose();
    }

    var prod = produkGaleri[selectedProdukIndex];

    // reset isi carousel
    carouselInnerEl.innerHTML = '';

    if (!prod || !Array.isArray(prod.fotos) || prod.fotos.length === 0) {
      if (modalTitleEl) {
        modalTitleEl.textContent = 'Galeri Produk';
      }
      var emptyDiv = document.createElement('div');
      emptyDiv.className = 'text-center p-4';
      emptyDiv.style.color = '#94a3b8';
      emptyDiv.style.fontWeight = '600';
      emptyDiv.textContent = 'Belum ada foto.';
      carouselInnerEl.appendChild(emptyDiv);
      return;
    }

    // judul modal = nama produk
    if (modalTitleEl) {
      modalTitleEl.textContent = prod.nama || 'Galeri Produk';
    }

    // isi carousel dengan foto-foto produk
    prod.fotos.forEach(function (path, idx) {
      // path sudah dinormalisasi (tanpa "public/"), relative ke /storage
      var cleaned = String(path).replace(/^\/+/, '');
      var src = '/storage/' + cleaned;
      var slide = makeSlide(src, idx === 0, 'Foto ' + (idx + 1));
      carouselInnerEl.appendChild(slide);
    });
  });

  // Setelah modal benar-benar muncul -> inisialisasi Bootstrap Carousel (fresh)
  modalEl.addEventListener('shown.bs.modal', function () {
    // Kalau isi kosong (misal "Belum ada foto."), ga usah init carousel
    var hasSlide = carouselInnerEl.querySelector('.carousel-item');
    if (!hasSlide) return;

    var carousel = window.bootstrap.Carousel.getOrCreateInstance(carouselEl, {
      interval: false,
      ride: false,
      pause: false,
      wrap: true
    });

    // selalu mulai dari slide pertama
    carousel.to(0);
  });

  // Saat modal ditutup -> bersihkan isi carousel supaya tidak numpuk
  modalEl.addEventListener('hidden.bs.modal', function () {
    carouselInnerEl.innerHTML = '';

    var instance = window.bootstrap.Carousel.getInstance(carouselEl);
    if (instance) {
      instance.dispose();
    }
  });
})();
