document.addEventListener("DOMContentLoaded", () => {
  const stage = document.querySelector(".gallery-stage");
  const grid = document.getElementById("sipensiGalleryGrid");
  const filterBtns = document.querySelectorAll(".gallery-filter-btn");
  const btnPrev = document.getElementById("galleryPrevPage");
  const btnNext = document.getElementById("galleryNextPage");

  // Lightbox
  const lightbox = document.getElementById("sipensiLightbox");
  const lightboxImg = document.getElementById("sipensiLightboxImg");
  const closeBtn = document.querySelector(".sipensi-lightbox__close");
  const backdrop = document.querySelector(".sipensi-lightbox__backdrop");

  const PAGE_SIZE = 9;
  if (!grid) return;

  const allItems = Array.from(grid.querySelectorAll(".gallery-item"));

  let currentFilter = "all";
  let currentPage = 1;

  const getFilteredItems = () =>
    allItems.filter((it) => {
      const cat = it.dataset.category || "kegiatan";
      return currentFilter === "all" || cat === currentFilter;
    });

  const pageCount = (filtered) => Math.max(1, Math.ceil(filtered.length / PAGE_SIZE));

  const setArrowTop = () => {
    if (!stage) return;
    stage.style.setProperty("--arrowTop", `${Math.round(stage.clientHeight / 2)}px`);
  };

  const switchWithAnim = (fn) => {
    grid.classList.add("is-switching");
    setTimeout(() => {
      fn();
      requestAnimationFrame(() => grid.classList.remove("is-switching"));
    }, 180);
  };

  const render = () => {
    const filtered = getFilteredItems();
    const totalPages = pageCount(filtered);

    currentPage = Math.min(Math.max(currentPage, 1), totalPages);

    allItems.forEach((it) => it.classList.add("is-hidden"));

    const start = (currentPage - 1) * PAGE_SIZE;
    const end = start + PAGE_SIZE;
    filtered.slice(start, end).forEach((it) => it.classList.remove("is-hidden"));

    if (btnPrev) btnPrev.disabled = currentPage <= 1;
    if (btnNext) btnNext.disabled = currentPage >= totalPages;

    setArrowTop();
  };

  // Filter
  filterBtns.forEach((btn) => {
    btn.addEventListener("click", () => {
      filterBtns.forEach((b) => b.classList.remove("active"));
      btn.classList.add("active");
      currentFilter = btn.dataset.filter || "all";
      currentPage = 1;
      switchWithAnim(render);
    });
  });

  // Paging
  btnPrev?.addEventListener("click", () => {
    if (btnPrev.disabled) return;
    currentPage -= 1;
    switchWithAnim(render);
  });

  btnNext?.addEventListener("click", () => {
    if (btnNext.disabled) return;
    currentPage += 1;
    switchWithAnim(render);
  });

  // Lightbox
  const openLightbox = (src, altText = "") => {
    if (!lightbox || !lightboxImg || !src) return;
    lightboxImg.src = src;
    lightboxImg.alt = altText || "Preview gambar";
    lightbox.classList.add("active");
    lightbox.setAttribute("aria-hidden", "false");
    document.documentElement.style.overflow = "hidden";
  };

  const closeLightbox = () => {
    if (!lightbox || !lightboxImg) return;
    lightbox.classList.remove("active");
    lightbox.setAttribute("aria-hidden", "true");
    lightboxImg.src = "";
    document.documentElement.style.overflow = "";
  };

  // Klik card -> lightbox
  grid.addEventListener("click", (e) => {
    const card = e.target.closest(".gallery-card");
    if (!card) return;
    const fullSrc = card.dataset.full || "";
    const img = card.querySelector("img");
    const altText = img ? img.getAttribute("alt") : "";
    openLightbox(fullSrc, altText);
  });

  // Close actions
  closeBtn?.addEventListener("click", closeLightbox);
  backdrop?.addEventListener("click", closeLightbox);
  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape" && lightbox?.classList.contains("active")) closeLightbox();
  });

  window.addEventListener("resize", setArrowTop);

  render();
});
