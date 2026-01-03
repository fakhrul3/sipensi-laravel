document.addEventListener('DOMContentLoaded', () => {
  // aktifkan mode JS (penting!)
  document.documentElement.classList.add('js');

  const els = document.querySelectorAll('.reveal');
  if (!els.length) return;

  const io = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add('is-visible');
      }
    });
  }, { threshold: 0.12 });

  els.forEach(el => io.observe(el));
});
