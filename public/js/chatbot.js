/**
 * Chatbot SIPENSI (Simple, No AI)
 * - Menjawab: jumlah lembaga inkubator per provinsi
 * - Data diambil dari /data/inkubator_by_provinsi.json
 * - Jika 2x berturut-turut gagal, percobaan ke-3 kasih kontak (mailto + wa.me)
 * - Animasi open/close smooth (pakai class: is-open + is-closing)
 */

(function () {
  // Guard: pastikan elemen ada
  const toggleBtn = document.getElementById("chatbotToggle");
  const closeBtn  = document.getElementById("chatbotClose");
  const windowEl  = document.getElementById("chatbotWindow");
  const bodyEl    = document.getElementById("chatbotBody");
  const formEl    = document.getElementById("chatbotForm");
  const inputEl   = document.getElementById("chatbotInput");

  if (!toggleBtn || !windowEl || !bodyEl || !formEl || !inputEl) return;

  // Root container untuk animasi muncul (sipensi-chatbot)
  const root = document.getElementById("sipensiChatbot") || document.querySelector(".sipensi-chatbot");

  // Trigger page enter animation
  requestAnimationFrame(() => {
    if (root) root.classList.add("chatbot-ready");
  });

  const EMAIL = "halo.sipensi@umkm.go.id";
  const WA_E164 = "62811380280"; // tanpa +
  const WA_LABEL = "(+62) 811-380-280";

  // LocalStorage key untuk open state
  const LS_KEY = "sipensi_chatbot_open";

  let failStreak = 0;
  let inkubatorByProvinsi = {};

  const normalize = (text) =>
    (text || "")
      .toLowerCase()
      .replace(/[^\p{L}\p{N}\s]/gu, " ")
      .replace(/\s+/g, " ")
      .trim();

  const addMsg = (type, html) => {
    const div = document.createElement("div");
    div.className = type === "user" ? "user-msg" : "bot-msg";
    div.innerHTML = html;
    bodyEl.appendChild(div);
    bodyEl.scrollTop = bodyEl.scrollHeight;
  };

  const saveOpenState = (isOpen) => {
    try {
      localStorage.setItem(LS_KEY, isOpen ? "1" : "0");
    } catch (_) {}
  };

  const getSavedOpenState = () => {
    try {
      return localStorage.getItem(LS_KEY) === "1";
    } catch (_) {
      return false;
    }
  };

  // Durasi closing (samain dengan CSS transition kamu)
  const CLOSE_MS = 260;

  const openChat = () => {
    windowEl.classList.remove("is-closing");
    windowEl.classList.add("is-open");
    windowEl.setAttribute("aria-hidden", "false");
    saveOpenState(true);

    // fokus setelah frame (biar input pasti kebaca)
    requestAnimationFrame(() => inputEl.focus());
  };

  const closeChat = () => {
    if (!windowEl.classList.contains("is-open")) return;

    // trigger animasi closing
    windowEl.classList.add("is-closing");
    windowEl.classList.remove("is-open");
    windowEl.setAttribute("aria-hidden", "true");
    saveOpenState(false);

    // bersihin class closing setelah animasi selesai
    window.setTimeout(() => {
      windowEl.classList.remove("is-closing");
    }, CLOSE_MS);
  };

  const toggleChat = () => {
    if (windowEl.classList.contains("is-open")) closeChat();
    else openChat();
  };

  const extractProvinsi = (textNorm) => {
    const keys = Object.keys(inkubatorByProvinsi).sort((a, b) => b.length - a.length);
    for (const k of keys) {
      if (textNorm.includes(k)) return k;
    }
    return null;
  };

  const contactCardHtml = () => `
    Maaf aku masih belum bisa jawab dengan tepat.<br><br>
    Silakan hubungi tim <b>SIPENSI</b>:<br>
    <a class="chatbot-link" href="mailto:${EMAIL}">
      <span class="chatbot-icon-inline" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none">
          <rect x="3" y="5" width="18" height="14" rx="2"
                stroke="currentColor" stroke-width="2"/>
          <path d="M3 7l9 6 9-6"
                stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </span>
      ${EMAIL}
    </a><br>
    <a class="chatbot-link"
       href="https://wa.me/${WA_E164}"
       target="_blank"
       rel="noopener">
      <span class="chatbot-icon-inline" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none">
          <path d="M12 3a9 9 0 0 0-7.6 13.8L3 21l4.4-1.3A9 9 0 1 0 12 3z"
                stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
          <path d="M9.5 9.5c.4 1.2 1.8 2.6 3 3
                   .6-.4 1.2-.8 1.8-1
                   .6-.2 1 .2 1.4.6l.6.6
                   c.4.4.4 1 0 1.4
                   -.6.6-1.4 1-2.2 1
                   -2.8 0-6.1-3.3-6.1-6.1
                   0-.8.4-1.6 1-2.2
                   .4-.4 1-.4 1.4 0l.6.6
                   c.4.4.6.8.4 1.3
                   -.2.6-.6 1.2-.9 1.8z"
                stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </span>
      WA Center ${WA_LABEL}
    </a>
  `;

  const handleUser = (userText) => {
    const t = normalize(userText);

    // Jika 2x gagal berturut-turut => percobaan ke-3 kasih kontak
    if (failStreak >= 2) {
      addMsg("bot", contactCardHtml());
      failStreak = 0;
      return;
    }

    // intent sederhana: harus ada kata jumlah/berapa + inkubator/lembaga
    const isAskingJumlah =
      /(jumlah|ada berapa|berapa|total)/.test(t) && /(inkubator|lembaga)/.test(t);

    if (!isAskingJumlah) {
      failStreak++;
      addMsg(
        "bot",
        `Aku bisa bantu cek <b>jumlah lembaga inkubator per provinsi</b>.<br>
         Contoh: <i>“jumlah inkubator di Jawa Barat”</i>.`
      );
      return;
    }

    const prov = extractProvinsi(t);
    if (!prov) {
      failStreak++;
      addMsg(
        "bot",
        `Provinsinya belum kebaca. Coba tulis jelas ya, contoh: <i>“jumlah inkubator di Sumatera Barat”</i>.`
      );
      return;
    }

    failStreak = 0;
    const jumlah = Number(inkubatorByProvinsi[prov] ?? 0);

    addMsg(
      "bot",
      `Jumlah <b>lembaga inkubator</b> di <b>${prov.toUpperCase()}</b> adalah <b>${jumlah}</b>.`
    );
  };

  // Load JSON data dari public/data
  const loadData = async () => {
    try {
      const res = await fetch("/data/inkubator_by_provinsi.json", { cache: "no-store" });
      if (!res.ok) throw new Error("JSON not found");

      const raw = await res.json();

      // normalisasi key jadi lowercase
      inkubatorByProvinsi = {};
      Object.keys(raw || {}).forEach((k) => {
        inkubatorByProvinsi[String(k).toLowerCase()] = Number(raw[k]) || 0;
      });
    } catch (e) {
      // kalau data gagal load, chatbot tetap jalan, tapi jawabannya akan banyak gagal
      addMsg(
        "bot",
        `Data provinsi belum bisa dimuat.<br>
         Pastikan file ada di: <b>public/data/inkubator_by_provinsi.json</b>`
      );
    }
  };

  /* =========================
     EVENTS
  ========================= */

  toggleBtn.addEventListener("click", (e) => {
    e.preventDefault();
    toggleChat();
  });

  closeBtn && closeBtn.addEventListener("click", (e) => {
    e.preventDefault();
    closeChat();
  });

  // Submit form
  formEl.addEventListener("submit", (e) => {
    e.preventDefault();
    const text = inputEl.value.trim();
    if (!text) return;

    addMsg("user", text);
    inputEl.value = "";
    handleUser(text);
  });

  // ESC to close
  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") closeChat();
  });

  // Click outside window to close (biar enak UX)
  document.addEventListener("click", (e) => {
    if (!windowEl.classList.contains("is-open")) return;

    const clickedInsideWindow = windowEl.contains(e.target);
    const clickedToggle = toggleBtn.contains(e.target);
    if (!clickedInsideWindow && !clickedToggle) closeChat();
  });

  // Restore open state setelah reload (optional)
  if (getSavedOpenState()) {
    // buka setelah data load + render siap
    setTimeout(openChat, 120);
  }

  loadData();
})();
