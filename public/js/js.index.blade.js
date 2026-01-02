
@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js" integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM=" crossorigin=""></script>
<script>
    // Hero Carousel
    let currentHeroSlide = 0;
    const heroSlides = document.querySelectorAll('.hero-slide');
    const heroIndicators = document.querySelectorAll('.hero-indicator');
    const totalSlides = heroSlides.length;

    function updateHeroSlide(index) {
        heroSlides.forEach((slide, i) => {
            slide.classList.toggle('active', i === index);
        });
        heroIndicators.forEach((indicator, i) => {
            indicator.classList.toggle('active', i === index);
        });
    }

    function heroNext() {
        currentHeroSlide = (currentHeroSlide + 1) % totalSlides;
        updateHeroSlide(currentHeroSlide);
    }

    function heroPrev() {
        currentHeroSlide = (currentHeroSlide - 1 + totalSlides) % totalSlides;
        updateHeroSlide(currentHeroSlide);
    }

    function heroGoTo(index) {
        currentHeroSlide = index;
        updateHeroSlide(currentHeroSlide);
    }

    // Auto-slide every 5 seconds
    setInterval(heroNext, 5000);

    // Counter Animation
    function animateCounter(element, target, duration = 2000) {
        const start = 0;
        const increment = target / (duration / 16);
        let current = start;

        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                element.textContent = Math.floor(target).toLocaleString('id-ID');
                clearInterval(timer);
            } else {
                element.textContent = Math.floor(current).toLocaleString('id-ID');
            }
        }, 16);
    }

    // Intersection Observer for counters
    const observerOptions = {
        threshold: 0.5,
        rootMargin: '0px 0px -100px 0px'
    };

    const counterObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !entry.target.dataset.animated) {
                entry.target.dataset.animated = 'true';
                const target = parseInt(entry.target.dataset.target);
                animateCounter(entry.target, target);
            }
        });
    }, observerOptions);

    document.addEventListener('DOMContentLoaded', function() {
        const counterInkubator = document.getElementById('counterInkubator');
        const counterTenant = document.getElementById('counterTenant');
        
        if (counterInkubator) {
            counterInkubator.dataset.target = {{ $total_inkubator }};
            counterObserver.observe(counterInkubator);
        }
        
        if (counterTenant) {
            counterTenant.dataset.target = {{ $total_tenant }};
            counterObserver.observe(counterTenant);
        }
    });

    // Map Implementation - Model seperti di folder vercel: GeoJSON polygon + Marker custom icon
    let map = null;
    // Semua provinsi menggunakan satu warna biru sesuai pedoman UI
    const provinceColor = "#4788AB"; // Secondary color dari pedoman UI

    function createCustomIcon(color) {
        return L.divIcon({
            className: "custom-marker",
            html: `
                <div style="
                    width: 40px;
                    height: 40px;
                    background-color: ${color};
                    border-radius: 50% 50% 50% 0;
                    transform: rotate(-45deg);
                    border: 3px solid white;
                    box-shadow: 0 2px 8px rgba(0,0,0,0.3);
                    position: relative;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                ">
                    <div style="
                        transform: rotate(45deg);
                        color: white;
                        font-weight: bold;
                        font-size: 20px;
                        text-shadow: 0 1px 3px rgba(0,0,0,0.5);
                    ">📍</div>
                </div>
            `,
            iconSize: [40, 40],
            iconAnchor: [20, 40],
            popupAnchor: [0, -40],
        });
    }

    function createProvinceGeometry(province, index) {
        const color = provinceColor; // Semua provinsi menggunakan warna biru yang sama
        const count = parseInt(province.total) || 0;
        const radius = 0.5;
        const points = [];

        if (!province.latitude || !province.longitude) {
            return null;
        }

        for (let i = 0; i <= 360; i += 10) {
            const radians = (i * Math.PI) / 180;
            const lat = parseFloat(province.latitude) + radius * Math.cos(radians);
            const lng = parseFloat(province.longitude) + radius * Math.sin(radians);
            points.push([lng, lat]); // GeoJSON uses [lng, lat]
        }
        points.push([points[0][0], points[0][1]]); // Close the polygon

        return {
            type: "Feature",
            properties: {
                name: province.name,
                count: count,
                color: color,
            },
            geometry: {
                type: "Polygon",
                coordinates: [points]
            }
        };
    }

    document.addEventListener('DOMContentLoaded', function() {
        map = L.map('map', {
            scrollWheelZoom: true,
            minZoom: 5,
            maxZoom: 10,
            maxBounds: [[-10, 95], [6, 141]],
            maxBoundsViscosity: 1.0
        });

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 19
        }).addTo(map);

        // Dummy data provinsi Indonesia dengan koordinat dan warna biru
        const dummyProvinces = [
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

        fetch("{{ route('get-sebaran-inkubators') }}")
            .then(response => response.json())
            .then(data => {
                // Jika data kosong atau error, gunakan dummy data
                if (!data || data.length === 0) {
                    data = dummyProvinces;
                } else {
                    // Merge dengan dummy data untuk memastikan semua provinsi ada
                    const existingIds = data.map(p => p.id);
                    dummyProvinces.forEach(dummy => {
                        if (!existingIds.includes(dummy.id)) {
                            data.push(dummy);
                        }
                    });
                }

                if (!data || data.length === 0) {
                    map.setView([-2.4833, 117.8903], 5);
                    return;
                }

                const centerLat = data.reduce((sum, p) => sum + parseFloat(p.latitude || 0), 0) / data.length;
                const centerLng = data.reduce((sum, p) => sum + parseFloat(p.longitude || 0), 0) / data.length;
                map.setView([centerLat, centerLng], 5);

                // Create GeoJSON features for colored provinces
                const features = data
                    .map((province, index) => createProvinceGeometry(province, index))
                    .filter(f => f !== null);

                // Add GeoJSON layer for colored provinces
                L.geoJSON({
                    type: "FeatureCollection",
                    features: features
                }, {
                    style: function(feature) {
                        return {
                            fillColor: feature.properties.color,
                            fillOpacity: 0.6,
                            color: "#fff",
                            weight: 2,
                            opacity: 0.8
                        };
                    },
                    onEachFeature: function(feature, layer) {
                        const count = feature.properties.count || 0;
                        layer.bindPopup(`
                            <div style="min-width: 180px; font-family: sans-serif;">
                                <div style="background: #F4F6F8; margin: -12px -12px 12px -12px; padding: 12px; border-bottom: 1px solid #E6E6E6; border-radius: 6px 6px 0 0;">
                                    <h4 style="font-weight: 700; color: #22466C; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px; margin: 0;">
                                        ${feature.properties.name}
                                    </h4>
                                </div>
                                <div style="display: flex; align-items: center; justify-content: space-between; padding: 8px 0;">
                                    <span style="color: #6b7280; font-size: 12px; font-weight: 500;">Jumlah Inkubator</span>
                                    <span style="background: #4788AB; color: white; font-size: 12px; padding: 4px 10px; border-radius: 6px; font-weight: 700;">
                                        ${count} Unit
                                    </span>
                                </div>
                                <a href="/inkubators?keyword=${encodeURIComponent(feature.properties.name)}" 
                                   style="display: block; text-align: center; margin-top: 12px; font-size: 12px; font-weight: 700; color: #4788AB; text-decoration: none; transition: all 0.3s ease;">
                                   Lihat Semua →
                                </a>
                            </div>
                        `);
                        layer.bindTooltip(feature.properties.name);

                        layer.on('mouseover', function(e) {
                            this.setStyle({
                                fillOpacity: 0.8,
                                weight: 3
                            });
                            this.openPopup();
                        });
                        layer.on('mouseout', function(e) {
                            this.setStyle({
                                fillOpacity: 0.6,
                                weight: 2
                            });
                        });
                        layer.on('click', function(e) {
                            this.openPopup();
                        });
                    }
                }).addTo(map);

                // Add markers with custom icons
                data.forEach((province, index) => {
                    if (!province.latitude || !province.longitude) return;
                    
                    const count = parseInt(province.total) || 0;
                    const color = provinceColor; // Semua provinsi menggunakan warna biru yang sama
                    const customIcon = createCustomIcon(color);

                    const marker = L.marker([province.latitude, province.longitude], {
                        icon: customIcon
                    }).addTo(map);

                    marker.bindTooltip(`
                        <div style="text-align: center;">
                            <div style="font-weight: bold; color: #22466C;">${province.name}</div>
                            <div style="font-size: 0.875rem; color: #6b7280;">${count} Inkubator</div>
                        </div>
                    `, { permanent: false, direction: 'top', offset: [0, -10] });

                    marker.bindPopup(`
                        <div style="min-width: 180px; font-family: sans-serif;">
                            <div style="background: #F4F6F8; margin: -12px -12px 12px -12px; padding: 12px; border-bottom: 1px solid #E6E6E6; border-radius: 6px 6px 0 0;">
                                <h4 style="font-weight: 700; color: #22466C; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px; margin: 0;">
                                    ${province.name}
                                </h4>
                            </div>
                            <div style="display: flex; align-items: center; justify-content: space-between; padding: 8px 0;">
                                <span style="color: #6b7280; font-size: 12px; font-weight: 500;">Jumlah Inkubator</span>
                                <span style="background: #4788AB; color: white; font-size: 12px; padding: 4px 10px; border-radius: 6px; font-weight: 700;">
                                    ${count} Unit
                                </span>
                            </div>
                            <a href="/inkubators?keyword=${encodeURIComponent(province.name)}" 
                               style="display: block; text-align: center; margin-top: 12px; font-size: 12px; font-weight: 700; color: #4788AB; text-decoration: none; transition: all 0.3s ease;">
                               Lihat Semua →
                            </a>
                        </div>
                    `);

                    // Hover effects for markers
                    marker.on('mouseover', function(e) {
                        this.openTooltip();
                    });
                    marker.on('click', function(e) {
                        this.openPopup();
                    });
                });
            })
            .catch(error => {
                console.error('Error load data sebaran inkubator:', error);
                // Jika error, gunakan dummy data
                const data = dummyProvinces;
                map.setView([-2.4833, 117.8903], 5);

                // Create GeoJSON features for colored provinces
                const features = data
                    .map((province, index) => createProvinceGeometry(province, index))
                    .filter(f => f !== null);

                // Add GeoJSON layer for colored provinces
                L.geoJSON({
                    type: "FeatureCollection",
                    features: features
                }, {
                    style: function(feature) {
                        return {
                            fillColor: feature.properties.color,
                            fillOpacity: 0.6,
                            color: "#fff",
                            weight: 2,
                            opacity: 0.8
                        };
                    },
                    onEachFeature: function(feature, layer) {
                        const count = feature.properties.count || 0;
                        layer.bindPopup(`
                            <div style="min-width: 180px; font-family: 'Montserrat', sans-serif;">
                                <div style="background: #F4F6F8; margin: -12px -12px 12px -12px; padding: 12px; border-bottom: 1px solid #E6E6E6; border-radius: 6px 6px 0 0;">
                                    <h4 style="font-weight: 700; color: #22466C; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px; margin: 0;">
                                        ${feature.properties.name}
                                    </h4>
                                </div>
                                <div style="display: flex; align-items: center; justify-content: space-between; padding: 8px 0;">
                                    <span style="color: #6b7280; font-size: 12px; font-weight: 500;">Jumlah Inkubator</span>
                                    <span style="background: #4788AB; color: white; font-size: 12px; padding: 4px 10px; border-radius: 6px; font-weight: 700;">
                                        ${count} Unit
                                    </span>
                                </div>
                                <a href="/inkubators?keyword=${encodeURIComponent(feature.properties.name)}" 
                                   style="display: block; text-align: center; margin-top: 12px; font-size: 12px; font-weight: 700; color: #4788AB; text-decoration: none; transition: all 0.3s ease;">
                                   Lihat Semua →
                                </a>
                            </div>
                        `);
                        layer.bindTooltip(feature.properties.name);

                        layer.on('mouseover', function(e) {
                            this.setStyle({
                                fillOpacity: 0.8,
                                weight: 3
                            });
                            this.openPopup();
                        });
                        layer.on('mouseout', function(e) {
                            this.setStyle({
                                fillOpacity: 0.6,
                                weight: 2
                            });
                        });
                        layer.on('click', function(e) {
                            this.openPopup();
                        });
                    }
                }).addTo(map);

                // Add markers with custom icons
                data.forEach((province, index) => {
                    if (!province.latitude || !province.longitude) return;
                    
                    const count = parseInt(province.total) || 0;
                    const color = provinceColor;
                    const customIcon = createCustomIcon(color);

                    const marker = L.marker([province.latitude, province.longitude], {
                        icon: customIcon
                    }).addTo(map);

                    marker.bindTooltip(`
                        <div style="text-align: center;">
                            <div style="font-weight: bold; color: #22466C;">${province.name}</div>
                            <div style="font-size: 0.875rem; color: #6b7280;">${count} Inkubator</div>
                        </div>
                    `, { permanent: false, direction: 'top', offset: [0, -10] });

                    marker.bindPopup(`
                        <div style="min-width: 180px; font-family: 'Montserrat', sans-serif;">
                            <div style="background: #F4F6F8; margin: -12px -12px 12px -12px; padding: 12px; border-bottom: 1px solid #E6E6E6; border-radius: 6px 6px 0 0;">
                                <h4 style="font-weight: 700; color: #22466C; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px; margin: 0;">
                                    ${province.name}
                                </h4>
                            </div>
                            <div style="display: flex; align-items: center; justify-content: space-between; padding: 8px 0;">
                                <span style="color: #6b7280; font-size: 12px; font-weight: 500;">Jumlah Inkubator</span>
                                <span style="background: #4788AB; color: white; font-size: 12px; padding: 4px 10px; border-radius: 6px; font-weight: 700;">
                                    ${count} Unit
                                </span>
                            </div>
                            <a href="/inkubators?keyword=${encodeURIComponent(province.name)}" 
                               style="display: block; text-align: center; margin-top: 12px; font-size: 12px; font-weight: 700; color: #4788AB; text-decoration: none; transition: all 0.3s ease;">
                               Lihat Semua →
                            </a>
                        </div>
                    `);

                    marker.on('mouseover', function(e) {
                        this.openTooltip();
                    });
                    marker.on('click', function(e) {
                        this.openPopup();
                    });
                });
            });
    });

    // Tahapan Inkubasi
    const tahapanData = [
        {
            title: "Tahap Pra-Inkubasi",
            description: "Tahap persiapan dan seleksi calon tenant UMKM. Meliputi proses identifikasi potensi bisnis, penilaian kelayakan, dan penyusunan rencana pengembangan bisnis awal.",
            icon: "📋",
            bgColor: "bg-primary",
            borderColor: "#22466C",
            steps: [
                "Identifikasi dan seleksi calon tenant UMKM",
                "Penilaian kelayakan bisnis dan potensi pasar",
                "Penyusunan rencana pengembangan bisnis",
                "Persiapan dokumen dan administrasi",
                "Orientasi program inkubasi"
            ]
        },
        {
            title: "Tahap Inkubasi",
            description: "Tahap implementasi program pendampingan intensif. Meliputi pelatihan, konsultasi, monitoring, evaluasi, dan pemberian akses ke berbagai fasilitas dan sumber daya.",
            icon: "🚀",
            bgColor: "bg-info",
            borderColor: "#4788AB",
            steps: [
                "Pelatihan manajemen bisnis dan keuangan",
                "Konsultasi pengembangan produk dan layanan",
                "Pelatihan pemasaran digital dan offline",
                "Monitoring dan evaluasi berkala",
                "Akses fasilitas coworking space dan teknologi",
                "Networking dengan investor dan mitra bisnis",
                "Pendampingan akses permodalan dan pembiayaan"
            ]
        },
        {
            title: "Tahap Pasca-Inkubasi",
            description: "Tahap pengawasan dan pengembangan lanjutan setelah masa inkubasi. Meliputi monitoring pertumbuhan bisnis, evaluasi keberlanjutan, dan pengembangan jaringan bisnis.",
            icon: "🌟",
            bgColor: "bg-success",
            borderColor: "#D8B049",
            steps: [
                "Monitoring pertumbuhan bisnis secara berkala",
                "Evaluasi keberlanjutan dan kinerja bisnis",
                "Pengembangan jaringan bisnis dan kemitraan",
                "Fasilitasi ekspansi dan skala bisnis",
                "Pendampingan akses pasar yang lebih luas",
                "Pelaporan dan dokumentasi hasil inkubasi"
            ]
        }
    ];

    let currentTahap = 0;

    function changeTahap(index) {
        currentTahap = index;
        const tabs = document.querySelectorAll('.tahapan-tab');
        tabs.forEach((tab, i) => {
            tab.classList.toggle('active', i === index);
        });
        renderTahapanContent();
    }

    function renderTahapanContent() {
        const tahap = tahapanData[currentTahap];
        const contentDiv = document.getElementById('tahapanContent');
        
        contentDiv.innerHTML = `
            <div class="tahapan-content" style="border-top-color: ${tahap.borderColor};">
                <div class="d-flex flex-column flex-md-row align-items-start gap-4 mb-5">
                    <div class="flex-shrink-0" style="width: 96px; height: 96px; background: ${tahap.borderColor}20; border-radius: 1rem; display: flex; align-items: center; justify-content: center; font-size: 3rem; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);">
                        ${tahap.icon}
                    </div>
                    <div class="flex-grow-1">
                        <div style="display: inline-block; padding: 0.25rem 1rem; border-radius: 9999px; font-size: 0.875rem; font-weight: 600; margin-bottom: 0.75rem; background: ${tahap.borderColor}20; color: ${tahap.borderColor};">
                            ${tahap.title}
                        </div>
                        <h3 style="font-size: 1.875rem; font-weight: 700; color: #111827; margin-bottom: 0.75rem;">
                            ${tahap.title}
                        </h3>
                        <p style="color: #6b7280; line-height: 1.75; font-size: 1.125rem;">
                            ${tahap.description}
                        </p>
                    </div>
                </div>
                <div style="margin-top: 2.5rem;">
                    <h4 style="font-size: 1.25rem; font-weight: 700; color: #1f2937; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                        <span style="width: 4px; height: 24px; background: ${tahap.borderColor};"></span>
                        Aktivitas Utama
                    </h4>
                    <div class="row g-3">
                        ${tahap.steps.map((step, idx) => `
                            <div class="col-md-6">
                                <div style="display: flex; align-items: start; gap: 1rem; padding: 1rem; border-radius: 0.5rem; background: ${tahap.borderColor}10; transition: all 0.3s;">
                                    <div style="flex-shrink: 0; width: 32px; height: 32px; border-radius: 0.5rem; background: ${tahap.borderColor}; color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.875rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                                        ${idx + 1}
                                    </div>
                                    <p style="color: #374151; font-weight: 500; flex: 1; margin: 0; padding-top: 0.25rem;">
                                        ${step}
                                    </p>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                </div>
            </div>
        `;
    }

    // Initialize tahapan
    document.addEventListener('DOMContentLoaded', function() {
        renderTahapanContent();
    });

    // Gallery
    const galleryImages = [
        @if ($gambar->count() > 0)
            @foreach ($gambar as $key => $item)
                {
                    id: {{ $key + 1 }},
                    src: '{{ $item->gambar_url ?? asset('theme/images/default.png') }}',
                    title: '{{ $item->judul ?? "Kegiatan Inkubasi UMKM" }}',
                    category: 'kegiatan'
                }{{ !$loop->last ? ',' : '' }}
            @endforeach
        @else
            {
                id: 1,
                src: '{{ asset('theme/images/jumbotron1.png') }}',
                title: 'Kegiatan Inkubasi UMKM',
                category: 'kegiatan'
            }
        @endif
    ];

    let selectedGalleryCategory = 'all';
    let currentLightboxIndex = 0;

    function filterGallery(category) {
        selectedGalleryCategory = category;
        const buttons = document.querySelectorAll('.gallery-filter-btn');
        buttons.forEach(btn => {
            btn.classList.toggle('active', btn.dataset.category === category);
        });
        renderGallery();
    }

    function renderGallery() {
        const filtered = selectedGalleryCategory === 'all' 
            ? galleryImages 
            : galleryImages.filter(img => img.category === selectedGalleryCategory);
        
        const grid = document.getElementById('galleryGrid');
        grid.innerHTML = filtered.map((image, index) => `
            <div class="gallery-item" onclick="openLightbox(${index})">
                <img src="${image.src}" alt="${image.title}" onerror="this.src='{{ asset('theme/images/default.png') }}'">
                <div class="gallery-item-overlay">
                    <div style="text-align: center; color: white; padding: 1rem;">
                        <h3 style="font-weight: 700; font-size: 1.125rem; margin-bottom: 0.25rem;">${image.title}</h3>
                        <p style="font-size: 0.875rem; color: #e5e7eb;">${image.category}</p>
                    </div>
                </div>
            </div>
        `).join('');
    }

    function openLightbox(index) {
        const filtered = selectedGalleryCategory === 'all' 
            ? galleryImages 
            : galleryImages.filter(img => img.category === selectedGalleryCategory);
        currentLightboxIndex = index;
        const image = filtered[index];
        const lightbox = document.getElementById('lightbox');
        const lightboxImage = document.getElementById('lightboxImage');
        const lightboxTitle = document.getElementById('lightboxTitle');
        const lightboxCategory = document.getElementById('lightboxCategory');
        
        lightboxImage.src = image.src;
        lightboxTitle.textContent = image.title;
        lightboxCategory.textContent = image.category;
        lightbox.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
        const lightbox = document.getElementById('lightbox');
        lightbox.classList.remove('active');
        document.body.style.overflow = '';
    }

    function lightboxPrev(event) {
        event.stopPropagation();
        const filtered = selectedGalleryCategory === 'all' 
            ? galleryImages 
            : galleryImages.filter(img => img.category === selectedGalleryCategory);
        currentLightboxIndex = (currentLightboxIndex - 1 + filtered.length) % filtered.length;
        const image = filtered[currentLightboxIndex];
        document.getElementById('lightboxImage').src = image.src;
        document.getElementById('lightboxTitle').textContent = image.title;
        document.getElementById('lightboxCategory').textContent = image.category;
    }

    function lightboxNext(event) {
        event.stopPropagation();
        const filtered = selectedGalleryCategory === 'all' 
            ? galleryImages 
            : galleryImages.filter(img => img.category === selectedGalleryCategory);
        currentLightboxIndex = (currentLightboxIndex + 1) % filtered.length;
        const image = filtered[currentLightboxIndex];
        document.getElementById('lightboxImage').src = image.src;
        document.getElementById('lightboxTitle').textContent = image.title;
        document.getElementById('lightboxCategory').textContent = image.category;
    }

    // Initialize gallery
    document.addEventListener('DOMContentLoaded', function() {
        renderGallery();
    });

</script>
@endpush