@php
    // Warna aksen per halaman: blue (admin / pilihan login), teal (dosen), amber (mahasiswa)
    $accent = trim(View::yieldContent('accent')) ?: 'blue';
@endphp
<!DOCTYPE html>
<html lang="id" data-accent="{{ $accent }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#091933">
    <title>@yield('title', 'Masuk - Absensi Kampus')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,600;12..96,700;12..96,800&family=Figtree:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script>
        // Tema dibagi dengan dashboard (kunci yang sama), diterapkan sebelum render agar tidak berkedip
        (function () {
            var t = 'light';
            try { t = localStorage.getItem('absensi_theme') || 'light'; } catch (e) {}
            document.documentElement.setAttribute('data-theme', t);
            document.documentElement.setAttribute('data-bs-theme', t);
        })();
    </script>

    <style>
        :root {
            --mint: #14c9a7;
            --bg: #f3f5fa;
            --surface: #ffffff;
            --surface-2: #f8f9fd;
            --text: #14213d;
            --muted: #66718c;
            --line: #e1e6f0;
            --danger-bg: rgba(244, 63, 94, 0.10);
            --danger-text: #be123c;
            --shadow-md: 0 18px 44px rgba(20, 33, 61, 0.10);

            /* aksen default (blue) */
            --accent: #3b63f0;
            --accent-soft: rgba(59, 99, 240, 0.12);
            --btn-1: #3b63f0;
            --btn-2: #2c4fd6;
            --glow: rgba(59, 99, 240, 0.45);
        }

        [data-accent="teal"]  { --accent: #0f9f86; --accent-soft: rgba(15, 159, 134, 0.13); --btn-1: #0f8a77; --btn-2: #0b6b5d; --glow: rgba(20, 201, 167, 0.40); }
        [data-accent="amber"] { --accent: #c9811a; --accent-soft: rgba(201, 129, 26, 0.14); --btn-1: #ad6409; --btn-2: #8f5207; --glow: rgba(245, 158, 11, 0.38); }

        [data-theme="dark"] {
            --bg: #0a0f1d;
            --surface: #121a2e;
            --surface-2: #17213a;
            --text: #eef2f9;
            --muted: #9aa8c2;
            --line: #223050;
            --danger-bg: rgba(244, 63, 94, 0.16);
            --danger-text: #fb7185;
            --shadow-md: 0 18px 44px rgba(0, 0, 0, 0.45);
            --accent: #8fa8ff;
            --accent-soft: rgba(91, 130, 255, 0.20);
        }
        [data-theme="dark"][data-accent="teal"]  { --accent: #3fe0c1; --accent-soft: rgba(20, 201, 167, 0.20); }
        [data-theme="dark"][data-accent="amber"] { --accent: #f5b34a; --accent-soft: rgba(245, 158, 11, 0.20); }

        * { -webkit-tap-highlight-color: transparent; }

        body {
            font-family: 'Figtree', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            min-height: 100dvh;
            -webkit-font-smoothing: antialiased;
            transition: background-color .2s ease, color .2s ease;
        }

        h1, h2, h3, .display-font { font-family: 'Bricolage Grotesque', sans-serif; letter-spacing: -0.025em; }

        :focus-visible { outline: 3px solid var(--accent); outline-offset: 2px; }

        /* SweetAlert2 Penyesuaian Mode Gelap */
        [data-theme="dark"] .swal2-popup {
            background-color: var(--surface) !important;
            color: var(--text) !important;
            border: 1px solid var(--line);
        }
        [data-theme="dark"] .swal2-title,
        [data-theme="dark"] .swal2-html-container {
            color: var(--text) !important;
        }

        /* ---------- Kerangka dua kolom ---------- */
        .auth-shell { min-height: 100vh; min-height: 100dvh; display: grid; grid-template-columns: 1fr; }

        @media (min-width: 992px) {
            .auth-shell { grid-template-columns: minmax(0, 1.05fr) minmax(0, 1fr); }
        }

        /* ---------- Panel brand (kiri, hanya laptop) ---------- */
        .auth-brand {
            display: none;
            position: relative;
            overflow: hidden;
            color: #fff;
            padding: 48px clamp(32px, 5vw, 72px);
            background:
                radial-gradient(70% 60% at 0% 0%, var(--glow) 0%, transparent 62%),
                radial-gradient(60% 50% at 100% 100%, rgba(20, 201, 167, 0.20) 0%, transparent 60%),
                linear-gradient(160deg, #0b2545 0%, #071a31 100%);
        }

        @media (min-width: 992px) {
            .auth-brand { display: flex; flex-direction: column; justify-content: space-between; }
        }

        .brand-row { display: flex; align-items: center; gap: 12px; position: relative; z-index: 2; }

        .brand-mark {
            width: 44px;
            height: 44px;
            border-radius: 13px;
            display: grid;
            place-items: center;
            font-size: 1.3rem;
            color: #06302a;
            background: linear-gradient(135deg, var(--mint) 0%, #0ea58a 100%);
            box-shadow: 0 10px 24px rgba(20, 201, 167, 0.32);
            flex-shrink: 0;
        }

        .brand-name { font-family: 'Bricolage Grotesque', sans-serif; font-weight: 700; font-size: 1.3rem; }

        .brand-body { position: relative; z-index: 2; max-width: 30rem; }

        .brand-title {
            font-family: 'Bricolage Grotesque', sans-serif;
            font-weight: 800;
            font-size: clamp(2rem, 1.2rem + 2.2vw, 3.1rem);
            line-height: 1.08;
            letter-spacing: -0.03em;
            margin: 0 0 1rem;
            text-wrap: balance;
        }

        .brand-lead { color: rgba(255, 255, 255, 0.72); font-size: 1.05rem; line-height: 1.65; margin: 0 0 1.75rem; }

        .brand-points { list-style: none; margin: 0; padding: 0; display: grid; gap: .8rem; }
        .brand-points li { display: flex; align-items: center; gap: .8rem; color: rgba(255, 255, 255, 0.86); font-weight: 500; }
        .brand-points .pt-ico {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: grid;
            place-items: center;
            background: rgba(255, 255, 255, 0.10);
            border: 1px solid rgba(255, 255, 255, 0.16);
            color: #6ee7b7;
            flex-shrink: 0;
        }

        .brand-foot { position: relative; z-index: 2; }

        .clock-pill {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 8px 16px 8px 12px;
            border-radius: 99px;
            background: rgba(255, 255, 255, 0.09);
            border: 1px solid rgba(255, 255, 255, 0.18);
            backdrop-filter: blur(8px);
        }
        .clock-dot { position: relative; width: 8px; height: 8px; border-radius: 50%; background: var(--mint); }
        .clock-dot::after { content: ""; position: absolute; inset: -4px; border-radius: 50%; border: 2px solid var(--mint); opacity: 0; animation: clockPulse 2s ease-out infinite; }
        @keyframes clockPulse { 0% { transform: scale(.5); opacity: .7; } 100% { transform: scale(1.4); opacity: 0; } }
        .clock-time { font-family: 'Bricolage Grotesque', sans-serif; font-weight: 700; font-variant-numeric: tabular-nums; }
        .clock-zone { font-size: .7rem; font-weight: 700; letter-spacing: .05em; color: #8fe9d7; background: rgba(20, 201, 167, 0.16); padding: 2px 8px; border-radius: 99px; }
        .clock-date { color: rgba(255, 255, 255, 0.65); font-size: .85rem; margin-left: 10px; }

        .brand-qr {
            position: absolute;
            right: -40px;
            bottom: -40px;
            width: clamp(220px, 28vw, 380px);
            opacity: .07;
            pointer-events: none;
            z-index: 1;
        }

        /* ---------- Panel form (kanan) ---------- */
        .auth-main {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 72px 20px 40px;
            background:
                radial-gradient(60% 40% at 100% 0%, var(--accent-soft) 0%, transparent 70%),
                var(--bg);
        }

        .theme-btn {
            position: absolute;
            top: calc(16px + env(safe-area-inset-top, 0px));
            right: 16px;
            width: 44px;
            height: 44px;
            display: grid;
            place-items: center;
            border-radius: 50%;
            background: var(--surface);
            border: 1px solid var(--line);
            color: var(--text);
            cursor: pointer;
            transition: all .18s ease;
        }
        .theme-btn:hover { border-color: var(--accent); color: var(--accent); }

        .mobile-brand { display: flex; align-items: center; gap: 12px; margin-bottom: 24px; }
        .mobile-brand .brand-name { color: var(--text); }
        @media (min-width: 992px) { .mobile-brand { display: none; } }

        .auth-card {
            width: 100%;
            max-width: 440px;
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 24px;
            box-shadow: var(--shadow-md);
            padding: clamp(1.5rem, 5vw, 2.5rem);
            transition: background-color .2s ease, border-color .2s ease;
        }

        .auth-head { margin-bottom: 1.6rem; }

        .auth-icon {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            display: grid;
            place-items: center;
            font-size: 1.6rem;
            background: var(--accent-soft);
            color: var(--accent);
            margin-bottom: 1.1rem;
        }

        .auth-title { font-size: 1.7rem; font-weight: 800; margin: 0 0 .35rem; color: var(--text); }
        .auth-sub { color: var(--muted); margin: 0; }

        /* Notifikasi error */
        .auth-alert {
            display: flex;
            align-items: flex-start;
            gap: .6rem;
            padding: .75rem .9rem;
            border-radius: 12px;
            background: var(--danger-bg);
            color: var(--danger-text);
            font-size: .92rem;
            font-weight: 500;
            margin-bottom: 1.1rem;
        }
        .auth-alert i { margin-top: 2px; }

        /* Form */
        .field { margin-bottom: 1.1rem; }
        .field label { display: block; font-weight: 600; font-size: .9rem; margin-bottom: .4rem; color: var(--text); }

        .field-box { position: relative; }
        .field-box > i.lead-ico { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--muted); pointer-events: none; transition: color .15s ease; }

        .field-box input {
            width: 100%;
            height: 52px;
            padding: 0 46px 0 42px;
            border-radius: 13px;
            border: 1.5px solid var(--line);
            background: var(--surface-2);
            color: var(--text);
            font-size: 1rem;
            transition: border-color .15s ease, box-shadow .15s ease, background-color .15s ease;
        }
        .field-box input::placeholder { color: var(--muted); opacity: .75; }
        .field-box input:focus { outline: 0; border-color: var(--accent); background: var(--surface); box-shadow: 0 0 0 4px var(--accent-soft); }
        .field-box:focus-within > i.lead-ico { color: var(--accent); }
        .field-box.has-error input { border-color: #f43f5e; }

        .toggle-pass {
            position: absolute;
            right: 6px;
            top: 50%;
            transform: translateY(-50%);
            width: 40px;
            height: 40px;
            display: grid;
            place-items: center;
            border: 0;
            border-radius: 10px;
            background: transparent;
            color: var(--muted);
            cursor: pointer;
        }
        .toggle-pass:hover { color: var(--accent); background: var(--accent-soft); }

        .btn-auth {
            width: 100%;
            height: 52px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .6rem;
            border: 0;
            border-radius: 13px;
            color: #fff;
            font-weight: 700;
            font-size: 1rem;
            background: linear-gradient(135deg, var(--btn-1) 0%, var(--btn-2) 100%);
            box-shadow: 0 10px 24px var(--accent-soft);
            margin-top: .4rem;
            transition: transform .18s ease, box-shadow .18s ease, opacity .18s ease;
        }
        .btn-auth:hover { transform: translateY(-1px); box-shadow: 0 14px 28px var(--accent-soft); }
        .btn-auth:disabled { opacity: .75; transform: none; cursor: progress; }
        .btn-auth .spin { display: none; }
        .btn-auth.is-loading .spin { display: inline-block; }

        .auth-back {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            margin-top: 1.4rem;
            color: var(--accent);
            font-weight: 600;
            text-decoration: none;
        }
        .auth-back:hover { text-decoration: underline; }

        .auth-foot { margin-top: 1.4rem; color: var(--muted); font-size: .8rem; text-align: center; }

        /* ---------- Pilihan peran (halaman choose-login) ---------- */
        .role-list { display: grid; gap: .8rem; }

        .role-row {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: .9rem 1rem;
            border: 1.5px solid var(--line);
            border-radius: 16px;
            background: var(--surface);
            color: var(--text);
            text-decoration: none;
            transition: border-color .18s ease, transform .18s ease, background-color .18s ease;
        }
        .role-row:hover { border-color: var(--rc); background: var(--surface-2); transform: translateY(-2px); color: var(--text); }

        .role-row .r-ico { width: 48px; height: 48px; border-radius: 14px; display: grid; place-items: center; font-size: 1.4rem; flex-shrink: 0; background: var(--rs); color: var(--rc); }
        .role-row .r-name { font-weight: 700; line-height: 1.2; }
        .role-row .r-desc { color: var(--muted); font-size: .85rem; }
        .role-row .r-go { margin-left: auto; color: var(--rc); font-size: 1.2rem; flex-shrink: 0; }

        .r-amber { --rc: #c9811a; --rs: rgba(201, 129, 26, 0.15); }
        .r-teal  { --rc: #0f9f86; --rs: rgba(15, 159, 134, 0.15); }
        .r-blue  { --rc: #3b63f0; --rs: rgba(59, 99, 240, 0.13); }
        [data-theme="dark"] .r-amber { --rc: #f5b34a; }
        [data-theme="dark"] .r-teal  { --rc: #3fe0c1; }
        [data-theme="dark"] .r-blue  { --rc: #8fa8ff; }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { animation: none !important; transition: none !important; }
        }
    </style>
</head>
<body>

<div class="auth-shell">

    {{-- Panel brand (laptop) --}}
    <aside class="auth-brand">
        <div class="brand-row">
            <span class="brand-mark"><i class="bi bi-qr-code-scan"></i></span>
            <span class="brand-name">Absensi Kampus</span>
        </div>

        <div class="brand-body">
            <p class="brand-title">@yield('brand_title', 'Presensi kampus, cukup sekali pindai.')</p>
            <p class="brand-lead">@yield('brand_lead', 'Dosen membuka sesi, mahasiswa memindai QR Code, dan rekap kehadiran tersusun otomatis.')</p>

            <ul class="brand-points">
                <li><span class="pt-ico"><i class="bi bi-qr-code-scan"></i></span> Presensi lewat QR Code</li>
                <li><span class="pt-ico"><i class="bi bi-lightning-charge-fill"></i></span> Data kehadiran real-time</li>
                <li><span class="pt-ico"><i class="bi bi-file-earmark-bar-graph-fill"></i></span> Rekap dan laporan otomatis</li>
            </ul>
        </div>

        <div class="brand-foot">
            <div class="clock-pill" role="timer" aria-label="Waktu Indonesia Barat">
                <span class="clock-dot" aria-hidden="true"></span>
                <span class="clock-time" id="wib-time">{{ now('Asia/Jakarta')->format('H:i:s') }}</span>
                <span class="clock-zone">WIB</span>
            </div>
            <span class="clock-date" id="wib-date">{{ now('Asia/Jakarta')->translatedFormat('l, d F Y') }}</span>
        </div>

        <svg class="brand-qr" viewBox="0 0 120 120" aria-hidden="true" focusable="false">
            <g fill="none" stroke="#fff" stroke-width="6">
                <rect x="3" y="3" width="36" height="36" rx="4"/>
                <rect x="81" y="3" width="36" height="36" rx="4"/>
                <rect x="3" y="81" width="36" height="36" rx="4"/>
            </g>
            <g fill="#fff">
                <rect x="13" y="13" width="16" height="16" rx="2"/>
                <rect x="91" y="13" width="16" height="16" rx="2"/>
                <rect x="13" y="91" width="16" height="16" rx="2"/>
                <rect x="52" y="6" width="10" height="10"/>
                <rect x="52" y="24" width="10" height="10"/>
                <rect x="6" y="52" width="10" height="10"/>
                <rect x="24" y="52" width="10" height="10"/>
                <rect x="52" y="52" width="10" height="10"/>
                <rect x="70" y="52" width="10" height="10"/>
                <rect x="88" y="52" width="10" height="10"/>
                <rect x="52" y="70" width="10" height="10"/>
                <rect x="52" y="88" width="10" height="10"/>
                <rect x="70" y="88" width="10" height="10"/>
                <rect x="88" y="70" width="10" height="10"/>
                <rect x="106" y="88" width="10" height="10"/>
                <rect x="88" y="106" width="10" height="10"/>
            </g>
        </svg>
    </aside>

    {{-- Panel form --}}
    <main class="auth-main">
        <button type="button" id="btn-theme-toggle" class="theme-btn" onclick="toggleTheme()" title="Ganti ke Mode Gelap" aria-label="Ganti tema">
            <i id="theme-icon" class="bi bi-moon-stars-fill"></i>
        </button>

        <div class="mobile-brand">
            <span class="brand-mark"><i class="bi bi-qr-code-scan"></i></span>
            <span class="brand-name">Absensi Kampus</span>
        </div>

        <div class="auth-card">
            @yield('content')
        </div>

        <p class="auth-foot">&copy; {{ date('Y') }} Sistem Absensi Kampus</p>
    </main>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    /* ---------- Tema ---------- */
    function updateThemeUI(theme) {
        var icon = document.getElementById('theme-icon');
        var btn  = document.getElementById('btn-theme-toggle');
        if (!icon || !btn) return;
        if (theme === 'dark') { icon.className = 'bi bi-sun-fill text-warning'; btn.title = 'Ganti ke Mode Terang'; }
        else { icon.className = 'bi bi-moon-stars-fill'; btn.title = 'Ganti ke Mode Gelap'; }
    }

    function toggleTheme() {
        var current = document.documentElement.getAttribute('data-theme') || 'light';
        var next = current === 'dark' ? 'light' : 'dark';
        document.documentElement.setAttribute('data-theme', next);
        document.documentElement.setAttribute('data-bs-theme', next);
        try { localStorage.setItem('absensi_theme', next); } catch (e) {}
        updateThemeUI(next);
    }

    document.addEventListener('DOMContentLoaded', function () {
        updateThemeUI(document.documentElement.getAttribute('data-theme') || 'light');

        /* Tampil / sembunyikan password */
        document.querySelectorAll('[data-toggle-password]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var input = document.getElementById(btn.getAttribute('data-toggle-password'));
                if (!input) return;
                var show = input.type === 'password';
                input.type = show ? 'text' : 'password';
                btn.querySelector('i').className = show ? 'bi bi-eye-slash' : 'bi bi-eye';
                btn.setAttribute('aria-label', show ? 'Sembunyikan password' : 'Tampilkan password');
            });
        });

        /* Tombol masuk: tampilkan loading agar tidak terklik dua kali */
        document.querySelectorAll('form.auth-form').forEach(function (form) {
            form.addEventListener('submit', function () {
                var btn = form.querySelector('.btn-auth');
                if (!btn) return;
                btn.disabled = true;
                btn.classList.add('is-loading');
            });
        });
    });

    // Tombol Back di browser: kembalikan tombol ke keadaan semula
    window.addEventListener('pageshow', function (e) {
        if (!e.persisted) return;
        document.querySelectorAll('.btn-auth').forEach(function (b) { b.disabled = false; b.classList.remove('is-loading'); });
    });

    /* ---------- Jam WIB, sinkron dengan waktu server ---------- */
    (function () {
        var timeEl = document.getElementById('wib-time');
        var dateEl = document.getElementById('wib-date');
        if (!timeEl) return;

        var TZ = 'Asia/Jakarta';
        var offset = {{ (int) round(microtime(true) * 1000) }} - Date.now();
        var timeFmt = new Intl.DateTimeFormat('en-GB', { timeZone: TZ, hour: '2-digit', minute: '2-digit', second: '2-digit', hourCycle: 'h23' });
        var dateFmt = new Intl.DateTimeFormat('id-ID', { timeZone: TZ, weekday: 'long', day: '2-digit', month: 'long', year: 'numeric' });

        function tick() {
            var now = new Date(Date.now() + offset);
            timeEl.textContent = timeFmt.format(now);
            if (dateEl) dateEl.textContent = dateFmt.format(now);
            setTimeout(tick, 1000 - (now.getTime() % 1000) + 5);
        }
        tick();
    })();

    /* ---------- Notifikasi SweetAlert2 Auth ---------- */
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: @json(session('success')),
            timer: 3000,
            timerProgressBar: true,
            showConfirmButton: false
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Login Gagal!',
            text: @json(session('error')),
            confirmButtonText: 'Coba Lagi'
        });
    @endif

    @if($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Login Gagal!',
            html: `{!! implode('<br>', $errors->all()) !!}`,
            confirmButtonText: 'Coba Lagi'
        });
    @endif
</script>
</body>
</html>