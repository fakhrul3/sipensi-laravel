document.addEventListener("DOMContentLoaded", () => {
  const mapEl = document.getElementById("mapSebaran");
  if (!mapEl || mapEl.dataset.inited) return;
  mapEl.dataset.inited = "1";

  // Ambil data dari window variable yang dikirim PHP
  const rawData = window.SEBARAN_INKUBATOR_DATA;

  const data = Array.isArray(rawData)
    ? rawData.map((item) => ({
        id: item.id ?? null,
        kode_provinsi: item.kode_provinsi ?? null, // Jangan sampai ketinggalan lagi
        name: item.name ?? "Provinsi Tidak Diketahui",
        latitude: Number(item.latitude),
        longitude: Number(item.longitude),
        total: Number.isFinite(Number(item.total)) ? Number(item.total) : 0,
      }))
    : [];

  // Init Map
  const map = L.map("mapSebaran", {
    scrollWheelZoom: false,
    minZoom: 5,
    maxZoom: 10,
    maxBounds: [[-11, 94], [6, 141]],
  }).setView([-2.5489, 118.0149], 5);

  L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    attribution: "&copy; OpenStreetMap contributors",
  }).addTo(map);

  const lembagaUrl = window.SIPENSI && window.SIPENSI.lembagaUrl ? window.SIPENSI.lembagaUrl : "/inkubator";

  const icon = L.divIcon({
    className: "custom-marker",
    html: `<div style="width:40px;height:40px;background:#4788AB;border-radius:50% 50% 50% 0;transform:rotate(-45deg);border:3px solid #fff;box-shadow:0 2px 8px rgba(0,0,0,.25);display:flex;align-items:center;justify-content:center;"><div style="transform:rotate(45deg);font-size:18px;color:#fff;">📍</div></div>`,
    iconSize: [40, 40],
    iconAnchor: [20, 40],
    popupAnchor: [0, -40],
  });

 // =========================
  // RENDER MARKER
  // =========================
  data.forEach((item) => {
    // 1. Cek koordinat (keamanan data)
    if (isNaN(item.latitude) || isNaN(item.longitude)) return;

    // 2. TAMBAHKAN KONDISI INI:
    // Jika total inkubator adalah 0, null, atau undefined, jangan buat marker
    if (!item.total || item.total === 0) {
        return; // Skip/Loncati provinsi ini
    }

    const marker = L.marker([item.latitude, item.longitude], { icon }).addTo(map);

    const detailUrl = `${lembagaUrl}?kode_provinsi=${item.kode_provinsi}`;

    marker.bindPopup(`
      <div class="sipensi-popup">
        <div class="popup-title"><b>${item.name}</b></div>
        <div class="popup-count">${item.total} Inkubator</div>
        <hr style="margin: 5px 0; border: 0; border-top: 1px solid #eee;">
        <a class="popup-link" href="${detailUrl}" style="color: #4788AB; text-decoration: none; font-weight: bold; display: inline-block; margin-top: 5px;">
          Lihat selengkapnya →
        </a>
      </div>
    `);
  });

  setTimeout(() => { map.invalidateSize(); }, 150);
});