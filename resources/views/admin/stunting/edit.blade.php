<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($stunting) ? 'Edit' : 'Tambah' }} Data Stunting — Admin WebGIS</title>
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --bg: #f4f6fb; --card: #ffffff; --border: #e3e8f0;
            --text: #18202e; --muted: #64748b; --red: #e53e3e;
            --blue: #2563eb; --sidebar: #1e2a3b; --green: #276749;
        }
        html.dark {
            --bg: #0d1117; --card: #161b27; --border: #232d3f;
            --text: #e2e8f0; --muted: #94a3b8; --sidebar: #111827;
        }
        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            background: var(--bg); color: var(--text);
            font-size: 14px; display: flex; min-height: 100vh;
        }

        /* SIDEBAR */
        .sidebar {
            width: 220px; background: var(--sidebar);
            flex-shrink: 0; display: flex; flex-direction: column;
            position: fixed; top: 0; left: 0; bottom: 0; z-index: 100;
        }
        .sidebar-brand { padding: 1.25rem; border-bottom: 1px solid rgba(255,255,255,.08); }
        .sidebar-brand-name { font-size: .95rem; font-weight: 700; color: #fff; }
        .sidebar-brand-sub  { font-size: .72rem; color: rgba(255,255,255,.4); margin-top: .15rem; }
        .sidebar-nav { padding: .75rem; flex: 1; }
        .nav-section-label {
            font-size: .65rem; font-weight: 700; letter-spacing: 1px;
            text-transform: uppercase; color: rgba(255,255,255,.3); padding: .6rem .5rem .3rem;
        }
        .nav-item {
            display: flex; align-items: center; gap: .6rem;
            padding: .55rem .75rem; border-radius: 6px;
            color: rgba(255,255,255,.6); font-size: .85rem;
            font-weight: 500; text-decoration: none; transition: .15s; margin-bottom: .15rem;
        }
        .nav-item:hover { background: rgba(255,255,255,.07); color: #fff; }
        .nav-item.active { background: var(--blue); color: #fff; }
        .nav-icon { font-size: 1rem; width: 20px; text-align: center; }
        .sidebar-footer { padding: .75rem; border-top: 1px solid rgba(255,255,255,.08); }
        .sidebar-user {
            display: flex; align-items: center; gap: .6rem;
            padding: .5rem .75rem; color: rgba(255,255,255,.55); font-size: .8rem;
        }

        /* MAIN */
        .main { margin-left: 220px; flex: 1; display: flex; flex-direction: column; }

        /* TOPBAR */
        .topbar {
            background: var(--card); border-bottom: 1px solid var(--border);
            padding: 0 1.5rem; height: 56px;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 50;
        }
        .topbar-title { font-weight: 600; font-size: 1rem; }
        .topbar-right { display: flex; align-items: center; gap: .75rem; }
        .btn-sm {
            padding: .38rem .85rem; border-radius: 5px; font-size: .82rem;
            font-weight: 500; cursor: pointer; border: 1px solid var(--border);
            background: transparent; color: var(--text); transition: .15s;
            text-decoration: none; display: inline-flex; align-items: center; gap: .4rem;
        }
        .btn-sm:hover { background: var(--border); }
        .btn-primary { background: var(--blue); color: #fff; border-color: var(--blue); }
        .btn-primary:hover { opacity: .88; background: var(--blue); }
        .btn-logout { background: var(--red); color: #fff; border-color: var(--red); }
        .btn-logout:hover { opacity: .85; }

        /* CONTENT */
        .content { padding: 1.5rem; flex: 1; }

        /* FORM CARD */
        .form-card {
            background: var(--card); border: 1px solid var(--border);
            border-radius: 10px; overflow: hidden; max-width: 900px;
        }
        .form-card-header {
            padding: 1rem 1.5rem; border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
        }
        .form-card-title { font-weight: 600; font-size: .95rem; }
        .form-card-body { padding: 1.5rem; }

        /* SECTION */
        .form-section { margin-bottom: 1.75rem; }
        .form-section-title {
            font-size: .75rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: .8px; color: var(--muted); margin-bottom: 1rem;
            padding-bottom: .5rem; border-bottom: 1px solid var(--border);
        }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        .form-grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem; }
        .form-group { display: flex; flex-direction: column; gap: .35rem; }
        .form-group.full { grid-column: 1 / -1; }

        label { font-size: .82rem; font-weight: 600; color: var(--text); }
        label .req { color: var(--red); margin-left: 2px; }
        label .opt { color: var(--muted); font-weight: 400; font-size: .75rem; margin-left: 4px; }

        input, select {
            padding: .55rem .85rem; border-radius: 7px;
            border: 1.5px solid var(--border); background: var(--bg);
            color: var(--text); font-size: .88rem; transition: .15s; width: 100%;
        }
        input:focus, select:focus {
            outline: none; border-color: var(--blue);
            box-shadow: 0 0 0 3px rgba(37,99,235,.1);
        }
        input.error { border-color: var(--red); }
        .error-msg { font-size: .75rem; color: var(--red); margin-top: .2rem; }

        /* YEAR TABLE */
        .year-table { width: 100%; border-collapse: collapse; font-size: .83rem; }
        .year-table th {
            background: var(--bg); padding: .5rem .75rem; text-align: center;
            font-weight: 600; color: var(--muted); font-size: .72rem;
            text-transform: uppercase; letter-spacing: .4px;
            border-bottom: 1px solid var(--border);
        }
        .year-table td { padding: .45rem .5rem; border-bottom: 1px solid var(--border); }
        .year-table tr:last-child td { border-bottom: none; }
        .year-label {
            font-weight: 700; font-size: .85rem; color: var(--text);
            padding: .45rem .75rem; white-space: nowrap;
        }
        .year-table input {
            padding: .4rem .6rem; font-size: .83rem;
            border-radius: 5px; border: 1.5px solid var(--border);
            background: var(--bg); color: var(--text); width: 100%;
        }
        .year-table input:focus {
            outline: none; border-color: var(--blue);
            box-shadow: 0 0 0 2px rgba(37,99,235,.08);
        }

        /* FORM ACTIONS */
        .form-actions {
            display: flex; gap: .75rem; align-items: center;
            padding: 1rem 1.5rem; border-top: 1px solid var(--border);
            background: var(--bg);
        }

        /* VALIDATION ERRORS */
        .alert-error {
            background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;
            padding: .75rem 1rem; border-radius: 8px; margin-bottom: 1rem;
            font-size: .88rem;
        }
        .alert-error ul { padding-left: 1.25rem; margin-top: .3rem; }

        @media (max-width: 900px) {
            .sidebar { transform: translateX(-100%); }
            .main { margin-left: 0; }
            .form-grid, .form-grid-3 { grid-template-columns: 1fr; }
        }
        @media (max-width: 600px) {
            .year-table { font-size: .75rem; }
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
        <a href="{{ route('home') }}" class="nav-item">
            <span class="nav-icon">🗺️</span> Lihat Peta
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
        <div class="topbar-title">
            {{ isset($stunting) ? '✏️ Edit Data' : '➕ Tambah Data' }} Puskesmas
        </div>
        <div class="topbar-right">
            <button class="btn-sm" id="themeBtn">🌙</button>
            <a href="{{ route('logout') }}" class="btn-sm btn-logout">Logout</a>
        </div>
    </header>

    <div class="content">

        @if($errors->any())
        <div class="alert-error">
            <b>❌ Ada kesalahan:</b>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-title">
                    {{ isset($stunting) ? 'Edit: '.$stunting->nama : 'Tambah Puskesmas Baru' }}
                </div>
                <a href="{{ route('admin.stuntings.index') }}" class="btn-sm">← Kembali</a>
            </div>

            <form method="POST" action="{{ isset($stunting) ? route('admin.stuntings.update', $stunting->id) : route('admin.stuntings.store') }}">
                @csrf
                @if(isset($stunting)) @method('PUT') @endif

                <div class="form-card-body">

                    <!-- INFO DASAR -->
                    <div class="form-section">
                        <div class="form-section-title">📍 Informasi Dasar</div>
                        <div class="form-grid">
                            <div class="form-group">
                                <label>Nama Puskesmas <span class="req">*</span></label>
                                <input type="text" name="nama" value="{{ old('nama', $stunting->nama ?? '') }}"
                                    placeholder="cth. PELAIHARI" class="{{ $errors->has('nama') ? 'error' : '' }}" required>
                                @error('nama') <div class="error-msg">{{ $message }}</div> @enderror
                            </div>
                            <div class="form-group">
                                <label>Kecamatan <span class="req">*</span></label>
                                <input type="text" name="kecamatan" value="{{ old('kecamatan', $stunting->kecamatan ?? '') }}"
                                    placeholder="cth. Pelaihari" class="{{ $errors->has('kecamatan') ? 'error' : '' }}" required>
                                @error('kecamatan') <div class="error-msg">{{ $message }}</div> @enderror
                            </div>
                            <div class="form-group">
                                <label>Desa / Kelurahan <span class="req">*</span></label>
                                <input type="text" name="desa" value="{{ old('desa', $stunting->desa ?? '') }}"
                                    placeholder="cth. Puskesmas PELAIHARI" class="{{ $errors->has('desa') ? 'error' : '' }}" required>
                                @error('desa') <div class="error-msg">{{ $message }}</div> @enderror
                            </div>
                            <div class="form-group">
                                <label>Status 2025 <span class="req">*</span></label>
                                <select name="status" required>
                                    <option value="">-- Pilih Status --</option>
                                    @foreach(['Stunting' => '🔴 Stunting (>10%)', 'Stunting Sedang' => '🟡 Stunting Sedang (5-10%)', 'Normal' => '🟢 Normal (<5%)'] as $val => $label)
                                        <option value="{{ $val }}" {{ old('status', $stunting->status ?? '') === $val ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('status') <div class="error-msg">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- KOORDINAT -->
                    <div class="form-section">
                        <div class="form-section-title">🗺️ Koordinat</div>
                        <div class="form-grid">
                            <div class="form-group">
                                <label>Latitude <span class="req">*</span></label>
                                <input type="number" name="latitude" step="any"
                                    value="{{ old('latitude', $stunting->latitude ?? '') }}"
                                    placeholder="-3.8071561" required>
                                @error('latitude') <div class="error-msg">{{ $message }}</div> @enderror
                            </div>
                            <div class="form-group">
                                <label>Longitude <span class="req">*</span></label>
                                <input type="number" name="longitude" step="any"
                                    value="{{ old('longitude', $stunting->longitude ?? '') }}"
                                    placeholder="114.760143" required>
                                @error('longitude') <div class="error-msg">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- DATA PER TAHUN -->
                    <div class="form-section">
                        <div class="form-section-title">📊 Data Per Tahun <span style="font-weight:400;text-transform:none;letter-spacing:0">(kosongkan jika tidak ada data)</span></div>
                        <div style="overflow-x:auto">
                            <table class="year-table">
                                <thead>
                                    <tr>
                                        <th style="text-align:left">Tahun</th>
                                        <th>Jumlah Balita</th>
                                        <th>Jumlah Stunting</th>
                                        <th>Prevalensi (%)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach([2020, 2021, 2022, 2023, 2024, 2025] as $y)
                                    <tr>
                                        <td class="year-label">{{ $y }}</td>
                                        <td>
                                            <input type="number" name="balita_{{ $y }}" min="0"
                                                value="{{ old('balita_'.$y, $stunting->{'balita_'.$y} ?? '') }}"
                                                placeholder="-">
                                        </td>
                                        <td>
                                            <input type="number" name="stunting_{{ $y }}" min="0"
                                                value="{{ old('stunting_'.$y, $stunting->{'stunting_'.$y} ?? '') }}"
                                                placeholder="-">
                                        </td>
                                        <td>
                                            <input type="number" name="prevalensi_{{ $y }}" min="0" max="100" step="0.01"
                                                value="{{ old('prevalensi_'.$y, $stunting->{'prevalensi_'.$y} ?? '') }}"
                                                placeholder="-"
                                                oninput="autoStatus(this, {{ $y }})">
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <p style="font-size:.75rem;color:var(--muted);margin-top:.6rem">
                            💡 Status akan otomatis terupdate berdasarkan prevalensi 2025 saat disimpan.
                        </p>
                    </div>

                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-sm btn-primary">
                        💾 {{ isset($stunting) ? 'Simpan Perubahan' : 'Tambah Data' }}
                    </button>
                    <a href="{{ route('admin.stuntings.index') }}" class="btn-sm">Batal</a>
                </div>
            </form>
        </div>

    </div>
</div>

<script>
    // Auto-suggest status berdasarkan prevalensi 2025
    function autoStatus(input, tahun) {
        if (tahun !== 2025) return;
        const val = parseFloat(input.value);
        const sel = document.querySelector('select[name="status"]');
        if (isNaN(val)) return;
        if (val > 10)      sel.value = 'Stunting';
        else if (val >= 5) sel.value = 'Stunting Sedang';
        else               sel.value = 'Normal';
    }

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