/* ==========================================================
   LEMBAGA INKUBATOR - LIST PAGE JS (FIXED COLUMN NAME)
   ========================================================== */

   (function () {
    const CFG = window.LI_CONFIG || {};
    const rows = Array.isArray(CFG.rows) ? CFG.rows : [];
    const baseUrl = (CFG.baseUrl || "").replace(/\/$/, "");

    // Mapping Label & Badge (Pastikan ID 1-5 sesuai dengan isi kolom jenis_inkubator)
    const jenisInfoMap = {
        1: { label: "Pemerintah Pusat", badge: "badge-pusat" },
        2: { label: "Pemerintah Daerah", badge: "badge-pemda" },
        3: { label: "Lembaga Pendidikan", badge: "badge-pendidikan" },
        4: { label: "Badan Usaha", badge: "badge-usaha" },
        5: { label: "Masyarakat", badge: "badge-masyarakat" }
    };

    const PAGE_SIZE = 10;
    let currentPage = 1;
    let filteredRows = [];

    const $ = (id) => document.getElementById(id);

    function safeText(s) {
        if (!s) return "-";
        return String(s).replace(/[&<>"']/g, (m) => (
            { "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#039;" }[m]
        ));
    }

    function renderPage() {
        const tbody = $("liTbody");
        if (!tbody) return;

        tbody.innerHTML = "";
        const start = (currentPage - 1) * PAGE_SIZE;
        const end = start + PAGE_SIZE;
        const pageRows = filteredRows.slice(start, end);

        if (!pageRows.length) {
            tbody.innerHTML = `<tr><td colspan="3" style="padding:40px; text-align:center; color:#94a3b8; font-weight:600;">Data tidak ditemukan...</td></tr>`;
            updatePaginationUI();
            return;
        }

        pageRows.forEach((r, idx) => {
            // PAKAI NAMA KOLOM BARU: jenis_inkubator
            const idJenis = r.jenis_inkubator; 
            const info = jenisInfoMap[idJenis] || { label: "Lainnya", badge: "badge-default" };

            const tr = document.createElement("tr");
            tr.innerHTML = `
                <td class="li-no">
                    <div class="li-no-wrap">${start + idx + 1}</div>
                </td>
                <td>
                    <div class="li-name-wrap">
                        <span class="li-avatar"></span>
                        <a class="li-name" href="${baseUrl}/lembaga-inkubator/${r.id}">
                            ${safeText(r.nama_inkubator)}
                        </a>
                    </div>
                </td>
                <td class="text-end">
                    <span class="badge-jenis ${info.badge}">${info.label}</span>
                </td>
            `;
            tbody.appendChild(tr);
        });

        updatePaginationUI();
    }

    function applyFilter() {
        const q = ($("liSearch")?.value || "").toLowerCase().trim();
        const selectedJenis = $("liJenis")?.value || "";
        const selectedProvinsi = $("liProvinsi")?.value || "";

        filteredRows = rows.filter((x) => {
            const nama = String(x.nama_inkubator || "").toLowerCase();
            
            // SESUAIKAN DI SINI JUGA: jenis_inkubator
            const valJenis = String(x.jenis_inkubator || "");
            const valProvinsi = String(x.kode_provinsi || "");

            const matchName = nama.includes(q);
            const matchJenis = selectedJenis === "" || valJenis === selectedJenis;
            const matchProvinsi = selectedProvinsi === "" || valProvinsi === selectedProvinsi;
            
            return matchName && matchJenis && matchProvinsi;
        });

        currentPage = 1;
        renderPage();
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

    function boot() {
        filteredRows = [...rows];
        renderPage();

        $("liSearch")?.addEventListener("input", applyFilter);
        $("liJenis")?.addEventListener("change", applyFilter);
        $("liProvinsi")?.addEventListener("change", applyFilter);

        $("liPrev")?.addEventListener("click", () => {
            if (currentPage > 1) { currentPage--; renderPage(); window.scrollTo(0,0); }
        });

        $("liNext")?.addEventListener("click", () => {
            const totalPages = Math.ceil(filteredRows.length / PAGE_SIZE) || 1;
            if (currentPage < totalPages) { currentPage++; renderPage(); window.scrollTo(0,0); }
        });
    }

    document.addEventListener("DOMContentLoaded", boot);
})();