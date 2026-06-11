<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebGIS – Kabupaten Tanah Laut</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { height: 100%; overflow: hidden; }

        :root {
            --green-dark:   #1a5c33;
            --green-mid:    #2d8a4e;
            --green-light:  #90EE90;
            --panel-bg:     #ffffff;
            --panel-text:   #2c3e50;
            --panel-sub:    #6c757d;
            --panel-border: #e9ecef;
            --panel-input:  #f4f6f8;
            --panel-w:      400px;
        }
        html.dark {
            --panel-bg:     #161b22;
            --panel-text:   #e6edf3;
            --panel-sub:    #8b949e;
            --panel-border: #30363d;
            --panel-input:  #21262d;
        }

        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif; }

        /* ── Peta fullscreen ── */
        #map {
            position: fixed; inset: 0; z-index: 0;
        }
        #map .leaflet-control-zoom,
        #map .leaflet-control-attribution { display: none; }

        .overlay {
            position: fixed; inset: 0; z-index: 1; pointer-events: none;
            background: linear-gradient(160deg,
                rgba(10,30,18,.72) 0%,
                rgba(10,30,18,.45) 50%,
                rgba(10,30,18,.30) 100%);
        }

        /* ── Navbar ── */
        .navbar {
            position: fixed; top: 0; left: 0; right: 0;
            height: 62px; z-index: 30;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 1.75rem;
            background: rgba(10,30,18,.45);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border-bottom: 1px solid rgba(144,238,144,.12);
        }
        .brand { display: flex; align-items: center; gap: 10px; text-decoration: none; }
        .brand-icon {
            width: 36px; height: 36px; border-radius: 9px; flex-shrink: 0;
            background: linear-gradient(135deg, var(--green-mid), var(--green-dark));
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem; box-shadow: 0 2px 8px rgba(45,138,78,.45);
        }
        .brand-name { font-size: 1rem; font-weight: 700; color: #fff; letter-spacing: .3px; }
        .brand-sub  { font-size: .68rem; color: rgba(255,255,255,.65); letter-spacing: .4px; margin-top: 1px; }
        .nav-right  { display: flex; align-items: center; gap: .6rem; }

        .btn-theme {
            width: 36px; height: 36px; border-radius: 8px;
            background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.18);
            color: #fff; font-size: .95rem; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            transition: background .2s;
        }
        .btn-theme:hover { background: rgba(255,255,255,.22); }

        .btn-login {
            height: 36px; padding: 0 1.1rem; border-radius: 8px;
            background: linear-gradient(135deg, var(--green-mid), var(--green-dark));
            border: none; color: #fff; font-size: .88rem; font-weight: 600;
            cursor: pointer; display: flex; align-items: center; gap: 6px;
            box-shadow: 0 2px 10px rgba(45,138,78,.4);
            transition: transform .15s, box-shadow .15s;
        }
        .btn-login:hover  { transform: translateY(-1px); box-shadow: 0 4px 16px rgba(45,138,78,.5); }
        .btn-login:active { transform: translateY(0); }

        /* ── Hero ── */
        .hero {
            position: fixed; inset: 62px 0 0 0; z-index: 2;
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            text-align: center; padding: 0 2rem 4rem;
            pointer-events: none;
            transition: opacity .4s ease, transform .4s ease;
        }
        .hero.hidden { opacity: 0; transform: translateY(-18px); }

        .hero-pill {
            display: inline-flex; align-items: center; gap: 6px;
            background: rgba(144,238,144,.12); border: 1px solid rgba(144,238,144,.28);
            color: var(--green-light); font-size: .7rem; font-weight: 700;
            letter-spacing: 1.6px; text-transform: uppercase;
            padding: 5px 14px; border-radius: 20px; margin-bottom: 1.4rem;
        }
        .hero h1 {
            font-size: clamp(2.4rem, 5.5vw, 4.2rem); font-weight: 800;
            color: #fff; line-height: 1.08; letter-spacing: -.5px;
            text-shadow: 0 3px 18px rgba(0,0,0,.45); margin-bottom: 1.1rem;
        }
        .hero h1 em { font-style: normal; color: var(--green-light); }
        .hero-desc {
            font-size: .98rem; color: rgba(255,255,255,.78);
            max-width: 420px; line-height: 1.75; margin-bottom: 2.8rem;
        }
        .hero-stats { display: flex; gap: 2.5rem; justify-content: center; }
        .stat { text-align: center; }
        .stat-val { font-size: 1.9rem; font-weight: 700; color: var(--green-light); line-height: 1; }
        .stat-lbl { font-size: .7rem; color: rgba(255,255,255,.6); text-transform: uppercase; letter-spacing: .6px; margin-top: 4px; }
        .stat-div { width: 1px; height: 42px; background: rgba(255,255,255,.18); align-self: center; }

        /* ── Backdrop ── */
        .backdrop {
            position: fixed; inset: 62px 0 0 0; z-index: 19;
            background: transparent; display: none;
        }
        .backdrop.open { display: block; }

        /* ── Panel login ── */
        .panel {
            position: fixed; top: 62px; right: 0; bottom: 0;
            width: var(--panel-w); z-index: 20;
            background: var(--panel-bg);
            box-shadow: -6px 0 36px rgba(0,0,0,.28);
            display: flex; flex-direction: column;
            transform: translateX(100%);
            transition: transform .35s cubic-bezier(.4,0,.2,1);
            overflow-y: auto;
        }
        .panel.open { transform: translateX(0); }
        .panel::-webkit-scrollbar { width: 4px; }
        .panel::-webkit-scrollbar-track { background: transparent; }
        .panel::-webkit-scrollbar-thumb { background: var(--panel-border); border-radius: 4px; }

        .panel-inner { padding: 2rem 1.75rem 2.5rem; flex: 1; }

        .ph { display: flex; align-items: center; justify-content: space-between; margin-bottom: 2rem; }
        .ph-title { font-size: 1.15rem; font-weight: 700; color: var(--panel-text); }
        .btn-close {
            width: 32px; height: 32px; border-radius: 7px;
            background: var(--panel-border); border: none;
            color: var(--panel-sub); font-size: 1rem; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            transition: background .15s;
        }
        .btn-close:hover { background: #d5d9e0; }
        html.dark .btn-close:hover { background: #30363d; }

        .panel-icon {
            width: 60px; height: 60px; border-radius: 16px;
            background: linear-gradient(135deg, var(--green-mid), var(--green-dark));
            display: flex; align-items: center; justify-content: center;
            font-size: 1.8rem; margin: 0 auto 1rem;
            box-shadow: 0 4px 16px rgba(45,138,78,.35);
        }
        .panel-greet { text-align: center; margin-bottom: 2rem; }
        .panel-greet h3 { font-size: 1.05rem; font-weight: 700; color: var(--panel-text); margin-bottom: .25rem; }
        .panel-greet p  { font-size: .82rem; color: var(--panel-sub); }

        /* form */
        .fg { margin-bottom: 1.1rem; }
        .fg label { display: block; margin-bottom: .38rem; font-size: .83rem; font-weight: 600; color: var(--panel-text); }
        .fg input {
            width: 100%; padding: .68rem .95rem;
            border: 1.5px solid var(--panel-border);
            border-radius: 8px; background: var(--panel-input);
            color: var(--panel-text); font-size: .92rem;
            transition: border-color .2s, box-shadow .2s, background .2s;
        }
        .fg input::placeholder { color: #adb5bd; }
        .fg input:focus {
            outline: none; border-color: var(--green-mid);
            background: var(--panel-bg);
            box-shadow: 0 0 0 3px rgba(45,138,78,.13);
        }

        /* password eye */
        .pw-wrap { position: relative; }
        .pw-wrap input { padding-right: 2.8rem; }
        .pw-eye {
            position: absolute; right: .8rem; top: 50%; transform: translateY(-50%);
            background: none; border: none; cursor: pointer;
            color: var(--panel-sub); font-size: .9rem; padding: 0; line-height: 1;
        }

        .btn-submit {
            width: 100%; padding: .78rem; border-radius: 8px;
            background: linear-gradient(135deg, var(--green-mid), var(--green-dark));
            border: none; color: #fff; font-size: .92rem; font-weight: 700;
            cursor: pointer; letter-spacing: .3px; margin-top: .35rem;
            transition: transform .15s, box-shadow .15s;
        }
        .btn-submit:hover  { transform: translateY(-1px); box-shadow: 0 5px 16px rgba(45,138,78,.4); }
        .btn-submit:active { transform: translateY(0); }

        /* alerts */
        .alert {
            padding: .65rem .9rem; border-radius: 8px;
            font-size: .83rem; margin-bottom: 1rem; border: 1px solid transparent;
        }
        .alert-err { background: #fff0f0; border-color: #f5c6cb; color: #a0232a; }
        .alert-ok  { background: #f0fff4; border-color: #b7e4c7; color: #1a5c33; }
        .alert-err ul { list-style: none; }
        .alert-err ul li { margin-bottom: .2rem; }
        .alert-err ul li:last-child { margin-bottom: 0; }
        html.dark .alert-err { background: #2d1215; color: #f48a8a; border-color: #5a2020; }
        html.dark .alert-ok  { background: #122418; color: #7be09e; border-color: #1e5e2e; }

        /* demo box */
        .demo-box {
            margin-top: 1.4rem; background: var(--panel-input);
            border: 1px solid var(--panel-border); border-radius: 9px;
            padding: .9rem 1rem; font-size: .78rem; color: var(--panel-sub); line-height: 1.7;
        }
        .demo-title { font-weight: 700; color: var(--panel-text); margin-bottom: .3rem; font-size: .8rem; }
        .demo-box strong { color: var(--panel-text); }

        @media (max-width: 460px) {
            :root { --panel-w: 100vw; }
            .hero-stats { gap: 1.2rem; }
            .navbar { padding: 0 1rem; }
        }
    </style>
</head>
<body>

<div id="map"></div>
<div class="overlay"></div>

<!-- ═══ NAVBAR ═══ -->
<nav class="navbar">
    <a href="#" class="brand">
        <div class="brand-icon">🗺️</div>
        <div>
            <div class="brand-name">WebGIS</div>
            <div class="brand-sub">Kabupaten Tanah Laut</div>
        </div>
    </a>
    <div class="nav-right">
        <button class="btn-theme" id="btnTheme" title="Ganti tema">🌙</button>
        <button class="btn-login" id="btnOpen">🔑 Masuk</button>
    </div>
</nav>

<!-- ═══ HERO ═══ -->
<div class="hero" id="hero">
    <div class="hero-pill">🗺️ Sistem Informasi Geografis</div>
    <h1>Peta Stunting<br><em>Tanah Laut</em></h1>
    <p class="hero-desc">
        Monitoring dan analisis data stunting balita dari 22&nbsp;Puskesmas
        di Kabupaten Tanah Laut secara interaktif.
    </p>
    <div class="hero-stats">
        <div class="stat"><div class="stat-val">22</div><div class="stat-lbl">Puskesmas</div></div>
        <div class="stat-div"></div>
        <div class="stat"><div class="stat-val">6,62%</div><div class="stat-lbl">Prevalensi 2025</div></div>
        <div class="stat-div"></div>
        <div class="stat"><div class="stat-val">2020–25</div><div class="stat-lbl">Data Tersedia</div></div>
    </div>
</div>

<!-- ═══ BACKDROP ═══ -->
<div class="backdrop" id="backdrop"></div>

<!-- ═══ PANEL LOGIN ═══ -->
<div class="panel" id="panel" role="dialog" aria-modal="true" aria-labelledby="panelTitle">
    <div class="panel-inner">

        <div class="ph">
            <span class="ph-title" id="panelTitle">Masuk ke Dashboard</span>
            <button class="btn-close" id="btnClose" aria-label="Tutup">✕</button>
        </div>

        <div class="panel-icon">🗺️</div>
        <div class="panel-greet">
            <h3>Selamat Datang</h3>
            <p>Gunakan akun Anda untuk melanjutkan</p>
        </div>

        {{-- ======= LARAVEL ALERTS — TIDAK DIUBAH ======= --}}
        @if ($errors->any())
            <div class="alert alert-err">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-ok">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-err">{{ session('error') }}</div>
        @endif
        {{-- ============================================= --}}

        {{-- ======= LARAVEL FORM — TIDAK DIUBAH ======= --}}
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="fg">
                <label for="username">Username</label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    placeholder="Masukkan username"
                    value="{{ old('username') }}"
                    autocomplete="username"
                    required
                    autofocus
                >
            </div>
            <div class="fg">
                <label for="password">Password</label>
                <div class="pw-wrap">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Masukkan password"
                        autocomplete="current-password"
                        required
                    >
                    <button type="button" class="pw-eye" id="btnEye" aria-label="Tampilkan password">👁️</button>
                </div>
            </div>
            <button type="submit" class="btn-submit">Masuk ke Dashboard →</button>
        </form>
        {{-- ============================================= --}}

        <div class="demo-box">
            <div class="demo-title">📝 Akun Demo</div>
            <div>Username: <strong>admin</strong> &nbsp;|&nbsp; Password: <strong>admin123</strong></div>
            <div>Username: <strong>user</strong> &nbsp;|&nbsp; Password: <strong>user123</strong></div>
        </div>

    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
<script>
/* ═══ PETA ═══════════════════════════════════════════════════════ */
const bgMap = L.map('map', {
    zoomControl: false, dragging: false,
    scrollWheelZoom: false, doubleClickZoom: false,
    touchZoom: false, keyboard: false, attributionControl: false,
}).setView([-3.8565, 114.9850], 10);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(bgMap);

fetch('/geojson/tanah_laut.geojson')
    .then(r => r.json())
    .then(d => L.geoJSON(d, {
        style: { color: '#4CAF50', weight: 2, fillColor: '#90EE90', fillOpacity: .18 }
    }).addTo(bgMap))
    .catch(() => {});

/* ═══ PANEL ═══════════════════════════════════════════════════════ */
const panel    = document.getElementById('panel');
const backdrop = document.getElementById('backdrop');
const hero     = document.getElementById('hero');

function openPanel() {
    panel.classList.add('open');
    backdrop.classList.add('open');
    hero.classList.add('hidden');
    setTimeout(() => document.getElementById('username').focus(), 350);
}
function closePanel() {
    panel.classList.remove('open');
    backdrop.classList.remove('open');
    hero.classList.remove('hidden');
}

document.getElementById('btnOpen').addEventListener('click', openPanel);
document.getElementById('btnClose').addEventListener('click', closePanel);
backdrop.addEventListener('click', closePanel);
document.addEventListener('keydown', e => {
    if (e.key === 'Escape' && panel.classList.contains('open')) closePanel();
});

/* ─ Buka otomatis jika ada error Laravel (Blade akan render true/false) ─ */
const hasError = {{ ($errors->any() || session('error')) ? 'true' : 'false' }};
if (hasError) document.addEventListener('DOMContentLoaded', openPanel);

/* ═══ TOGGLE PASSWORD ══════════════════════════════════════════════ */
const pwInput = document.getElementById('password');
document.getElementById('btnEye').addEventListener('click', function () {
    const show = pwInput.type === 'password';
    pwInput.type = show ? 'text' : 'password';
    this.textContent  = show ? '🙈' : '👁️';
    this.setAttribute('aria-label', show ? 'Sembunyikan password' : 'Tampilkan password');
});

/* ═══ TEMA ════════════════════════════════════════════════════════ */
const btnTheme = document.getElementById('btnTheme');
const htmlEl   = document.documentElement;

function applyTheme(dark) {
    htmlEl.classList.toggle('dark', dark);
    btnTheme.textContent = dark ? '☀️' : '🌙';
    localStorage.setItem('theme', dark ? 'dark' : 'light');
}

const saved   = localStorage.getItem('theme');
const sysDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
applyTheme(saved === 'dark' || (!saved && sysDark));

btnTheme.addEventListener('click', () => applyTheme(!htmlEl.classList.contains('dark')));
</script>
</body>
</html>