<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Stunting — Admin WebGIS</title>
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
            --yellow: #d69e2e;
            --green: #276749;
            --blue: #2563eb;
            --sidebar: #1e2a3b;
        }

        html.dark {
            --bg: #0d1117;
            --card: #161b27;
            --border: #232d3f;
            --text: #e2e8f0;
            --muted: #94a3b8;
            --sidebar: #111827;
        }

        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            background: var(--bg);
            color: var(--text);
            font-size: 14px;
            display: flex;
            min-height: 100vh;
        }

        /* SIDEBAR */
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
        }

        .sidebar-brand {
            padding: 1.25rem;
            border-bottom: 1px solid rgba(255, 255, 255, .08);
        }

        .sidebar-brand-name {
            font-size: .95rem;
            font-weight: 700;
            color: #fff;
        }

        .sidebar-brand-sub {
            font-size: .72rem;
            color: rgba(255, 255, 255, .4);
            margin-top: .15rem;
        }

        .sidebar-nav {
            padding: .75rem;
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
            color: rgba(255, 255, 255, .55);
            font-size: .8rem;
        }

        /* MAIN */
        .main {
            margin-left: 220px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        /* TOPBAR */
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
            display: inline-flex;
            align-items: center;
            gap: .4rem;
        }

        .btn-sm:hover {
            background: var(--border);
        }

        .btn-primary {
            background: var(--blue);
            color: #fff;
            border-color: var(--blue);
        }

        .btn-primary:hover {
            opacity: .88;
            background: var(--blue);
        }

        .btn-danger {
            background: var(--red);
            color: #fff;
            border-color: var(--red);
        }

        .btn-danger:hover {
            opacity: .88;
            background: var(--red);
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

        /* CONTENT */
        .content {
            padding: 1.5rem;
            flex: 1;
        }

        /* FLASH */
        .alert {
            padding: .75rem 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            font-size: .88rem;
            font-weight: 500;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* TABLE CARD */
        .table-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 10px;
            overflow: hidden;
        }

        .table-head {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: .75rem;
        }

        .table-head-title {
            font-weight: 600;
            font-size: .95rem;
        }

        .table-head-sub {
            font-size: .75rem;
            color: var(--muted);
            margin-top: .1rem;
        }

        /* SEARCH */
        .search-input {
            padding: .38rem .75rem;
            border-radius: 6px;
            border: 1px solid var(--border);
            background: var(--bg);
            color: var(--text);
            font-size: .82rem;
            width: 200px;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--blue);
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
            padding: .2rem .6rem;
            border-radius: 100px;
            font-size: .72rem;
            font-weight: 600;
        }

        .badge-red {
            background: #fff0f0;
            color: #b91c1c;
        }

        .badge-yellow {
            background: #fffbeb;
            color: #92400e;
        }

        .badge-green {
            background: #f0fff4;
            color: #15803d;
        }

        html.dark .badge-red {
            background: #2d1515;
        }

        html.dark .badge-yellow {
            background: #2a1f00;
        }

        html.dark .badge-green {
            background: #0f2318;
        }

        .action-btns {
            display: flex;
            gap: .4rem;
            justify-content: center;
        }

        /* PAGINATION */
        .pagination {
            display: flex;
            gap: .4rem;
            padding: .9rem 1.25rem;
            flex-wrap: wrap;
            align-items: center;
        }

        .pagination a,
        .pagination span {
            padding: .3rem .65rem;
            border-radius: 5px;
            font-size: .8rem;
            border: 1px solid var(--border);
            text-decoration: none;
            color: var(--text);
            background: var(--card);
            transition: .15s;
        }

        .pagination a:hover {
            background: var(--blue);
            color: #fff;
            border-color: var(--blue);
        }

        .pagination .active span {
            background: var(--blue);
            color: #fff;
            border-color: var(--blue);
        }

        .pagination .disabled span {
            opacity: .4;
            cursor: not-allowed;
        }

        @media (max-width: 900px) {
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
            <a href="{{ route('admin.dashboard') }}" class="nav-item">
                <span class="nav-icon">📊</span> Dashboard
            </a>
            <a href="{{ route('admin.stuntings.index') }}" class="nav-item active">
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
                <span>{{ Auth::user()->name ?? 'Admin' }}</span>
            </div>
        </div>
    </aside>

    <!-- MAIN -->
    <div class="main">
        <header class="topbar">
            <div class="topbar-title">Data Stunting Puskesmas</div>
            <div class="topbar-right">
                <button class="btn-sm" id="themeBtn">🌙</button>
                <a href="{{ route('logout') }}" class="btn-sm btn-logout">Logout</a>
            </div>
        </header>

        <div class="content">

            @if (session('success'))
                <div class="alert alert-success">✅ {{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-error">❌ {{ session('error') }}</div>
            @endif

            <div class="table-card">
                <div class="table-head">
                    <div>
                        <div class="table-head-title">Data Puskesmas</div>
                        <div class="table-head-sub">Total {{ $stuntings->total() }} Puskesmas</div>
                    </div>
                    <div style="display:flex;gap:.5rem;align-items:center;flex-wrap:wrap">
                        <input type="text" class="search-input" id="searchInput" placeholder="🔍 Cari puskesmas...">
                        <a href="{{ route('admin.stuntings.create') }}" class="btn-sm btn-primary">
                            ➕ Tambah Data
                        </a>
                    </div>
                </div>

                <div class="table-scroll">
                    <table id="dataTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Puskesmas</th>
                                <th>Kecamatan</th>
                                <th class="c">Status</th>
                                <th class="c">Prev 2023</th>
                                <th class="c">Prev 2024</th>
                                <th class="c">Prev 2025</th>
                                <th class="c">Balita 2025</th>
                                <th class="c">Stunting 2025</th>
                                <th class="c">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($stuntings as $i => $item)
                                <tr>
                                    <td>{{ $stuntings->firstItem() + $i }}</td>
                                    <td><b>{{ $item->nama }}</b></td>
                                    <td>{{ $item->kecamatan }}</td>
                                    <td class="c">
                                        @if ($item->status === 'Stunting')
                                            <span class="badge badge-red">🔴 Stunting</span>
                                        @elseif($item->status === 'Stunting Sedang')
                                            <span class="badge badge-yellow">🟡 Sedang</span>
                                        @else
                                            <span class="badge badge-green">🟢 Normal</span>
                                        @endif
                                    </td>
                                    <td class="c">
                                        {{ $item->prevalensi_2023 ? $item->prevalensi_2023 . '%' : '-' }}
                                    </td>
                                    <td class="c">
                                        {{ $item->prevalensi_2024 ? $item->prevalensi_2024 . '%' : '-' }}
                                    </td>
                                    <td class="c">
                                        <b>{{ $item->prevalensi_2025 ? $item->prevalensi_2025 . '%' : '-' }}</b>
                                    </td>
                                    <td class="c">
                                        {{ $item->balita_2025 ? number_format($item->balita_2025) : '-' }}</td>
                                    <td class="c" style="color:#ef4444;font-weight:600">
                                        {{ $item->stunting_2025 ?? '-' }}</td>
                                    <td class="c">
                                        <div class="action-btns">
                                            <a href="{{ route('admin.stuntings.edit', $item->id) }}"
                                                class="btn-sm btn-primary">✏️ Edit</a>
                                            <form method="POST"
                                                action="{{ route('admin.stuntings.destroy', $item->id) }}"
                                                onsubmit="return confirm('Hapus data {{ $item->nama }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-sm btn-danger">🗑️ Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="pagination">
                    {{ $stuntings->links() }}
                </div>
            </div>

        </div>
    </div>

    <script>
        // Search filter
        document.getElementById('searchInput').addEventListener('input', function() {
            const q = this.value.toLowerCase();
            document.querySelectorAll('#dataTable tbody tr').forEach(tr => {
                tr.style.display = tr.textContent.toLowerCase().includes(q) ? '' : 'none';
            });
        });

        // Theme
        const themeBtn = document.getElementById('themeBtn');

        function initTheme() {
            const dark = localStorage.getItem('theme') === 'dark';
            document.documentElement.classList.toggle('dark', dark);
            themeBtn.textContent = dark ? '☀️' : '🌙';
        }
        themeBtn.addEventListener('click', () => {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            themeBtn.textContent = isDark ? '☀️' : '🌙';
        });
        initTheme();
    </script>
</body>

</html>
