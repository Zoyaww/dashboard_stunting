<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - WebGIS</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
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
        }

        .navbar {
            background-color: var(--navbar-bg);
            border-bottom: 1px solid var(--border-color);
            padding: 1.25rem 2rem;
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

        .btn-logout {
            padding: 0.5rem 1rem;
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9rem;
            text-decoration: none;
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
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
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
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .stat-label {
            font-size: 0.875rem;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        .stat-value {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        .card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 6px;
            padding: 2rem;
            margin-bottom: 2rem;
            transition: all 0.2s ease;
        }

        .card:hover {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.95rem;
            text-decoration: none;
            display: inline-block;
            transition: all 0.2s ease;
        }

        .btn-primary {
            background-color: #007bff;
            color: white;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-success {
            background-color: #28a745;
            color: white;
        }

        .btn-success:hover {
            background-color: #218838;
        }

        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1.5rem;
            border: 1px solid #c3e6cb;
        }

        .feature-list {
            list-style: none;
        }

        .feature-list li {
            padding: 0.75rem 0;
            border-bottom: 1px solid var(--accent-light);
            color: var(--text-secondary);
        }

        .feature-list li:last-child {
            border-bottom: none;
        }

        .feature-list li::before {
            content: '✓ ';
            color: #28a745;
            font-weight: bold;
            margin-right: 0.5rem;
        }

        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .card-title {
                flex-direction: column;
                align-items: flex-start;
            }

            .page-header h2 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="navbar-content">
            <h1>WebGIS Admin</h1>
            <div class="navbar-right">
                <span style="color: var(--text-secondary);">👤 {{ session('user')['username'] ?? 'Admin' }}</span>
                <a href="{{ route('logout') }}" class="btn-logout">Logout</a>
                <button class="theme-toggle" id="themeToggle">🌙</button>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="page-header">
            <h2>📊 Admin Dashboard</h2>
        </div>

        @if (session('success'))
            <div class="success-message">
                ✓ {{ session('success') }}
            </div>
        @endif

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Total Lokasi</div>
                <div class="stat-value">{{ $totalLocations }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Lokasi Aktif</div>
                <div class="stat-value">{{ $activeLocations }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Kategori</div>
                <div class="stat-value">{{ $categories->count() }}</div>
            </div>
        </div>

        <div class="card">
            <div class="card-title">
                <span>📍 Manajemen Lokasi</span>
                <a href="/admin/locations/create" class="btn btn-success">+ Tambah Lokasi</a>
            </div>
            <ul class="feature-list">
                <li>Kelola semua data lokasi geografis</li>
                <li>Tambah, edit, dan hapus lokasi</li>
                <li>Tentukan kategori dan status</li>
                <li>Koordinat latitude & longitude</li>
                <li>Sinkronisasi otomatis ke peta</li>
            </ul>
            <div style="margin-top: 1.5rem;">
                <a href="/admin/locations" class="btn btn-primary">Kelola Lokasi →</a>
            </div>
        </div>

        <div class="card">
            <div class="card-title">
                <span>🎯 Fitur Admin</span>
            </div>
            <ul class="feature-list">
                <li>CRUD lengkap untuk data lokasi</li>
                <li>Validasi data real-time</li>
                <li>Pagination otomatis</li>
                <li>Export & import data (coming soon)</li>
                <li>Analytics & reporting (coming soon)</li>
            </ul>
        </div>
    </div>

    <script>
        const themeToggle = document.getElementById('themeToggle');
        const htmlElement = document.documentElement;

        function initTheme() {
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme === 'dark') {
                htmlElement.classList.add('dark-mode');
                themeToggle.textContent = '☀️';
            }
        }

        themeToggle.addEventListener('click', () => {
            htmlElement.classList.toggle('dark-mode');
            const isDark = htmlElement.classList.contains('dark-mode');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            themeToggle.textContent = isDark ? '☀️' : '🌙';
        });

        initTheme();
    </script>
</body>
</html>
