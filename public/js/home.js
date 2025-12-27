document.addEventListener('DOMContentLoaded', () => {
  // tanda bahwa JS aktif (dipakai CSS animasi)
  document.documentElement.classList.add('js');

  /* =========================
     NAVBAR SCROLL
  ========================== */
  const navbar = document.getElementById('mainNavbar');

  const handleNavbarScroll = () => {
    if (!navbar) return;

    if (window.scrollY > 10) {
      navbar.classList.add('navbar-scrolled');
      navbar.classList.remove('navbar-transparent');
    } else {
      navbar.classList.add('navbar-transparent');
      navbar.classList.remove('navbar-scrolled');
    }
  };

  handleNavbarScroll();
  window.addEventListener('scroll', handleNavbarScroll, { passive: true });

  /* =========================
     COUNT UP NUMBER
  ========================== */
  const counters = document.querySelectorAll('.stat-number');
  const duration = 3000;

  const easeInOutCubic = (t) =>
    t < 0.5 ? 4 * t * t * t : 1 - Math.pow(-2 * t + 2, 3) / 2;

  counters.forEach((el) => {
    const target = Number(el.dataset.target);
    if (!target) return;

    let start = null;

    const animate = (time) => {
      if (!start) start = time;
      const progress = Math.min((time - start) / duration, 1);
      const eased = easeInOutCubic(progress);

      el.textContent = Math.round(target * eased).toLocaleString('id-ID');

      if (progress < 1) requestAnimationFrame(animate);
    };

    el.textContent = '0';
    requestAnimationFrame(animate);
  });

  /* =========================
     SCROLL REVEAL
  ========================== */
  const revealEls = document.querySelectorAll('.reveal');

  if (revealEls.length) {
    const observer = new IntersectionObserver((entries, obs) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          
          /* =========================
   SCROLL REVEAL (repeat)
========================= */
const revealEls = document.querySelectorAll('.reveal');

if (revealEls.length) {
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('is-visible');
      } else {
        entry.target.classList.remove('is-visible');
      }
    });
      }, {
        threshold: 0.15,
        rootMargin: '0px 0px -10% 0px'
      });

      revealEls.forEach(el => observer.observe(el));
    }

        }
      });
    }, {
      threshold: 0.15,
      rootMargin: '0px 0px -10% 0px'
    });

    revealEls.forEach(el => observer.observe(el));
  }
});
