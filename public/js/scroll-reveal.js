document.addEventListener("DOMContentLoaded", () => {
  // =========================
  // 1) REVEAL ONCE (SECTION)
  // =========================
  const onceSections = Array.from(document.querySelectorAll("[data-reveal-once]"));

  // siapin state hidden cuma kalau JS kebaca
  onceSections.forEach(sec => sec.classList.add("reveal-once-init"));

  if (!("IntersectionObserver" in window) || onceSections.length === 0) {
    onceSections.forEach(sec => {
      sec.classList.remove("reveal-once-init");
      sec.classList.add("is-visible");
    });
  } else {
    const onceObserver = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.remove("reveal-once-init");
          entry.target.classList.add("is-visible");
          onceObserver.unobserve(entry.target);
        }
      });
    }, { threshold: 0.12, rootMargin: "0px 0px -80px 0px" });

    onceSections.forEach(sec => onceObserver.observe(sec));
  }

  // =========================
  // 2) SCROLL REVEAL (ELEMENT)
  //    skip kalau ada di dalam data-reveal-once (biar galeri/sebaran ringan)
  // =========================
  const items = Array
    .from(document.querySelectorAll(".reveal"))
    .filter(el => !el.closest("[data-reveal-once]"));

  if (!("IntersectionObserver" in window) || items.length === 0) {
    items.forEach(el => el.classList.add("is-visible"));
  } else {
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.classList.add("is-visible");
            observer.unobserve(entry.target); // sekali aja
          }
        });
      },
      { threshold: 0.15, rootMargin: "0px 0px -80px 0px" }
    );

    items.forEach(el => observer.observe(el));
  }

  // =========================
  // 3) PAGE ENTER khusus Tentang
  // =========================
  const tentangRoot = document.getElementById("tentangRoot");
  if (tentangRoot) {
    requestAnimationFrame(() => tentangRoot.classList.add("is-loaded"));
  }
});
