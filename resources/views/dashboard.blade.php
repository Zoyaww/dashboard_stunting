<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebGIS Stunting — Tanah Laut</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
    <style>
        *,
        *::before,
        *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --bg: #f7f8fc;
            --card: #ffffff;
            --border: #e4e7ef;
            --text: #1a1f2e;
            --muted: #6b7280;
            --red: #ef4444;
            --red-bg: #fef2f2;
            --yellow: #f59e0b;
            --yellow-bg: #fffbeb;
            --green: #22c55e;
            --green-bg: #f0fdf4;
            --blue: #3b82f6;
            --nav: #ffffff;
        }

        html.dark {
            --bg: #0f1117;
            --card: #1a1d27;
            --border: #2a2d3a;
            --text: #e8eaf0;
            --muted: #9ca3af;
            --red-bg: #2d1515;
            --yellow-bg: #2d2510;
            --green-bg: #0f2318;
            --nav: #13151f;
        }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: var(--bg);
            color: var(--text);
            font-size: 14px;
            line-height: 1.5;
            transition: background .25s, color .25s;
        }

        /* ── NAV ── */
        .nav {
            background: var(--nav);
            border-bottom: 1px solid var(--border);
            padding: 0 1.5rem;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 200;
        }

        .nav-brand {
            font-weight: 700;
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        .nav-dot {
            width: 8px;
            height: 8px;
            background: var(--blue);
            border-radius: 50%;
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: .75rem;
        }

        .nav-user {
            color: var(--muted);
            font-size: .85rem;
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

        /* ── LAYOUT ── */
        .wrap {
            max-width: 1440px;
            margin: 0 auto;
            padding: 1.25rem 1.5rem;
        }

        /* ── STAT CARDS ── */
        .stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-bottom: 1.25rem;
        }

        .stat {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 1rem 1.2rem;
        }

        .stat-label {
            font-size: .75rem;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: var(--muted);
            margin-bottom: .4rem;
            font-weight: 600;
        }

        .stat-val {
            font-size: 1.75rem;
            font-weight: 700;
            letter-spacing: -1px;
        }

        .stat-sub {
            font-size: .78rem;
            color: var(--muted);
            margin-top: .2rem;
        }

        .stat-sub.up {
            color: var(--red);
        }

        .stat-sub.down {
            color: var(--green);
        }

        /* ── MAP CARD ── */
        .map-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 1.25rem;
        }

        .map-toolbar {
            display: flex;
            align-items: center;
            gap: .75rem;
            flex-wrap: wrap;
            padding: .75rem 1rem;
            border-bottom: 1px solid var(--border);
            background: var(--card);
        }

        .map-toolbar-title {
            font-weight: 600;
            font-size: .95rem;
            margin-right: auto;
        }

        .filter-label {
            font-size: .8rem;
            color: var(--muted);
            font-weight: 500;
        }

        .filter-select {
            padding: .35rem .7rem;
            border-radius: 5px;
            border: 1px solid var(--border);
            background: var(--bg);
            color: var(--text);
            font-size: .82rem;
            cursor: pointer;
        }

        .legend-pills {
            display: flex;
            gap: .5rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .lpill {
            display: flex;
            align-items: center;
            gap: .35rem;
            font-size: .75rem;
            color: var(--muted);
            padding: .25rem .6rem;
            border-radius: 100px;
            border: 1px solid var(--border);
            background: var(--bg);
        }

        .ldot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .ldot.red {
            background: var(--red);
        }

        .ldot.yellow {
            background: var(--yellow);
        }

        .ldot.green {
            background: var(--green);
        }

        .ldot.grey {
            background: #9ca3af;
        }

        #map {
            width: 100%;
            height: 520px;
        }

        .map-footer {
            padding: .6rem 1rem;
            border-top: 1px solid var(--border);
            display: flex;
            gap: .75rem;
            align-items: center;
        }

        .btn-map {
            padding: .4rem .9rem;
            border-radius: 5px;
            border: 1px solid var(--border);
            background: transparent;
            color: var(--text);
            font-size: .82rem;
            font-weight: 500;
            cursor: pointer;
            transition: .15s;
        }

        .btn-map:hover {
            background: var(--border);
        }

        .btn-map.primary {
            background: var(--text);
            color: var(--card);
            border-color: var(--text);
        }

        .btn-map.primary:hover {
            opacity: .85;
        }

        /* ── DATA TABLE ── */
        .table-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 1.25rem;
        }

        .table-header {
            padding: .85rem 1.2rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: .5rem;
        }

        .table-title {
            font-weight: 600;
            font-size: .95rem;
        }

        .table-sub {
            font-size: .78rem;
            color: var(--muted);
        }

        .table-wrap {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: .82rem;
        }

        thead th {
            background: var(--bg);
            padding: .6rem .9rem;
            text-align: left;
            font-weight: 600;
            color: var(--muted);
            font-size: .73rem;
            text-transform: uppercase;
            letter-spacing: .4px;
            border-bottom: 1px solid var(--border);
            white-space: nowrap;
        }

        thead th.center {
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

        td.center {
            text-align: center;
        }

        /* warning badges */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: .3rem;
            padding: .2rem .55rem;
            border-radius: 100px;
            font-size: .72rem;
            font-weight: 600;
        }

        .badge.red {
            background: var(--red-bg);
            color: #b91c1c;
        }

        .badge.yellow {
            background: var(--yellow-bg);
            color: #92400e;
        }

        .badge.green {
            background: var(--green-bg);
            color: #15803d;
        }

        .badge.grey {
            background: var(--border);
            color: var(--muted);
        }

        /* warning rows */
        .warn-red {
            background: var(--red-bg) !important;
        }

        .warn-yellow {
            background: var(--yellow-bg) !important;
        }

        /* mini bar */
        .bar-wrap {
            display: flex;
            align-items: center;
            gap: .5rem;
            min-width: 120px;
        }

        .bar-track {
            flex: 1;
            height: 5px;
            background: var(--border);
            border-radius: 3px;
            overflow: hidden;
        }

        .bar-fill {
            height: 100%;
            border-radius: 3px;
            transition: width .3s;
        }

        .bar-val {
            font-size: .78rem;
            color: var(--muted);
            min-width: 36px;
            text-align: right;
        }

        /* ── RESPONSIVE ── */
        @media (max-width: 1024px) {
            .stats {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 640px) {
            .stats {
                grid-template-columns: 1fr 1fr;
            }

            .map-toolbar {
                flex-direction: column;
                align-items: flex-start;
            }

            .map-toolbar-title {
                margin-right: 0;
            }

            #map {
                height: 380px;
            }
        }
    </style>
</head>

<body>

    <!-- NAV -->
    <nav class="nav">
        <div class="nav-brand">
            <div class="nav-dot"></div>
            WebGIS Stunting — Tanah Laut
        </div>
        <div class="nav-right">
            <span class="nav-user">👤 {{ session('user')['username'] ?? 'User' }}</span>
            <button class="btn-sm" id="themeBtn" title="Toggle dark mode">🌙</button>
            <a href="{{ route('logout') }}" class="btn-sm btn-logout">Logout</a>
        </div>
    </nav>

    <div class="wrap">

        <!-- STAT CARDS -->
        <div class="stats">
            <div class="stat">
                <div class="stat-label">Puskesmas</div>
                <div class="stat-val">22</div>
                <div class="stat-sub">Kabupaten Tanah Laut</div>
            </div>
            <div class="stat">
                <div class="stat-label">Prevalensi Stunting 2025</div>
                <div class="stat-val" style="color:var(--red)">6.62%</div>
                <div class="stat-sub up">↑ dari 5.55% (2024)</div>
            </div>
            <div class="stat">
                <div class="stat-label">Zona Merah (&gt;10%)</div>
                <div class="stat-val" style="color:var(--red)" id="zonaCount">—</div>
                <div class="stat-sub">Puskesmas prevalensi tinggi</div>
            </div>
            <div class="stat">
                <div class="stat-label">Total Balita Ditimbang</div>
                <div class="stat-val">25.773</div>
                <div class="stat-sub">Data Maret 2025</div>
            </div>
        </div>

        <!-- MAP -->
        <div class="map-card">
            <div class="map-toolbar">
                <span class="map-toolbar-title">🗺️ Peta Sebaran Stunting per Puskesmas</span>
                <span class="filter-label">Tahun:</span>
                <select class="filter-select" id="filterTahun">
                    <option value="2025" selected>2025</option>
                    <option value="2024">2024</option>
                    <option value="2023">2023</option>
                </select>
                <div class="legend-pills">
                    <div class="lpill">
                        <div class="ldot red"></div> &gt;10% Tinggi
                    </div>
                    <div class="lpill">
                        <div class="ldot yellow"></div> 5–10% Sedang
                    </div>
                    <div class="lpill">
                        <div class="ldot green"></div> &lt;5% Rendah
                    </div>
                    <div class="lpill">
                        <div class="ldot grey"></div> Tidak ada data
                    </div>
                </div>
            </div>
            <div id="map"></div>
            <div class="map-footer">
                <button class="btn-map primary" onclick="zoomToLocation()">📍 Lokasi Saya</button>
                <button class="btn-map" onclick="resetView()">🔄 Reset</button>
            </div>
        </div>

        <!-- TABLE WARNING -->
        <div class="table-card">
            <div class="table-header">
                <div>
                    <div class="table-title">📊 Data Gizi Balita per Puskesmas — 2025</div>
                    <div class="table-sub">BB/U = Berat Badan/Umur &nbsp;·&nbsp; TB/U = Tinggi Badan/Umur &nbsp;·&nbsp;
                        BB/TB = Berat Badan/Tinggi Badan</div>
                </div>
                <select class="filter-select" id="filterTabel" style="min-width:120px">
                    <option value="all">Semua Puskesmas</option>
                    <option value="red">Zona Merah saja</option>
                    <option value="yellow">Zona Kuning saja</option>
                </select>
            </div>
            <div class="table-wrap">
                <table id="mainTable">
                    <thead>
                        <tr>
                            <th>Puskesmas</th>
                            <th>Kecamatan</th>
                            <th class="center">Balita</th>
                            <th class="center">Status</th>
                            <th class="center">Prev Stunting</th>

                            <!-- BB/U -->
                            <th class="center" style="border-left:2px solid var(--border)">BB/U<br><small>Sgt
                                    Kurang</small></th>
                            <th class="center">BB/U<br><small>Kurang</small></th>
                            <th class="center">BB/U<br><small>Normal</small></th>
                            <th class="center">BB/U<br><small>Risiko Lebih</small></th>

                            <!-- TB/U -->
                            <th class="center" style="border-left:2px solid var(--border)">TB/U<br><small>Sgt
                                    Pendek</small></th>
                            <th class="center">TB/U<br><small>Pendek</small></th>
                            <th class="center">TB/U<br><small>Normal</small></th>
                            <th class="center">TB/U<br><small>Tinggi</small></th>

                            <!-- BB/TB -->
                            <th class="center" style="border-left:2px solid var(--border)">BB/TB<br><small>Gizi
                                    Buruk</small></th>
                            <th class="center">BB/TB<br><small>Gizi Kurang</small></th>
                            <th class="center">BB/TB<br><small>Normal</small></th>
                            <th class="center">BB/TB<br><small>Gizi Lebih</small></th>
                            <th class="center">BB/TB<br><small>Obesitas</small></th>

                            <!-- Summary -->
                            <th class="center" style="border-left:2px solid var(--border)">Stunting</th>
                            <th class="center">Wasting</th>
                            <th class="center">Underweight</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody"></tbody>
                </table>
            </div>
        </div>

    </div><!-- /wrap -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
    <script>
        // ── DATA ──────────────────────────────────────────────────────────────────
        const DATA_2025 = [{
                nama: "PANYIPATAN",
                kec: "Panyipatan",
                lat: -3.8601,
                lng: 114.7002,
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
                kec: "Bati-Bati",
                lat: -3.745,
                lng: 114.682,
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
                kec: "Takisung",
                lat: -3.764,
                lng: 114.752,
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
                kec: "Kurau",
                lat: -3.5234,
                lng: 114.621,
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
                kec: "Tambang Ulang",
                lat: -3.61,
                lng: 114.78,
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
                kec: "Bumi Makmur",
                lat: -3.6855,
                lng: 114.8033,
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
                kec: "Bati-Bati",
                lat: -3.691,
                lng: 114.825,
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
                kec: "Bati-Bati",
                lat: -3.72,
                lng: 114.84,
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
                kec: "Tambang Ulang",
                lat: -3.701,
                lng: 114.82,
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
                kec: "Pelaihari",
                lat: -3.7965,
                lng: 114.7824,
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
                kec: "Pelaihari",
                lat: -3.82,
                lng: 114.76,
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
                kec: "Pelaihari",
                lat: -3.7902,
                lng: 114.7765,
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
                kec: "Bajuin",
                lat: -3.84,
                lng: 114.79,
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
                kec: "Bajuin",
                lat: -3.87,
                lng: 114.81,
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
                kec: "Batu Ampar",
                lat: -3.91,
                lng: 114.85,
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
                kec: "Jorong",
                lat: -3.9044,
                lng: 115.0045,
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
                kec: "Jorong",
                lat: -3.95,
                lng: 115.08,
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
                kec: "Kintap",
                lat: -3.98,
                lng: 115.15,
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
                kec: "Kintap",
                lat: -4.02,
                lng: 115.20,
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
                kec: "Bati-Bati",
                lat: -3.72,
                lng: 114.76,
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
                kec: "Bumi Makmur",
                lat: -3.75,
                lng: 114.79,
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
                kec: "Pelaihari",
                lat: -3.83,
                lng: 114.92,
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
            },
        ];

        // Data ringkasan per tahun (untuk popup — 2023 & 2024 hanya prev)
        const PREV_DATA = {
            2023: {
                PANYIPATAN: 3.17,
                BATAKAN: 3.59,
                TANGKISUNG: 2.70,
                KURAU: 5.83,
                PADANGLUAS: 16.15,
                BUMIMAKMUR: 2.45,
                BATIBATI: 6.09,
                KAITKAIT: 2.79,
                TAMBANGULANG: 3.43,
                PELAIHARI: 3.70,
                SUNGAIRIAM: 7.94,
                ANGSAU: 4.30,
                TANJUNGHABULU: 0.81,
                TIRTAJAYA: 1.38,
                TAJAUPECAH: 5.61,
                JORONG: 5.98,
                ASAMASAM: 9.38,
                KINTAP: 2.53,
                SEICUKA: 2.42,
                BENTOKKAMPUNG: 6.67,
                DURIANBUNGKUK: 2.82,
                PANGGUNG: 5.29
            },
            2024: {
                PANYIPATAN: 15.23,
                BATAKAN: 5.52,
                TANGKISUNG: 1.88,
                KURAU: 7.52,
                PADANGLUAS: 13.88,
                BUMIMAKMUR: 4.70,
                BATIBATI: 5.78,
                KAITKAIT: 2.70,
                TAMBANGULANG: 7.13,
                PELAIHARI: 3.06,
                SUNGAIRIAM: 10.52,
                ANGSAU: 3.36,
                TANJUNGHABULU: 2.29,
                TIRTAJAYA: 2.21,
                TAJAUPECAH: 3.45,
                JORONG: 7.59,
                ASAMASAM: 13.57,
                KINTAP: 2.18,
                SEICUKA: 1.26,
                BENTOKKAMPUNG: 5.70,
                DURIANBUNGKUK: 8.61,
                PANGGUNG: 8.16
            },
        };

        // ── THEME ─────────────────────────────────────────────────────────────────
        const btn = document.getElementById('themeBtn');

        function initTheme() {
            const saved = localStorage.getItem('theme');
            const dark = saved === 'dark' || (!saved && window.matchMedia('(prefers-color-scheme: dark)').matches);
            document.documentElement.classList.toggle('dark', dark);
            btn.textContent = dark ? '☀️' : '🌙';
        }
        btn.addEventListener('click', () => {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            btn.textContent = isDark ? '☀️' : '🌙';
        });
        initTheme();

        // ── MAP ───────────────────────────────────────────────────────────────────
        let map, markers = [];

        function iconUrl(prev) {
            if (prev === null || prev === undefined)
            return 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-grey.png';
            if (prev > 10)
            return 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png';
            if (prev >= 5)
            return 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-gold.png';
            return 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-green.png';
        }

        function statusBadge(prev) {
            if (prev > 10) return '<span style="color:#ef4444;font-weight:700">🔴 Tinggi</span>';
            if (prev >= 5) return '<span style="color:#f59e0b;font-weight:700">🟡 Sedang</span>';
            return '<span style="color:#22c55e;font-weight:700">🟢 Rendah</span>';
        }

        function miniBar(val, max, color) {
            const pct = max ? Math.round(val / max * 100) : 0;
            return `<div class="bar-wrap"><div class="bar-track"><div class="bar-fill" style="width:${pct}%;background:${color}"></div></div><span class="bar-val">${val}</span></div>`;
        }

        function buildPopup(d, tahun) {
            if (tahun == 2025) {
                return `<div style="min-width:320px;font-family:system-ui,sans-serif;font-size:13px">
<b style="font-size:15px">📍 ${d.nama}</b><br>
<span style="color:#6b7280;font-size:12px">Kecamatan ${d.kec} &nbsp;·&nbsp; ${d.balita.toLocaleString('id-ID')} balita</span>
<hr style="margin:8px 0;border-color:#e4e7ef">
<b style="font-size:12px;color:#6b7280">STATUS: ${statusBadge(d.prev)} &nbsp; Prev. ${d.prev}%</b>
<hr style="margin:8px 0;border-color:#e4e7ef">
<table style="width:100%;border-collapse:collapse;font-size:12px">
<tr style="background:#f7f8fc">
  <th style="padding:4px 6px;text-align:left;border:1px solid #e4e7ef" colspan="2">BB/U (Berat Badan/Umur)</th>
</tr>
<tr><td style="padding:3px 6px;border:1px solid #e4e7ef;color:#b91c1c">Sangat Kurang</td><td style="padding:3px 6px;border:1px solid #e4e7ef;text-align:right"><b>${d.bbu_sk}</b></td></tr>
<tr><td style="padding:3px 6px;border:1px solid #e4e7ef;color:#d97706">Kurang</td><td style="padding:3px 6px;border:1px solid #e4e7ef;text-align:right"><b>${d.bbu_k}</b></td></tr>
<tr><td style="padding:3px 6px;border:1px solid #e4e7ef;color:#16a34a">Normal</td><td style="padding:3px 6px;border:1px solid #e4e7ef;text-align:right"><b>${d.bbu_n}</b></td></tr>
<tr><td style="padding:3px 6px;border:1px solid #e4e7ef;color:#6b7280">Risiko Lebih</td><td style="padding:3px 6px;border:1px solid #e4e7ef;text-align:right"><b>${d.bbu_rl}</b></td></tr>
<tr style="background:#f7f8fc">
  <th style="padding:4px 6px;text-align:left;border:1px solid #e4e7ef" colspan="2">TB/U (Tinggi Badan/Umur)</th>
</tr>
<tr><td style="padding:3px 6px;border:1px solid #e4e7ef;color:#b91c1c">Sangat Pendek</td><td style="padding:3px 6px;border:1px solid #e4e7ef;text-align:right"><b>${d.tbu_sp}</b></td></tr>
<tr><td style="padding:3px 6px;border:1px solid #e4e7ef;color:#d97706">Pendek</td><td style="padding:3px 6px;border:1px solid #e4e7ef;text-align:right"><b>${d.tbu_p}</b></td></tr>
<tr><td style="padding:3px 6px;border:1px solid #e4e7ef;color:#16a34a">Normal</td><td style="padding:3px 6px;border:1px solid #e4e7ef;text-align:right"><b>${d.tbu_n}</b></td></tr>
<tr><td style="padding:3px 6px;border:1px solid #e4e7ef;color:#6b7280">Tinggi</td><td style="padding:3px 6px;border:1px solid #e4e7ef;text-align:right"><b>${d.tbu_t}</b></td></tr>
<tr style="background:#f7f8fc">
  <th style="padding:4px 6px;text-align:left;border:1px solid #e4e7ef" colspan="2">BB/TB (Berat Badan/Tinggi Badan)</th>
</tr>
<tr><td style="padding:3px 6px;border:1px solid #e4e7ef;color:#b91c1c">Gizi Buruk</td><td style="padding:3px 6px;border:1px solid #e4e7ef;text-align:right"><b>${d.bbtb_gb}</b></td></tr>
<tr><td style="padding:3px 6px;border:1px solid #e4e7ef;color:#d97706">Gizi Kurang</td><td style="padding:3px 6px;border:1px solid #e4e7ef;text-align:right"><b>${d.bbtb_gk}</b></td></tr>
<tr><td style="padding:3px 6px;border:1px solid #e4e7ef;color:#16a34a">Normal</td><td style="padding:3px 6px;border:1px solid #e4e7ef;text-align:right"><b>${d.bbtb_n}</b></td></tr>
<tr><td style="padding:3px 6px;border:1px solid #e4e7ef;color:#6b7280">Gizi Lebih</td><td style="padding:3px 6px;border:1px solid #e4e7ef;text-align:right"><b>${d.bbtb_gl}</b></td></tr>
<tr><td style="padding:3px 6px;border:1px solid #e4e7ef;color:#6b7280">Obesitas</td><td style="padding:3px 6px;border:1px solid #e4e7ef;text-align:right"><b>${d.bbtb_ob}</b></td></tr>
<tr style="background:#f7f8fc">
  <th style="padding:4px 6px;text-align:left;border:1px solid #e4e7ef" colspan="2">Masalah Gizi</th>
</tr>
<tr><td style="padding:3px 6px;border:1px solid #e4e7ef">Stunting (TB/U)</td><td style="padding:3px 6px;border:1px solid #e4e7ef;text-align:right;color:#ef4444"><b>${d.stunting}</b></td></tr>
<tr><td style="padding:3px 6px;border:1px solid #e4e7ef">Wasting (BB/TB)</td><td style="padding:3px 6px;border:1px solid #e4e7ef;text-align:right;color:#f59e0b"><b>${d.wasting}</b></td></tr>
<tr><td style="padding:3px 6px;border:1px solid #e4e7ef">Underweight (BB/U)</td><td style="padding:3px 6px;border:1px solid #e4e7ef;text-align:right;color:#f59e0b"><b>${d.uw}</b></td></tr>
</table></div>`;
            }

            // 2023/2024 — hanya prev
            const key = d.nama.replace(/\s/g, '').toUpperCase();
            const p = PREV_DATA[tahun] ? PREV_DATA[tahun][key] : null;
            return `<div style="min-width:220px;font-family:system-ui,sans-serif;font-size:13px">
<b style="font-size:15px">📍 ${d.nama}</b><br>
<span style="color:#6b7280;font-size:12px">Kecamatan ${d.kec}</span>
<hr style="margin:8px 0;border-color:#e4e7ef">
<b>Prevalensi Stunting ${tahun}:</b> ${p !== null && p !== undefined ? statusBadge(p) + ' ' + p + '%' : '⚪ Data tidak tersedia'}
<br><br><span style="color:#6b7280;font-size:11px">Data BB/U, TB/U, BB/TB tersedia untuk tahun 2025</span>
</div>`;
        }

        function renderMarkers(tahun) {
            markers.forEach(m => map.removeLayer(m));
            markers = [];

            let redCount = 0;
            DATA_2025.forEach(d => {
                let prev = d.prev;
                if (tahun != 2025) {
                    const key = d.nama.replace(/\s/g, '').toUpperCase();
                    prev = PREV_DATA[tahun] ? PREV_DATA[tahun][key] : null;
                }
                if (prev > 10) redCount++;

                const icon = L.icon({
                    iconUrl: iconUrl(prev),
                    shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34],
                });
                const m = L.marker([d.lat, d.lng], {
                        icon
                    })
                    .bindPopup(buildPopup(d, tahun), {
                        maxWidth: 360,
                        maxHeight: 480
                    })
                    .addTo(map);
                markers.push(m);
            });

            document.getElementById('zonaCount').textContent = redCount;
        }

        function zoomToLocation() {
            if (!navigator.geolocation) return;
            navigator.geolocation.getCurrentPosition(
                p => {
                    map.setView([p.coords.latitude, p.coords.longitude], 15);
                    L.marker([p.coords.latitude, p.coords.longitude]).bindPopup('Lokasi Anda').addTo(map).openPopup();
                },
                () => alert('Tidak dapat mengakses lokasi Anda')
            );
        }

        function resetView() {
            map.setView([-3.8565, 114.985], 10);
        }

        function initMap() {
            map = L.map('map').setView([-3.8565, 114.985], 10);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
                maxZoom: 19
            }).addTo(map);

            fetch('/geojson/tanah_laut.geojson')
                .then(r => r.json())
                .then(data => L.geoJSON(data, {
                    style: {
                        color: '#22c55e',
                        weight: 2,
                        fillColor: '#bbf7d0',
                        fillOpacity: .25
                    }
                }).addTo(map))
                .catch(() => {});

            renderMarkers(2025);

            document.getElementById('filterTahun').addEventListener('change', function() {
                renderMarkers(parseInt(this.value));
            });
        }

        // ── TABLE ─────────────────────────────────────────────────────────────────
        function renderTable(filter) {
            const tbody = document.getElementById('tableBody');
            const rows = DATA_2025
                .filter(d => {
                    if (filter === 'red') return d.prev > 10;
                    if (filter === 'yellow') return d.prev >= 5 && d.prev <= 10;
                    return true;
                })
                .sort((a, b) => b.prev - a.prev);

            tbody.innerHTML = rows.map(d => {
                let cls = '';
                if (d.prev > 10) cls = 'warn-red';
                else if (d.prev >= 5) cls = 'warn-yellow';

                const badgeCls = d.prev > 10 ? 'red' : d.prev >= 5 ? 'yellow' : 'green';
                const badgeTxt = d.prev > 10 ? '🔴 Tinggi' : d.prev >= 5 ? '🟡 Sedang' : '🟢 Rendah';

                return `<tr class="${cls}">
<td><b>${d.nama}</b></td>
<td>${d.kec}</td>
<td class="center">${d.balita.toLocaleString('id-ID')}</td>
<td class="center"><span class="badge ${badgeCls}">${badgeTxt}</span></td>
<td class="center"><b>${d.prev}%</b></td>

<td class="center" style="border-left:2px solid var(--border);color:#b91c1c">${d.bbu_sk}</td>
<td class="center" style="color:#d97706">${d.bbu_k}</td>
<td class="center">${d.bbu_n}</td>
<td class="center" style="color:#6b7280">${d.bbu_rl}</td>

<td class="center" style="border-left:2px solid var(--border);color:#b91c1c">${d.tbu_sp}</td>
<td class="center" style="color:#d97706">${d.tbu_p}</td>
<td class="center">${d.tbu_n}</td>
<td class="center" style="color:#6b7280">${d.tbu_t}</td>

<td class="center" style="border-left:2px solid var(--border);color:#b91c1c">${d.bbtb_gb}</td>
<td class="center" style="color:#d97706">${d.bbtb_gk}</td>
<td class="center">${d.bbtb_n}</td>
<td class="center" style="color:#6b7280">${d.bbtb_gl}</td>
<td class="center" style="color:#6b7280">${d.bbtb_ob}</td>

<td class="center" style="border-left:2px solid var(--border);color:#ef4444"><b>${d.stunting}</b></td>
<td class="center" style="color:#f59e0b"><b>${d.wasting}</b></td>
<td class="center" style="color:#f59e0b"><b>${d.uw}</b></td>
</tr>`;
            }).join('');
        }

        document.getElementById('filterTabel').addEventListener('change', function() {
            renderTable(this.value);
        });

        // ── BOOT ──────────────────────────────────────────────────────────────────
        document.addEventListener('DOMContentLoaded', () => {
            initMap();
            renderTable('all');
        });
    </script>
</body>

</html>
