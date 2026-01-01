// public/js/navbar-force.js
(function () {
  const initNavbarForce = () => {
    const navbar = document.getElementById("mainNavbar");
    if (!navbar) return;

    const applyState = () => {
      if (window.scrollY > 10) {
        navbar.classList.add("navbar-scrolled");
        navbar.classList.remove("navbar-transparent");
      } else {
        navbar.classList.add("navbar-transparent");
        navbar.classList.remove("navbar-scrolled");
      }
    };

    // FORCE state saat load
    applyState();

    // FORCE state saat scroll
    window.addEventListener("scroll", applyState, { passive: true });

    // FORCE state saat resize (kadang kepanggil JS lain)
    window.addEventListener("resize", applyState);
  };

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initNavbarForce);
  } else {
    initNavbarForce();
  }
})();
