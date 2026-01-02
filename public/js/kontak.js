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
