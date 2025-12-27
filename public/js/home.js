document.addEventListener('DOMContentLoaded', () => {
  // tanda bahwa JS aktif (dipakai CSS animasi)
  document.documentElement.classList.add('js');

  /* =========================
     NAVBAR SCROLL
  ========================== */
  (function () {
  function applyNavbarScroll() {
    const nav = document.getElementById("mainNavbar");
    if (!nav) return;

    const onScroll = () => {
      if (window.scrollY > 10) {
        nav.classList.add("navbar-scrolled");
        nav.classList.remove("navbar-transparent");
      } else {
        nav.classList.remove("navbar-scrolled");
        nav.classList.add("navbar-transparent");
      }
    };

    // init + listen
    onScroll();
    window.addEventListener("scroll", onScroll, { passive: true });
  }

  // jalan setelah DOM siap
  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", applyNavbarScroll);
  } else {
    applyNavbarScroll();
  }
})();


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

document.addEventListener("DOMContentLoaded", () => {
  const counters = document.querySelectorAll(".stat-number");
  const duration = 2000; // 2 detik

  counters.forEach(counter => {
    const target = +counter.dataset.target;
    let start = 0;
    const startTime = performance.now();

    function update(now) {
      const progress = Math.min((now - startTime) / duration, 1);
      const value = Math.floor(progress * target);

      counter.textContent = value.toLocaleString("id-ID");

      if (progress < 1) {
        requestAnimationFrame(update);
      } else {
        counter.textContent = target.toLocaleString("id-ID");
      }
    }

    requestAnimationFrame(update);
  });
});
