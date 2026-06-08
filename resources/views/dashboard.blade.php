<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebGIS - Dashboard Kabupaten Tanah Laut</title>
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
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

        #map {
            width: 100%;
            height: 500px;
            border-radius: 6px;
            margin-bottom: 1.5rem;
            border: 1px solid var(--border-color);
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

        .section-divider {
            margin: 2rem 0;
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

        .map-info {
            background: var(--accent-light);
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1rem;
            font-size: 0.9rem;
            color: var(--text-secondary);
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
                <div class="stat-label">Total Lokasi Terpetakan</div>
                <div class="stat-value">1,250</div>
                <div class="stat-change positive">↑ 12% dari bulan lalu</div>
            </div>

            <div class="stat-card">
                <div class="stat-label">Lapisan Peta Aktif</div>
                <div class="stat-value">8</div>
                <div class="stat-change">3 lapisan dalam pengembangan</div>
            </div>

            <div class="stat-card">
                <div class="stat-label">Pengguna Terdaftar</div>
                <div class="stat-value">156</div>
                <div class="stat-change positive">↑ 8 pengguna baru minggu ini</div>
            </div>

            <div class="stat-card">
                <div class="stat-label">Data Terproses</div>
                <div class="stat-value">42.5K</div>
                <div class="stat-change">Pembaruan real-time</div>
            </div>
        </div>

        <div class="content-grid">
            <div class="card">
                <div class="card-title">Peta Interaktif</div>
                <div class="map-info">
                    📍 Menggunakan OpenStreetMap - gratis, open source, tanpa API key
                </div>
                <div id="map"></div>
                <div class="action-buttons">
                    <button class="btn btn-primary" onclick="zoomToLocation()">📍 Lokasi Saya</button>
                    <button class="btn" onclick="addMarker()">📌 Tambah Marker</button>
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
                <li>Search lokasi dan routing dengan presisi tinggi</li>
                <li>Generate laporan dan dokumentasi komprehensif</li>
                <li>API access untuk integrasi third-party</li>
            </ul>
            <div class="action-buttons">
                <button class="btn btn-primary" onclick="this.textContent = 'Fitur sedang dikembangkan...'">Buka Editor</button>
                <button class="btn" onclick="this.textContent = 'Fitur sedang dikembangkan...'">Dokumentasi</button>
            </div>
        </div>

        <div class="card">
            <div class="card-title">Informasi Sistem</div>
            <p>
                WebGIS adalah sistem manajemen informasi geografis terpadu yang dirancang untuk memenuhi kebutuhan pemetaan dan analisis data spasial. Platform ini menyediakan tools canggih untuk visualisasi data geografis, analisis spasial, dan kolaborasi tim dalam satu ekosistem yang terintegrasi.
            </p>
            <p style="margin-top: 1rem;">
                Dengan dukungan untuk berbagai format data GIS standard dan API yang fleksibel, WebGIS memungkinkan integrasi seamless dengan sistem existing Anda. Fitur real-time synchronization memastikan semua data selalu up-to-date di semua platform.
            </p>
        </div>
    </div>

    <div class="footer">
        <p>WebGIS © 2026. All rights reserved.</p>
    </div>

    <!-- Leaflet JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
    <script>
        let map;
        let markerCount = 0;
        const themeToggle = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');
        const htmlElement = document.documentElement;

        // Inisialisasi tema
        function initTheme() {
            const savedTheme = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            
            if (savedTheme) {
                if (savedTheme === 'dark') {
                    htmlElement.classList.add('dark-mode');
                    themeIcon.textContent = '☀️';
                } else {
                    htmlElement.classList.remove('dark-mode');
                    themeIcon.textContent = '🌙';
                }
            } else if (prefersDark) {
                htmlElement.classList.add('dark-mode');
                themeIcon.textContent = '☀️';
            } else {
                htmlElement.classList.remove('dark-mode');
                themeIcon.textContent = '🌙';
            }
        }

        // Toggle tema
        themeToggle.addEventListener('click', () => {
            htmlElement.classList.toggle('dark-mode');
            const isDarkMode = htmlElement.classList.contains('dark-mode');
            localStorage.setItem('theme', isDarkMode ? 'dark' : 'light');
            themeIcon.textContent = isDarkMode ? '☀️' : '🌙';
        });

        // Inisialisasi peta
        function initMap() {
            // Jakarta sebagai default koordinat
            const centerLat = -6.2088;
            const centerLng = 106.8456;

            map = L.map('map').setView([centerLat, centerLng], 13);

            // Tambahkan OpenStreetMap tiles
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
                maxZoom: 19,
            }).addTo(map);

            // Tambahkan beberapa marker contoh
            const locations = [
                { lat: -6.2088, lng: 106.8456, name: 'Jakarta Pusat' },
                { lat: -6.3111, lng: 106.7064, name: 'Jakarta Selatan' },
                { lat: -6.1516, lng: 106.6561, name: 'Jakarta Timur' },
            ];

            locations.forEach(loc => {
                L.marker([loc.lat, loc.lng])
                    .bindPopup(`<strong>${loc.name}</strong>`)
                    .addTo(map);
            });
        }

        // Zoom ke lokasi pengguna
        function zoomToLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    position => {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;
                        map.setView([lat, lng], 15);
                        L.marker([lat, lng])
                            .bindPopup('Lokasi Anda Saat Ini')
                            .addTo(map)
                            .openPopup();
                    },
                    () => {
                        alert('Tidak dapat mengakses lokasi Anda');
                    }
                );
            }
        }

        // Tambah marker
        function addMarker() {
            markerCount++;
            const randomLat = -6.2088 + (Math.random() - 0.5) * 0.3;
            const randomLng = 106.8456 + (Math.random() - 0.5) * 0.3;
            
            L.marker([randomLat, randomLng])
                .bindPopup(`<strong>Marker ${markerCount}</strong><br>Lat: ${randomLat.toFixed(4)}<br>Lng: ${randomLng.toFixed(4)}`)
                .addTo(map)
                .openPopup();
        }

        // Inisialisasi saat halaman dimuat
        document.addEventListener('DOMContentLoaded', () => {
            initTheme();
            initMap();
        });
    </script>
</body>
</html>
