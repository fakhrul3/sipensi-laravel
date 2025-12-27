/* =====================================================
   MITRA KOLABORATOR — JS (STABLE)
   - Background slideshow cross-fade (2 layers)
   - Paging logo grid 3x3
   - Smooth arrow click (no jleg)
   - body.page-ready for entrance animation
===================================================== */
(function () {
  "use strict";

  const PER_PAGE = 9;
  const BG_INTERVAL = 5500; // ms

  function raf2(fn){
    requestAnimationFrame(() => requestAnimationFrame(fn));
  }

  // ---------- Page ready (entrance transition)
  document.addEventListener("DOMContentLoaded", () => {
    raf2(() => document.body.classList.add("page-ready"));
  });

  // ---------- Background slideshow
  function initBackgroundSlideshow(){
    const bg = document.querySelector(".mitra-bg");
    if(!bg) return;

    let slides = [];
    try{
      slides = JSON.parse(bg.getAttribute("data-slides") || "[]");
    }catch(e){
      slides = [];
    }
    if(!Array.isArray(slides) || slides.length === 0) return;

    const layerA = bg.querySelector(".mitra-bg-layer.layer-a");
    const layerB = bg.querySelector(".mitra-bg-layer.layer-b");
    if(!layerA || !layerB) return;

    // preload ringan
    slides.slice(0, 4).forEach(src => {
      const img = new Image();
      img.src = src;
    });

    let idx = 0;
    let useA = true;

    function setLayer(layer, src, active){
      layer.style.backgroundImage = `url('${src}')`;
      layer.classList.toggle("active", !!active);
    }

    // init
    setLayer(layerA, slides[0], true);
    setLayer(layerB, slides[slides.length > 1 ? 1 : 0], false);

    setInterval(() => {
      idx = (idx + 1) % slides.length;

      // swap layers
      useA = !useA;
      const front = useA ? layerA : layerB;
      const back  = useA ? layerB : layerA;

      // set next on back then activate it
      setLayer(back, slides[idx], true);
      front.classList.remove("active");
    }, BG_INTERVAL);
  }

  // ---------- Grid pager (per tab-pane)
  function initPane(pane){
    const grid = pane.querySelector("[data-mitra-grid]");
    if(!grid) return;

    const items = Array.from(grid.querySelectorAll("[data-mitra-item]"));
    const prevBtn = pane.querySelector("[data-mitra-prev]");
    const nextBtn = pane.querySelector("[data-mitra-next]");
    const indicator = pane.querySelector("[data-mitra-indicator]");

    if(items.length === 0) return;

    let page = 1;
    const pages = Math.max(1, Math.ceil(items.length / PER_PAGE));

    function applyVisibility(){
      const start = (page - 1) * PER_PAGE;
      const end = start + PER_PAGE;

      items.forEach((el, i) => {
        el.classList.toggle("is-hidden", !(i >= start && i < end));
      });

      if(indicator){
        indicator.style.display = pages > 1 ? "block" : "none";
        indicator.textContent = `Halaman ${page} dari ${pages}`;
      }

      // show/hide arrows (pakai class supaya konsisten)
      if(prevBtn) prevBtn.classList.toggle("is-hidden", pages <= 1);
      if(nextBtn) nextBtn.classList.toggle("is-hidden", pages <= 1);
    }

    function go(toPage, dir){
      if(pages <= 1) return;

      const target = Math.min(pages, Math.max(1, toPage));
      if(target === page) return;

      // anim keluar
      grid.style.setProperty("--shift", dir === "left" ? "-28px" : "28px");
      grid.classList.add("is-fading");

      // prevent double click while animating
      if(prevBtn) prevBtn.disabled = true;
      if(nextBtn) nextBtn.disabled = true;

      const onEnd = (e) => {
        if(e.propertyName !== "opacity") return;
        grid.removeEventListener("transitionend", onEnd);

        page = target;
        applyVisibility();

        // anim masuk (reset)
        grid.classList.remove("is-fading");
        grid.style.setProperty("--shift", "0px");

        // re-enable after 1 frame
        raf2(() => {
          if(prevBtn) prevBtn.disabled = false;
          if(nextBtn) nextBtn.disabled = false;
        });
      };

      grid.addEventListener("transitionend", onEnd);
    }

    // init first render
    applyVisibility();

    // hook arrows
    if(prevBtn){
      prevBtn.addEventListener("click", () => go(page - 1, "left"));
    }
    if(nextBtn){
      nextBtn.addEventListener("click", () => go(page + 1, "right"));
    }

    // expose reset for tab change
    pane.__mitraReset = () => {
      page = 1;
      applyVisibility();
      if(prevBtn) prevBtn.disabled = false;
      if(nextBtn) nextBtn.disabled = false;
    };
  }

  function initAllPanes(){
    document.querySelectorAll(".tab-pane").forEach(initPane);
  }

  // ---------- Bootstrap tab: reset pager on tab shown
  function initTabEvents(){
    document.querySelectorAll('[data-bs-toggle="pill"], [data-bs-toggle="tab"]').forEach(btn => {
      btn.addEventListener("shown.bs.tab", (ev) => {
        const targetSel = ev.target.getAttribute("data-bs-target") || ev.target.getAttribute("href");
        if(!targetSel) return;
        const pane = document.querySelector(targetSel);
        if(pane && typeof pane.__mitraReset === "function") pane.__mitraReset();
      });
    });
  }

  // boot
  document.addEventListener("DOMContentLoaded", () => {
    initBackgroundSlideshow();
    initAllPanes();
    initTabEvents();
  });

})();
