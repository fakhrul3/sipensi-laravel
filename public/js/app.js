/**
 * Global UI behaviors:
 * - Navbar scroll state (match dashboard)
 * - Smooth page transition dashboard <-> mitra
 *
 * Works without jQuery.
 */

(function () {
  const READY_CLASS = 'page-ready';
  const LEAVING_CLASS = 'page-leaving';

  function markReady() {
    document.body.classList.add(READY_CLASS);
    document.body.classList.remove(LEAVING_CLASS);
  }

  function initPageTransition() {
    markReady();

    // Handle bfcache (back/forward) so page doesn't stay transparent
    window.addEventListener('pageshow', () => {
      markReady();
    });

    // Intercept internal link clicks (same-origin) to fade out before navigation
    document.addEventListener('click', (e) => {
      const a = e.target.closest('a');
      if (!a) return;

      // Allow normal behavior for:
      // - new tab / download
      // - anchors only
      // - external links
      // - JS pseudo links
      if (a.target && a.target !== '_self') return;
      if (a.hasAttribute('download')) return;

      const href = a.getAttribute('href') || '';
      if (!href || href.startsWith('#') || href.startsWith('javascript:')) return;

      // Same origin only
      let url;
      try {
        url = new URL(href, window.location.href);
      } catch {
        return;
      }
      if (url.origin !== window.location.origin) return;

      // If navigating to same page (incl. hash), let it through
      if (url.href === window.location.href) return;

      e.preventDefault();

      document.body.classList.add(LEAVING_CLASS);

      // Small delay so transition is visible (keep snappy)
      window.setTimeout(() => {
        window.location.href = url.href;
      }, 180);
    });
  }

  function initNavbarScroll() {
    const nav = document.getElementById('mainNavbar');
    if (!nav) return;

    const onScroll = () => {
      nav.classList.toggle('navbar-scrolled', window.scrollY > 10);
    };

    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
  }

  document.addEventListener('DOMContentLoaded', () => {
    initPageTransition();
    initNavbarScroll();
  });
})();
