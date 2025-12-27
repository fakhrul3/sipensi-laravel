(function () {
  // 1) Fade-in on load (prevents "jedar" on first paint)
  document.documentElement.classList.remove('no-js');

  const body = document.body;
  // mark entering
  body.classList.add('is-entering');
  requestAnimationFrame(() => {
    requestAnimationFrame(() => body.classList.remove('is-entering'));
  });

  // 2) Intercept internal navigation with [data-page-link]
  function isModifiedClick(e) {
    return e.metaKey || e.ctrlKey || e.shiftKey || e.altKey || e.button !== 0;
  }

  document.addEventListener('click', (e) => {
    const a = e.target.closest('a[data-page-link]');
    if (!a) return;

    const href = a.getAttribute('href');
    if (!href || href.startsWith('mailto:') || href.startsWith('tel:')) return;
    if (isModifiedClick(e)) return;

    // external / new tab
    const url = new URL(href, window.location.href);
    if (url.origin !== window.location.origin) return;

    // same-page hash jump (no need to leave)
    if (url.pathname === window.location.pathname && url.hash) return;

    e.preventDefault();
    body.classList.add('is-leaving');

    // navigate after transition
    window.setTimeout(() => { window.location.href = url.href; }, 220);
  });

  // 3) Active state for anchor links (TENTANG/KONTAK) on HOME only.
  // Fragment (#...) never reaches server, so we do it in JS.
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

  window.addEventListener("load", () => {
    document.body.classList.add("page-ready");
});
