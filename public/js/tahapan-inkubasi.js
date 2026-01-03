document.addEventListener("DOMContentLoaded", () => {
  const wrap = document.querySelector("[data-tahapan-wrap]");
  const tabs = document.querySelector("[data-tahapan-tabs]");
  if (!wrap || !tabs) return;

  const order = ["pra-inkubasi", "inkubasi", "pasca-inkubasi"];
  let currentIndex = 0; // default Pasca

  const btns = Array.from(tabs.querySelectorAll(".tahapan-tab-btn"));
  const contents = Array.from(wrap.querySelectorAll("[data-tahap-content]"));
  const btnLeft = wrap.querySelector('[data-nav="left"]');
  const btnRight = wrap.querySelector('[data-nav="right"]');

  const show = (key) => {
    // tab active
    btns.forEach(b => b.classList.toggle("active", b.dataset.tahap === key));

    // content
    contents.forEach(c => {
      const isMatch = c.dataset.tahapContent === key;
      if (isMatch) {
        c.style.display = "";
        // trigger transition
        requestAnimationFrame(() => c.classList.add("is-active"));
      } else {
        c.classList.remove("is-active");
        c.style.display = "none";
      }
    });
  };

  const updateArrows = () => {
    if (!btnLeft || !btnRight) return;
    btnLeft.classList.toggle("hidden", currentIndex === 0);
    btnRight.classList.toggle("hidden", currentIndex === order.length - 1);
  };

  const go = (idx) => {
    if (idx < 0 || idx >= order.length) return;
    currentIndex = idx;
    show(order[currentIndex]);
    updateArrows();
  };

  // click tab
  tabs.addEventListener("click", (e) => {
    const btn = e.target.closest(".tahapan-tab-btn");
    if (!btn) return;
    const key = btn.dataset.tahap;
    const idx = order.indexOf(key);
    if (idx !== -1) go(idx);
  });

  // arrows
  btnLeft?.addEventListener("click", () => go(currentIndex - 1));
  btnRight?.addEventListener("click", () => go(currentIndex + 1));

  // init
  show(order[currentIndex]);
  updateArrows();
});
