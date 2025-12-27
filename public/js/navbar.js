document.addEventListener("DOMContentLoaded", () => {
  const nav = document.getElementById("mainNavbar");
  if (!nav) return;

  const setState = () => {
    if (window.scrollY > 10) {
      nav.classList.add("navbar-scrolled");
      nav.classList.remove("navbar-transparent");
    } else {
      nav.classList.remove("navbar-scrolled");
      nav.classList.add("navbar-transparent");
    }
  };

  setState();
  window.addEventListener("scroll", setState, { passive: true });
});
