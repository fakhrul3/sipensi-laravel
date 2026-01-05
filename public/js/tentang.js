document.addEventListener("DOMContentLoaded", () => {
  const hero = document.querySelector(".tentang-hero");
  if (!hero) return;

  // Matikan parallax di mobile (biar aman & smooth)
  const isMobile = window.matchMedia("(max-width: 991px)").matches;
  if (isMobile) return;

  const speed = 0.35; // 0.25–0.45 ideal, makin kecil makin halus

  let ticking = false;

  const updateParallax = () => {
    const rect = hero.getBoundingClientRect();
    const scrollTop = window.scrollY || window.pageYOffset;

    // posisi hero relatif ke dokumen
    const heroTop = rect.top + scrollTop;

    // seberapa jauh scroll melewati hero
    const offset = scrollTop - heroTop;

    // hitung posisi Y background (basis 50%)
    const yPos = 50 + (offset * speed) / 10;

    hero.style.setProperty("--hero-y", `${yPos}%`);
    ticking = false;
  };

  // init
  updateParallax();

  window.addEventListener(
    "scroll",
    () => {
      if (!ticking) {
        window.requestAnimationFrame(updateParallax);
        ticking = true;
      }
    },
    { passive: true }
  );

  window.addEventListener("resize", updateParallax);
});
