document.addEventListener("DOMContentLoaded", () => {
  const grid = document.getElementById("sipensiGalleryGrid");
  const items = Array.from(document.querySelectorAll(".gallery-item"));
  const buttons = Array.from(document.querySelectorAll(".gallery-filter-btn"));

  const prevBtn = document.getElementById("galleryPrevPage") || document.querySelector(".gallery-arrow-prev");
  const nextBtn = document.getElementById("galleryNextPage") || document.querySelector(".gallery-arrow-next");

  const PAGE_SIZE = 9;

  let currentFilter = "all";
  let currentPage = 1;
  let isAnimating = false;

  // ===== helper =====
  const getFilteredItems = () => {
    return items.filter((item) => {
      const cat = item.dataset.category;
      return currentFilter === "all" || cat === currentFilter;
    });
  };

  // bikin item siap animasi reveal
  const armRevealForVisible = () => {
    const visible = items.filter((it) => !it.classList.contains("is-hidden"));
    visible.forEach((it) => it.classList.add("reveal-ready"));
  };

  // arrow center (pakai bounding visible items)
  const updateArrowCenter = () => {
    if (!grid) return;

    const visible = items.filter((it) => !it.classList.contains("is-hidden"));
    if (visible.length === 0) return;

    const gridRect = grid.getBoundingClientRect();

    let top = Infinity;
    let bottom = -Infinity;

    visible.forEach((it) => {
      const r = it.getBoundingClientRect();
      top = Math.min(top, r.top);
      bottom = Math.max(bottom, r.bottom);
    });

    const centerY = (top + bottom) / 2;
    const arrowTopPx = centerY - gridRect.top;

    grid.style.setProperty("--arrowTop", `${arrowTopPx}px`);
  };

  // render tanpa animasi (initial)
  const renderInstant = () => {
    const filtered = getFilteredItems();
    const totalPages = Math.max(1, Math.ceil(filtered.length / PAGE_SIZE));

    if (currentPage > totalPages) currentPage = totalPages;
    if (currentPage < 1) currentPage = 1;

    items.forEach((it) => it.classList.add("is-hidden"));

    const start = (currentPage - 1) * PAGE_SIZE;
    const end = start + PAGE_SIZE;
    filtered.slice(start, end).forEach((it) => it.classList.remove("is-hidden"));

    // state disabled
    if (prevBtn) prevBtn.disabled = currentPage === 1;
    if (nextBtn) nextBtn.disabled = currentPage === totalPages;

    // set reveal & compute arrow
    armRevealForVisible();
    requestAnimationFrame(() => {
      updateArrowCenter();
      runRevealObserver(); // supaya yang tampil langsung bisa anim saat scroll
    });
  };

  // render dengan animasi page transition
  const renderAnimated = () => {
    if (!grid || isAnimating) return;
    isAnimating = true;

    // 1) fade-out grid
    grid.classList.add("is-switching");

    // 2) tunggu sedikit, baru swap item
    window.setTimeout(() => {
      const filtered = getFilteredItems();
      const totalPages = Math.max(1, Math.ceil(filtered.length / PAGE_SIZE));

      if (currentPage > totalPages) currentPage = totalPages;
      if (currentPage < 1) currentPage = 1;

      items.forEach((it) => it.classList.add("is-hidden"));

      const start = (currentPage - 1) * PAGE_SIZE;
      const end = start + PAGE_SIZE;
      filtered.slice(start, end).forEach((it) => it.classList.remove("is-hidden"));

      if (prevBtn) prevBtn.disabled = currentPage === 1;
      if (nextBtn) nextBtn.disabled = currentPage === totalPages;

      // 3) prepare reveal + update arrow
      armRevealForVisible();
      updateArrowCenter();

      // 4) fade-in balik
      grid.classList.remove("is-switching");

      // 5) re-observe yang tampil (biar transisi scroll jalan)
      runRevealObserver();

      window.setTimeout(() => {
        isAnimating = false;
      }, 260);
    }, 220);
  };

  // ===== Scroll Reveal (re-usable observer) =====
  let observer = null;

  const runRevealObserver = () => {
    if (observer) observer.disconnect();

    observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add("is-visible");
          }
        });
      },
      { threshold: 0.15 }
    );

    // observe hanya yang terlihat (biar gak berat)
    const visible = items.filter((it) => !it.classList.contains("is-hidden"));
    visible.forEach((it) => observer.observe(it));
  };

  // ===== events =====
  buttons.forEach((btn) => {
    btn.addEventListener("click", () => {
      if (isAnimating) return;

      buttons.forEach((b) => b.classList.remove("active"));
      btn.classList.add("active");

      currentFilter = btn.dataset.filter || "all";
      currentPage = 1;

      renderAnimated();
    });
  });

  prevBtn?.addEventListener("click", () => {
    if (isAnimating) return;
    currentPage--;
    renderAnimated();
  });

  nextBtn?.addEventListener("click", () => {
    if (isAnimating) return;
    currentPage++;
    renderAnimated();
  });

  window.addEventListener("resize", () => {
    requestAnimationFrame(updateArrowCenter);
  });

  // initial
  renderInstant();
});
