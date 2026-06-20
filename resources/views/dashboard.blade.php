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
            --card: #fff;
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
            --nav: #fff;
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
            font-family: 'Segoe UI', system-ui, sans-serif;
            background: var(--bg);
            color: var(--text);
            font-size: 14px;
            line-height: 1.5;
            transition: background .25s, color .25s;
        }

        /* NAV */
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

        /* LAYOUT */
        .wrap {
            max-width: 1440px;
            margin: 0 auto;
            padding: 1.25rem 1.5rem;
        }

        /* STATS */
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
            font-size: .73rem;
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

        /* MAP */
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
        }

        .map-title {
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

        /* TABLE */
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
            gap: .75rem;
        }

        .table-title {
            font-weight: 600;
            font-size: .95rem;
        }

        .table-sub {
            font-size: .75rem;
            color: var(--muted);
            margin-top: .2rem;
        }

        .table-controls {
            display: flex;
            gap: .5rem;
            align-items: center;
            flex-wrap: wrap;
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
            font-size: .72rem;
            text-transform: uppercase;
            letter-spacing: .4px;
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

        .sep {
            border-left: 2px solid var(--border) !important;
        }

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

        .warn-red {
            background: var(--red-bg) !important;
        }

        .warn-yellow {
            background: var(--yellow-bg) !important;
        }

        .c-red {
            color: #b91c1c;
        }

        .c-orange {
            color: #d97706;
        }

        .c-green {
            color: #16a34a;
        }

        .c-muted {
            color: var(--muted);
        }

        /* TABS */
        .tabs {
            display: flex;
            gap: 0;
            border-bottom: 1px solid var(--border);
        }

        .tab {
            padding: .45rem 1rem;
            font-size: .82rem;
            font-weight: 500;
            color: var(--muted);
            cursor: pointer;
            border-bottom: 2px solid transparent;
            margin-bottom: -1px;
            transition: .15s;
            background: none;
            border-top: none;
            border-left: none;
            border-right: none;
        }

        .tab.active {
            color: var(--blue);
            border-bottom-color: var(--blue);
        }

        .tab:hover:not(.active) {
            color: var(--text);
        }

        @media(max-width:1024px) {
            .stats {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media(max-width:640px) {
            .stats {
                grid-template-columns: 1fr 1fr;
            }

            #map {
                height: 380px;
            }

            .map-toolbar {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>

<body>

    <nav class="nav">
        <div class="nav-brand">
            <div class="nav-dot"></div>WebGIS Stunting — Tanah Laut
        </div>
        <div class="nav-right">
            <span class="nav-user">👤 {{ session('user')['username'] ?? 'User' }}</span>
            <button class="btn-sm" id="themeBtn">🌙</button>
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
                <div class="stat-sub">Tahun yang dipilih</div>
            </div>
            <div class="stat">
                <div class="stat-label">Total Balita 2025</div>
                <div class="stat-val">25.773</div>
                <div class="stat-sub">Data Maret 2025</div>
            </div>
        </div>

        <!-- MAP -->
        <div class="map-card">
            <div class="map-toolbar">
                <span class="map-title">🗺️ Peta Sebaran Stunting per Puskesmas</span>
                <span class="filter-label">Tahun:</span>
                <select class="filter-select" id="filterTahun">
                    <option value="2025" selected>2025</option>
                    <option value="2024">2024</option>
                    <option value="2023">2023</option>
                </select>
                <div class="legend-pills">
                    <div class="lpill">
                        <div class="ldot red"></div>&gt;10% Tinggi
                    </div>
                    <div class="lpill">
                        <div class="ldot yellow"></div>5–10% Sedang
                    </div>
                    <div class="lpill">
                        <div class="ldot green"></div>&lt;5% Rendah
                    </div>
                </div>
            </div>
            <div id="map"></div>
            <div class="map-footer">
                <button class="btn-map primary" onclick="zoomToLocation()">📍 Lokasi Saya</button>
                <button class="btn-map" onclick="resetView()">🔄 Reset</button>
            </div>
        </div>

        <!-- DATA TABLE -->
        <div class="table-card">
            <div class="table-header">
                <div>
                    <div class="table-title">📊 Data Gizi Balita per Puskesmas</div>
                    <div class="table-sub">BB/U = Berat Badan/Umur &nbsp;·&nbsp; TB/U = Tinggi Badan/Umur &nbsp;·&nbsp;
                        BB/TB = Berat Badan/Tinggi Badan &nbsp;·&nbsp; Diurutkan dari prevalensi tertinggi</div>
                </div>
                <div class="table-controls">
                    <select class="filter-select" id="filterTahunTabel">
                        <option value="2025" selected>2025</option>
                        <option value="2024">2024</option>
                        <option value="2023">2023</option>
                    </select>
                    <select class="filter-select" id="filterZona">
                        <option value="all">Semua</option>
                        <option value="red">Zona Merah saja</option>
                        <option value="yellow">Zona Kuning saja</option>
                        <option value="green">Zona Hijau saja</option>
                    </select>
                </div>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Puskesmas</th>
                            <th>Kecamatan</th>
                            <th class="c">Balita</th>
                            <th class="c">Status</th>
                            <th class="c">Prev Stunting</th>
                            <!-- BB/U -->
                            <th class="c sep">BB/U Sgt Kurang</th>
                            <th class="c">BB/U Kurang</th>
                            <th class="c">BB/U Normal</th>
                            <th class="c">BB/U Risiko Lebih</th>
                            <!-- TB/U -->
                            <th class="c sep">TB/U Sgt Pendek</th>
                            <th class="c">TB/U Pendek</th>
                            <th class="c">TB/U Normal</th>
                            <th class="c">TB/U Tinggi</th>
                            <!-- BB/TB -->
                            <th class="c sep">BB/TB Gizi Buruk</th>
                            <th class="c">BB/TB Gizi Kurang</th>
                            <th class="c">BB/TB Normal</th>
                            <th class="c">BB/TB Gizi Lebih</th>
                            <th class="c">BB/TB Obesitas</th>
                            <!-- Masalah -->
                            <th class="c sep">Stunting</th>
                            <th class="c">Wasting</th>
                            <th class="c">Underweight</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody"></tbody>
                </table>
            </div>
        </div>

    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
    <script>
        // ── DATA ─────────────────────────────────────────────────────────────────
        const COORDS = {
            "PANYIPATAN": {
                kec: "Panyipatan",
                lat: -3.9612116,
                lng: 114.7203451
            },
            "BATAKAN": {
                kec: "Bati-Bati",
                lat: -4.0839072,
                lng: 114.6364547
            },
            "TANGKISUNG": {
                kec: "Takisung",
                lat: -3.8779557,
                lng: 114.6600400
            },
            "KURAU": {
                kec: "Kurau",
                lat: -3.5927085,
                lng: 114.6186152
            },
            "PADANG LUAS": {
                kec: "Tambang Ulang",
                lat: -3.6242257,
                lng: 114.6230569
            },
            "BUMI MAKMUR": {
                kec: "Bumi Makmur",
                lat: -3.5626736,
                lng: 114.6305945
            },
            "BATI BATI": {
                kec: "Bati-Bati",
                lat: -3.5982349,
                lng: 114.7041123
            },
            "KAIT KAIT": {
                kec: "Bati-Bati",
                lat: -3.5917088,
                lng: 114.8038168
            },
            "TAMBANG ULANG": {
                kec: "Tambang Ulang",
                lat: -3.6921140,
                lng: 114.7284487
            },
            "PELAIHARI": {
                kec: "Pelaihari",
                lat: -3.8071561,
                lng: 114.7601430
            },
            "SUNGAI RIAM": {
                kec: "Pelaihari",
                lat: -3.9000122,
                lng: 114.7289771
            },
            "ANGSAU": {
                kec: "Pelaihari",
                lat: -3.7987574,
                lng: 114.7823621
            },
            "TANJUNG HABULU": {
                kec: "Bajuin",
                lat: -3.7118778,
                lng: 114.8814850
            },
            "TIRTA JAYA": {
                kec: "Bajuin",
                lat: -3.7984260,
                lng: 114.8180264
            },
            "TAJAU PECAH": {
                kec: "Batu Ampar",
                lat: -3.8842628,
                lng: 114.8236465
            },
            "JORONG": {
                kec: "Jorong",
                lat: -3.9745951,
                lng: 114.9252116
            },
            "ASAM ASAM": {
                kec: "Jorong",
                lat: -3.9016548,
                lng: 115.0905449
            },
            "KINTAP": {
                kec: "Kintap",
                lat: -3.8645801,
                lng: 115.2077282
            },
            "SEI CUKA": {
                kec: "Kintap",
                lat: -3.8405329,
                lng: 115.3318267
            },
            "BENTOK KAMPUNG": {
                kec: "Bati-Bati",
                lat: -3.5407699,
                lng: 114.7677541
            },
            "DURIAN BUNGKUK": {
                kec: "Bumi Makmur",
                lat: -3.9124641,
                lng: 114.8681949
            },
            "PANGGUNG": {
                kec: "Pelaihari",
                lat: -3.7502049,
                lng: 114.7654142
            },
        };

        const DATA_2025 = [{
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
                bbtb_rgl: 68,
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
                bbtb_rgl: 32,
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
                bbtb_rgl: 206,
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
                bbtb_rgl: 13,
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
                bbtb_rgl: 42,
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
                bbtb_rgl: 33,
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
                bbtb_rgl: 89,
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
                bbtb_rgl: 45,
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
                bbtb_rgl: 81,
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
                bbtb_rgl: 154,
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
                bbtb_rgl: 25,
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
                bbtb_rgl: 337,
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
                bbtb_rgl: 10,
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
                bbtb_rgl: 105,
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
                bbtb_rgl: 75,
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
                bbtb_rgl: 32,
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
                bbtb_rgl: 118,
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
                bbtb_rgl: 108,
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
                bbtb_rgl: 1,
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
                bbtb_rgl: 135,
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
                bbtb_rgl: 65,
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
                bbtb_rgl: 88,
                bbtb_gl: 23,
                bbtb_ob: 10,
                stunting: 75,
                wasting: 52,
                uw: 97,
                prev: 7.19
            }
        ];

        const DATA_2024 = [{
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
                bbtb_rgl: 51,
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
                bbtb_rgl: 31,
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
                bbtb_rgl: 82,
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
                bbtb_rgl: 9,
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
                bbtb_rgl: 48,
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
                bbtb_rgl: 24,
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
                bbtb_rgl: 50,
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
                bbtb_rgl: 27,
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
                bbtb_rgl: 72,
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
                bbtb_rgl: 96,
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
                bbtb_rgl: 39,
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
                bbtb_rgl: 60,
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
                bbtb_rgl: 0,
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
                bbtb_rgl: 80,
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
                bbtb_rgl: 87,
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
                bbtb_rgl: 50,
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
                bbtb_rgl: 135,
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
                bbtb_rgl: 79,
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
                bbtb_rgl: 1,
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
                bbtb_rgl: 101,
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
                bbtb_rgl: 70,
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
                bbtb_rgl: 43,
                bbtb_gl: 18,
                bbtb_ob: 6,
                stunting: 87,
                wasting: 60,
                uw: 111,
                prev: 8.16
            }
        ];

        const DATA_2023 = [{
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
                bbtb_rgl: 70,
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
                bbtb_rgl: 28,
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
                bbtb_rgl: 94,
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
                bbtb_rgl: 10,
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
                bbtb_rgl: 43,
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
                bbtb_rgl: 23,
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
                bbtb_rgl: 55,
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
                bbtb_rgl: 37,
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
                bbtb_rgl: 80,
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
                bbtb_rgl: 50,
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
                bbtb_rgl: 41,
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
                bbtb_rgl: 120,
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
                bbtb_rgl: 16,
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
                bbtb_rgl: 128,
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
                bbtb_rgl: 203,
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
                bbtb_rgl: 71,
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
                bbtb_rgl: 185,
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
                bbtb_rgl: 19,
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
                bbtb_rgl: 0,
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
                bbtb_rgl: 74,
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
                bbtb_rgl: 39,
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
                bbtb_rgl: 93,
                bbtb_gl: 24,
                bbtb_ob: 8,
                stunting: 60,
                wasting: 53,
                uw: 51,
                prev: 5.29
            }
        ];

        const ALL_DATA = {
            2025: DATA_2025,
            2024: DATA_2024,
            2023: DATA_2023
        };

        // ── THEME ────────────────────────────────────────────────────────────────
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
        });
        initTheme();

        // ── MAP ──────────────────────────────────────────────────────────────────
        let map, markers = [];

        function iconUrl(prev) {
            if (prev > 10)
            return 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png';
            if (prev >= 5)
            return 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-gold.png';
            return 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-green.png';
        }

        function statusLabel(prev) {
            if (prev > 10) return `<span style="color:#ef4444;font-weight:700">🔴 Tinggi (${prev}%)</span>`;
            if (prev >= 5) return `<span style="color:#f59e0b;font-weight:700">🟡 Sedang (${prev}%)</span>`;
            return `<span style="color:#22c55e;font-weight:700">🟢 Rendah (${prev}%)</span>`;
        }

        function buildPopup(d, kec) {
            // Kumpulkan data semua tahun untuk puskesmas ini
            const rows = [2023, 2024, 2025].map(y => {
                const found = ALL_DATA[y].find(x => x.nama === d.nama);
                if (!found)
                return `<tr><td colspan="20" style="text-align:center;color:#9ca3af;padding:4px 8px">${y}: data tidak tersedia</td></tr>`;
                const f = found;
                const sc = (v, cls) =>
                    `<td class="${cls}" style="padding:3px 6px;border:1px solid #e4e7ef;text-align:center">${v}</td>`;
                return `<tr style="background:${y===2025?'#f0fdf4':y===2024?'#fffbeb':'#f7f8fc'}">
            <td style="padding:3px 6px;border:1px solid #e4e7ef;font-weight:600">${y}</td>
            <td style="padding:3px 6px;border:1px solid #e4e7ef;text-align:center">${f.balita.toLocaleString('id-ID')}</td>
            ${sc(f.bbu_sk,'c-red')}${sc(f.bbu_k,'c-orange')}${sc(f.bbu_n,'c-green')}${sc(f.bbu_rl,'c-muted')}
            ${sc(f.tbu_sp,'c-red')}${sc(f.tbu_p,'c-orange')}${sc(f.tbu_n,'c-green')}${sc(f.tbu_t,'c-muted')}
            ${sc(f.bbtb_gb,'c-red')}${sc(f.bbtb_gk,'c-orange')}${sc(f.bbtb_n,'c-green')}${sc(f.bbtb_gl,'c-muted')}${sc(f.bbtb_ob,'c-muted')}
            <td style="padding:3px 6px;border:1px solid #e4e7ef;text-align:center;color:#ef4444;font-weight:600">${f.stunting}</td>
            <td style="padding:3px 6px;border:1px solid #e4e7ef;text-align:center;color:#f59e0b;font-weight:600">${f.wasting}</td>
            <td style="padding:3px 6px;border:1px solid #e4e7ef;text-align:center;color:#f59e0b;font-weight:600">${f.uw}</td>
            <td style="padding:3px 6px;border:1px solid #e4e7ef;text-align:center">${statusLabel(f.prev)}</td>
        </tr>`;
            }).join('');

            return `<div style="min-width:700px;font-family:system-ui,sans-serif;font-size:12px">
<b style="font-size:14px">📍 ${d.nama}</b> &nbsp;<span style="color:#6b7280">Kecamatan ${kec}</span>
<hr style="margin:6px 0;border-color:#e4e7ef">
<table style="width:100%;border-collapse:collapse;font-size:11px">
<thead>
<tr style="background:#f7f8fc">
  <th style="padding:4px 6px;border:1px solid #ddd;text-align:left">Tahun</th>
  <th style="padding:4px 6px;border:1px solid #ddd;text-align:center">Balita</th>
  <th colspan="4" style="padding:4px 6px;border:1px solid #ddd;text-align:center;background:#fef2f2">BB/U</th>
  <th colspan="4" style="padding:4px 6px;border:1px solid #ddd;text-align:center;background:#eff6ff">TB/U</th>
  <th colspan="5" style="padding:4px 6px;border:1px solid #ddd;text-align:center;background:#f0fdf4">BB/TB</th>
  <th colspan="3" style="padding:4px 6px;border:1px solid #ddd;text-align:center;background:#fffbeb">Masalah</th>
  <th style="padding:4px 6px;border:1px solid #ddd;text-align:center">Status</th>
</tr>
<tr style="background:#f7f8fc;font-size:10px">
  <th style="border:1px solid #ddd;padding:2px 6px"></th>
  <th style="border:1px solid #ddd;padding:2px 6px"></th>
  <th style="border:1px solid #ddd;padding:2px 6px;text-align:center;color:#b91c1c">Sgt Kurang</th>
  <th style="border:1px solid #ddd;padding:2px 6px;text-align:center;color:#d97706">Kurang</th>
  <th style="border:1px solid #ddd;padding:2px 6px;text-align:center;color:#16a34a">Normal</th>
  <th style="border:1px solid #ddd;padding:2px 6px;text-align:center;color:#6b7280">Risiko Lebih</th>
  <th style="border:1px solid #ddd;padding:2px 6px;text-align:center;color:#b91c1c">Sgt Pendek</th>
  <th style="border:1px solid #ddd;padding:2px 6px;text-align:center;color:#d97706">Pendek</th>
  <th style="border:1px solid #ddd;padding:2px 6px;text-align:center;color:#16a34a">Normal</th>
  <th style="border:1px solid #ddd;padding:2px 6px;text-align:center;color:#6b7280">Tinggi</th>
  <th style="border:1px solid #ddd;padding:2px 6px;text-align:center;color:#b91c1c">Gizi Buruk</th>
  <th style="border:1px solid #ddd;padding:2px 6px;text-align:center;color:#d97706">Gizi Kurang</th>
  <th style="border:1px solid #ddd;padding:2px 6px;text-align:center;color:#16a34a">Normal</th>
  <th style="border:1px solid #ddd;padding:2px 6px;text-align:center;color:#6b7280">Gizi Lebih</th>
  <th style="border:1px solid #ddd;padding:2px 6px;text-align:center;color:#6b7280">Obesitas</th>
  <th style="border:1px solid #ddd;padding:2px 6px;text-align:center;color:#ef4444">Stunting</th>
  <th style="border:1px solid #ddd;padding:2px 6px;text-align:center;color:#f59e0b">Wasting</th>
  <th style="border:1px solid #ddd;padding:2px 6px;text-align:center;color:#f59e0b">Underweight</th>
  <th style="border:1px solid #ddd;padding:2px 6px;text-align:center">Prevalensi</th>
</tr>
</thead>
<tbody>${rows}</tbody>
</table></div>`;
        }

        function renderMarkers(tahun) {
            markers.forEach(m => map.removeLayer(m));
            markers = [];
            const data = ALL_DATA[tahun] || DATA_2025;
            let redCount = 0;
            data.forEach(d => {
                const coord = COORDS[d.nama];
                if (!coord) return;
                if (d.prev > 10) redCount++;
                const icon = L.icon({
                    iconUrl: iconUrl(d.prev),
                    shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34]
                });
                const m = L.marker([coord.lat, coord.lng], {
                        icon
                    })
                    .bindPopup(buildPopup(d, coord.kec), {
                        maxWidth: 750,
                        maxHeight: 500
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
                () => alert('Tidak dapat mengakses lokasi')
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
            fetch('/geojson/tanah_laut.geojson').then(r => r.json()).then(data => {
                L.geoJSON(data, {
                    style: {
                        color: '#22c55e',
                        weight: 2,
                        fillColor: '#bbf7d0',
                        fillOpacity: .25
                    }
                }).addTo(map);
            }).catch(() => {});
            renderMarkers(2025);
            document.getElementById('filterTahun').addEventListener('change', function() {
                renderMarkers(parseInt(this.value));
            });
        }

        // ── TABLE ────────────────────────────────────────────────────────────────
        function renderTable(tahun, zona) {
            const data = (ALL_DATA[tahun] || DATA_2025)
                .filter(d => {
                    if (zona === 'red') return d.prev > 10;
                    if (zona === 'yellow') return d.prev >= 5 && d.prev <= 10;
                    if (zona === 'green') return d.prev < 5;
                    return true;
                })
                .sort((a, b) => b.prev - a.prev);

            const tbody = document.getElementById('tableBody');
            tbody.innerHTML = data.map(d => {
                const cls = d.prev > 10 ? 'warn-red' : d.prev >= 5 ? 'warn-yellow' : '';
                const bc = d.prev > 10 ? 'red' : d.prev >= 5 ? 'yellow' : 'green';
                const bt = d.prev > 10 ? '🔴 Tinggi' : d.prev >= 5 ? '🟡 Sedang' : '🟢 Rendah';
                const coord = COORDS[d.nama] || {};
                return `<tr class="${cls}">
<td><b>${d.nama}</b></td>
<td>${coord.kec||'-'}</td>
<td class="c">${d.balita.toLocaleString('id-ID')}</td>
<td class="c"><span class="badge ${bc}">${bt}</span></td>
<td class="c"><b>${d.prev}%</b></td>
<td class="c sep c-red">${d.bbu_sk}</td><td class="c c-orange">${d.bbu_k}</td><td class="c c-green">${d.bbu_n}</td><td class="c c-muted">${d.bbu_rl}</td>
<td class="c sep c-red">${d.tbu_sp}</td><td class="c c-orange">${d.tbu_p}</td><td class="c c-green">${d.tbu_n}</td><td class="c c-muted">${d.tbu_t}</td>
<td class="c sep c-red">${d.bbtb_gb}</td><td class="c c-orange">${d.bbtb_gk}</td><td class="c c-green">${d.bbtb_n}</td><td class="c c-muted">${d.bbtb_gl}</td><td class="c c-muted">${d.bbtb_ob}</td>
<td class="c sep" style="color:#ef4444;font-weight:600">${d.stunting}</td>
<td class="c" style="color:#f59e0b;font-weight:600">${d.wasting}</td>
<td class="c" style="color:#f59e0b;font-weight:600">${d.uw}</td>
</tr>`;
            }).join('');
        }

        document.getElementById('filterTahunTabel').addEventListener('change', function() {
            renderTable(parseInt(this.value), document.getElementById('filterZona').value);
        });
        document.getElementById('filterZona').addEventListener('change', function() {
            renderTable(parseInt(document.getElementById('filterTahunTabel').value), this.value);
        });

        // ── BOOT ─────────────────────────────────────────────────────────────────
        document.addEventListener('DOMContentLoaded', () => {
            initMap();
            renderTable(2025, 'all');
        });
    </script>
</body>

</html>
