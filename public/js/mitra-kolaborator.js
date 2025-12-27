document.addEventListener('DOMContentLoaded', () => {

  /* ===============================
     SIMPLE IN-VIEW ANIMATION
  ================================ */
  const io = new IntersectionObserver((entries) => {
    entries.forEach(e => {
      if (e.isIntersecting) e.target.classList.add('in-view');
    });
  }, { threshold: 0.12 });

  document.querySelectorAll('.mitra-animate').forEach(el => io.observe(el));

  /* ===============================
     BACKGROUND SLIDESHOW (fade 8s)
     (tidak ikut re-render saat ganti tab)
  ================================ */
  document.querySelectorAll('.mitra-bg').forEach(bg => {
    const slides = JSON.parse(bg.dataset.slides || '[]');
    const layers = bg.querySelectorAll('.mitra-bg-layer');
    if (!slides.length || layers.length < 2) return;

    let idx = 0;
    const a = layers[0];
    const b = layers[1];

    a.style.backgroundImage = `url(${slides[0]})`;
    a.classList.add('active');
    b.classList.remove('active');

    if (bg._mitraInterval) clearInterval(bg._mitraInterval);

    bg._mitraInterval = setInterval(() => {
      const next = (idx + 1) % slides.length;

      const activeLayer = (idx % 2 === 0) ? a : b;
      const idleLayer   = (idx % 2 === 0) ? b : a;

      idleLayer.style.backgroundImage = `url(${slides[next]})`;
      idleLayer.classList.add('active');
      activeLayer.classList.remove('active');

      idx = next;
    }, 8000);
  });

  /* ===============================
     PAGING LOGO (3x3 fixed = 9)
     - arrows only if > 9
     - infinite looping
     - smooth transition on page change
  ================================ */
  const PER_PAGE = 9;

  function applyPage(items, page) {
    const start = (page - 1) * PER_PAGE;
    const end = start + PER_PAGE;

    items.forEach((el, i) => {
      el.classList.toggle('is-hidden', !(i >= start && i < end));
    });
  }

  function animateGrid(gridEl, direction, done) {
    if (!gridEl) return done();

    const shift = direction === 'next' ? '-18px' : '18px';
    gridEl.style.setProperty('--shift', shift);
    gridEl.classList.add('is-fading');

    setTimeout(() => {
      done();
      gridEl.style.setProperty('--shift', '0px');
      gridEl.classList.remove('is-fading');
    }, 220);
  }

  function setupWrap(wrap) {
    const grid = wrap.querySelector('[data-mitra-grid]');
    const items = Array.from(wrap.querySelectorAll('[data-mitra-item]'));
    const prevBtn = wrap.querySelector('[data-mitra-prev]');
    const nextBtn = wrap.querySelector('[data-mitra-next]');
    const indicator = wrap.querySelector('[data-mitra-indicator]');

    if (!grid || !items.length || !prevBtn || !nextBtn || !indicator) return;

    const totalPages = Math.max(1, Math.ceil(items.length / PER_PAGE));

    let page = parseInt(wrap.dataset.page || '1', 10);
    if (Number.isNaN(page)) page = 1;
    page = Math.min(Math.max(page, 1), totalPages);
    wrap.dataset.page = String(page);

    applyPage(items, page);

    const showNav = totalPages > 1;
    prevBtn.style.display = showNav ? 'block' : 'none';
    nextBtn.style.display = showNav ? 'block' : 'none';
    indicator.style.display = showNav ? 'block' : 'none';
    indicator.textContent = showNav ? `Halaman ${page} dari ${totalPages}` : '';

    prevBtn.onclick = null;
    nextBtn.onclick = null;

    prevBtn.onclick = () => {
      if (!showNav) return;
      animateGrid(grid, 'prev', () => {
        page = (page === 1) ? totalPages : (page - 1);
        wrap.dataset.page = String(page);
        applyPage(items, page);
        indicator.textContent = `Halaman ${page} dari ${totalPages}`;
      });
    };

    nextBtn.onclick = () => {
      if (!showNav) return;
      animateGrid(grid, 'next', () => {
        page = (page === totalPages) ? 1 : (page + 1);
        wrap.dataset.page = String(page);
        applyPage(items, page);
        indicator.textContent = `Halaman ${page} dari ${totalPages}`;
      });
    };
  }

  function setupActiveTab() {
    document.querySelectorAll('.tab-pane.active [data-mitra-wrap]').forEach(setupWrap);
  }

  // initial setup
  setupActiveTab();

  // when change tab (Bootstrap) - hanya setup paging + animasi konten tab
  document.addEventListener('shown.bs.tab', (ev) => {
    const paneId = ev.target?.getAttribute('data-bs-target');
    if (paneId) {
      const pane = document.querySelector(paneId);
      if (pane) {
        pane.classList.add('tab-soft-enter');
        setTimeout(() => pane.classList.remove('tab-soft-enter'), 260);
      }
    }
    setupActiveTab();
  });

});
