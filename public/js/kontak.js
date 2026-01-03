/* =====================================================
   KONTAK + FAQ – SIPENSI (FINAL)
   - Page enter animation (langsung saat page load)
   - FAQ accordion toggle (onclick="toggleFaq(this)")
   - bg-faq: drag to pan + wheel to zoom + dblclick reset
===================================================== */

/* =========================
   PAGE ENTER (PASTI JALAN)
========================= */
document.addEventListener("DOMContentLoaded", () => {
  const page = document.querySelector(".page-kontak");
  if (!page) return;

  // biar browser sempat render state awal (opacity 0) dulu
  requestAnimationFrame(() => {
    page.classList.add("page-loaded");
  });
});

/* =========================
   FAQ TOGGLE
========================= */
function toggleFaq(el) {
  const item = el.closest(".faq-item");
  if (!item) return;

  const answer = item.querySelector(".faq-answer");
  if (!answer) return;

  const allItems = document.querySelectorAll(".faq-accordion .faq-item");
  const isActive = item.classList.contains("active");

  // tutup semua item lain
  allItems.forEach((other) => {
    if (other !== item) {
      other.classList.remove("active");
      const otherAns = other.querySelector(".faq-answer");
      if (otherAns) otherAns.style.maxHeight = "0px";
    }
  });

  // toggle item ini
  if (isActive) {
    item.classList.remove("active");
    answer.style.maxHeight = "0px";
  } else {
    item.classList.add("active");
    answer.style.maxHeight = answer.scrollHeight + "px";
  }
}

document.addEventListener("DOMContentLoaded", () => {
  // reset semua jawaban
  document.querySelectorAll(".faq-accordion .faq-answer").forEach((ans) => {
    ans.style.maxHeight = "0px";
  });

  // kalau ada yang default active, set tingginya
  document.querySelectorAll(".faq-accordion .faq-item.active").forEach((item) => {
    const ans = item.querySelector(".faq-answer");
    if (ans) ans.style.maxHeight = ans.scrollHeight + "px";
  });

  // update height saat resize supaya tidak kepotong
  window.addEventListener("resize", () => {
    const active = document.querySelector(".faq-accordion .faq-item.active .faq-answer");
    if (active) active.style.maxHeight = active.scrollHeight + "px";
  });

  /* =========================
     BG FAQ: PAN / ZOOM
  ========================= */
  const host =
    document.querySelector(".faq-image-container .bg-faq") ||
    document.querySelector(".faq-image-container.bg-faq") ||
    document.querySelector(".bg-faq");

  if (!host) return;

  const getNum = (name, fallback) => {
    const v = getComputedStyle(host).getPropertyValue(name).trim();
    const num = parseFloat(v);
    return Number.isFinite(num) ? num : fallback;
  };

  let zoom = getNum("--zoom", 130);
  let posX = getNum("--posX", 50);
  let posY = getNum("--posY", 35);

  const clamp = (v, min, max) => Math.max(min, Math.min(max, v));
  const apply = () => {
    host.style.setProperty("--zoom", `${zoom}%`);
    host.style.setProperty("--posX", `${posX}%`);
    host.style.setProperty("--posY", `${posY}%`);
  };

  host.style.touchAction = "none";
  apply();

  // drag to pan
  let isDown = false;
  let startX = 0, startY = 0;
  let startPosX = 0, startPosY = 0;

  host.addEventListener("pointerdown", (e) => {
    isDown = true;
    host.setPointerCapture(e.pointerId);
    startX = e.clientX;
    startY = e.clientY;
    startPosX = posX;
    startPosY = posY;
  });

  host.addEventListener("pointermove", (e) => {
    if (!isDown) return;
    const rect = host.getBoundingClientRect();
    const dx = e.clientX - startX;
    const dy = e.clientY - startY;

    const moveX = (dx / rect.width) * 100;
    const moveY = (dy / rect.height) * 100;

    posX = clamp(startPosX + moveX, 0, 100);
    posY = clamp(startPosY + moveY, 0, 100);
    apply();
  });

  const endDrag = () => { isDown = false; };
  host.addEventListener("pointerup", endDrag);
  host.addEventListener("pointercancel", endDrag);
  host.addEventListener("pointerleave", endDrag);

  // wheel to zoom
  host.addEventListener("wheel", (e) => {
    e.preventDefault();
    const delta = e.deltaY > 0 ? -5 : 5;
    zoom = clamp(zoom + delta, 100, 220);
    apply();
  }, { passive: false });

  // dblclick reset
  host.addEventListener("dblclick", () => {
    zoom = 130;
    posX = 50;
    posY = 35;
    apply();
  });
});
