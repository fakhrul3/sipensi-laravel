/* ==========================================================
   LEMBAGA INKUBATOR - LIST PAGE JS (NO API)
   - Data & mapping di-inject dari Blade via window.LI_CONFIG
   - Search + filter client-side
   - Pagination client-side (max 10 rows per page)
   - Klik nama -> /lembaga-inkubator/{id}
   ========================================================== */

(function () {
  const CFG = window.LI_CONFIG || {};
  const rows = Array.isArray(CFG.rows) ? CFG.rows : [];
  const jenisMap = CFG.jenisMap || {};
  const baseUrl = (CFG.baseUrl || "").replace(/\/$/, "");

  const PAGE_SIZE = 10;
  let currentPage = 1;
  let filteredRows = [];

  const $ = (id) => document.getElementById(id);

  function safeText(s) {
    return String(s ?? "").replace(/[&<>"']/g, (m) => (
      { "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#039;" }[m]
    ));
  }

  function detailUrl(id) {
    const prefix = baseUrl ? baseUrl : "";
    return `${prefix}/lembaga-inkubator/${id}`;
  }

  function resolveJenis(jenisCode) {
    const jm = jenisMap[jenisCode] || { label: "-", badge: "" };
    return {
      label: jm.label || "-",
      badge: jm.badge || ""
    };
  }

  function updatePaginationUI() {
    const totalPages = Math.ceil(filteredRows.length / PAGE_SIZE) || 1;

    const pageInfo = $("liPageInfo");
    const prevBtn = $("liPrev");
    const nextBtn = $("liNext");

    if (pageInfo) pageInfo.textContent = `Halaman ${currentPage} dari ${totalPages}`;
    if (prevBtn) prevBtn.disabled = currentPage <= 1;
    if (nextBtn) nextBtn.disabled = currentPage >= totalPages;
  }

  function renderPage() {
    const tbody = $("liTbody");
    if (!tbody) return;

    tbody.innerHTML = "";

    const start = (currentPage - 1) * PAGE_SIZE;
    const end = start + PAGE_SIZE;
    const pageRows = filteredRows.slice(start, end);

    if (!pageRows.length) {
      const tr = document.createElement("tr");
      tr.innerHTML = `
        <td colspan="3" style="padding:22px 18px;color:#6c7a80;font-weight:700;">
          Tidak ada data yang cocok.
        </td>
      `;
      tbody.appendChild(tr);
      updatePaginationUI();
      return;
    }

    pageRows.forEach((r, idx) => {
      const jenis = resolveJenis(r.jenis);

      const tr = document.createElement("tr");
      tr.innerHTML = `
        <td class="li-no">
          <div class="li-no-wrap">${start + idx + 1}</div>
        </td>

        <td>
          <div class="li-name-wrap">
            <span class="li-avatar" aria-hidden="true"></span>
            <a class="li-name" href="${detailUrl(r.id)}">
              ${safeText(r.nama)}
            </a>
          </div>
        </td>

        <td class="text-end">
          <span class="badge-jenis ${safeText(jenis.badge)}">${safeText(jenis.label)}</span>
        </td>
      `;
      tbody.appendChild(tr);
    });

    updatePaginationUI();
  }

  function applyFilter(allRows) {
    const q = ($("liSearch")?.value || "").toLowerCase().trim();
    const jenis = $("liJenis")?.value || "";

    filteredRows = allRows.filter((x) => {
      const okName = String(x.nama || "").toLowerCase().includes(q);
      const okJenis = jenis ? String(x.jenis) === String(jenis) : true;
      return okName && okJenis;
    });

    currentPage = 1; // reset ke page 1 setelah filter
    renderPage();
  }

  function boot() {
    filteredRows = rows.slice(); // clone
    renderPage();

    // Search & filter
    $("liSearch")?.addEventListener("input", () => applyFilter(rows));
    $("liJenis")?.addEventListener("change", () => applyFilter(rows));

    // Pagination
    $("liPrev")?.addEventListener("click", () => {
      if (currentPage > 1) {
        currentPage--;
        renderPage();
      }
    });

    $("liNext")?.addEventListener("click", () => {
      const totalPages = Math.ceil(filteredRows.length / PAGE_SIZE) || 1;
      if (currentPage < totalPages) {
        currentPage++;
        renderPage();
      }
    });

    // Optional: keyboard support (left/right)
    document.addEventListener("keydown", (e) => {
      if (e.key === "ArrowLeft") {
        const prevBtn = $("liPrev");
        if (prevBtn && !prevBtn.disabled) prevBtn.click();
      }
      if (e.key === "ArrowRight") {
        const nextBtn = $("liNext");
        if (nextBtn && !nextBtn.disabled) nextBtn.click();
      }
    });
  }

  document.addEventListener("DOMContentLoaded", boot);
})();
``
window.addEventListener("scroll", () => {
  const nav = document.querySelector(".sipensi-navbar");
  if(window.scrollY > 80){
    nav.classList.add("nav-solid");
  }else{
    nav.classList.remove("nav-solid");
  }
});

