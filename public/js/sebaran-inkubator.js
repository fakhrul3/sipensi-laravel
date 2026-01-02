document.addEventListener("DOMContentLoaded", () => {
  const mapEl = document.getElementById("mapSebaran");
  if (!mapEl || mapEl.dataset.inited) return;
  mapEl.dataset.inited = "1";

  const data = window.SEBARAN_INKUBATOR_DATA && window.SEBARAN_INKUBATOR_DATA.length
    ? window.SEBARAN_INKUBATOR_DATA
    : [
       { id: 1, name: "Aceh", latitude: 4.6951, longitude: 96.7494, total: 21 },
            { id: 2, name: "Sumatera Utara", latitude: 2.1154, longitude: 99.5451, total: 16 },
            { id: 3, name: "Sumatera Barat", latitude: -0.9492, longitude: 100.3543, total: 18 },
            { id: 4, name: "Riau", latitude: 0.5071, longitude: 101.4478, total: 6 },
            { id: 5, name: "Jambi", latitude: -1.4852, longitude: 102.4380, total: 5 },
            { id: 6, name: "Sumatera Selatan", latitude: -3.3194, longitude: 103.9144, total: 25 },
            { id: 7, name: "Bengkulu", latitude: -3.7928, longitude: 102.2608, total: 1 },
            { id: 8, name: "Lampung", latitude: -5.4280, longitude: 105.2619, total: 24 },
            { id: 9, name: "Kepulauan Bangka Belitung", latitude: -2.0961, longitude: 106.1443, total: 5 },
            { id: 10, name: "Kepulauan Riau", latitude: 0.9186, longitude: 104.4554, total: 4 },
            { id: 11, name: "DKI Jakarta", latitude: -6.2088, longitude: 106.8456, total: 36 },
            { id: 12, name: "Jawa Barat", latitude: -6.9175, longitude: 107.6191, total: 107 },
            { id: 13, name: "Jawa Tengah", latitude: -7.0253, longitude: 110.3769, total: 113 },
            { id: 14, name: "DI Yogyakarta", latitude: -7.7956, longitude: 110.3695, total: 30 },
            { id: 15, name: "Jawa Timur", latitude: -7.2575, longitude: 112.7521, total: 137 },
            { id: 16, name: "Banten", latitude: -6.4058, longitude: 106.0640, total: 36 },
            { id: 17, name: "Bali", latitude: -8.6705, longitude: 115.2126, total: 26 },
            { id: 18, name: "Nusa Tenggara Barat", latitude: -8.5833, longitude: 116.1167, total: 24 },
            { id: 19, name: "Nusa Tenggara Timur", latitude: -8.6574, longitude: 121.0794, total: 3 },
            { id: 20, name: "Kalimantan Barat", latitude: -0.0263, longitude: 109.3425, total: 7 },
            { id: 21, name: "Kalimantan Tengah", latitude: -2.2102, longitude: 113.9200, total: 4 },
            { id: 22, name: "Kalimantan Selatan", latitude: -3.3194, longitude: 114.5908, total: 15 },
            { id: 23, name: "Kalimantan Timur", latitude: -0.5021, longitude: 117.1536, total: 1 },
            { id: 24, name: "Kalimantan Utara", latitude: 3.0738, longitude: 116.0414, total: 1 },
            { id: 25, name: "Sulawesi Utara", latitude: 1.4748, longitude: 124.8426, total: 4 },
            { id: 26, name: "Sulawesi Tengah", latitude: -1.4300, longitude: 121.4456, total: 10 },
            { id: 27, name: "Sulawesi Selatan", latitude: -5.1477, longitude: 119.4327, total: 31 },
            { id: 28, name: "Sulawesi Tenggara", latitude: -3.9678, longitude: 122.5947, total: 5 },
            { id: 29, name: "Gorontalo", latitude: 0.6999, longitude: 122.4467, total: 10 },
            { id: 30, name: "Sulawesi Barat", latitude: -2.8441, longitude: 119.2321, total: 2 },
            { id: 31, name: "Maluku", latitude: -3.2385, longitude: 130.1453, total: 3 },
            { id: 32, name: "Maluku Utara", latitude: 0.7306, longitude: 127.5699, total: 2 },
            { id: 33, name: "Papua Barat", latitude: -0.8615, longitude: 134.0620, total: 1 },
            { id: 34, name: "Papua", latitude: -4.2699, longitude: 138.0804, total: 3 }
      ];

  const map = L.map("mapSebaran", {
    scrollWheelZoom: false,
    minZoom: 5,
    maxZoom: 10,
    maxBounds: [[-11, 94], [6, 141]]
  }).setView([-2.5489, 118.0149], 5);

  L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    attribution: "&copy; OpenStreetMap contributors"
  }).addTo(map);

  const icon = L.divIcon({
    className: "custom-marker",
    html: `
      <div style="
        width:40px;height:40px;background:#4788AB;
        border-radius:50% 50% 50% 0;
        transform:rotate(-45deg);
        border:3px solid #fff;
        box-shadow:0 2px 8px rgba(0,0,0,.3);
        display:flex;align-items:center;justify-content:center;">
        <div style="transform:rotate(45deg);font-size:18px;color:#fff;">📍</div>
      </div>`,
    iconSize: [40, 40],
    iconAnchor: [20, 40],
    popupAnchor: [0, -40],
  });

  data.forEach(item => {
    if (!item.latitude || !item.longitude) return;

    L.marker([item.latitude, item.longitude], { icon })
      .addTo(map)
      .bindPopup(`
        <strong>${item.name}</strong><br>
        ${item.total || 0} Inkubator
      `);
  });
});
