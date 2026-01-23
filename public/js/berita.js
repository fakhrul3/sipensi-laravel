document.addEventListener("DOMContentLoaded", () => {
  const track = document.querySelector("[data-news-track]");
  const section = document.querySelector("[data-news-section]");
  const cards = document.querySelectorAll("[data-news-card]");
  if (!track || !section) return;

  const btnPrev = document.querySelector(".news-nav-btn.prev");
  const btnNext = document.querySelector(".news-nav-btn.next");

  // Scroll 4 cards sekaligus (card width 300px + gap 20px = 320px per card)
  // 4 cards = 4 * 300px + 3 * 20px = 1200px + 60px = 1260px
  const cardWidth = 300;
  const cardGap = 20;
  const cardsPerScroll = 4;
  const scrollAmount = (cardWidth * cardsPerScroll) + (cardGap * (cardsPerScroll - 1));

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
