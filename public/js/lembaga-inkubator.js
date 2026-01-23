/* ==========================================================
   LEMBAGA INKUBATOR - LIST PAGE JS (FINAL FIXED VERSION)
   ========================================================== */

   (function () {
    const CFG = window.LI_CONFIG || {};
    const rows = Array.isArray(CFG.rows) ? CFG.rows : [];
    const baseUrl = (CFG.baseUrl || "").replace(/\/$/, "");

    // ✅ base URL untuk halaman detail (dikirim dari blade)
    // fallback kalau belum ada: `${baseUrl}/lembaga-inkubator`
    const detailBase = (CFG.detailBase || `${baseUrl}/lembaga-inkubator`).replace(/\/$/, "");

    // ✅ storage base untuk ambil file logo dari public storage
    // wajib diset dari blade: storageBase: "{{ asset('storage') }}"
    const storageBase = (CFG.storageBase || `${baseUrl}/storage`).replace(/\/$/, "");

    // ✅ default logo (fallback)
    const defaultLogo = `${baseUrl}/assets/images/brand/default-inkubator.png`;

    // Mapping Label & Badge
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

    // normalisasi angka/string (atasi "01" vs "1")
    const norm = (v) => {
        if (v === "" || v === null || v === undefined) return "";
        const n = Number(v);
        return Number.isFinite(n) ? String(n) : String(v).trim();
    };

    function safeText(s) {
        if (!s) return "-";
        return String(s).replace(/[&<>"']/g, (m) => (
            { "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#039;" }[m]
        ));
    }

    // ✅ build url logo dari value DB (kolom `logo`)
    // support:
    // - "file.jpg"
    // - "folder/file.jpg"
    // - "/storage/file.jpg"
    // - "storage/file.jpg"
    // - "http(s)://...."
    function buildLogoUrl(logo) {
        if (!logo) return "";
        const s = String(logo).trim();
        if (!s) return "";

        if (s.startsWith("http://") || s.startsWith("https://")) return s;
        if (s.startsWith("/storage/")) return s;
        if (s.startsWith("storage/")) return "/" + s;

        // default: path relatif ke storageBase
        return `${storageBase}/${s.replace(/^\//, "")}`;
    }

    // ✅ ambil id yang benar (fallback beberapa kemungkinan key)
    function getRowId(r) {
        return (
            r?.id ??
            r?.id_inkubator ??
            r?.inkubator_id ??
            r?.id_lembaga ??
            r?.uuid ??
            null
        );
    }

    function renderPage() {
        const tbody = $("liTbody");
        if (!tbody) return;

        tbody.innerHTML = "";

        const start = (currentPage - 1) * PAGE_SIZE;
        const end = start + PAGE_SIZE;
        const pageRows = filteredRows.slice(start, end);

        if (!pageRows.length) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="3" style="padding:40px; text-align:center; color:#94a3b8; font-weight:600;">
                        Data tidak ditemukan...
                    </td>
                </tr>
            `;
            updatePaginationUI();
            return;
        }

        pageRows.forEach((r, idx) => {
            const idJenis = r.jenis_inkubator;
            const info = jenisInfoMap[idJenis] || { label: "Lainnya", badge: "badge-default" };

            const id = getRowId(r);

            // kalau id kosong, kasih warning biar gampang ngecek data
            if (!id) {
                console.warn("ID inkubator tidak ditemukan pada row ini:", r);
            }

            // ✅ logo url (kalau kosong, langsung pakai default)
            const logoUrl = buildLogoUrl(r.logo) || defaultLogo;

            const tr = document.createElement("tr");
            tr.innerHTML = `
                <td class="li-no">
                    <div class="li-no-wrap">${start + idx + 1}</div>
                </td>
                <td>
                    <div class="li-name-wrap">

                        <!-- ✅ ganti avatar kosong jadi img logo -->
                        <img
                          class="li-avatar"
                          src="${logoUrl}"
                          onerror="this.src='${defaultLogo}'"
                          alt="${safeText(r.nama_inkubator)}"
                          title="${safeText(r.nama_inkubator)}"
                        />

                        <a class="li-name" href="${detailBase}/${id}">
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
        const selectedProvinsi = norm($("liProvinsi")?.value || "");

        filteredRows = rows.filter((x) => {
            const nama = String(x.nama_inkubator || "").toLowerCase();
            const valJenis = String(x.jenis_inkubator || "");
            const valProvinsi = norm(x.kode_provinsi);

            const matchName = nama.includes(q);
            const matchJenis = selectedJenis === "" || valJenis === selectedJenis;
            const matchProvinsi = selectedProvinsi === "" || valProvinsi === selectedProvinsi;

            return matchName && matchJenis && matchProvinsi;
        });

        currentPage = 1;
        renderPage();

        // =========================
        // UPDATE URL TANPA RELOAD
        // =========================
        const params = new URLSearchParams(window.location.search);

        if (selectedProvinsi) params.set("kode_provinsi", selectedProvinsi);
        else params.delete("kode_provinsi");

        if (selectedJenis) params.set("jenis", selectedJenis);
        else params.delete("jenis");

        window.history.replaceState({}, "", `${window.location.pathname}?${params.toString()}`);
    }

    function updatePaginationUI() {
        const totalPages = Math.ceil(filteredRows.length / PAGE_SIZE) || 1;

        if ($("liPageInfo")) {
            $("liPageInfo").textContent = `Halaman ${currentPage} dari ${totalPages}`;
        }

        if ($("liPrev")) $("liPrev").disabled = currentPage <= 1;
        if ($("liNext")) $("liNext").disabled = currentPage >= totalPages;
    }

    function boot() {
        // set default provinsi dari URL (map / dashboard)
        if (CFG.currentProvinsi) {
            const provEl = $("liProvinsi");
            if (provEl) {
                provEl.value = String(CFG.currentProvinsi);
            }
        }

        // render pertama HARUS lewat filter
        applyFilter();

        $("liSearch")?.addEventListener("input", applyFilter);
        $("liJenis")?.addEventListener("change", applyFilter);
        $("liProvinsi")?.addEventListener("change", applyFilter);

        $("liPrev")?.addEventListener("click", () => {
            if (currentPage > 1) {
                currentPage--;
                renderPage();
                window.scrollTo(0, 0);
            }
        });

        $("liNext")?.addEventListener("click", () => {
            const totalPages = Math.ceil(filteredRows.length / PAGE_SIZE) || 1;
            if (currentPage < totalPages) {
                currentPage++;
                renderPage();
                window.scrollTo(0, 0);
            }
        });
    }

    document.addEventListener("DOMContentLoaded", boot);
})();
