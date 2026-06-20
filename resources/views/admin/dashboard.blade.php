<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — WebGIS Stunting Tanah Laut</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
    <style>
        *,
        *::before,
        *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --bg: #f4f6fb;
            --card: #ffffff;
            --border: #e3e8f0;
            --text: #18202e;
            --muted: #64748b;
            --red: #e53e3e;
            --red-s: #fff0f0;
            --yellow: #d69e2e;
            --yellow-s: #fffbeb;
            --green: #276749;
            --green-s: #f0fff4;
            --blue: #2563eb;
            --blue-s: #eff6ff;
            --nav: #18202e;
            --sidebar: #1e2a3b;
        }

        html.dark {
            --bg: #0d1117;
            --card: #161b27;
            --border: #232d3f;
            --text: #e2e8f0;
            --muted: #94a3b8;
            --red-s: #2d1515;
            --yellow-s: #2a1f00;
            --green-s: #0f2318;
            --blue-s: #0f1e3d;
            --nav: #0d1117;
            --sidebar: #111827;
        }

        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            background: var(--bg);
            color: var(--text);
            font-size: 14px;
            line-height: 1.5;
            display: flex;
            min-height: 100vh;
            transition: background .25s, color .25s;
        }

        /* ── SIDEBAR ── */
        .sidebar {
            width: 220px;
            background: var(--sidebar);
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 100;
            transition: background .25s;
        }

        .sidebar-brand {
            padding: 1.25rem 1.25rem 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, .08);
        }

        .sidebar-brand-name {
            font-size: .95rem;
            font-weight: 700;
            color: #fff;
            letter-spacing: -.2px;
        }

        .sidebar-brand-sub {
            font-size: .72rem;
            color: rgba(255, 255, 255, .4);
            margin-top: .15rem;
        }

        .sidebar-nav {
            padding: .75rem .75rem;
            flex: 1;
        }

        .nav-section-label {
            font-size: .65rem;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: rgba(255, 255, 255, .3);
            padding: .6rem .5rem .3rem;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: .6rem;
            padding: .55rem .75rem;
            border-radius: 6px;
            color: rgba(255, 255, 255, .6);
            font-size: .85rem;
            font-weight: 500;
            text-decoration: none;
            transition: .15s;
            margin-bottom: .15rem;
            cursor: pointer;
        }

        .nav-item:hover {
            background: rgba(255, 255, 255, .07);
            color: #fff;
        }

        .nav-item.active {
            background: var(--blue);
            color: #fff;
        }

        .nav-icon {
            font-size: 1rem;
            width: 20px;
            text-align: center;
            flex-shrink: 0;
        }

        .sidebar-footer {
            padding: .75rem;
            border-top: 1px solid rgba(255, 255, 255, .08);
        }

        .sidebar-user {
            display: flex;
            align-items: center;
            gap: .6rem;
            padding: .5rem .75rem;
            border-radius: 6px;
            color: rgba(255, 255, 255, .55);
            font-size: .8rem;
        }

        /* ── MAIN ── */
        .main {
            margin-left: 220px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* ── TOPBAR ── */
        .topbar {
            background: var(--card);
            border-bottom: 1px solid var(--border);
            padding: 0 1.5rem;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
            transition: background .25s;
        }

        .topbar-title {
            font-weight: 600;
            font-size: 1rem;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: .75rem;
        }

        .btn-sm {
            padding: .35rem .85rem;
            border-radius: 5px;
            font-size: .82rem;
            font-weight: 500;
            cursor: pointer;
            border: 1px solid var(--border);
            background: transparent;
            color: var(--text);
            transition: .15s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-sm:hover {
            background: var(--border);
        }

        .btn-logout {
            background: var(--red);
            color: #fff;
            border-color: var(--red);
        }

        .btn-logout:hover {
            opacity: .85;
            background: var(--red);
        }

        /* ── CONTENT ── */
        .content {
            padding: 1.5rem;
            flex: 1;
        }

        /* ── STAT CARDS ── */
        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .kpi {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 1.1rem 1.2rem;
            position: relative;
            overflow: hidden;
        }

        .kpi::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            border-radius: 10px 10px 0 0;
        }

        .kpi.red::before {
            background: var(--red);
        }

        .kpi.yellow::before {
            background: var(--yellow);
        }

        .kpi.green::before {
            background: #22c55e;
        }

        .kpi.blue::before {
            background: var(--blue);
        }

        .kpi-icon {
            font-size: 1.4rem;
            margin-bottom: .5rem;
        }

        .kpi-val {
            font-size: 1.9rem;
            font-weight: 700;
            letter-spacing: -1px;
            line-height: 1;
            margin-bottom: .3rem;
        }

        .kpi-label {
            font-size: .75rem;
            color: var(--muted);
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: .4px;
        }

        .kpi-sub {
            font-size: .78rem;
            margin-top: .35rem;
        }

        .kpi-sub.up {
            color: var(--red);
        }

        .kpi-sub.down {
            color: #22c55e;
        }

        .kpi-sub.neutral {
            color: var(--muted);
        }

        /* ── FILTER BAR ── */
        .filter-bar {
            display: flex;
            align-items: center;
            gap: .75rem;
            margin-bottom: 1.25rem;
            flex-wrap: wrap;
        }

        .filter-label {
            font-size: .8rem;
            color: var(--muted);
            font-weight: 600;
        }

        .filter-select {
            padding: .38rem .75rem;
            border-radius: 6px;
            border: 1px solid var(--border);
            background: var(--card);
            color: var(--text);
            font-size: .82rem;
            cursor: pointer;
        }

        .filter-badge {
            padding: .3rem .75rem;
            border-radius: 100px;
            font-size: .78rem;
            font-weight: 600;
            cursor: pointer;
            border: 1px solid var(--border);
            background: var(--card);
            color: var(--muted);
            transition: .15s;
        }

        .filter-badge.active {
            background: var(--blue);
            color: #fff;
            border-color: var(--blue);
        }

        /* ── CHART GRID ── */
        .chart-grid-top {
            display: grid;
            grid-template-columns: 1.6fr 1fr;
            gap: 1.25rem;
            margin-bottom: 1.25rem;
        }

        .chart-grid-bottom {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 1.25rem;
            margin-bottom: 1.25rem;
        }

        .chart-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 1.25rem;
        }

        .chart-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 1.1rem;
        }

        .chart-title {
            font-weight: 600;
            font-size: .92rem;
        }

        .chart-sub {
            font-size: .75rem;
            color: var(--muted);
            margin-top: .15rem;
        }

        .chart-badge {
            font-size: .72rem;
            font-weight: 600;
            padding: .2rem .6rem;
            border-radius: 100px;
            background: var(--blue-s);
            color: var(--blue);
        }

        .chart-wrap {
            position: relative;
        }

        /* ── TABLE SECTION ── */
        .table-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 1.25rem;
        }

        .table-head {
            padding: .9rem 1.2rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: .5rem;
        }

        .table-head-title {
            font-weight: 600;
            font-size: .92rem;
        }

        .table-head-sub {
            font-size: .75rem;
            color: var(--muted);
            margin-top: .1rem;
        }

        .table-scroll {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: .82rem;
        }

        thead th {
            background: var(--bg);
            padding: .55rem .9rem;
            text-align: left;
            font-weight: 600;
            color: var(--muted);
            font-size: .71rem;
            text-transform: uppercase;
            letter-spacing: .5px;
            border-bottom: 1px solid var(--border);
            white-space: nowrap;
        }

        thead th.c {
            text-align: center;
        }

        tbody tr {
            border-bottom: 1px solid var(--border);
            transition: background .1s;
        }

        tbody tr:last-child {
            border-bottom: none;
        }

        tbody tr:hover {
            background: var(--bg);
        }

        td {
            padding: .55rem .9rem;
            vertical-align: middle;
            white-space: nowrap;
        }

        td.c {
            text-align: center;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: .3rem;
            padding: .18rem .55rem;
            border-radius: 100px;
            font-size: .71rem;
            font-weight: 600;
        }

        .badge.red {
            background: var(--red-s);
            color: #b91c1c;
        }

        .badge.yellow {
            background: var(--yellow-s);
            color: #92400e;
        }

        .badge.green {
            background: var(--green-s);
            color: #15803d;
        }

        /* ── TREND INDICATOR ── */
        .trend {
            font-size: .75rem;
            font-weight: 600;
        }

        .trend.up {
            color: var(--red);
        }

        .trend.down {
            color: #22c55e;
        }

        .trend.flat {
            color: var(--muted);
        }

        /* ── SPARKLINE ── */
        .sparkline {
            display: inline-block;
            width: 60px;
            height: 24px;
            vertical-align: middle;
        }

        @media (max-width: 1200px) {
            .chart-grid-top {
                grid-template-columns: 1fr;
            }

            .chart-grid-bottom {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 900px) {
            .kpi-grid {
                grid-template-columns: 1fr 1fr;
            }

            .chart-grid-bottom {
                grid-template-columns: 1fr;
            }

            .sidebar {
                transform: translateX(-100%);
            }

            .main {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <div class="sidebar-brand-name">WebGIS Stunting</div>
            <div class="sidebar-brand-sub">Kabupaten Tanah Laut</div>
        </div>
        <nav class="sidebar-nav">
            <div class="nav-section-label">Menu</div>
            <a href="#" class="nav-item active">
                <span class="nav-icon">📊</span> Dashboard
            </a>
            <a href="{{ route('admin.stuntings.index') }}" class="nav-item">
                <span class="nav-icon">📋</span> Data Stunting
            </a>

            <div class="nav-section-label" style="margin-top:.75rem">Akun</div>
            <a href="{{ route('logout') }}" class="nav-item">
                <span class="nav-icon">🚪</span> Logout
            </a>
        </nav>
        <div class="sidebar-footer">
            <div class="sidebar-user">
                <span>👤</span>
                <span>{{ session('user')['username'] ?? 'Admin' }}</span>
            </div>
        </div>
    </aside>

    <!-- MAIN -->
    <div class="main">

        <!-- TOPBAR -->
        <header class="topbar">
            <div class="topbar-title">Dashboard Admin</div>
            <div class="topbar-right">
                <select class="filter-select" id="globalTahun">
                    <option value="2025" selected>2025</option>
                    <option value="2024">2024</option>
                    <option value="2023">2023</option>
                </select>
                <button class="btn-sm" id="themeBtn">🌙</button>
                <a href="{{ route('logout') }}" class="btn-sm btn-logout">Logout</a>
            </div>
        </header>

        <!-- CONTENT -->
        <div class="content">

            <!-- KPI CARDS -->
            <div class="kpi-grid">
                <div class="kpi red">
                    <div class="kpi-icon">🔴</div>
                    <div class="kpi-val" id="kpiZonaMerah">—</div>
                    <div class="kpi-label">Zona Merah (&gt;10%)</div>
                    <div class="kpi-sub up" id="kpiZonaMerahSub">—</div>
                </div>
                <div class="kpi yellow">
                    <div class="kpi-icon">🟡</div>
                    <div class="kpi-val" id="kpiZonaKuning">—</div>
                    <div class="kpi-label">Zona Kuning (5–10%)</div>
                    <div class="kpi-sub neutral" id="kpiZonaKuningPct">—</div>
                </div>
                <div class="kpi green">
                    <div class="kpi-icon">🟢</div>
                    <div class="kpi-val" id="kpiZonaHijau">—</div>
                    <div class="kpi-label">Zona Hijau (&lt;5%)</div>
                    <div class="kpi-sub down" id="kpiZonaHijauPct">—</div>
                </div>
                <div class="kpi blue">
                    <div class="kpi-icon">📍</div>
                    <div class="kpi-val" id="kpiPrev">—</div>
                    <div class="kpi-label">Rata-rata Prevalensi</div>
                    <div class="kpi-sub" id="kpiPrevSub">—</div>
                </div>
            </div>

            <!-- CHART ROW 1: Tren + Ranking -->
            <div class="chart-grid-top">

                <!-- Tren Prevalensi -->
                <div class="chart-card">
                    <div class="chart-header">
                        <div>
                            <div class="chart-title">Tren Prevalensi Stunting 2023–2025</div>
                            <div class="chart-sub">Per Puskesmas · klik legenda untuk sembunyikan</div>
                        </div>
                        <div class="chart-badge">3 Tahun</div>
                    </div>
                    <div class="chart-wrap" style="height:320px">
                        <canvas id="chartTren"></canvas>
                    </div>
                </div>

                <!-- Bar chart stunting vs wasting vs underweight -->
                <div class="chart-card">
                    <div class="chart-header">
                        <div>
                            <div class="chart-title">Masalah Gizi — Total Kab.</div>
                            <div class="chart-sub" id="masalahGiziSub">Tahun 2025</div>
                        </div>
                    </div>
                    <div class="chart-wrap" style="height:320px">
                        <canvas id="chartMasalah"></canvas>
                    </div>
                </div>

            </div>

            <!-- CHART ROW 2: 3 Pie charts -->
            <div class="chart-grid-bottom">
                <div class="chart-card">
                    <div class="chart-header">
                        <div>
                            <div class="chart-title">BB/U — Berat Badan/Umur</div>
                            <div class="chart-sub" id="bbuSub">Distribusi status gizi · 2025</div>
                        </div>
                    </div>
                    <div class="chart-wrap" style="height:240px">
                        <canvas id="chartBBU"></canvas>
                    </div>
                </div>
                <div class="chart-card">
                    <div class="chart-header">
                        <div>
                            <div class="chart-title">TB/U — Tinggi Badan/Umur</div>
                            <div class="chart-sub" id="tbuSub">Distribusi status gizi · 2025</div>
                        </div>
                    </div>
                    <div class="chart-wrap" style="height:240px">
                        <canvas id="chartTBU"></canvas>
                    </div>
                </div>
                <div class="chart-card">
                    <div class="chart-header">
                        <div>
                            <div class="chart-title">BB/TB — Berat Badan/Tinggi Badan</div>
                            <div class="chart-sub" id="bbtbSub">Distribusi status gizi · 2025</div>
                        </div>
                    </div>
                    <div class="chart-wrap" style="height:240px">
                        <canvas id="chartBBTB"></canvas>
                    </div>
                </div>
            </div>

            <!-- RANKING TABLE -->
            <div class="table-card">
                <div class="table-head">
                    <div>
                        <div class="table-head-title">Ranking Puskesmas — Prevalensi Stunting</div>
                        <div class="table-head-sub" id="rankingSub">Diurutkan dari tertinggi · 2025</div>
                    </div>
                </div>
                <div class="table-scroll">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Puskesmas</th>
                                <th>Kecamatan</th>
                                <th class="c">Balita</th>
                                <th class="c">Stunting</th>
                                <th class="c">Wasting</th>
                                <th class="c">Underweight</th>
                                <th class="c">Prev 2023</th>
                                <th class="c">Prev 2024</th>
                                <th class="c">Prev 2025</th>
                                <th class="c">Tren</th>
                                <th class="c">Status</th>
                            </tr>
                        </thead>
                        <tbody id="rankingBody"></tbody>
                    </table>
                </div>
            </div>

        </div><!-- /content -->
    </div><!-- /main -->

    <script>
        // ── DATA ─────────────────────────────────────────────────────────────────
        const KECS = {
            "PANYIPATAN": "Panyipatan",
            "BATAKAN": "Bati-Bati",
            "TANGKISUNG": "Takisung",
            "KURAU": "Kurau",
            "PADANG LUAS": "Tambang Ulang",
            "BUMI MAKMUR": "Bumi Makmur",
            "BATI BATI": "Bati-Bati",
            "KAIT KAIT": "Bati-Bati",
            "TAMBANG ULANG": "Tambang Ulang",
            "PELAIHARI": "Pelaihari",
            "SUNGAI RIAM": "Pelaihari",
            "ANGSAU": "Pelaihari",
            "TANJUNG HABULU": "Bajuin",
            "TIRTA JAYA": "Bajuin",
            "TAJAU PECAH": "Batu Ampar",
            "JORONG": "Jorong",
            "ASAM ASAM": "Jorong",
            "KINTAP": "Kintap",
            "SEI CUKA": "Kintap",
            "BENTOK KAMPUNG": "Bati-Bati",
            "DURIAN BUNGKUK": "Bumi Makmur",
            "PANGGUNG": "Pelaihari"
        };

        const DATA = {
            2025: [{
                    nama: "PANYIPATAN",
                    balita: 877,
                    bbu_sk: 31,
                    bbu_k: 133,
                    bbu_n: 681,
                    bbu_rl: 32,
                    tbu_sp: 40,
                    tbu_p: 97,
                    tbu_n: 740,
                    tbu_t: 0,
                    bbtb_gb: 8,
                    bbtb_gk: 46,
                    bbtb_n: 721,
                    bbtb_gl: 17,
                    bbtb_ob: 17,
                    stunting: 137,
                    wasting: 54,
                    uw: 164,
                    prev: 15.62
                },
                {
                    nama: "BATAKAN",
                    balita: 822,
                    bbu_sk: 26,
                    bbu_k: 146,
                    bbu_n: 634,
                    bbu_rl: 16,
                    tbu_sp: 19,
                    tbu_p: 31,
                    tbu_n: 771,
                    tbu_t: 1,
                    bbtb_gb: 13,
                    bbtb_gk: 78,
                    bbtb_n: 687,
                    bbtb_gl: 7,
                    bbtb_ob: 5,
                    stunting: 50,
                    wasting: 91,
                    uw: 172,
                    prev: 6.08
                },
                {
                    nama: "TANGKISUNG",
                    balita: 2356,
                    bbu_sk: 24,
                    bbu_k: 59,
                    bbu_n: 2111,
                    bbu_rl: 162,
                    tbu_sp: 25,
                    tbu_p: 58,
                    tbu_n: 2240,
                    tbu_t: 23,
                    bbtb_gb: 8,
                    bbtb_gk: 34,
                    bbtb_n: 2103,
                    bbtb_gl: 3,
                    bbtb_ob: 3,
                    stunting: 83,
                    wasting: 42,
                    uw: 83,
                    prev: 3.52
                },
                {
                    nama: "KURAU",
                    balita: 305,
                    bbu_sk: 2,
                    bbu_k: 26,
                    bbu_n: 269,
                    bbu_rl: 8,
                    tbu_sp: 3,
                    tbu_p: 20,
                    tbu_n: 282,
                    tbu_t: 0,
                    bbtb_gb: 1,
                    bbtb_gk: 5,
                    bbtb_n: 283,
                    bbtb_gl: 1,
                    bbtb_ob: 0,
                    stunting: 23,
                    wasting: 6,
                    uw: 28,
                    prev: 7.54
                },
                {
                    nama: "PADANG LUAS",
                    balita: 716,
                    bbu_sk: 22,
                    bbu_k: 118,
                    bbu_n: 543,
                    bbu_rl: 33,
                    tbu_sp: 23,
                    tbu_p: 56,
                    tbu_n: 636,
                    tbu_t: 0,
                    bbtb_gb: 4,
                    bbtb_gk: 64,
                    bbtb_n: 580,
                    bbtb_gl: 16,
                    bbtb_ob: 9,
                    stunting: 79,
                    wasting: 68,
                    uw: 140,
                    prev: 11.03
                },
                {
                    nama: "BUMI MAKMUR",
                    balita: 936,
                    bbu_sk: 13,
                    bbu_k: 65,
                    bbu_n: 847,
                    bbu_rl: 11,
                    tbu_sp: 19,
                    tbu_p: 37,
                    tbu_n: 880,
                    tbu_t: 0,
                    bbtb_gb: 2,
                    bbtb_gk: 29,
                    bbtb_n: 860,
                    bbtb_gl: 6,
                    bbtb_ob: 6,
                    stunting: 56,
                    wasting: 31,
                    uw: 78,
                    prev: 5.98
                },
                {
                    nama: "BATI BATI",
                    balita: 1028,
                    bbu_sk: 11,
                    bbu_k: 84,
                    bbu_n: 889,
                    bbu_rl: 44,
                    tbu_sp: 10,
                    tbu_p: 93,
                    tbu_n: 926,
                    tbu_t: 2,
                    bbtb_gb: 11,
                    bbtb_gk: 47,
                    bbtb_n: 845,
                    bbtb_gl: 24,
                    bbtb_ob: 11,
                    stunting: 103,
                    wasting: 58,
                    uw: 95,
                    prev: 10.02
                },
                {
                    nama: "KAIT KAIT",
                    balita: 562,
                    bbu_sk: 8,
                    bbu_k: 62,
                    bbu_n: 473,
                    bbu_rl: 19,
                    tbu_sp: 6,
                    tbu_p: 12,
                    tbu_n: 544,
                    tbu_t: 0,
                    bbtb_gb: 3,
                    bbtb_gk: 30,
                    bbtb_n: 470,
                    bbtb_gl: 5,
                    bbtb_ob: 8,
                    stunting: 18,
                    wasting: 33,
                    uw: 70,
                    prev: 3.20
                },
                {
                    nama: "TAMBANG ULANG",
                    balita: 1426,
                    bbu_sk: 4,
                    bbu_k: 100,
                    bbu_n: 1264,
                    bbu_rl: 58,
                    tbu_sp: 11,
                    tbu_p: 75,
                    tbu_n: 1339,
                    tbu_t: 1,
                    bbtb_gb: 0,
                    bbtb_gk: 33,
                    bbtb_n: 1291,
                    bbtb_gl: 11,
                    bbtb_ob: 9,
                    stunting: 86,
                    wasting: 33,
                    uw: 104,
                    prev: 6.03
                },
                {
                    nama: "PELAIHARI",
                    balita: 2127,
                    bbu_sk: 7,
                    bbu_k: 70,
                    bbu_n: 2011,
                    bbu_rl: 39,
                    tbu_sp: 9,
                    tbu_p: 47,
                    tbu_n: 2071,
                    tbu_t: 0,
                    bbtb_gb: 2,
                    bbtb_gk: 29,
                    bbtb_n: 1927,
                    bbtb_gl: 9,
                    bbtb_ob: 5,
                    stunting: 56,
                    wasting: 31,
                    uw: 77,
                    prev: 2.63
                },
                {
                    nama: "SUNGAI RIAM",
                    balita: 493,
                    bbu_sk: 11,
                    bbu_k: 60,
                    bbu_n: 404,
                    bbu_rl: 18,
                    tbu_sp: 15,
                    tbu_p: 37,
                    tbu_n: 440,
                    tbu_t: 0,
                    bbtb_gb: 1,
                    bbtb_gk: 28,
                    bbtb_n: 426,
                    bbtb_gl: 9,
                    bbtb_ob: 3,
                    stunting: 52,
                    wasting: 29,
                    uw: 71,
                    prev: 10.55
                },
                {
                    nama: "ANGSAU",
                    balita: 2039,
                    bbu_sk: 29,
                    bbu_k: 89,
                    bbu_n: 1567,
                    bbu_rl: 354,
                    tbu_sp: 60,
                    tbu_p: 19,
                    tbu_n: 1951,
                    tbu_t: 9,
                    bbtb_gb: 5,
                    bbtb_gk: 66,
                    bbtb_n: 1537,
                    bbtb_gl: 75,
                    bbtb_ob: 17,
                    stunting: 79,
                    wasting: 71,
                    uw: 118,
                    prev: 3.87
                },
                {
                    nama: "TANJUNG HABULU",
                    balita: 512,
                    bbu_sk: 0,
                    bbu_k: 22,
                    bbu_n: 489,
                    bbu_rl: 1,
                    tbu_sp: 0,
                    tbu_p: 15,
                    tbu_n: 496,
                    tbu_t: 1,
                    bbtb_gb: 0,
                    bbtb_gk: 26,
                    bbtb_n: 476,
                    bbtb_gl: 0,
                    bbtb_ob: 0,
                    stunting: 15,
                    wasting: 26,
                    uw: 22,
                    prev: 2.93
                },
                {
                    nama: "TIRTA JAYA",
                    balita: 941,
                    bbu_sk: 9,
                    bbu_k: 94,
                    bbu_n: 797,
                    bbu_rl: 41,
                    tbu_sp: 9,
                    tbu_p: 20,
                    tbu_n: 912,
                    tbu_t: 0,
                    bbtb_gb: 2,
                    bbtb_gk: 63,
                    bbtb_n: 751,
                    bbtb_gl: 14,
                    bbtb_ob: 6,
                    stunting: 29,
                    wasting: 65,
                    uw: 103,
                    prev: 3.08
                },
                {
                    nama: "TAJAU PECAH",
                    balita: 931,
                    bbu_sk: 12,
                    bbu_k: 58,
                    bbu_n: 803,
                    bbu_rl: 58,
                    tbu_sp: 10,
                    tbu_p: 51,
                    tbu_n: 867,
                    tbu_t: 2,
                    bbtb_gb: 3,
                    bbtb_gk: 36,
                    bbtb_n: 770,
                    bbtb_gl: 28,
                    bbtb_ob: 16,
                    stunting: 61,
                    wasting: 39,
                    uw: 70,
                    prev: 6.55
                },
                {
                    nama: "JORONG",
                    balita: 1070,
                    bbu_sk: 8,
                    bbu_k: 73,
                    bbu_n: 966,
                    bbu_rl: 23,
                    tbu_sp: 9,
                    tbu_p: 69,
                    tbu_n: 994,
                    tbu_t: 0,
                    bbtb_gb: 4,
                    bbtb_gk: 40,
                    bbtb_n: 973,
                    bbtb_gl: 11,
                    bbtb_ob: 7,
                    stunting: 78,
                    wasting: 44,
                    uw: 81,
                    prev: 7.29
                },
                {
                    nama: "ASAM ASAM",
                    balita: 1798,
                    bbu_sk: 38,
                    bbu_k: 186,
                    bbu_n: 1506,
                    bbu_rl: 68,
                    tbu_sp: 55,
                    tbu_p: 180,
                    tbu_n: 1558,
                    tbu_t: 1,
                    bbtb_gb: 19,
                    bbtb_gk: 83,
                    bbtb_n: 1518,
                    bbtb_gl: 40,
                    bbtb_ob: 18,
                    stunting: 235,
                    wasting: 102,
                    uw: 224,
                    prev: 13.07
                },
                {
                    nama: "KINTAP",
                    balita: 2077,
                    bbu_sk: 20,
                    bbu_k: 71,
                    bbu_n: 1860,
                    bbu_rl: 126,
                    tbu_sp: 21,
                    tbu_p: 51,
                    tbu_n: 1997,
                    tbu_t: 6,
                    bbtb_gb: 13,
                    bbtb_gk: 34,
                    bbtb_n: 1822,
                    bbtb_gl: 66,
                    bbtb_ob: 32,
                    stunting: 72,
                    wasting: 47,
                    uw: 91,
                    prev: 3.47
                },
                {
                    nama: "SEI CUKA",
                    balita: 955,
                    bbu_sk: 2,
                    bbu_k: 177,
                    bbu_n: 772,
                    bbu_rl: 4,
                    tbu_sp: 1,
                    tbu_p: 29,
                    tbu_n: 918,
                    tbu_t: 3,
                    bbtb_gb: 12,
                    bbtb_gk: 104,
                    bbtb_n: 837,
                    bbtb_gl: 0,
                    bbtb_ob: 0,
                    stunting: 30,
                    wasting: 116,
                    uw: 179,
                    prev: 3.14
                },
                {
                    nama: "BENTOK KAMPUNG",
                    balita: 1581,
                    bbu_sk: 29,
                    bbu_k: 143,
                    bbu_n: 1352,
                    bbu_rl: 57,
                    tbu_sp: 30,
                    tbu_p: 156,
                    tbu_n: 1392,
                    tbu_t: 0,
                    bbtb_gb: 21,
                    bbtb_gk: 81,
                    bbtb_n: 1295,
                    bbtb_gl: 39,
                    bbtb_ob: 9,
                    stunting: 186,
                    wasting: 102,
                    uw: 172,
                    prev: 11.76
                },
                {
                    nama: "DURIAN BUNGKUK",
                    balita: 1178,
                    bbu_sk: 18,
                    bbu_k: 138,
                    bbu_n: 957,
                    bbu_rl: 65,
                    tbu_sp: 23,
                    tbu_p: 81,
                    tbu_n: 1072,
                    tbu_t: 2,
                    bbtb_gb: 22,
                    bbtb_gk: 67,
                    bbtb_n: 973,
                    bbtb_gl: 21,
                    bbtb_ob: 30,
                    stunting: 104,
                    wasting: 89,
                    uw: 156,
                    prev: 8.83
                },
                {
                    nama: "PANGGUNG",
                    balita: 1043,
                    bbu_sk: 15,
                    bbu_k: 82,
                    bbu_n: 907,
                    bbu_rl: 39,
                    tbu_sp: 16,
                    tbu_p: 59,
                    tbu_n: 967,
                    tbu_t: 1,
                    bbtb_gb: 4,
                    bbtb_gk: 48,
                    bbtb_n: 870,
                    bbtb_gl: 23,
                    bbtb_ob: 10,
                    stunting: 75,
                    wasting: 52,
                    uw: 97,
                    prev: 7.19
                }
            ],
            2024: [{
                    nama: "PANYIPATAN",
                    balita: 860,
                    bbu_sk: 25,
                    bbu_k: 129,
                    bbu_n: 667,
                    bbu_rl: 39,
                    tbu_sp: 35,
                    tbu_p: 96,
                    tbu_n: 729,
                    tbu_t: 0,
                    bbtb_gb: 7,
                    bbtb_gk: 63,
                    bbtb_n: 709,
                    bbtb_gl: 17,
                    bbtb_ob: 13,
                    stunting: 131,
                    wasting: 70,
                    uw: 154,
                    prev: 15.23
                },
                {
                    nama: "BATAKAN",
                    balita: 870,
                    bbu_sk: 21,
                    bbu_k: 129,
                    bbu_n: 701,
                    bbu_rl: 19,
                    tbu_sp: 18,
                    tbu_p: 30,
                    tbu_n: 821,
                    tbu_t: 1,
                    bbtb_gb: 15,
                    bbtb_gk: 69,
                    bbtb_n: 749,
                    bbtb_gl: 5,
                    bbtb_ob: 1,
                    stunting: 48,
                    wasting: 84,
                    uw: 150,
                    prev: 5.52
                },
                {
                    nama: "TANGKISUNG",
                    balita: 2608,
                    bbu_sk: 24,
                    bbu_k: 42,
                    bbu_n: 2458,
                    bbu_rl: 84,
                    tbu_sp: 22,
                    tbu_p: 27,
                    tbu_n: 2551,
                    tbu_t: 8,
                    bbtb_gb: 4,
                    bbtb_gk: 48,
                    bbtb_n: 2463,
                    bbtb_gl: 6,
                    bbtb_ob: 2,
                    stunting: 49,
                    wasting: 52,
                    uw: 66,
                    prev: 1.88
                },
                {
                    nama: "KURAU",
                    balita: 306,
                    bbu_sk: 6,
                    bbu_k: 26,
                    bbu_n: 266,
                    bbu_rl: 8,
                    tbu_sp: 4,
                    tbu_p: 19,
                    tbu_n: 283,
                    tbu_t: 0,
                    bbtb_gb: 1,
                    bbtb_gk: 14,
                    bbtb_n: 280,
                    bbtb_gl: 1,
                    bbtb_ob: 1,
                    stunting: 23,
                    wasting: 15,
                    uw: 32,
                    prev: 7.52
                },
                {
                    nama: "PADANG LUAS",
                    balita: 771,
                    bbu_sk: 34,
                    bbu_k: 138,
                    bbu_n: 557,
                    bbu_rl: 42,
                    tbu_sp: 36,
                    tbu_p: 71,
                    tbu_n: 663,
                    tbu_t: 0,
                    bbtb_gb: 7,
                    bbtb_gk: 80,
                    bbtb_n: 602,
                    bbtb_gl: 18,
                    bbtb_ob: 13,
                    stunting: 107,
                    wasting: 87,
                    uw: 172,
                    prev: 13.88
                },
                {
                    nama: "BUMI MAKMUR",
                    balita: 1064,
                    bbu_sk: 26,
                    bbu_k: 156,
                    bbu_n: 859,
                    bbu_rl: 23,
                    tbu_sp: 22,
                    tbu_p: 28,
                    tbu_n: 1014,
                    tbu_t: 0,
                    bbtb_gb: 9,
                    bbtb_gk: 96,
                    bbtb_n: 920,
                    bbtb_gl: 10,
                    bbtb_ob: 5,
                    stunting: 50,
                    wasting: 105,
                    uw: 182,
                    prev: 4.70
                },
                {
                    nama: "BATI BATI",
                    balita: 1281,
                    bbu_sk: 27,
                    bbu_k: 149,
                    bbu_n: 1048,
                    bbu_rl: 57,
                    tbu_sp: 16,
                    tbu_p: 58,
                    tbu_n: 1207,
                    tbu_t: 0,
                    bbtb_gb: 16,
                    bbtb_gk: 123,
                    bbtb_n: 1046,
                    bbtb_gl: 21,
                    bbtb_ob: 23,
                    stunting: 74,
                    wasting: 139,
                    uw: 176,
                    prev: 5.78
                },
                {
                    nama: "KAIT KAIT",
                    balita: 592,
                    bbu_sk: 3,
                    bbu_k: 32,
                    bbu_n: 544,
                    bbu_rl: 13,
                    tbu_sp: 1,
                    tbu_p: 15,
                    tbu_n: 576,
                    tbu_t: 0,
                    bbtb_gb: 1,
                    bbtb_gk: 16,
                    bbtb_n: 540,
                    bbtb_gl: 4,
                    bbtb_ob: 4,
                    stunting: 16,
                    wasting: 17,
                    uw: 35,
                    prev: 2.70
                },
                {
                    nama: "TAMBANG ULANG",
                    balita: 1445,
                    bbu_sk: 35,
                    bbu_k: 206,
                    bbu_n: 1139,
                    bbu_rl: 65,
                    tbu_sp: 38,
                    tbu_p: 65,
                    tbu_n: 1342,
                    tbu_t: 0,
                    bbtb_gb: 0,
                    bbtb_gk: 92,
                    bbtb_n: 1235,
                    bbtb_gl: 29,
                    bbtb_ob: 17,
                    stunting: 103,
                    wasting: 92,
                    uw: 241,
                    prev: 7.13
                },
                {
                    nama: "PELAIHARI",
                    balita: 2223,
                    bbu_sk: 14,
                    bbu_k: 92,
                    bbu_n: 2090,
                    bbu_rl: 27,
                    tbu_sp: 23,
                    tbu_p: 45,
                    tbu_n: 2154,
                    tbu_t: 0,
                    bbtb_gb: 4,
                    bbtb_gk: 28,
                    bbtb_n: 2076,
                    bbtb_gl: 19,
                    bbtb_ob: 0,
                    stunting: 68,
                    wasting: 32,
                    uw: 106,
                    prev: 3.06
                },
                {
                    nama: "SUNGAI RIAM",
                    balita: 523,
                    bbu_sk: 12,
                    bbu_k: 63,
                    bbu_n: 422,
                    bbu_rl: 26,
                    tbu_sp: 15,
                    tbu_p: 40,
                    tbu_n: 468,
                    tbu_t: 0,
                    bbtb_gb: 1,
                    bbtb_gk: 25,
                    bbtb_n: 443,
                    bbtb_gl: 7,
                    bbtb_ob: 8,
                    stunting: 55,
                    wasting: 26,
                    uw: 75,
                    prev: 10.52
                },
                {
                    nama: "ANGSAU",
                    balita: 2112,
                    bbu_sk: 20,
                    bbu_k: 92,
                    bbu_n: 1902,
                    bbu_rl: 98,
                    tbu_sp: 21,
                    tbu_p: 50,
                    tbu_n: 2032,
                    tbu_t: 8,
                    bbtb_gb: 15,
                    bbtb_gk: 72,
                    bbtb_n: 1933,
                    bbtb_gl: 18,
                    bbtb_ob: 12,
                    stunting: 71,
                    wasting: 87,
                    uw: 112,
                    prev: 3.36
                },
                {
                    nama: "TANJUNG HABULU",
                    balita: 524,
                    bbu_sk: 0,
                    bbu_k: 13,
                    bbu_n: 511,
                    bbu_rl: 0,
                    tbu_sp: 0,
                    tbu_p: 12,
                    tbu_n: 512,
                    tbu_t: 0,
                    bbtb_gb: 0,
                    bbtb_gk: 14,
                    bbtb_n: 510,
                    bbtb_gl: 0,
                    bbtb_ob: 0,
                    stunting: 12,
                    wasting: 14,
                    uw: 13,
                    prev: 2.29
                },
                {
                    nama: "TIRTA JAYA",
                    balita: 950,
                    bbu_sk: 4,
                    bbu_k: 74,
                    bbu_n: 830,
                    bbu_rl: 42,
                    tbu_sp: 2,
                    tbu_p: 19,
                    tbu_n: 929,
                    tbu_t: 0,
                    bbtb_gb: 0,
                    bbtb_gk: 56,
                    bbtb_n: 787,
                    bbtb_gl: 19,
                    bbtb_ob: 7,
                    stunting: 21,
                    wasting: 56,
                    uw: 78,
                    prev: 2.21
                },
                {
                    nama: "TAJAU PECAH",
                    balita: 956,
                    bbu_sk: 9,
                    bbu_k: 26,
                    bbu_n: 864,
                    bbu_rl: 57,
                    tbu_sp: 10,
                    tbu_p: 23,
                    tbu_n: 920,
                    tbu_t: 2,
                    bbtb_gb: 3,
                    bbtb_gk: 21,
                    bbtb_n: 798,
                    bbtb_gl: 26,
                    bbtb_ob: 18,
                    stunting: 33,
                    wasting: 24,
                    uw: 35,
                    prev: 3.45
                },
                {
                    nama: "JORONG",
                    balita: 1186,
                    bbu_sk: 18,
                    bbu_k: 100,
                    bbu_n: 1033,
                    bbu_rl: 35,
                    tbu_sp: 26,
                    tbu_p: 64,
                    tbu_n: 1096,
                    tbu_t: 0,
                    bbtb_gb: 6,
                    bbtb_gk: 37,
                    bbtb_n: 1074,
                    bbtb_gl: 12,
                    bbtb_ob: 7,
                    stunting: 90,
                    wasting: 43,
                    uw: 118,
                    prev: 7.59
                },
                {
                    nama: "ASAM ASAM",
                    balita: 1688,
                    bbu_sk: 33,
                    bbu_k: 227,
                    bbu_n: 1351,
                    bbu_rl: 77,
                    tbu_sp: 53,
                    tbu_p: 176,
                    tbu_n: 1459,
                    tbu_t: 0,
                    bbtb_gb: 14,
                    bbtb_gk: 82,
                    bbtb_n: 1388,
                    bbtb_gl: 50,
                    bbtb_ob: 17,
                    stunting: 229,
                    wasting: 96,
                    uw: 260,
                    prev: 13.57
                },
                {
                    nama: "KINTAP",
                    balita: 2481,
                    bbu_sk: 28,
                    bbu_k: 50,
                    bbu_n: 2352,
                    bbu_rl: 51,
                    tbu_sp: 25,
                    tbu_p: 29,
                    tbu_n: 2421,
                    tbu_t: 2,
                    bbtb_gb: 12,
                    bbtb_gk: 35,
                    bbtb_n: 2327,
                    bbtb_gl: 20,
                    bbtb_ob: 7,
                    stunting: 54,
                    wasting: 47,
                    uw: 78,
                    prev: 2.18
                },
                {
                    nama: "SEI CUKA",
                    balita: 1347,
                    bbu_sk: 3,
                    bbu_k: 75,
                    bbu_n: 1221,
                    bbu_rl: 48,
                    tbu_sp: 1,
                    tbu_p: 16,
                    tbu_n: 1271,
                    tbu_t: 33,
                    bbtb_gb: 1,
                    bbtb_gk: 32,
                    bbtb_n: 1311,
                    bbtb_gl: 0,
                    bbtb_ob: 1,
                    stunting: 17,
                    wasting: 33,
                    uw: 78,
                    prev: 1.26
                },
                {
                    nama: "BENTOK KAMPUNG",
                    balita: 1578,
                    bbu_sk: 29,
                    bbu_k: 118,
                    bbu_n: 1382,
                    bbu_rl: 49,
                    tbu_sp: 28,
                    tbu_p: 62,
                    tbu_n: 1482,
                    tbu_t: 2,
                    bbtb_gb: 30,
                    bbtb_gk: 89,
                    bbtb_n: 1325,
                    bbtb_gl: 18,
                    bbtb_ob: 12,
                    stunting: 90,
                    wasting: 119,
                    uw: 147,
                    prev: 5.70
                },
                {
                    nama: "DURIAN BUNGKUK",
                    balita: 1243,
                    bbu_sk: 27,
                    bbu_k: 96,
                    bbu_n: 1048,
                    bbu_rl: 72,
                    tbu_sp: 16,
                    tbu_p: 91,
                    tbu_n: 1131,
                    tbu_t: 1,
                    bbtb_gb: 24,
                    bbtb_gk: 85,
                    bbtb_n: 1015,
                    bbtb_gl: 23,
                    bbtb_ob: 21,
                    stunting: 107,
                    wasting: 109,
                    uw: 123,
                    prev: 8.61
                },
                {
                    nama: "PANGGUNG",
                    balita: 1066,
                    bbu_sk: 11,
                    bbu_k: 100,
                    bbu_n: 915,
                    bbu_rl: 40,
                    tbu_sp: 20,
                    tbu_p: 67,
                    tbu_n: 978,
                    tbu_t: 1,
                    bbtb_gb: 3,
                    bbtb_gk: 57,
                    bbtb_n: 939,
                    bbtb_gl: 18,
                    bbtb_ob: 6,
                    stunting: 87,
                    wasting: 60,
                    uw: 111,
                    prev: 8.16
                }
            ],
            2023: [{
                    nama: "PANYIPATAN",
                    balita: 1011,
                    bbu_sk: 9,
                    bbu_k: 33,
                    bbu_n: 946,
                    bbu_rl: 23,
                    tbu_sp: 13,
                    tbu_p: 19,
                    tbu_n: 978,
                    tbu_t: 1,
                    bbtb_gb: 0,
                    bbtb_gk: 37,
                    bbtb_n: 865,
                    bbtb_gl: 33,
                    bbtb_ob: 6,
                    stunting: 32,
                    wasting: 37,
                    uw: 42,
                    prev: 3.17
                },
                {
                    nama: "BATAKAN",
                    balita: 1003,
                    bbu_sk: 9,
                    bbu_k: 47,
                    bbu_n: 936,
                    bbu_rl: 11,
                    tbu_sp: 9,
                    tbu_p: 27,
                    tbu_n: 965,
                    tbu_t: 2,
                    bbtb_gb: 3,
                    bbtb_gk: 23,
                    bbtb_n: 946,
                    bbtb_gl: 3,
                    bbtb_ob: 0,
                    stunting: 36,
                    wasting: 26,
                    uw: 56,
                    prev: 3.59
                },
                {
                    nama: "TANGKISUNG",
                    balita: 2405,
                    bbu_sk: 21,
                    bbu_k: 57,
                    bbu_n: 2302,
                    bbu_rl: 25,
                    tbu_sp: 19,
                    tbu_p: 46,
                    tbu_n: 2339,
                    tbu_t: 0,
                    bbtb_gb: 12,
                    bbtb_gk: 54,
                    bbtb_n: 2231,
                    bbtb_gl: 8,
                    bbtb_ob: 6,
                    stunting: 65,
                    wasting: 66,
                    uw: 78,
                    prev: 2.70
                },
                {
                    nama: "KURAU",
                    balita: 309,
                    bbu_sk: 2,
                    bbu_k: 28,
                    bbu_n: 272,
                    bbu_rl: 7,
                    tbu_sp: 2,
                    tbu_p: 16,
                    tbu_n: 291,
                    tbu_t: 0,
                    bbtb_gb: 0,
                    bbtb_gk: 8,
                    bbtb_n: 289,
                    bbtb_gl: 1,
                    bbtb_ob: 1,
                    stunting: 18,
                    wasting: 8,
                    uw: 30,
                    prev: 5.83
                },
                {
                    nama: "PADANG LUAS",
                    balita: 737,
                    bbu_sk: 19,
                    bbu_k: 122,
                    bbu_n: 550,
                    bbu_rl: 46,
                    tbu_sp: 28,
                    tbu_p: 91,
                    tbu_n: 616,
                    tbu_t: 1,
                    bbtb_gb: 16,
                    bbtb_gk: 62,
                    bbtb_n: 581,
                    bbtb_gl: 20,
                    bbtb_ob: 12,
                    stunting: 119,
                    wasting: 78,
                    uw: 141,
                    prev: 16.15
                },
                {
                    nama: "BUMI MAKMUR",
                    balita: 1104,
                    bbu_sk: 5,
                    bbu_k: 69,
                    bbu_n: 1023,
                    bbu_rl: 7,
                    tbu_sp: 5,
                    tbu_p: 22,
                    tbu_n: 1076,
                    tbu_t: 1,
                    bbtb_gb: 3,
                    bbtb_gk: 65,
                    bbtb_n: 1005,
                    bbtb_gl: 4,
                    bbtb_ob: 4,
                    stunting: 27,
                    wasting: 68,
                    uw: 74,
                    prev: 2.45
                },
                {
                    nama: "BATI BATI",
                    balita: 1380,
                    bbu_sk: 15,
                    bbu_k: 106,
                    bbu_n: 1210,
                    bbu_rl: 49,
                    tbu_sp: 9,
                    tbu_p: 75,
                    tbu_n: 1293,
                    tbu_t: 2,
                    bbtb_gb: 7,
                    bbtb_gk: 58,
                    bbtb_n: 1224,
                    bbtb_gl: 22,
                    bbtb_ob: 11,
                    stunting: 84,
                    wasting: 65,
                    uw: 121,
                    prev: 6.09
                },
                {
                    nama: "KAIT KAIT",
                    balita: 538,
                    bbu_sk: 5,
                    bbu_k: 49,
                    bbu_n: 463,
                    bbu_rl: 21,
                    tbu_sp: 3,
                    tbu_p: 12,
                    tbu_n: 522,
                    tbu_t: 1,
                    bbtb_gb: 0,
                    bbtb_gk: 33,
                    bbtb_n: 454,
                    bbtb_gl: 12,
                    bbtb_ob: 2,
                    stunting: 15,
                    wasting: 33,
                    uw: 54,
                    prev: 2.79
                },
                {
                    nama: "TAMBANG ULANG",
                    balita: 1369,
                    bbu_sk: 8,
                    bbu_k: 83,
                    bbu_n: 1223,
                    bbu_rl: 55,
                    tbu_sp: 10,
                    tbu_p: 37,
                    tbu_n: 1321,
                    tbu_t: 1,
                    bbtb_gb: 5,
                    bbtb_gk: 46,
                    bbtb_n: 1195,
                    bbtb_gl: 29,
                    bbtb_ob: 14,
                    stunting: 47,
                    wasting: 51,
                    uw: 91,
                    prev: 3.43
                },
                {
                    nama: "PELAIHARI",
                    balita: 1838,
                    bbu_sk: 16,
                    bbu_k: 77,
                    bbu_n: 1708,
                    bbu_rl: 37,
                    tbu_sp: 15,
                    tbu_p: 53,
                    tbu_n: 1768,
                    tbu_t: 0,
                    bbtb_gb: 3,
                    bbtb_gk: 30,
                    bbtb_n: 1730,
                    bbtb_gl: 19,
                    bbtb_ob: 2,
                    stunting: 68,
                    wasting: 33,
                    uw: 93,
                    prev: 3.70
                },
                {
                    nama: "SUNGAI RIAM",
                    balita: 504,
                    bbu_sk: 8,
                    bbu_k: 55,
                    bbu_n: 408,
                    bbu_rl: 33,
                    tbu_sp: 11,
                    tbu_p: 29,
                    tbu_n: 463,
                    tbu_t: 1,
                    bbtb_gb: 0,
                    bbtb_gk: 21,
                    bbtb_n: 428,
                    bbtb_gl: 7,
                    bbtb_ob: 7,
                    stunting: 40,
                    wasting: 21,
                    uw: 63,
                    prev: 7.94
                },
                {
                    nama: "ANGSAU",
                    balita: 1952,
                    bbu_sk: 14,
                    bbu_k: 57,
                    bbu_n: 1685,
                    bbu_rl: 196,
                    tbu_sp: 34,
                    tbu_p: 50,
                    tbu_n: 1775,
                    tbu_t: 90,
                    bbtb_gb: 13,
                    bbtb_gk: 57,
                    bbtb_n: 1728,
                    bbtb_gl: 22,
                    bbtb_ob: 7,
                    stunting: 84,
                    wasting: 70,
                    uw: 71,
                    prev: 4.30
                },
                {
                    nama: "TANJUNG HABULU",
                    balita: 616,
                    bbu_sk: 0,
                    bbu_k: 6,
                    bbu_n: 609,
                    bbu_rl: 1,
                    tbu_sp: 0,
                    tbu_p: 5,
                    tbu_n: 610,
                    tbu_t: 1,
                    bbtb_gb: 0,
                    bbtb_gk: 11,
                    bbtb_n: 589,
                    bbtb_gl: 0,
                    bbtb_ob: 0,
                    stunting: 5,
                    wasting: 11,
                    uw: 6,
                    prev: 0.81
                },
                {
                    nama: "TIRTA JAYA",
                    balita: 870,
                    bbu_sk: 1,
                    bbu_k: 15,
                    bbu_n: 821,
                    bbu_rl: 33,
                    tbu_sp: 1,
                    tbu_p: 11,
                    tbu_n: 858,
                    tbu_t: 0,
                    bbtb_gb: 0,
                    bbtb_gk: 11,
                    bbtb_n: 714,
                    bbtb_gl: 13,
                    bbtb_ob: 4,
                    stunting: 12,
                    wasting: 11,
                    uw: 16,
                    prev: 1.38
                },
                {
                    nama: "TAJAU PECAH",
                    balita: 944,
                    bbu_sk: 6,
                    bbu_k: 22,
                    bbu_n: 838,
                    bbu_rl: 78,
                    tbu_sp: 7,
                    tbu_p: 46,
                    tbu_n: 890,
                    tbu_t: 1,
                    bbtb_gb: 1,
                    bbtb_gk: 11,
                    bbtb_n: 661,
                    bbtb_gl: 59,
                    bbtb_ob: 8,
                    stunting: 53,
                    wasting: 12,
                    uw: 28,
                    prev: 5.61
                },
                {
                    nama: "JORONG",
                    balita: 1054,
                    bbu_sk: 11,
                    bbu_k: 60,
                    bbu_n: 955,
                    bbu_rl: 28,
                    tbu_sp: 12,
                    tbu_p: 51,
                    tbu_n: 990,
                    tbu_t: 1,
                    bbtb_gb: 5,
                    bbtb_gk: 24,
                    bbtb_n: 935,
                    bbtb_gl: 12,
                    bbtb_ob: 7,
                    stunting: 63,
                    wasting: 29,
                    uw: 71,
                    prev: 5.98
                },
                {
                    nama: "ASAM ASAM",
                    balita: 1888,
                    bbu_sk: 19,
                    bbu_k: 147,
                    bbu_n: 1633,
                    bbu_rl: 89,
                    tbu_sp: 40,
                    tbu_p: 137,
                    tbu_n: 1711,
                    tbu_t: 0,
                    bbtb_gb: 13,
                    bbtb_gk: 42,
                    bbtb_n: 1581,
                    bbtb_gl: 50,
                    bbtb_ob: 17,
                    stunting: 177,
                    wasting: 55,
                    uw: 166,
                    prev: 9.38
                },
                {
                    nama: "KINTAP",
                    balita: 2332,
                    bbu_sk: 31,
                    bbu_k: 59,
                    bbu_n: 2204,
                    bbu_rl: 38,
                    tbu_sp: 21,
                    tbu_p: 38,
                    tbu_n: 2243,
                    tbu_t: 21,
                    bbtb_gb: 29,
                    bbtb_gk: 36,
                    bbtb_n: 2242,
                    bbtb_gl: 2,
                    bbtb_ob: 2,
                    stunting: 59,
                    wasting: 65,
                    uw: 90,
                    prev: 2.53
                },
                {
                    nama: "SEI CUKA",
                    balita: 1569,
                    bbu_sk: 1,
                    bbu_k: 52,
                    bbu_n: 1495,
                    bbu_rl: 21,
                    tbu_sp: 2,
                    tbu_p: 36,
                    tbu_n: 1405,
                    tbu_t: 31,
                    bbtb_gb: 0,
                    bbtb_gk: 93,
                    bbtb_n: 1383,
                    bbtb_gl: 0,
                    bbtb_ob: 0,
                    stunting: 38,
                    wasting: 93,
                    uw: 53,
                    prev: 2.42
                },
                {
                    nama: "BENTOK KAMPUNG",
                    balita: 1633,
                    bbu_sk: 13,
                    bbu_k: 87,
                    bbu_n: 1498,
                    bbu_rl: 35,
                    tbu_sp: 28,
                    tbu_p: 81,
                    tbu_n: 1520,
                    tbu_t: 2,
                    bbtb_gb: 6,
                    bbtb_gk: 48,
                    bbtb_n: 1478,
                    bbtb_gl: 18,
                    bbtb_ob: 8,
                    stunting: 109,
                    wasting: 54,
                    uw: 100,
                    prev: 6.67
                },
                {
                    nama: "DURIAN BUNGKUK",
                    balita: 1171,
                    bbu_sk: 2,
                    bbu_k: 52,
                    bbu_n: 1056,
                    bbu_rl: 61,
                    tbu_sp: 2,
                    tbu_p: 31,
                    tbu_n: 1132,
                    tbu_t: 5,
                    bbtb_gb: 11,
                    bbtb_gk: 39,
                    bbtb_n: 1048,
                    bbtb_gl: 19,
                    bbtb_ob: 13,
                    stunting: 33,
                    wasting: 50,
                    uw: 54,
                    prev: 2.82
                },
                {
                    nama: "PANGGUNG",
                    balita: 1135,
                    bbu_sk: 4,
                    bbu_k: 47,
                    bbu_n: 1031,
                    bbu_rl: 53,
                    tbu_sp: 13,
                    tbu_p: 47,
                    tbu_n: 1070,
                    tbu_t: 5,
                    bbtb_gb: 8,
                    bbtb_gk: 45,
                    bbtb_n: 957,
                    bbtb_gl: 24,
                    bbtb_ob: 8,
                    stunting: 60,
                    wasting: 53,
                    uw: 51,
                    prev: 5.29
                }
            ]
        };

        // ── THEME ─────────────────────────────────────────────────────────────────
        const themeBtn = document.getElementById('themeBtn');

        function initTheme() {
            const dark = localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia(
                '(prefers-color-scheme: dark)').matches);
            document.documentElement.classList.toggle('dark', dark);
            themeBtn.textContent = dark ? '☀️' : '🌙';
        }
        themeBtn.addEventListener('click', () => {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            themeBtn.textContent = isDark ? '☀️' : '🌙';
            updateAllCharts(parseInt(document.getElementById('globalTahun').value));
        });
        initTheme();

        // ── CHART INSTANCES ───────────────────────────────────────────────────────
        let chartTren, chartMasalah, chartBBU, chartTBU, chartBBTB;

        function isDark() {
            return document.documentElement.classList.contains('dark');
        }

        function gridColor() {
            return isDark() ? 'rgba(255,255,255,.07)' : 'rgba(0,0,0,.06)';
        }

        function textColor() {
            return isDark() ? '#94a3b8' : '#64748b';
        }

        // ── TREN CHART ────────────────────────────────────────────────────────────
        function buildTrenChart() {
            const names = DATA[2025].map(d => d.nama.length > 12 ? d.nama.slice(0, 12) + '…' : d.nama);
            const p23 = DATA[2023].map(d => d.prev);
            const p24 = DATA[2024].map(d => d.prev);
            const p25 = DATA[2025].map(d => d.prev);

            if (chartTren) chartTren.destroy();
            chartTren = new Chart(document.getElementById('chartTren'), {
                type: 'line',
                data: {
                    labels: names,
                    datasets: [{
                            label: '2023',
                            data: p23,
                            borderColor: '#94a3b8',
                            backgroundColor: 'rgba(148,163,184,.1)',
                            tension: .35,
                            pointRadius: 3,
                            borderWidth: 1.5
                        },
                        {
                            label: '2024',
                            data: p24,
                            borderColor: '#f59e0b',
                            backgroundColor: 'rgba(245,158,11,.1)',
                            tension: .35,
                            pointRadius: 3,
                            borderWidth: 1.5
                        },
                        {
                            label: '2025',
                            data: p25,
                            borderColor: '#ef4444',
                            backgroundColor: 'rgba(239,68,68,.1)',
                            tension: .35,
                            pointRadius: 4,
                            borderWidth: 2
                        },
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    plugins: {
                        legend: {
                            labels: {
                                color: textColor(),
                                font: {
                                    size: 11
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: ctx => ` ${ctx.dataset.label}: ${ctx.parsed.y}%`,
                                afterBody: (items) => {
                                    const i = items[0].dataIndex;
                                    const p = DATA[2025][i];
                                    if (!p) return;
                                    const d = p.prev - DATA[2023][i].prev;
                                    return [`Δ 2023→2025: ${d > 0 ? '+' : ''}${d.toFixed(2)}%`];
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            ticks: {
                                color: textColor(),
                                font: {
                                    size: 9
                                },
                                maxRotation: 45
                            },
                            grid: {
                                color: gridColor()
                            }
                        },
                        y: {
                            ticks: {
                                color: textColor(),
                                callback: v => v + '%'
                            },
                            grid: {
                                color: gridColor()
                            },
                            title: {
                                display: true,
                                text: 'Prevalensi (%)',
                                color: textColor(),
                                font: {
                                    size: 10
                                }
                            }
                        }
                    }
                }
            });
        }

        // ── MASALAH GIZI CHART ────────────────────────────────────────────────────
        function buildMasalahChart(tahun) {
            const d = DATA[tahun];
            const names = d.map(x => x.nama.length > 10 ? x.nama.slice(0, 10) + '…' : x.nama);

            if (chartMasalah) chartMasalah.destroy();
            chartMasalah = new Chart(document.getElementById('chartMasalah'), {
                type: 'bar',
                data: {
                    labels: names,
                    datasets: [{
                            label: 'Stunting',
                            data: d.map(x => x.stunting),
                            backgroundColor: 'rgba(239,68,68,.8)',
                            borderRadius: 3
                        },
                        {
                            label: 'Wasting',
                            data: d.map(x => x.wasting),
                            backgroundColor: 'rgba(245,158,11,.8)',
                            borderRadius: 3
                        },
                        {
                            label: 'Underweight',
                            data: d.map(x => x.uw),
                            backgroundColor: 'rgba(59,130,246,.8)',
                            borderRadius: 3
                        },
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            labels: {
                                color: textColor(),
                                font: {
                                    size: 10
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            ticks: {
                                color: textColor(),
                                font: {
                                    size: 8
                                },
                                maxRotation: 45
                            },
                            grid: {
                                color: gridColor()
                            },
                            stacked: false
                        },
                        y: {
                            ticks: {
                                color: textColor()
                            },
                            grid: {
                                color: gridColor()
                            }
                        }
                    }
                }
            });
            document.getElementById('masalahGiziSub').textContent = `Tahun ${tahun}`;
        }

        // ── PIE CHARTS ────────────────────────────────────────────────────────────
        function sum(arr, key) {
            return arr.reduce((a, d) => a + (d[key] || 0), 0);
        }

        function buildPieCharts(tahun) {
            const d = DATA[tahun];

            const pieOpts = (labels, data, colors) => ({
                type: 'doughnut',
                data: {
                    labels,
                    datasets: [{
                        data,
                        backgroundColor: colors,
                        borderWidth: 1,
                        borderColor: isDark() ? '#161b27' : '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '62%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: textColor(),
                                font: {
                                    size: 10
                                },
                                padding: 8,
                                boxWidth: 10
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: ctx => ` ${ctx.label}: ${ctx.parsed.toLocaleString('id-ID')} balita`
                            }
                        }
                    }
                }
            });

            if (chartBBU) chartBBU.destroy();
            chartBBU = new Chart(document.getElementById('chartBBU'), pieOpts(
                ['Sangat Kurang', 'Kurang', 'Normal', 'Risiko Lebih'],
                [sum(d, 'bbu_sk'), sum(d, 'bbu_k'), sum(d, 'bbu_n'), sum(d, 'bbu_rl')],
                ['#ef4444', '#f59e0b', '#22c55e', '#94a3b8']
            ));

            if (chartTBU) chartTBU.destroy();
            chartTBU = new Chart(document.getElementById('chartTBU'), pieOpts(
                ['Sangat Pendek', 'Pendek', 'Normal', 'Tinggi'],
                [sum(d, 'tbu_sp'), sum(d, 'tbu_p'), sum(d, 'tbu_n'), sum(d, 'tbu_t')],
                ['#ef4444', '#f59e0b', '#22c55e', '#94a3b8']
            ));

            if (chartBBTB) chartBBTB.destroy();
            chartBBTB = new Chart(document.getElementById('chartBBTB'), pieOpts(
                ['Gizi Buruk', 'Gizi Kurang', 'Normal', 'Gizi Lebih', 'Obesitas'],
                [sum(d, 'bbtb_gb'), sum(d, 'bbtb_gk'), sum(d, 'bbtb_n'), sum(d, 'bbtb_gl'), sum(d, 'bbtb_ob')],
                ['#ef4444', '#f59e0b', '#22c55e', '#94a3b8', '#6366f1']
            ));

            document.getElementById('bbuSub').textContent = `Distribusi status gizi · ${tahun}`;
            document.getElementById('tbuSub').textContent = `Distribusi status gizi · ${tahun}`;
            document.getElementById('bbtbSub').textContent = `Distribusi status gizi · ${tahun}`;
        }

        // ── KPI CARDS ─────────────────────────────────────────────────────────────
        function updateKPI(tahun) {
            const d = DATA[tahun];
            const merah = d.filter(x => x.prev > 10).length;
            const kuning = d.filter(x => x.prev >= 5 && x.prev <= 10).length;
            const hijau = d.filter(x => x.prev < 5).length;
            const avgPrev = (d.reduce((a, x) => a + x.prev, 0) / d.length).toFixed(2);

            document.getElementById('kpiZonaMerah').textContent = merah;
            document.getElementById('kpiZonaKuning').textContent = kuning;
            document.getElementById('kpiZonaHijau').textContent = hijau;
            document.getElementById('kpiPrev').textContent = avgPrev + '%';
            document.getElementById('kpiZonaMerahSub').textContent = `${((merah/22)*100).toFixed(0)}% dari total Puskesmas`;
            document.getElementById('kpiZonaKuningPct').textContent = `${kuning} Puskesmas`;
            document.getElementById('kpiZonaHijauPct').textContent = `${hijau} Puskesmas`;

            // Tren avg vs tahun sebelumnya
            if (tahun > 2023) {
                const prev = DATA[tahun - 1];
                const avgPrev2 = (prev.reduce((a, x) => a + x.prev, 0) / prev.length).toFixed(2);
                const delta = (parseFloat(avgPrev) - parseFloat(avgPrev2)).toFixed(2);
                const sub = document.getElementById('kpiPrevSub');
                sub.textContent = `${delta > 0 ? '↑' : '↓'} ${Math.abs(delta)}% vs ${tahun-1}`;
                sub.className = 'kpi-sub ' + (delta > 0 ? 'up' : 'down');
            } else {
                document.getElementById('kpiPrevSub').textContent = `Tahun ${tahun}`;
                document.getElementById('kpiPrevSub').className = 'kpi-sub neutral';
            }
        }

        // ── RANKING TABLE ─────────────────────────────────────────────────────────
        function updateRanking(tahun) {
            const d = [...DATA[tahun]].sort((a, b) => b.prev - a.prev);
            const tbody = document.getElementById('rankingBody');
            document.getElementById('rankingSub').textContent = `Diurutkan dari tertinggi · ${tahun}`;

            tbody.innerHTML = d.map((row, i) => {
                const p23 = DATA[2023].find(x => x.nama === row.nama)?.prev ?? '—';
                const p24 = DATA[2024].find(x => x.nama === row.nama)?.prev ?? '—';
                const p25 = DATA[2025].find(x => x.nama === row.nama)?.prev ?? '—';

                // Tren 2023→tahun
                const pCur = DATA[tahun].find(x => x.nama === row.nama)?.prev;
                const pPrev = tahun > 2023 ? DATA[tahun - 1].find(x => x.nama === row.nama)?.prev : null;
                let tren = '<span class="trend flat">—</span>';
                if (pPrev !== null && pPrev !== undefined) {
                    const d2 = pCur - pPrev;
                    tren =
                        `<span class="trend ${d2 > 0.5 ? 'up' : d2 < -0.5 ? 'down' : 'flat'}">${d2 > 0 ? '↑' : d2 < 0 ? '↓' : '→'} ${Math.abs(d2).toFixed(2)}%</span>`;
                }

                const bc = row.prev > 10 ? 'red' : row.prev >= 5 ? 'yellow' : 'green';
                const bt = row.prev > 10 ? '🔴 Tinggi' : row.prev >= 5 ? '🟡 Sedang' : '🟢 Rendah';
                const rowCls = row.prev > 10 ? 'warn-red' : row.prev >= 5 ? 'warn-yellow' : '';

                return `<tr class="${rowCls}" style="${row.prev>10?'background:var(--red-s)':row.prev>=5?'background:var(--yellow-s)':''}">
<td><b>${i+1}</b></td>
<td><b>${row.nama}</b></td>
<td>${KECS[row.nama]||'-'}</td>
<td class="c">${row.balita.toLocaleString('id-ID')}</td>
<td class="c" style="color:#ef4444;font-weight:600">${row.stunting}</td>
<td class="c" style="color:#f59e0b;font-weight:600">${row.wasting}</td>
<td class="c" style="color:#3b82f6;font-weight:600">${row.uw}</td>
<td class="c">${p23}%</td>
<td class="c">${p24}%</td>
<td class="c"><b>${p25}%</b></td>
<td class="c">${tren}</td>
<td class="c"><span class="badge ${bc}">${bt}</span></td>
</tr>`;
            }).join('');
        }

        // ── UPDATE ALL ─────────────────────────────────────────────────────────────
        function updateAllCharts(tahun) {
            updateKPI(tahun);
            buildMasalahChart(tahun);
            buildPieCharts(tahun);
            updateRanking(tahun);
        }

        // ── BOOT ──────────────────────────────────────────────────────────────────
        document.addEventListener('DOMContentLoaded', () => {
            buildTrenChart();
            updateAllCharts(2025);

            document.getElementById('globalTahun').addEventListener('change', function() {
                updateAllCharts(parseInt(this.value));
            });
        });
    </script>
</body>

</html>
