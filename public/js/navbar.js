// public/js/navbar.js
document.addEventListener("DOMContentLoaded", () => {
  const navbar = document.getElementById("mainNavbar");
  if (!navbar) return;

  const setTransparent = () => {
    navbar.classList.add("navbar-transparent");
    navbar.classList.remove("navbar-scrolled");
  };

  const setScrolled = () => {
    navbar.classList.add("navbar-scrolled");
    navbar.classList.remove("navbar-transparent");
  };

  const updateNavbar = () => {
    // ✅ SEMUA PAGE: top transparan, scroll putih
    if (window.scrollY > 10) setScrolled();
    else setTransparent();
  };

  // INIT
  updateNavbar();

  // SCROLL
  window.addEventListener("scroll", updateNavbar, { passive: true });

  // MOBILE: saat menu dibuka, paksa putih biar kebaca
  const collapse = document.getElementById("mainNavbarNav");
  if (collapse) {
    collapse.addEventListener("show.bs.collapse", setScrolled);
    collapse.addEventListener("hidden.bs.collapse", updateNavbar);
  }
});
