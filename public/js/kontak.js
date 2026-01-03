/**
 * FAQ TOGGLE
 * Dipanggil dari onclick="toggleFaq(this)"
 */
function toggleFaq(el) {
  const item = el.closest('.faq-item');
  const answer = item.querySelector('.faq-answer');
  const icon = el.querySelector('.faq-icon');

  // Tutup semua FAQ lain (optional, tapi UX lebih rapi)
  document.querySelectorAll('.faq-item').forEach(other => {
    if (other !== item) {
      other.classList.remove('active');
      other.querySelector('.faq-answer').style.maxHeight = null;
      const otherIcon = other.querySelector('.faq-icon');
      if (otherIcon) otherIcon.style.transform = 'rotate(0deg)';
    }
  });

  // Toggle aktif
  item.classList.toggle('active');

  if (item.classList.contains('active')) {
    answer.style.maxHeight = answer.scrollHeight + 'px';
    if (icon) icon.style.transform = 'rotate(180deg)';
  } else {
    answer.style.maxHeight = null;
    if (icon) icon.style.transform = 'rotate(0deg)';
  }
}

/**
 * Optional: animasi halus pas reload
 */
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.faq-answer').forEach(ans => {
    ans.style.maxHeight = null;
  });
});

document.addEventListener("DOMContentLoaded", () => {
  const el = document.querySelector(".faq-image-container.bg-faq");
  if (!el) return;

  // ambil nilai awal dari inline style / default
  const getNum = (name, fallback) => {
    const v = getComputedStyle(el).getPropertyValue(name).trim();
    const num = parseFloat(v);
    return Number.isFinite(num) ? num : fallback;
  };

  let zoom = getNum("--zoom", 130);  // persen
  let posX = getNum("--posX", 50);   // persen
  let posY = getNum("--posY", 35);   // persen

  const clamp = (v, min, max) => Math.max(min, Math.min(max, v));
  const apply = () => {
    el.style.setProperty("--zoom", `${zoom}%`);
    el.style.setProperty("--posX", `${posX}%`);
    el.style.setProperty("--posY", `${posY}%`);
  };

  apply();

  // ===== DRAG TO PAN =====
  let isDown = false;
  let startX = 0, startY = 0;
  let startPosX = 0, startPosY = 0;

  el.addEventListener("pointerdown", (e) => {
    isDown = true;
    el.setPointerCapture(e.pointerId);
    startX = e.clientX;
    startY = e.clientY;
    startPosX = posX;
    startPosY = posY;
  });

  el.addEventListener("pointermove", (e) => {
    if (!isDown) return;

    const rect = el.getBoundingClientRect();
    const dx = e.clientX - startX;
    const dy = e.clientY - startY;

    // konversi pixel drag -> persen posisi
    const moveX = (dx / rect.width) * 100;
    const moveY = (dy / rect.height) * 100;

    posX = clamp(startPosX + moveX, 0, 100);
    posY = clamp(startPosY + moveY, 0, 100);
    apply();
  });

  const endDrag = () => { isDown = false; };
  el.addEventListener("pointerup", endDrag);
  el.addEventListener("pointercancel", endDrag);
  el.addEventListener("pointerleave", endDrag);

  // ===== SCROLL TO ZOOM (mouse wheel) =====
  el.addEventListener("wheel", (e) => {
    e.preventDefault();

    // scroll up = zoom in, scroll down = zoom out
    const delta = e.deltaY > 0 ? -5 : 5;
    zoom = clamp(zoom + delta, 100, 220); // batas zoom
    apply();
  }, { passive: false });

  // ===== DOUBLE CLICK RESET =====
  el.addEventListener("dblclick", () => {
    zoom = 130;
    posX = 50;
    posY = 35;
    apply();
  });
});
