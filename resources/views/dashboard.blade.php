<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebGIS - Dashboard Kabupaten Tanah Laut</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            height: 100%;
        }

        :root {
            --bg-primary: #ffffff;
            --bg-secondary: #f8f9fa;
            --text-primary: #2c3e50;
            --text-secondary: #6c757d;
            --border-color: #e9ecef;
            --card-bg: #ffffff;
            --navbar-bg: #ffffff;
            --accent-dark: #495057;
            --accent-light: #e9ecef;
        }

        html.dark-mode {
            --bg-primary: #1a1a1a;
            --bg-secondary: #2d2d2d;
            --text-primary: #e9ecef;
            --text-secondary: #b0b0b0;
            --border-color: #404040;
            --card-bg: #262626;
            --navbar-bg: #1f1f1f;
            --accent-dark: #e9ecef;
            --accent-light: #404040;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: var(--bg-secondary);
            color: var(--text-primary);
            line-height: 1.6;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .navbar {
            background-color: var(--navbar-bg);
            border-bottom: 1px solid var(--border-color);
            padding: 1.25rem 2rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .navbar-content {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar h1 {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .nav-user {
            color: var(--text-secondary);
            font-size: 0.95rem;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .btn-logout {
            padding: 0.5rem 1rem;
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-logout:hover {
            background-color: #c82333;
        }

        .theme-toggle {
            background: none;
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            cursor: pointer;
            padding: 0.5rem 0.75rem;
            border-radius: 4px;
            font-size: 1.2rem;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
        }

        .theme-toggle:hover {
            background-color: var(--accent-light);
            border-color: var(--text-secondary);
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }

        .page-header {
            margin-bottom: 2rem;
        }

        .page-header h2 {
            font-size: 2rem;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .page-header p {
            color: var(--text-secondary);
            font-size: 1rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 6px;
            padding: 1.5rem;
            transition: all 0.2s ease;
        }

        .stat-card:hover {
            border-color: var(--text-secondary);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .stat-label {
            font-size: 0.875rem;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.75rem;
            font-weight: 500;
        }

        .stat-value {
            font-size: 2.25rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .stat-change {
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        .stat-change.positive {
            color: #28a745;
        }

        .stat-change.negative {
            color: #dc3545;
        }

        .content-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 6px;
            padding: 2rem;
            transition: all 0.2s ease;
        }

        .card:hover {
            border-color: var(--text-secondary);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .card-title::before {
            content: '';
            display: inline-block;
            width: 4px;
            height: 24px;
            background-color: var(--accent-dark);
            border-radius: 2px;
        }

        /* Filter bar */
        .filter-bar {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 12px;
            padding: 12px 14px;
            background: var(--accent-light);
            border-radius: 6px;
        }

        .filter-bar label {
            font-weight: 600;
            color: var(--text-primary);
            font-size: 14px;
        }

        .filter-bar select {
            padding: 6px 12px;
            border-radius: 4px;
            border: 1px solid var(--border-color);
            background: var(--card-bg);
            color: var(--text-primary);
            font-size: 14px;
            cursor: pointer;
        }

        .filter-legend {
            font-size: 13px;
            color: var(--text-secondary);
        }

        #map {
            width: 100%;
            height: 500px;
            border-radius: 6px;
            margin-bottom: 1.5rem;
            border: 1px solid var(--border-color);
        }

        .map-info {
            background: var(--accent-light);
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1rem;
            font-size: 0.9rem;
            color: var(--text-secondary);
        }

        .feature-list {
            list-style: none;
        }

        .feature-list li {
            padding: 0.75rem 0;
            color: var(--text-secondary);
            border-bottom: 1px solid var(--accent-light);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .feature-list li:last-child {
            border-bottom: none;
        }

        .feature-list li::before {
            content: '•';
            color: #adb5bd;
            font-weight: bold;
            margin-right: 0.25rem;
        }

        .recent-activity {
            list-style: none;
        }

        .activity-item {
            padding: 1rem 0;
            border-bottom: 1px solid var(--accent-light);
            font-size: 0.95rem;
            color: var(--text-secondary);
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-time {
            color: var(--text-secondary);
            font-size: 0.85rem;
            display: block;
            opacity: 0.8;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: 1px solid var(--border-color);
            background-color: transparent;
            color: var(--text-primary);
            text-decoration: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.95rem;
            transition: all 0.2s ease;
            font-weight: 500;
        }

        .btn:hover {
            background-color: var(--accent-light);
            border-color: var(--text-secondary);
        }

        .btn-primary {
            background-color: var(--accent-dark);
            color: var(--bg-primary);
            border-color: var(--accent-dark);
        }

        .btn-primary:hover {
            opacity: 0.9;
        }

        .card p {
            color: var(--text-secondary);
            line-height: 1.8;
        }

        .footer {
            background-color: var(--navbar-bg);
            border-top: 1px solid var(--border-color);
            padding: 2rem;
            text-align: center;
            color: var(--text-secondary);
            font-size: 0.9rem;
            margin-top: 3rem;
        }

        .leaflet-container {
            background: var(--bg-primary) !important;
        }

        @media (max-width: 1024px) {
            .content-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .container {
                padding: 1rem;
            }

            .page-header h2 {
                font-size: 1.5rem;
            }

            .navbar h1 {
                font-size: 1.2rem;
            }

            .navbar-content {
                flex-direction: column;
                gap: 1rem;
            }

            .action-buttons {
                flex-direction: column;
            }

            .navbar-right {
                gap: 1rem;
            }

            #map {
                height: 400px;
            }

            .filter-bar {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>

<body>
    <div class="navbar">
        <div class="navbar-content">
            <h1>WebGIS</h1>
            <div class="navbar-right">
                <div class="user-menu">
                    <div class="nav-user">
                        👤 {{ session('user')['username'] ?? 'User' }}
                    </div>
                    <a href="{{ route('logout') }}" class="btn-logout">Logout</a>
                </div>
                <button class="theme-toggle" id="themeToggle" title="Toggle dark mode">
                    <span id="themeIcon">🌙</span>
                </button>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="page-header">
            <h2>Selamat datang kembali</h2>
            <p>Berikut adalah ringkasan data geografis Anda</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Total Puskesmas Terpetakan</div>
                <div class="stat-value">22</div>
                <div class="stat-change positive">↑ Kabupaten Tanah Laut</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Prevalensi Stunting 2025</div>
                <div class="stat-value">6.62%</div>
                <div class="stat-change negative">↑ dari 5.55% tahun 2024</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Puskesmas Zona Merah</div>
                <div class="stat-value">6</div>
                <div class="stat-change negative">Prevalensi &gt; 10%</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Total Balita Ditimbang 2025</div>
                <div class="stat-value">25.773</div>
                <div class="stat-change">Data per Maret 2025</div>
            </div>
        </div>

        <div class="content-grid">
            <div class="card">
                <div class="card-title">Peta Stunting Interaktif</div>
                <div class="map-info">
                    📍 Kabupaten Tanah Laut — Data Puskesmas 2020–2025
                </div>

                <!-- Filter Tahun -->
                <div class="filter-bar">
                    <label>🗓️ Filter Tahun:</label>
                    <select id="filterTahun">
                        <option value="2025">2025</option>
                        <option value="2024">2024</option>
                        <option value="2023">2023</option>
                        <option value="2022">2022</option>
                        <option value="2021">2021</option>
                        <option value="2020">2020</option>
                    </select>
                    <span class="filter-legend">
                        🔴 Stunting (&gt;10%) &nbsp;
                        🟡 Sedang (5–10%) &nbsp;
                        🟢 Normal (&lt;5%) &nbsp;
                        ⚪ Data tidak tersedia
                    </span>
                </div>

                <div id="map"></div>

                <div class="action-buttons">
                    <button class="btn btn-primary" onclick="zoomToLocation()">📍 Lokasi Saya</button>
                    <button class="btn" onclick="resetView()">🔄 Reset Peta</button>
                </div>
            </div>

            <div class="card">
                <div class="card-title">Aktivitas Terbaru</div>
                <ul class="recent-activity">
                    <li class="activity-item">
                        <span class="activity-time">2 jam yang lalu</span>
                        Lapisan administrasi diperbarui
                    </li>
                    <li class="activity-item">
                        <span class="activity-time">5 jam yang lalu</span>
                        Pengguna baru: Adi Pratama
                    </li>
                    <li class="activity-item">
                        <span class="activity-time">1 hari yang lalu</span>
                        Data infrastruktur disinkronkan
                    </li>
                    <li class="activity-item">
                        <span class="activity-time">2 hari yang lalu</span>
                        Laporan kelengkapan selesai dibuat
                    </li>
                    <li class="activity-item">
                        <span class="activity-time">3 hari yang lalu</span>
                        Backup data berkala berhasil
                    </li>
                </ul>
            </div>
        </div>

        <div class="card">
            <div class="card-title">Fitur Utama</div>
            <ul class="feature-list">
                <li>Visualisasi peta interaktif dengan multiple layers</li>
                <li>Analisis data spasial dan statistik geografis</li>
                <li>Import dan export dalam format standar GIS</li>
                <li>Filter data stunting per tahun (2020–2025)</li>
                <li>Generate laporan dan dokumentasi komprehensif</li>
                <li>API access untuk integrasi third-party</li>
            </ul>
            <div class="action-buttons">
                <button class="btn btn-primary" onclick="this.textContent = 'Fitur sedang dikembangkan...'">Buka
                    Editor</button>
                <button class="btn" onclick="this.textContent = 'Fitur sedang dikembangkan...'">Dokumentasi</button>
            </div>
        </div>

        <div class="card">
            <div class="card-title">Informasi Sistem</div>
            <p>
                WebGIS adalah sistem manajemen informasi geografis terpadu yang dirancang untuk memenuhi kebutuhan
                pemetaan dan analisis data spasial stunting di Kabupaten Tanah Laut. Platform ini menyediakan tools
                canggih untuk visualisasi data geografis, analisis spasial per tahun, dan monitoring tren stunting
                dari 2020 hingga 2025.
            </p>
            <p style="margin-top: 1rem;">
                Data bersumber dari laporan Puskesmas melalui aplikasi e-PPGBM. Marker pada peta menunjukkan lokasi
                22 Puskesmas di Kabupaten Tanah Laut dengan warna yang mencerminkan tingkat prevalensi stunting
                sesuai tahun yang dipilih.
            </p>
        </div>
    </div>

    <div class="footer">
        <p>WebGIS Stunting Kabupaten Tanah Laut © 2026. All rights reserved.</p>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
    <script>
        let map;
        let markers = [];
        let stuntingData = [];

        const themeToggle = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');
        const htmlElement = document.documentElement;

        // ── Theme ──────────────────────────────────────────────
        function initTheme() {
            const savedTheme = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
                htmlElement.classList.add('dark-mode');
                themeIcon.textContent = '☀️';
            } else {
                htmlElement.classList.remove('dark-mode');
                themeIcon.textContent = '🌙';
            }
        }

        themeToggle.addEventListener('click', () => {
            htmlElement.classList.toggle('dark-mode');
            const isDark = htmlElement.classList.contains('dark-mode');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            themeIcon.textContent = isDark ? '☀️' : '🌙';
        });

        // ── Helpers ────────────────────────────────────────────
        function getIconUrl(prevalensi) {
            if (prevalensi === null || prevalensi === undefined || prevalensi === '') {
                return 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-grey.png';
            } else if (prevalensi > 10) {
                return 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png';
            } else if (prevalensi >= 5) {
                return 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-gold.png';
            } else {
                return 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-green.png';
            }
        }

        function buildPopup(item) {
            let rows = '';
            [2020, 2021, 2022, 2023, 2024, 2025].forEach(y => {
                const b = item['balita_' + y];
                const s = item['stunting_' + y];
                const p = item['prevalensi_' + y];
                const color = p > 10 ? 'red' : p >= 5 ? 'orange' : 'green';
                const icon2 = p > 10 ? '🔴' : p >= 5 ? '🟡' : p ? '🟢' : '⚪';
                rows += '<tr style="text-align:center;border-top:1px solid #eee">' +
                    '<td style="padding:4px 8px;border:1px solid #ddd"><b>' + y + '</b></td>' +
                    '<td style="padding:4px 8px;border:1px solid #ddd">' + (b ? b.toLocaleString('id-ID') : '-') +
                    '</td>' +
                    '<td style="padding:4px 8px;border:1px solid #ddd">' + (s !== null && s !== undefined ? s :
                    '-') + '</td>' +
                    '<td style="padding:4px 8px;border:1px solid #ddd;color:' + (p ? color : '#aaa') +
                    ';font-weight:bold">' +
                    (p ? icon2 + ' ' + p + '%' : '⚪ -') + '</td>' +
                    '</tr>';
            });

            return '<div style="min-width:310px;font-family:sans-serif;font-size:13px">' +
                '<b style="font-size:15px">📍 ' + item.nama + '</b><br>' +
                '<span style="color:#888;font-size:12px">Kecamatan: ' + item.kecamatan + '</span>' +
                '<hr style="margin:6px 0;border-color:#eee">' +
                '<table style="width:100%;border-collapse:collapse">' +
                '<tr style="background:#f5f5f5;font-weight:bold;text-align:center">' +
                '<td style="padding:4px 8px;border:1px solid #ddd">Tahun</td>' +
                '<td style="padding:4px 8px;border:1px solid #ddd">Balita</td>' +
                '<td style="padding:4px 8px;border:1px solid #ddd">Stunting</td>' +
                '<td style="padding:4px 8px;border:1px solid #ddd">Prevalensi</td>' +
                '</tr>' +
                rows +
                '</table>' +
                '<hr style="margin:6px 0;border-color:#eee">' +
                '<div style="text-align:center;font-size:13px">' +
                'Status 2025: <b style="color:' +
                (item.status === 'Stunting' ? 'red' : item.status === 'Stunting Sedang' ? 'orange' : 'green') +
                '">' + item.status + '</b>' +
                '</div></div>';
        }

        function renderMarkers(tahun) {
            // Hapus marker lama
            markers.forEach(m => map.removeLayer(m));
            markers = [];

            stuntingData.forEach(item => {
                const p = item['prevalensi_' + tahun];

                const leafletIcon = L.icon({
                    iconUrl: getIconUrl(p),
                    shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34],
                });

                const marker = L.marker(
                        [parseFloat(item.latitude), parseFloat(item.longitude)], {
                            icon: leafletIcon
                        }
                    )
                    .bindPopup(buildPopup(item), {
                        maxWidth: 360
                    })
                    .addTo(map);

                markers.push(marker);
            });
        }

        // ── Map controls ───────────────────────────────────────
        function zoomToLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    pos => {
                        map.setView([pos.coords.latitude, pos.coords.longitude], 15);
                        L.marker([pos.coords.latitude, pos.coords.longitude])
                            .bindPopup('Lokasi Anda Saat Ini')
                            .addTo(map)
                            .openPopup();
                    },
                    () => alert('Tidak dapat mengakses lokasi Anda')
                );
            }
        }

        function resetView() {
            map.setView([-3.8565, 114.9850], 10);
        }

        // ── Init Map ───────────────────────────────────────────
        function initMap() {
            map = L.map('map').setView([-3.8565, 114.9850], 10);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
                maxZoom: 19,
            }).addTo(map);

            // GeoJSON batas wilayah
            fetch('/geojson/tanah_laut.geojson')
                .then(r => r.json())
                .then(data => {
                    L.geoJSON(data, {
                        style: {
                            color: "#008000",
                            weight: 2,
                            fillColor: "#90EE90",
                            fillOpacity: 0.3
                        }
                    }).addTo(map);
                });

            // Data stunting
            fetch('/api/stuntings')
                .then(r => r.json())
                .then(data => {
                    stuntingData = data;
                    renderMarkers(2025); // default 2025

                    document.getElementById('filterTahun').addEventListener('change', function() {
                        renderMarkers(this.value);
                    });
                });
        }

        // ── Boot ───────────────────────────────────────────────
        document.addEventListener('DOMContentLoaded', () => {
            initTheme();
            initMap();
        });
    </script>
</body>

</html>
