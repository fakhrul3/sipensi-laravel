(function () {
  // 1) Enable JS mode + remove no-js ASAP
  document.documentElement.classList.remove('no-js');

  const body = document.body;

  // 2) Fade-in on first paint (prevent "jedar")
  body.classList.add('is-entering');
  requestAnimationFrame(() => {
    requestAnimationFrame(() => body.classList.remove('is-entering'));
  });

  // 3) Mark page-ready early (DOMContentLoaded), avoid waiting images
  document.addEventListener('DOMContentLoaded', () => {
    body.classList.add('page-ready');
  });

  // 4) Intercept internal navigation with [data-page-link]
  function isModifiedClick(e) {
    return e.metaKey || e.ctrlKey || e.shiftKey || e.altKey || e.button !== 0;
  }

  document.addEventListener('click', (e) => {
    const a = e.target.closest('a[data-page-link]');
    if (!a) return;

    const href = a.getAttribute('href');
    if (!href || href.startsWith('mailto:') || href.startsWith('tel:')) return;
    if (isModifiedClick(e)) return;

    const url = new URL(href, window.location.href);
    if (url.origin !== window.location.origin) return;

    if (url.pathname === window.location.pathname && url.hash) return;

    e.preventDefault();
    body.classList.add('is-leaving');

    window.setTimeout(() => { window.location.href = url.href; }, 220);
  });

  // 5) Active state for anchor links (HOME only)
  function syncAnchorActive() {
    const hash = (window.location.hash || '').replace('#', '');
    const anchors = document.querySelectorAll('a.nav-link[data-anchor]');
    anchors.forEach(a => a.classList.remove('active'));
    if (!hash) return;

    anchors.forEach(a => {
      if (a.getAttribute('data-anchor') === hash) a.classList.add('active');
    });
  }

  window.addEventListener('hashchange', syncAnchorActive);
  window.addEventListener('load', syncAnchorActive);
})();
