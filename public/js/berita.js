document.addEventListener("DOMContentLoaded", () => {
  const track = document.querySelector("[data-news-track]");
  const section = document.querySelector("[data-news-section]");
  const cards = document.querySelectorAll("[data-news-card]");
  if (!track || !section) return;

  const btnPrev = document.querySelector(".news-nav-btn.prev");
  const btnNext = document.querySelector(".news-nav-btn.next");

  const scrollAmount = 320;

  btnNext?.addEventListener("click", () => {
    track.scrollBy({ left: scrollAmount, behavior: "smooth" });
  });

  btnPrev?.addEventListener("click", () => {
    track.scrollBy({ left: -scrollAmount, behavior: "smooth" });
  });

  // REVEAL on first scroll into view
  const io = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (!entry.isIntersecting) return;

      cards.forEach((card, idx) => {
        setTimeout(() => card.classList.add("is-in"), idx * 60);
      });

      io.disconnect(); // jalan sekali aja (first time)
    });
  }, { threshold: 0.2 });

  io.observe(section);
});
