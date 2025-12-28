// Reveal animation (repeatable)
document.addEventListener("DOMContentLoaded", () => {
  const items = document.querySelectorAll(".reveal");

  const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if(entry.isIntersecting){
        entry.target.classList.add("is-visible");
      } else {
        entry.target.classList.remove("is-visible");
      }
    });
  }, { threshold: 0.15 });

  items.forEach(el => observer.observe(el));
});
