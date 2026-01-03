document.addEventListener("DOMContentLoaded", () => {
  const counters = document.querySelectorAll(".counter");
  if (!counters.length) return;

  const formatDot = (n) => n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");

  const animateCount = (el) => {
    const target = Number(el.dataset.target || 0);
    const duration = Number(el.dataset.duration || 1200); // ms
    const start = performance.now();

    const step = (now) => {
      const progress = Math.min((now - start) / duration, 1);
      const current = Math.floor(progress * target);

      el.textContent = (el.dataset.format === "dot") ? formatDot(current) : current;

      if (progress < 1) requestAnimationFrame(step);
    };

    requestAnimationFrame(step);
  };

  const resetCount = (el) => {
    el.textContent = "0";
    el.dataset.ran = "0";
  };

  const io = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        const el = entry.target;

        if (entry.isIntersecting) {
          // jalan tiap masuk viewport
          if (el.dataset.ran !== "1") {
            el.dataset.ran = "1";
            animateCount(el);
          }
        } else {
          // keluar viewport -> reset biar bisa run lagi pas masuk
          resetCount(el);
        }
      });
    },
    { threshold: 0.35 } // angka mulai jalan pas kartu lumayan keliatan
  );

  counters.forEach((el) => {
    resetCount(el); // pastiin start dari 0
    io.observe(el);
  });
});
