<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#091933">
    <meta name="description" content="Absensi Kampus: sistem presensi digital berbasis QR Code untuk mahasiswa dan dosen, lengkap dengan sesi kelas, rekap otomatis, dan riwayat kehadiran.">
    <title>Sistem Absensi Kampus - Portal Akademik</title>

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
            --navy: #0b2545;
            --blue: #3b63f0;
            --blue-600: #2c4fd6;
            --blue-soft: rgba(59, 99, 240, 0.10);
            --mint: #14c9a7;
            --amber: #f59e0b;
            --bg: #f3f5fa;
            --surface: #ffffff;
            --surface-2: #f8f9fd;
            --text: #14213d;
            --muted: #66718c;
            --line: #e4e8f1;
            --nav-bg: rgba(255, 255, 255, 0.80);
            --shadow-sm: 0 1px 2px rgba(20, 33, 61, 0.04), 0 4px 14px rgba(20, 33, 61, 0.04);
            --shadow-md: 0 14px 36px rgba(20, 33, 61, 0.10);
        }

        [data-theme="dark"] {
            --bg: #0a0f1d;
            --surface: #121a2e;
            --surface-2: #17213a;
            --text: #eef2f9;
            --muted: #9aa8c2;
            --line: #223050;
            --blue: #5b82ff;
            --blue-600: #4a70f0;
            --blue-soft: rgba(91, 130, 255, 0.18);
            --nav-bg: rgba(18, 26, 46, 0.82);
            --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.30), 0 4px 14px rgba(0, 0, 0, 0.20);
            --shadow-md: 0 16px 36px rgba(0, 0, 0, 0.45);
        }

        html { scroll-behavior: smooth; -webkit-text-size-adjust: 100%; }

        body {
            font-family: 'Figtree', sans-serif;
            background-color: var(--bg);
            color: var(--text);
            background-image:
                radial-gradient(at 0% 0%, rgba(59, 99, 240, 0.07) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(20, 201, 167, 0.07) 0px, transparent 50%);
            min-height: 100vh;
            min-height: 100dvh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
            transition: background-color .2s ease, color .2s ease;
        }

        main { flex: 1 0 auto; }

        h1, h2, h3, h4, h5, h6, .display-font {
            font-family: 'Bricolage Grotesque', sans-serif;
            letter-spacing: -0.025em;
        }

        .min-w-0 { min-width: 0; }

        :focus-visible { outline: 3px solid rgba(59, 99, 240, 0.55); outline-offset: 2px; }
        .hero-banner :focus-visible { outline-color: #fff; }

        /* Penyesuaian utilitas Bootstrap di mode gelap */
        [data-theme="dark"] .text-secondary,
        [data-theme="dark"] .text-muted { color: var(--muted) !important; }
        [data-theme="dark"] .dropdown-menu { background: var(--surface); border: 1px solid var(--line) !important; }
        [data-theme="dark"] .dropdown-item { color: var(--text); }
        [data-theme="dark"] .dropdown-item:hover { background: var(--surface-2); }
        [data-theme="dark"] .dropdown-divider { border-color: var(--line); }

        /* ---------- Navbar ---------- */
        .navbar-custom {
            background: var(--nav-bg);
            backdrop-filter: saturate(160%) blur(14px);
            -webkit-backdrop-filter: saturate(160%) blur(14px);
            border-bottom: 1px solid var(--line);
        }

        .navbar-brand { color: var(--text); }
        .navbar-brand:hover { color: var(--text); }

        .brand-logo {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--mint) 0%, #0ea58a 100%);
            color: #06302a;
            box-shadow: 0 8px 18px rgba(20, 201, 167, 0.28);
            flex-shrink: 0;
        }

        .brand-text { font-size: 1.08rem; }

        .btn-login-nav {
            background: linear-gradient(135deg, var(--blue) 0%, var(--blue-600) 100%);
            color: #fff;
            border: 0;
            border-radius: 12px;
            min-height: 44px;
            padding: 8px 16px;
            font-weight: 600;
            box-shadow: 0 6px 16px rgba(59, 99, 240, 0.28);
            white-space: nowrap;
        }
        .btn-login-nav:hover, .btn-login-nav:focus, .btn-login-nav.show { color: #fff; background: linear-gradient(135deg, var(--blue-600) 0%, #1f3bb0 100%); }

        .icon-btn {
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
        .icon-btn:hover { background: var(--blue-soft); border-color: var(--blue); color: var(--blue); }

        .active-role-pill {
            background: rgba(20, 201, 167, 0.12);
            border: 1px solid rgba(20, 201, 167, 0.35);
            color: #0b8a73;
            padding: 6px 14px;
            border-radius: 99px;
            font-size: .82rem;
            font-weight: 600;
            transition: background .18s ease;
        }
        .active-role-pill:hover {
            background: rgba(20, 201, 167, 0.20);
            color: #0b8a73;
        }
        [data-theme="dark"] .active-role-pill,
        [data-theme="dark"] .active-role-pill:hover { color: #3fe0c1; }

        /* Jam WIB */
        .clock-pill {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 99px;
            padding: 6px 14px 6px 12px;
            box-shadow: var(--shadow-sm);
            white-space: nowrap;
        }
        .clock-dot { position: relative; width: 8px; height: 8px; border-radius: 50%; background: var(--mint); flex-shrink: 0; }
        .clock-dot::after { content: ""; position: absolute; inset: -4px; border-radius: 50%; border: 2px solid var(--mint); opacity: 0; animation: clockPulse 2s ease-out infinite; }
        @keyframes clockPulse { 0% { transform: scale(.5); opacity: .7; } 100% { transform: scale(1.4); opacity: 0; } }
        .clock-date { color: var(--muted); font-size: .8rem; font-weight: 500; padding-right: 10px; border-right: 1px solid var(--line); }
        .clock-time { font-family: 'Bricolage Grotesque', sans-serif; font-weight: 700; font-size: 1rem; color: var(--text); font-variant-numeric: tabular-nums; }
        .clock-zone { font-size: .7rem; font-weight: 700; color: var(--blue); background: var(--blue-soft); padding: 2px 7px; border-radius: 99px; letter-spacing: .04em; }

        .login-menu { min-width: min(280px, 92vw); }

        /* ---------- Hero ---------- */
        .hero-banner {
            background:
                radial-gradient(60% 80% at 100% 0%, rgba(20, 201, 167, 0.26) 0%, transparent 60%),
                linear-gradient(135deg, #091933 0%, #16336b 60%, #2c4fd6 100%);
            border-radius: clamp(20px, 4vw, 32px);
            color: #fff;
            position: relative;
            overflow: hidden;
            padding: clamp(1.25rem, 4.5vw, 3.5rem);
            box-shadow: var(--shadow-md);
        }
        [data-theme="dark"] .hero-banner { border: 1px solid var(--line); }

        .hero-qr {
            position: absolute;
            right: -34px;
            bottom: -34px;
            width: clamp(170px, 28vw, 330px);
            opacity: .09;
            pointer-events: none;
            z-index: 1;
        }

        .hero-content { position: relative; z-index: 2; }

        .hero-title {
            font-size: clamp(1.7rem, 1rem + 3.4vw, 3rem);
            line-height: 1.1;
            font-weight: 800;
            margin-bottom: 1rem;
            text-wrap: balance;
            color: #fff;
        }

        .hero-lead {
            font-size: clamp(.95rem, .85rem + .4vw, 1.1rem);
            line-height: 1.65;
            color: rgba(255, 255, 255, 0.74);
            max-width: 36rem;
            margin-bottom: 1.5rem;
        }

        .glass-pill { background: rgba(255, 255, 255, 0.12); border: 1px solid rgba(255, 255, 255, 0.25); border-radius: 99px; }

        .feature-chip {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            padding: .5rem .9rem;
            font-size: .88rem;
            font-weight: 600;
            background: rgba(255, 255, 255, 0.10);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 99px;
        }
        .feature-chip i { color: #6ee7b7; }


        .icon-box {
            width: 46px;
            height: 46px;
            border-radius: 13px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 21px;
            flex-shrink: 0;
        }
        .ib-amber { background: rgba(245, 158, 11, 0.16); color: #d97706; }
        .ib-teal  { background: rgba(20, 201, 167, 0.16); color: #0e9f86; }
        .ib-blue  { background: rgba(59, 99, 240, 0.14);  color: #3b63f0; }
        [data-theme="dark"] .ib-amber { color: #fbbf24; }
        [data-theme="dark"] .ib-teal  { color: #3fe0c1; }
        [data-theme="dark"] .ib-blue  { color: #8fa8ff; }

        /* Kartu sesi aktif (sudah login) */
        .session-glass-card {
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.22);
            border-radius: 20px;
            color: #fff;
            padding: clamp(1rem, 3vw, 1.5rem);
        }
        .avatar-lg {
            width: 58px;
            height: 58px;
            flex-shrink: 0;
            transition: transform .15s ease;
        }
        .avatar-lg:hover {
            transform: scale(1.05);
        }

        .status-dot { width: 9px; height: 9px; border-radius: 50%; background: #34d399; display: inline-block; flex-shrink: 0; box-shadow: 0 0 0 4px rgba(52, 211, 153, 0.25); }

        .btn-dashboard-cta {
            background: #fff;
            color: #0f172a;
            border: 0;
            border-radius: 14px;
            min-height: 50px;
            padding: 12px 24px;
            font-weight: 700;
            box-shadow: 0 10px 24px rgba(0, 0, 0, 0.18);
        }
        .btn-dashboard-cta:hover, .btn-dashboard-cta:focus { background: #f8fafc; color: var(--blue); }

        /* ---------- Kartu statistik ---------- */
        .stat-card {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 20px;
            box-shadow: var(--shadow-sm);
            padding: 1.1rem 1.15rem;
            height: 100%;
            transition: background-color .2s ease, border-color .2s ease;
        }
        .stat-head { display: flex; align-items: center; justify-content: space-between; gap: .6rem; margin-bottom: .9rem; }
        .stat-label { font-size: .84rem; font-weight: 600; color: var(--muted); line-height: 1.25; }

        .stat-icon { width: 38px; height: 38px; border-radius: 11px; display: grid; place-items: center; flex-shrink: 0; font-size: 1rem; }
        .tone-blue  .stat-icon { background: rgba(59, 99, 240, 0.12);  color: #3b63f0; }
        .tone-mint  .stat-icon { background: rgba(20, 201, 167, 0.14); color: #0ea58a; }
        .tone-sky   .stat-icon { background: rgba(14, 165, 233, 0.13); color: #0284c7; }
        .tone-amber .stat-icon { background: rgba(245, 158, 11, 0.15); color: #d97706; }
        [data-theme="dark"] .tone-blue  .stat-icon { background: rgba(91, 130, 255, 0.20); color: #8fa8ff; }
        [data-theme="dark"] .tone-mint  .stat-icon { background: rgba(20, 201, 167, 0.20); color: #3fe0c1; }
        [data-theme="dark"] .tone-sky   .stat-icon { background: rgba(56, 189, 248, 0.20); color: #7dd3fc; }
        [data-theme="dark"] .tone-amber .stat-icon { background: rgba(245, 158, 11, 0.20); color: #fbbf24; }

        /* Angka selalu memakai var(--text) agar terlihat di mode gelap */
        .stat-number {
            font-family: 'Bricolage Grotesque', sans-serif;
            font-size: clamp(1.8rem, 1.2rem + 2vw, 2.4rem);
            font-weight: 800;
            line-height: 1;
            letter-spacing: -0.03em;
            color: var(--text);
            font-variant-numeric: tabular-nums;
        }
        .stat-note { color: var(--muted); font-size: .8rem; margin-top: .5rem; }

        .live-dot { width: 9px; height: 9px; border-radius: 50%; background: var(--amber); display: inline-block; animation: pulse 1.8s ease-out infinite; }
        @keyframes pulse { 0% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.5); } 100% { box-shadow: 0 0 0 10px rgba(245, 158, 11, 0); } }

        /* ---------- Kartu konten ---------- */
        .content-card {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 20px;
            box-shadow: var(--shadow-sm);
            padding: clamp(1rem, 3vw, 1.75rem);
            transition: background-color .2s ease, border-color .2s ease;
        }
        .content-card h2 { color: var(--text); }
        .content-card h2 i { color: var(--blue); }

        .date-chip { font-size: .8rem; font-weight: 600; color: var(--blue); background: var(--blue-soft); border-radius: 99px; padding: 6px 14px; }

        /* ---------- Jadwal ---------- */
        .sched-list { list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: .6rem; }

        .sched {
            display: grid;
            grid-template-columns: auto 1fr auto;
            align-items: center;
            gap: .4rem 1rem;
            padding: .85rem 1rem;
            border: 1px solid var(--line);
            border-left: 4px solid var(--line);
            border-radius: 14px;
            background: var(--surface);
            transition: background-color .2s ease, border-color .2s ease;
        }
        .sched[data-status="berlangsung"] { border-left-color: var(--mint); background: rgba(20, 201, 167, 0.07); }
        .sched[data-status="akan"]        { border-left-color: var(--blue); }
        .sched[data-status="selesai"]     { opacity: .62; }

        .sched-time { text-align: center; min-width: 3.9rem; line-height: 1.25; }
        .sched-time strong { display: block; font-family: 'Bricolage Grotesque', sans-serif; font-size: 1.05rem; color: var(--text); font-variant-numeric: tabular-nums; }
        .sched-time span { font-size: .78rem; color: var(--muted); font-variant-numeric: tabular-nums; }

        .sched-title { font-weight: 700; color: var(--text); line-height: 1.3; }
        .sched-sub { font-size: .82rem; color: var(--muted); margin-top: 2px; }

        .sched-meta { display: flex; flex-direction: column; align-items: flex-end; gap: .4rem; }

        .room-badge { display: inline-flex; align-items: center; gap: 5px; font-size: .78rem; font-weight: 600; color: var(--blue); background: var(--blue-soft); border-radius: 99px; padding: 3px 10px; white-space: nowrap; }

        .st-badge { font-size: .72rem; font-weight: 700; border-radius: 99px; padding: 3px 10px; white-space: nowrap; }
        .st-badge.st-berlangsung { background: rgba(20, 201, 167, 0.16); color: #0b8a73; }
        .st-badge.st-akan        { background: var(--blue-soft); color: var(--blue); }
        .st-badge.st-selesai     { background: rgba(100, 116, 139, 0.16); color: var(--muted); }
        [data-theme="dark"] .st-badge.st-berlangsung { color: #3fe0c1; }

        @media (max-width: 575.98px) {
            .sched { grid-template-columns: auto 1fr; }
            .sched-meta { grid-column: 1 / -1; flex-direction: row; justify-content: space-between; align-items: center; padding-top: .45rem; border-top: 1px dashed var(--line); }
        }

        .empty-state { padding: 2rem 1rem; text-align: center; color: var(--muted); border: 1px dashed var(--line); border-radius: 16px; background: var(--surface-2); }
        .empty-state i { font-size: 2rem; display: block; margin-bottom: .4rem; }
        .empty-state .fw-semibold { color: var(--text); }

        /* ---------- Alur penggunaan (urutan nyata, jadi memakai nomor) ---------- */
        .step-item { position: relative; padding-left: 3rem; padding-bottom: 1.4rem; }
        .step-item:last-child { padding-bottom: 0; }
        .step-item:not(:last-child)::before { content: ''; position: absolute; left: 15px; top: 34px; bottom: 4px; width: 2px; background: var(--line); }
        .step-item .fw-semibold { color: var(--text); }
        .step-num { position: absolute; left: 0; top: 0; width: 32px; height: 32px; border-radius: 50%; background: var(--blue); color: #fff; font-weight: 700; font-size: .9rem; display: flex; align-items: center; justify-content: center; }

        footer {
            background: var(--surface);
            border-top: 1px solid var(--line);
            color: var(--muted);
            padding-bottom: calc(1.5rem + env(safe-area-inset-bottom, 0px));
        }

        @media (hover: hover) {
                .btn-login-nav:hover { transform: translateY(-1px); }
            .btn-dashboard-cta:hover { transform: translateY(-2px); }
        }

        .hero-content { animation: rise .6s ease-out both; }
        @keyframes rise { from { opacity: 0; transform: translateY(14px); } to { opacity: 1; transform: none; } }

        @media (min-width: 768px) {
            .brand-text { font-size: 1.25rem; }
            .brand-logo { width: 40px; height: 40px; }
        }

        @media (max-width: 575.98px) {
                .icon-box { width: 42px; height: 42px; font-size: 19px; }
            .clock-pill { padding: 5px 12px 5px 10px; gap: 8px; }
            .clock-time { font-size: .92rem; }
            .clock-zone { display: none; }
        }

        @media (prefers-reduced-motion: reduce) {
            html { scroll-behavior: auto; }
            *, *::before, *::after { animation: none !important; transition: none !important; }
        }

        /* ---------- Konten publik ---------- */
        .mini-stat { background: var(--surface-2); border: 1px solid var(--line); border-radius: 16px; padding: .9rem 1rem; height: 100%; display: flex; flex-direction: column; gap: 2px; }
        .mini-stat i { color: var(--blue); font-size: 1.25rem; margin-bottom: .3rem; }
        .mini-stat strong { color: var(--text); font-family: 'Bricolage Grotesque', sans-serif; }
        .mini-stat span { color: var(--muted); font-size: .8rem; line-height: 1.35; }

        .feature-card { background: var(--surface); border: 1px solid var(--line); border-radius: 20px; box-shadow: var(--shadow-sm); padding: 1.4rem; transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease; }
        .feature-card h3 { color: var(--text); }
        @media (hover: hover) { .feature-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-md); border-color: var(--blue); } }
        .feature-list { list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: .55rem; }
        .feature-list li { position: relative; padding-left: 1.6rem; color: var(--muted); font-size: .92rem; line-height: 1.45; }
        .feature-list li::before { content: "\F26A"; font-family: 'bootstrap-icons'; position: absolute; left: 0; top: 0; color: var(--mint); }

        /* ---------- Ilustrasi hero (tamu) ---------- */
        .hero-visual { position: relative; max-width: 340px; margin: 0 auto; padding: 1.2rem 0; }

        .scan-card {
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border: 1px solid rgba(255, 255, 255, 0.24);
            border-radius: 28px;
            padding: 1.1rem;
            box-shadow: 0 24px 50px rgba(4, 12, 30, 0.35);
            color: #fff;
        }
        .scan-card-top { display: flex; align-items: center; gap: .55rem; margin-bottom: .9rem; font-size: .82rem; font-weight: 600; color: rgba(255, 255, 255, 0.85); }
        .scan-dot { width: 9px; height: 9px; border-radius: 50%; background: #34d399; box-shadow: 0 0 0 4px rgba(52, 211, 153, 0.25); animation: pulse 1.8s ease-out infinite; }

        .qr-box { position: relative; background: #fff; border-radius: 20px; padding: 16px; overflow: hidden; }
        .qr-box svg { display: block; width: 100%; height: auto; }
        .scan-line {
            position: absolute; left: 10px; right: 10px; top: 14px; height: 3px; border-radius: 3px;
            background: linear-gradient(90deg, transparent, var(--mint), transparent);
            box-shadow: 0 0 14px 2px rgba(20, 201, 167, 0.7);
            animation: scanMove 2.8s ease-in-out infinite;
        }
        @keyframes scanMove { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(calc(100% * 0 + 218px)); } }

        .scan-result { display: flex; align-items: center; gap: .75rem; margin-top: .9rem; background: #fff; color: #0f172a; border-radius: 16px; padding: .7rem .85rem; }
        .scan-result strong { display: block; font-family: 'Bricolage Grotesque', sans-serif; font-size: .95rem; line-height: 1.2; }
        .scan-result small { display: block; color: #64748b; font-size: .74rem; line-height: 1.3; margin-top: 2px; }
        .scan-check { width: 34px; height: 34px; border-radius: 50%; display: grid; place-items: center; background: rgba(20, 201, 167, 0.18); color: #0b8a73; font-size: 1.1rem; flex-shrink: 0; }

        .float-chip {
            position: absolute; z-index: 3; display: inline-flex; align-items: center; gap: .4rem;
            padding: .45rem .8rem; font-size: .78rem; font-weight: 700; color: #fff; white-space: nowrap;
            background: rgba(9, 25, 51, 0.72); border: 1px solid rgba(255, 255, 255, 0.25); border-radius: 99px;
            box-shadow: 0 10px 24px rgba(4, 12, 30, 0.3);
            animation: floaty 5s ease-in-out infinite;
        }
        .float-chip i { color: #6ee7b7; }
        .fc-1 { top: 0; right: -6px; }
        .fc-2 { bottom: 2px; left: -10px; animation-delay: -2.5s; }
        @keyframes floaty { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-7px); } }

        @media (max-width: 575.98px) {
            .hero-visual { max-width: 290px; }
            .fc-1 { right: 0; }
            .fc-2 { left: 0; }
            @keyframes scanMove { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(180px); } }
        }

        /* ---------- Penyesuaian HP ---------- */
        .stealth-link { color: inherit; text-decoration: none; cursor: default; display: inline-block; padding: .45rem .5rem; margin: -.45rem -.5rem; -webkit-tap-highlight-color: transparent; }
        .stealth-link:hover, .stealth-link:focus { color: inherit; text-decoration: none; }

        .mobile-clock {
            display: flex; align-items: center; justify-content: center; gap: .5rem;
            margin: -.25rem auto 1rem; padding: .4rem .9rem; width: fit-content; max-width: 100%;
            background: var(--surface); border: 1px solid var(--line); border-radius: 99px;
            font-size: .78rem; color: var(--muted); box-shadow: var(--shadow-sm);
        }
        .mobile-clock strong { font-family: 'Bricolage Grotesque', sans-serif; color: var(--text); font-size: .9rem; font-variant-numeric: tabular-nums; }

        .mlb-btn {
            display: flex; align-items: center; justify-content: center; gap: .5rem;
            min-height: 50px; border-radius: 14px; font-weight: 700; font-size: .95rem;
            text-decoration: none; color: #0b1a33; transition: transform .15s ease;
        }
        .mlb-btn:active { transform: scale(.97); }
        .mlb-btn:hover, .mlb-btn:focus { color: #0b1a33; }
        .mlb-amber { background: linear-gradient(135deg, #fbbf24, #f59e0b); }
        .mlb-teal  { background: linear-gradient(135deg, #34e0c0, #14c9a7); }

        @media (max-width: 767.98px) {
            .feature-card { display: grid; grid-template-columns: auto 1fr; column-gap: .9rem; align-items: start; padding: 1.1rem; }
            .feature-card .icon-box { margin-bottom: 0 !important; grid-row: 1 / span 2; }
            .feature-card h3 { margin-bottom: .6rem; align-self: center; }
            .feature-card .feature-list { grid-column: 2; }
        }


        /* ---------- Konten lengkap: fitur, perbandingan, FAQ ---------- */
        .section-head { text-align: center; max-width: 40rem; margin: 0 auto 1.75rem; }
        .section-head p { color: var(--muted); margin-bottom: 0; }
        .feat-tile { height: 100%; }
        .feat-tile h3 { font-size: 1.05rem; margin-bottom: .45rem; }
        .feat-tile p { color: var(--muted); font-size: .9rem; line-height: 1.55; margin: 0; }

        .compare-wrap { border: 1px solid var(--line); border-radius: 16px; overflow: hidden; }
        .compare-table { width: 100%; margin: 0; border-collapse: collapse; font-size: .92rem; min-width: 560px; }
        .compare-table th, .compare-table td { padding: .85rem 1rem; border-bottom: 1px solid var(--line); vertical-align: top; text-align: left; }
        .compare-table tr:last-child td { border-bottom: 0; }
        .compare-table thead th { background: var(--surface-2); font-family: 'Bricolage Grotesque', sans-serif; color: var(--text); font-size: .9rem; }
        .compare-table thead th.col-new { color: var(--blue); }
        .compare-table tbody th { color: var(--text); font-weight: 600; width: 24%; }
        .compare-table td { color: var(--muted); }
        .compare-table td.col-new { color: var(--text); background: var(--blue-soft); }
        .compare-table td i { margin-right: .35rem; }
        .compare-table td.col-old i { color: #ef4444; }
        .compare-table td.col-new i { color: var(--mint); }


        /* Tabel perbandingan menjadi kartu bertumpuk di HP/tablet kecil agar tidak terpotong */
        @media (max-width: 767.98px) {
            .compare-wrap { border: 0; border-radius: 0; overflow: visible; }
            .compare-table { min-width: 0; }
            .compare-table thead { display: none; }
            .compare-table, .compare-table tbody, .compare-table tr, .compare-table th, .compare-table td { display: block; width: 100%; }
            .compare-table tr { border: 1px solid var(--line); border-radius: 14px; overflow: hidden; margin-bottom: .75rem; background: var(--surface); }
            .compare-table tr:last-child { margin-bottom: 0; }
            .compare-table tbody th { width: 100%; background: var(--surface-2); border-bottom: 1px solid var(--line); padding: .7rem 1rem; }
            .compare-table td { border-bottom: 0; padding: .65rem 1rem; }
            .compare-table td::before { content: attr(data-label); display: block; font-size: .7rem; font-weight: 700; letter-spacing: .05em; text-transform: uppercase; color: var(--muted); margin-bottom: .2rem; }
            .compare-table td.col-new::before { color: var(--blue); }
            .compare-table tr:last-child td { border-bottom: 0; }
        }

        .faq .accordion-item { background: var(--surface); border: 1px solid var(--line); border-radius: 14px !important; overflow: hidden; margin-bottom: .6rem; }
        .faq .accordion-button { background: var(--surface); color: var(--text); font-weight: 600; box-shadow: none; padding: 1rem 1.15rem; }
        .faq .accordion-button:not(.collapsed) { background: var(--blue-soft); color: var(--blue); }
        .faq .accordion-body { color: var(--muted); line-height: 1.65; padding: 1rem 1.15rem 1.2rem; }

        .cta-band {
            background: radial-gradient(60% 90% at 100% 0%, rgba(20, 201, 167, 0.26) 0%, transparent 60%), linear-gradient(135deg, #091933 0%, #16336b 60%, #2c4fd6 100%);
            border-radius: clamp(20px, 4vw, 28px); color: #fff; padding: clamp(1.5rem, 5vw, 2.75rem); text-align: center; box-shadow: var(--shadow-md);
        }
        .cta-band h2 { color: #fff; }
        .cta-band p { color: rgba(255, 255, 255, 0.76); max-width: 34rem; margin: 0 auto 1.25rem; }
        .cta-band .mlb-btn { padding: 0 1.4rem; }
        .cta-band .mlb-btn { min-height: 52px; color: #0b1a33; box-shadow: 0 8px 20px rgba(0, 0, 0, 0.18); }
        @media (max-width: 575.98px) {
            .cta-band .d-grid { gap: .75rem !important; }
            .cta-band .mlb-btn { width: 100%; font-size: 1rem; }
        }

        @media (max-width: 575.98px) {
            .container { padding-left: 1rem; padding-right: 1rem; }
            .hero-banner { border-radius: 24px; padding: 1.4rem 1.15rem 1.6rem; }
            .hero-title { font-size: 1.75rem; line-height: 1.15; }
            .hero-lead { font-size: .95rem; line-height: 1.6; margin-bottom: 1.25rem; }
            .btn-dashboard-cta, .hero-banner .btn-outline-light { width: 100%; min-height: 52px; }
            .hero-banner .dropdown { width: 100%; }
            .hero-banner .dropdown-menu { width: min(300px, 86vw); }
            .feature-chip { font-size: .8rem; padding: .4rem .75rem; }
            .hero-visual { max-width: 250px; padding: 1.4rem 0 1.2rem; margin-top: .5rem; }
            .scan-card { padding: .9rem; border-radius: 24px; }
            .qr-box { padding: 12px; border-radius: 16px; }
            .scan-result small { display: none; }
            .fc-1 { top: .2rem; right: -4px; font-size: .72rem; }
            .fc-2 { bottom: 0; left: -4px; font-size: .72rem; }
            .content-card { border-radius: 18px; }
            .content-card h2.h3 { font-size: 1.3rem; }
            .mini-stat { padding: .75rem .8rem; }
            footer { font-size: .78rem; }
        }
    </style>
</head>
<body>

@php
    $isLoggedIn  = auth()->check();
    $currentUser = auth()->user();
    $currentRole = strtolower($currentUser->role ?? '');

    $roleTitle = match($currentRole) {
        'admin'     => 'Administrator',
        'dosen'     => 'Dosen',
        'mahasiswa' => 'Mahasiswa',
        default     => ucfirst($currentRole ?: 'Pengguna'),
    };

    $dashboardUrl = match($currentRole) {
        'admin'     => route('admin.dashboard'),
        'dosen'     => route('dosen.dashboard'),
        'mahasiswa' => route('mahasiswa.dashboard'),
        default     => url('/'),
    };

    // Mendukung folder 'uploads/' maupun 'storage/' + cache-busting agar foto terbaru langsung terbaca
    $userAvatar = null;
    if (!empty($currentUser?->foto)) {
        $rawPath = str_starts_with($currentUser->foto, 'uploads/')
            ? asset(ltrim($currentUser->foto, '/'))
            : asset('storage/' . ltrim($currentUser->foto, '/'));
        $userAvatar = $rawPath . '?v=' . optional($currentUser->updated_at)->timestamp;
    }

    // URL login admin (tombol rahasia di banner). Sesuaikan nama route / path jika berbeda.
    $adminLoginUrl = Route::has('admin.login') ? route('admin.login') : url('/admin/login');

    // Waktu acuan: WIB (hanya untuk jam di navbar)
    $nowWib = now('Asia/Jakarta');
@endphp

    <!-- Navbar Atas -->
    <nav class="navbar navbar-custom sticky-top py-2 py-md-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2 fw-bold m-0 min-w-0" href="{{ route('home') }}">
                <span class="brand-logo d-flex align-items-center justify-content-center">
                    <i class="bi bi-qr-code-scan fs-5"></i>
                </span>
                <span class="display-font brand-text text-truncate">Absensi Kampus</span>
            </a>

            <div class="d-flex align-items-center gap-2 flex-shrink-0">
                <!-- Jam & tanggal WIB (live, sinkron waktu server) -->
                <div class="clock-pill d-none d-sm-inline-flex" role="timer" aria-label="Waktu Indonesia Barat">
                    <span class="clock-dot" aria-hidden="true"></span>
                    <span class="clock-date d-none d-xl-inline" id="wib-date">{{ $nowWib->translatedFormat('l, d F Y') }}</span>
                    <span class="clock-time" id="wib-time">{{ $nowWib->format('H:i:s') }}</span>
                    <span class="clock-zone">WIB</span>
                </div>

                <button type="button" id="btn-theme-toggle" class="icon-btn" onclick="toggleTheme()" title="Ganti ke Mode Gelap" aria-label="Ganti tema">
                    <i id="theme-icon" class="bi bi-moon-stars-fill"></i>
                </button>

                @auth
                    <a href="{{ Route::has('profile.index') ? route('profile.index') : $dashboardUrl }}"
                       class="active-role-pill text-decoration-none d-none d-lg-inline-flex align-items-center gap-2"
                       title="Pengaturan Profil {{ $currentUser->name }}">
                        @if($userAvatar)
                            <img src="{{ $userAvatar }}" alt="{{ $currentUser->name }}"
                                 class="rounded-circle object-fit-cover" style="width: 22px; height: 22px;">
                        @else
                            <span class="status-dot"></span>
                        @endif
                        <span>Login: <strong>{{ $roleTitle }}</strong></span>
                    </a>

                    <a href="{{ $dashboardUrl }}" class="btn btn-login-nav d-inline-flex align-items-center gap-2">
                        <i class="bi bi-speedometer2"></i>
                        <span class="d-none d-sm-inline">Dashboard {{ $roleTitle }}</span>
                        <span class="d-sm-none">Dashboard</span>
                    </a>
                @else
                    <div class="dropdown">
                        <button class="btn btn-login-nav dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-box-arrow-in-right"></i>
                            <span class="d-none d-sm-inline">Masuk Portal</span>
                            <span class="d-sm-none">Masuk</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end login-menu shadow-lg border-0 rounded-4 p-2 mt-2">
                            <li>
                                <a class="dropdown-item rounded-3 py-2 d-flex align-items-center gap-3" href="{{ route('mahasiswa.login') }}">
                                    <i class="bi bi-mortarboard-fill fs-5" style="color:#d18a16;"></i>
                                    <div>
                                        <div class="fw-semibold">Login Mahasiswa</div>
                                        <small class="text-muted">Presensi & riwayat kelas</small>
                                    </div>
                                </a>
                            </li>
                            <li><hr class="dropdown-divider my-1"></li>
                            <li>
                                <a class="dropdown-item rounded-3 py-2 d-flex align-items-center gap-3" href="{{ route('dosen.login') }}">
                                    <i class="bi bi-person-workspace fs-5" style="color:#0f9f86;"></i>
                                    <div>
                                        <div class="fw-semibold">Login Dosen</div>
                                        <small class="text-muted">Kelola sesi & rekap</small>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    <main>
    <div class="container py-3 py-md-4 py-lg-5">

        <!-- Jam WIB untuk HP -->
        <div class="mobile-clock d-sm-none" role="timer" aria-label="Waktu Indonesia Barat">
            <span class="clock-dot" aria-hidden="true"></span>
            <span id="wib-date-m">{{ $nowWib->translatedFormat('l, d F Y') }}</span>
            <strong id="wib-time-m">{{ $nowWib->format('H:i:s') }}</strong>
            <span class="clock-zone">WIB</span>
        </div>

        <!-- Hero -->
        <section class="hero-banner mb-3 mb-md-4">
            <svg class="hero-qr" viewBox="0 0 120 120" aria-hidden="true" focusable="false">
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

            <div class="hero-content row align-items-center g-4 g-lg-5">
                <div class="col-lg-7">
                    @auth
                        <div class="glass-pill d-inline-flex align-items-center gap-2 px-3 py-2 mb-3 mw-100">
                            <span class="status-dot"></span>
                            <span class="small fw-semibold text-truncate">Login sebagai {{ $roleTitle }}, {{ $currentUser->name }}</span>
                        </div>
                    @else
                        <span class="glass-pill d-inline-flex align-items-center gap-2 px-3 py-2 mb-3 small fw-semibold">
                            <a href="{{ $adminLoginUrl }}" class="stealth-link" aria-label="Portal"><i class="bi bi-broadcast text-warning"></i></a> Portal Presensi Akademik Terintegrasi
                        </span>
                    @endauth

                    <h1 class="hero-title">Pantau Kehadiran & Aktivitas Perkuliahan Secara Real-Time</h1>
                    <p class="hero-lead">
                        Sistem absensi digital kampus untuk memudahkan mahasiswa melakukan presensi QR Code dan dosen mengelola pertemuan, dengan rekap kehadiran yang tersusun otomatis.
                    </p>

                    @auth
                        <div class="d-grid d-sm-flex flex-wrap align-items-center gap-3">
                            <a href="{{ $dashboardUrl }}" class="btn btn-dashboard-cta btn-lg d-inline-flex align-items-center justify-content-center gap-2">
                                <i class="bi bi-grid-1x2-fill text-primary"></i>
                                <span>Masuk ke Dashboard {{ $roleTitle }}</span>
                            </a>

                            <form action="{{ route('logout') }}" method="POST" class="d-grid" onsubmit="return confirm('Keluar dari akun {{ $roleTitle }}?')">
                                @csrf
                                <button type="submit" class="btn btn-outline-light btn-lg rounded-4 px-4 fw-semibold" style="min-height:50px;">
                                    <i class="bi bi-box-arrow-right me-1"></i> Ganti Akun / Logout
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="d-grid d-sm-flex flex-wrap align-items-center gap-3 mb-4">
                            <div class="dropdown d-grid">
                                <button type="button" class="btn btn-dashboard-cta btn-lg d-inline-flex align-items-center justify-content-center gap-2 dropdown-toggle" id="hero-login-btn" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-box-arrow-in-right text-primary"></i>
                                    <span>Masuk Portal</span>
                                </button>
                                <ul class="dropdown-menu login-menu shadow-lg border-0 rounded-4 p-2 mt-2" aria-labelledby="hero-login-btn">
                                    <li><a class="dropdown-item rounded-3 py-2 d-flex align-items-center gap-3" href="{{ route('mahasiswa.login') }}"><i class="bi bi-mortarboard-fill fs-5" style="color:#d18a16;"></i><span class="fw-semibold">Mahasiswa</span></a></li>
                                    <li><a class="dropdown-item rounded-3 py-2 d-flex align-items-center gap-3" href="{{ route('dosen.login') }}"><i class="bi bi-person-workspace fs-5" style="color:#0f9f86;"></i><span class="fw-semibold">Dosen</span></a></li>
                                </ul>
                            </div>
                            <a href="#fitur" class="btn btn-outline-light btn-lg rounded-4 px-4 fw-semibold d-inline-flex align-items-center justify-content-center gap-2" style="min-height:50px;">
                                <i class="bi bi-arrow-down-circle"></i> Lihat Fitur
                            </a>
                        </div>
                        <div class="d-flex flex-wrap gap-2">
                            <span class="feature-chip"><i class="bi bi-qr-code-scan"></i> Presensi scan QR</span>
                            <span class="feature-chip"><i class="bi bi-lightning-charge-fill"></i> Data real-time</span>
                            <span class="feature-chip"><i class="bi bi-file-earmark-bar-graph-fill"></i> Rekap otomatis</span>
                        </div>
                    @endauth
                </div>

                <!-- Sisi kanan hero -->
                <div class="col-lg-5 @guest d-none d-lg-block @endguest">
                    @auth
                        <div class="session-glass-card">
                            <div class="d-flex align-items-center gap-3 mb-3 pb-3 border-bottom border-white border-opacity-25">
                                <a href="{{ Route::has('profile.index') ? route('profile.index') : '#' }}" title="Ubah Foto Profil" class="text-decoration-none">
                                    @if($userAvatar)
                                        <img src="{{ $userAvatar }}" alt="{{ $currentUser->name }}"
                                             class="avatar-lg rounded-circle object-fit-cover border border-2 border-white">
                                    @else
                                        <div class="avatar-lg rounded-circle bg-white text-primary fw-bold d-flex align-items-center justify-content-center fs-4">
                                            {{ strtoupper(substr($currentUser->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </a>
                                <div class="min-w-0">
                                    <span class="badge bg-success bg-opacity-75 rounded-pill mb-1">
                                        <i class="bi bi-check-circle-fill me-1"></i> Sesi Aktif ({{ $roleTitle }})
                                    </span>
                                    <h5 class="fw-bold mb-0 text-truncate text-white">{{ $currentUser->name }}</h5>
                                    <small class="text-white-50 text-truncate d-block">{{ $currentUser->email }}</small>
                                </div>
                            </div>

                            <p class="small text-white-50 mb-3">
                                Anda sudah masuk ke dalam sistem. Klik tombol di bawah untuk langsung melanjutkan aktivitas pada panel <strong class="text-white">{{ $roleTitle }}</strong>.
                            </p>

                            <div class="d-grid gap-2">
                                <a href="{{ $dashboardUrl }}" class="btn btn-light fw-bold text-primary rounded-3 py-2">
                                    Lanjut ke Dashboard {{ $roleTitle }}
                                </a>
                                @if(Route::has('profile.index'))
                                    <a href="{{ route('profile.index') }}" class="btn btn-outline-light btn-sm rounded-3 py-2">
                                        <i class="bi bi-person-circle me-1"></i> Pengaturan Profil & Foto
                                    </a>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="hero-visual d-none d-lg-block" aria-hidden="true">
                            <div class="float-chip fc-1"><i class="bi bi-lightning-charge-fill"></i> Real-time</div>
                            <div class="float-chip fc-2"><i class="bi bi-file-earmark-bar-graph-fill"></i> Rekap otomatis</div>

                            <div class="scan-card">
                                <div class="scan-card-top">
                                    <span class="scan-dot"></span>
                                    <span class="scan-label">Sesi presensi dibuka</span>
                                </div>

                                <div class="qr-box">
                                    <svg viewBox="0 0 120 120" focusable="false">
                                        <g fill="none" stroke="#0b2545" stroke-width="6">
                                            <rect x="3" y="3" width="36" height="36" rx="4"/>
                                            <rect x="81" y="3" width="36" height="36" rx="4"/>
                                            <rect x="3" y="81" width="36" height="36" rx="4"/>
                                        </g>
                                        <g fill="#0b2545">
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
                                    <span class="scan-line"></span>
                                </div>

                                <div class="scan-result">
                                    <span class="scan-check"><i class="bi bi-check-lg"></i></span>
                                    <span>
                                        <strong>Kehadiran tercatat</strong>
                                        <small>Cukup scan, selesai dalam hitungan detik</small>
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endauth
                </div>
            </div>
        </section>

        <!-- Apa itu Absensi Kampus -->
        <section class="mb-4 mb-md-5" aria-labelledby="tentang-title">
            <div class="content-card">
                <div class="row g-4 align-items-center">
                    <div class="col-lg-7">
                        <span class="date-chip d-inline-block mb-3">Tentang Aplikasi</span>
                        <h2 id="tentang-title" class="h3 fw-bold mb-3">Absensi kuliah tanpa kertas, tanpa tanda tangan, tanpa titip absen</h2>
                        <p class="text-secondary mb-3">
                            Absensi Kampus adalah aplikasi web untuk mencatat kehadiran perkuliahan secara digital.
                            Dosen cukup membuka sesi kelas dan menampilkan QR Code, lalu mahasiswa memindainya dari ponsel
                            untuk mencatat kehadiran. Semua data tersimpan otomatis dan langsung bisa direkap.
                        </p>
                        <p class="text-secondary mb-0">
                            Dengan begitu dosen tidak perlu memanggil nama satu per satu, mahasiswa tidak perlu antre tanda tangan,
                            dan pihak kampus tidak perlu menyalin data dari lembar kertas ke spreadsheet.
                        </p>
                        <p class="text-secondary mt-3 mb-0">
                            Aplikasi ini berjalan di browser, jadi tidak perlu memasang aplikasi tambahan. Cukup buka dari
                            ponsel atau laptop, masuk dengan akun yang diberikan kampus, dan semua fitur sesuai peran Anda langsung tersedia.
                        </p>
                    </div>
                    <div class="col-lg-5">
                        <div class="row g-3">
                            <div class="col-6"><div class="mini-stat"><i class="bi bi-stopwatch-fill"></i><strong>Cepat</strong><span>Presensi dalam hitungan detik</span></div></div>
                            <div class="col-6"><div class="mini-stat"><i class="bi bi-shield-check"></i><strong>Akurat</strong><span>Satu QR untuk satu sesi kelas</span></div></div>
                            <div class="col-6"><div class="mini-stat"><i class="bi bi-file-earmark-spreadsheet-fill"></i><strong>Otomatis</strong><span>Rekap tanpa input manual</span></div></div>
                            <div class="col-6"><div class="mini-stat"><i class="bi bi-phone-fill"></i><strong>Fleksibel</strong><span>Akses dari ponsel maupun laptop</span></div></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Fitur lengkap -->
        <section id="fitur" class="mb-4 mb-md-5" aria-labelledby="fitur-title" style="scroll-margin-top: 90px;">
            <div class="section-head">
                <h2 id="fitur-title" class="h3 fw-bold mb-2">Fitur lengkap untuk perkuliahan sehari-hari</h2>
                <p>Dari membuka kelas sampai laporan akhir semester, semuanya ada dalam satu portal.</p>
            </div>
            <div class="row g-3 g-md-4">
                <div class="col-sm-6 col-lg-3">
                    <article class="feature-card feat-tile">
                        <div class="icon-box ib-blue mb-3"><i class="bi bi-qr-code-scan"></i></div>
                        <h3 class="fw-bold">Presensi scan QR</h3>
                        <p>Mahasiswa memindai QR Code kelas dari ponsel, dan kehadiran langsung tercatat dalam hitungan detik.</p>
                    </article>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <article class="feature-card feat-tile">
                        <div class="icon-box ib-teal mb-3"><i class="bi bi-door-open-fill"></i></div>
                        <h3 class="fw-bold">Sesi kelas terkontrol</h3>
                        <p>Dosen membuka dan menutup sesi perkuliahan. Presensi hanya sah selama sesi kelas dibuka.</p>
                    </article>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <article class="feature-card feat-tile">
                        <div class="icon-box ib-amber mb-3"><i class="bi bi-lightning-charge-fill"></i></div>
                        <h3 class="fw-bold">Data real-time</h3>
                        <p>Kehadiran yang masuk langsung terlihat oleh dosen selama kelas berlangsung, tanpa perlu muat ulang data manual.</p>
                    </article>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <article class="feature-card feat-tile">
                        <div class="icon-box ib-blue mb-3"><i class="bi bi-file-earmark-bar-graph-fill"></i></div>
                        <h3 class="fw-bold">Rekap otomatis</h3>
                        <p>Rekap kehadiran tersusun sendiri per pertemuan sampai laporan akhir semester, tanpa input manual.</p>
                    </article>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <article class="feature-card feat-tile">
                        <div class="icon-box ib-teal mb-3"><i class="bi bi-clock-history"></i></div>
                        <h3 class="fw-bold">Riwayat & persentase</h3>
                        <p>Mahasiswa dapat melihat riwayat kehadiran per mata kuliah dan memantau persentase kehadiran sendiri.</p>
                    </article>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <article class="feature-card feat-tile">
                        <div class="icon-box ib-amber mb-3"><i class="bi bi-patch-check-fill"></i></div>
                        <h3 class="fw-bold">Validasi kehadiran</h3>
                        <p>Dosen dapat memantau dan memvalidasi kehadiran mahasiswa pada setiap pertemuan.</p>
                    </article>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <article class="feature-card feat-tile">
                        <div class="icon-box ib-blue mb-3"><i class="bi bi-person-lock"></i></div>
                        <h3 class="fw-bold">Akses sesuai peran</h3>
                        <p>Setiap pengguna punya panel sendiri dan hanya melihat data yang memang menjadi haknya.</p>
                    </article>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <article class="feature-card feat-tile">
                        <div class="icon-box ib-teal mb-3"><i class="bi bi-phone-fill"></i></div>
                        <h3 class="fw-bold">Nyaman di semua perangkat</h3>
                        <p>Tampilan menyesuaikan ponsel dan laptop, tersedia mode terang atau gelap, dan jam selalu mengikuti WIB.</p>
                    </article>
                </div>
            </div>
        </section>

        <!-- Kegunaan per peran -->
        <section id="peran" class="mb-4 mb-md-5" aria-labelledby="peran-title" style="scroll-margin-top: 90px;">
            <div class="text-center mb-4">
                <h2 id="peran-title" class="h3 fw-bold mb-2">Apa yang bisa dilakukan?</h2>
                <p class="text-secondary mb-0">Mahasiswa dan dosen punya panel sendiri sesuai kebutuhannya.</p>
            </div>
            <div class="row g-3 g-md-4">
                <div class="col-md-6">
                    <article class="feature-card tone-amber h-100">
                        <div class="icon-box ib-amber mb-3"><i class="bi bi-mortarboard-fill"></i></div>
                        <h3 class="h5 fw-bold">Mahasiswa</h3>
                        <ul class="feature-list">
                            <li>Scan QR untuk presensi kelas langsung dari ponsel</li>
                            <li>Lihat riwayat kehadiran per mata kuliah</li>
                            <li>Pantau persentase kehadiran sendiri sepanjang semester</li>
                            <li>Pastikan setiap pertemuan sudah tercatat dengan benar</li>
                            <li>Atur profil dan foto</li>
                            <li>Pilih tampilan terang atau gelap sesuai kenyamanan</li>
                        </ul>
                    </article>
                </div>
                <div class="col-md-6">
                    <article class="feature-card tone-teal h-100">
                        <div class="icon-box ib-teal mb-3"><i class="bi bi-person-workspace"></i></div>
                        <h3 class="h5 fw-bold">Dosen</h3>
                        <ul class="feature-list">
                            <li>Buka dan tutup sesi perkuliahan kapan saja</li>
                            <li>Tampilkan QR Code untuk kelas di layar</li>
                            <li>Pantau mahasiswa yang hadir secara real-time</li>
                            <li>Validasi kehadiran mahasiswa</li>
                            <li>Lihat rekap pertemuan tiap mata kuliah</li>
                            <li>Atur profil dan foto</li>
                        </ul>
                    </article>
                </div>
            </div>
        </section>

        <!-- Alur + keamanan -->
        <div class="row g-3 g-md-4">
            <div class="col-lg-7">
                <section class="content-card h-100">
                    <h2 class="h5 fw-bold mb-4">
                        <i class="bi bi-info-circle me-2"></i>Cara Kerjanya
                    </h2>
                    <div>
                        <div class="step-item">
                            <span class="step-num">1</span>
                            <div class="fw-semibold">Akun disiapkan kampus</div>
                            <small class="text-secondary">Pihak kampus membuatkan akun Mahasiswa atau Dosen, sehingga tidak perlu mendaftar sendiri.</small>
                        </div>
                        <div class="step-item">
                            <span class="step-num">2</span>
                            <div class="fw-semibold">Masuk sesuai peran</div>
                            <small class="text-secondary">Login memakai akun Mahasiswa atau Dosen, lalu Anda langsung diarahkan ke dashboard masing-masing.</small>
                        </div>
                        <div class="step-item">
                            <span class="step-num">3</span>
                            <div class="fw-semibold">Dosen membuka sesi dan menampilkan QR</div>
                            <small class="text-secondary">Saat kelas dimulai, dosen membuka sesi dan QR Code muncul di layar.</small>
                        </div>
                        <div class="step-item">
                            <span class="step-num">4</span>
                            <div class="fw-semibold">Mahasiswa memindai QR</div>
                            <small class="text-secondary">Mahasiswa login, lalu memindai QR dari ponsel. Kehadiran langsung tercatat.</small>
                        </div>
                        <div class="step-item">
                            <span class="step-num">5</span>
                            <div class="fw-semibold">Dosen memantau dan menutup sesi</div>
                            <small class="text-secondary">Dosen melihat siapa saja yang sudah hadir, memvalidasi bila perlu, lalu menutup sesi di akhir kelas.</small>
                        </div>
                        <div class="step-item">
                            <span class="step-num">6</span>
                            <div class="fw-semibold">Rekap dan laporan otomatis</div>
                            <small class="text-secondary">Data kehadiran tersimpan otomatis untuk rekap harian sampai laporan akhir semester.</small>
                        </div>
                    </div>
                </section>
            </div>

            <div class="col-lg-5">
                <section class="content-card h-100">
                    <h2 class="h5 fw-bold mb-3">
                        <i class="bi bi-lock-fill me-2"></i>Data Terlindungi
                    </h2>
                    <p class="text-secondary">
                        Data kampus bersifat privat. Jadwal, daftar mahasiswa, dan rekap kehadiran hanya bisa dilihat
                        setelah login, dan hanya sesuai hak akses peran masing-masing.
                    </p>
                    <ul class="feature-list mb-0">
                        <li>Akun dibuat dan dikelola oleh pihak kampus</li>
                        <li>Setiap peran hanya melihat datanya sendiri</li>
                        <li>Presensi hanya sah selama sesi kelas dibuka</li>
                    </ul>
                    @guest
                        <a href="{{ route('mahasiswa.login') }}" class="btn btn-login-nav w-100 d-inline-flex align-items-center justify-content-center gap-2 mt-4">
                            <i class="bi bi-box-arrow-in-right"></i> Masuk untuk Mulai
                        </a>
                    @endguest
                </section>
            </div>
        </div>

        <!-- Perbandingan -->
        <section class="mt-4 mt-md-5 mb-4 mb-md-5" aria-labelledby="banding-title">
            <div class="section-head">
                <h2 id="banding-title" class="h3 fw-bold mb-2">Absensi manual vs Absensi Kampus</h2>
                <p>Perbedaan yang paling terasa dibanding mencatat kehadiran di kertas.</p>
            </div>
            <div class="content-card p-2 p-md-3">
                <div class="compare-wrap table-responsive">
                    <table class="compare-table">
                        <thead>
                            <tr><th scope="col">Aspek</th><th scope="col">Cara manual</th><th scope="col" class="col-new">Absensi Kampus</th></tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">Mencatat kehadiran</th>
                                <td class="col-old" data-label="Cara manual"><i class="bi bi-x-circle-fill"></i>Dipanggil satu per satu atau tanda tangan di kertas</td>
                                <td class="col-new" data-label="Absensi Kampus"><i class="bi bi-check-circle-fill"></i>Scan QR dari ponsel</td>
                            </tr>
                            <tr>
                                <th scope="row">Waktu di dalam kelas</th>
                                <td class="col-old" data-label="Cara manual"><i class="bi bi-x-circle-fill"></i>Menyita waktu perkuliahan</td>
                                <td class="col-new" data-label="Absensi Kampus"><i class="bi bi-check-circle-fill"></i>Selesai dalam hitungan detik</td>
                            </tr>
                            <tr>
                                <th scope="row">Rekap kehadiran</th>
                                <td class="col-old" data-label="Cara manual"><i class="bi bi-x-circle-fill"></i>Disalin manual ke spreadsheet</td>
                                <td class="col-new" data-label="Absensi Kampus"><i class="bi bi-check-circle-fill"></i>Tersusun otomatis</td>
                            </tr>
                            <tr>
                                <th scope="row">Pemantauan dosen</th>
                                <td class="col-old" data-label="Cara manual"><i class="bi bi-x-circle-fill"></i>Baru diketahui setelah lembar absen direkap</td>
                                <td class="col-new" data-label="Absensi Kampus"><i class="bi bi-check-circle-fill"></i>Real-time saat sesi berlangsung</td>
                            </tr>
                            <tr>
                                <th scope="row">Riwayat mahasiswa</th>
                                <td class="col-old" data-label="Cara manual"><i class="bi bi-x-circle-fill"></i>Sulit dilacak dan tersebar di banyak lembar</td>
                                <td class="col-new" data-label="Absensi Kampus"><i class="bi bi-check-circle-fill"></i>Tersedia per mata kuliah di akun masing-masing</td>
                            </tr>
                            <tr>
                                <th scope="row">Penyimpanan data</th>
                                <td class="col-old" data-label="Cara manual"><i class="bi bi-x-circle-fill"></i>Lembaran kertas yang bisa hilang atau rusak</td>
                                <td class="col-new" data-label="Absensi Kampus"><i class="bi bi-check-circle-fill"></i>Tersimpan digital dan terpusat</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- FAQ -->
        <section id="faq" class="mb-4 mb-md-5" aria-labelledby="faq-title" style="scroll-margin-top: 90px;">
            <div class="section-head">
                <h2 id="faq-title" class="h3 fw-bold mb-2">Pertanyaan yang sering diajukan</h2>
                <p>Jawaban singkat seputar akun, presensi, dan keamanan data.</p>
            </div>
            <div class="accordion faq mx-auto" id="faqList" style="max-width: 820px;">
                <div class="accordion-item">
                    <h3 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq0" aria-expanded="true" aria-controls="faq0">Apakah harus memasang aplikasi di ponsel?</button>
                    </h3>
                    <div id="faq0" class="accordion-collapse collapse show" data-bs-parent="#faqList">
                        <div class="accordion-body">Tidak. Absensi Kampus adalah aplikasi web, jadi cukup dibuka lewat browser di ponsel maupun laptop.</div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h3 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq1" aria-expanded="false" aria-controls="faq1">Bagaimana cara mendapatkan akun?</button>
                    </h3>
                    <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqList">
                        <div class="accordion-body">Akun dibuat dan dikelola oleh pihak kampus. Gunakan akun Mahasiswa atau Dosen yang sudah diberikan, lalu masuk lewat tombol "Masuk Portal".</div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h3 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2" aria-expanded="false" aria-controls="faq2">Bagaimana cara mahasiswa melakukan presensi?</button>
                    </h3>
                    <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqList">
                        <div class="accordion-body">Login sebagai mahasiswa, lalu pindai QR Code yang ditampilkan dosen di kelas. Begitu berhasil, kehadiran langsung tercatat.</div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h3 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3" aria-expanded="false" aria-controls="faq3">Kapan presensi dianggap sah?</button>
                    </h3>
                    <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqList">
                        <div class="accordion-body">Presensi hanya sah selama sesi kelas dibuka oleh dosen. Setelah sesi ditutup, QR tidak lagi dipakai untuk mencatat kehadiran.</div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h3 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4" aria-expanded="false" aria-controls="faq4">Apa yang bisa dilakukan dosen?</button>
                    </h3>
                    <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqList">
                        <div class="accordion-body">Dosen dapat membuka dan menutup sesi, menampilkan QR Code, memantau serta memvalidasi kehadiran mahasiswa, dan melihat rekap pertemuan tiap mata kuliah.</div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h3 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5" aria-expanded="false" aria-controls="faq5">Apakah data saya aman?</button>
                    </h3>
                    <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqList">
                        <div class="accordion-body">Jadwal, daftar mahasiswa, dan rekap kehadiran hanya bisa dilihat setelah login, dan hanya sesuai hak akses peran masing-masing.</div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h3 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq6" aria-expanded="false" aria-controls="faq6">Saya lupa kata sandi atau akun bermasalah, harus bagaimana?</button>
                    </h3>
                    <div id="faq6" class="accordion-collapse collapse" data-bs-parent="#faqList">
                        <div class="accordion-body">Hubungi pihak kampus (bagian akademik) yang mengelola akun Anda agar dapat dibantu.</div>
                    </div>
                </div>
            </div>
        </section>

        @guest
        <!-- Lokasi kampus -->
        <section class="mb-2" aria-labelledby="map-title">
            <style>
                .campus-map-card {
                    background: var(--surface);
                    border: 1px solid var(--line);
                    border-radius: 20px;
                    box-shadow: var(--shadow-sm);
                    overflow: hidden;
                }
                .campus-map-head {
                    padding: 1rem 1.25rem;
                    display: flex;
                    align-items: center;
                    gap: .75rem;
                }
                .campus-map-head .map-icon {
                    width: 40px; height: 40px; flex-shrink: 0;
                    display: grid; place-items: center;
                    border-radius: 12px;
                    background: var(--blue-soft);
                    color: var(--blue);
                    font-size: 1.15rem;
                }
                .campus-map-frame {
                    position: relative;
                    width: 100%;
                    height: 280px;
                    border-top: 1px solid var(--line);
                }
                .campus-map-frame iframe {
                    position: absolute;
                    inset: 0;
                    width: 100%;
                    height: 100%;
                    border: 0;
                    filter: var(--map-filter, none);
                }
                [data-theme="dark"] .campus-map-frame iframe { filter: invert(0.9) hue-rotate(180deg); }
                @media (min-width: 768px) {
                    .campus-map-frame { height: 380px; }
                }
            </style>
            <div class="campus-map-card">
                <div class="campus-map-head">
                    <span class="map-icon"><i class="bi bi-geo-alt-fill"></i></span>
                    <div class="min-w-0">
                        <h2 id="map-title" class="h5 fw-bold mb-0">Lokasi Kampus</h2>
                        <p class="small text-secondary mb-0">Universitas Boyolali (UBY)</p>
                    </div>
                    <a href="https://maps.app.goo.gl/yTRndwFAtVQmf8QP9" target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary ms-auto rounded-pill text-nowrap">
                        <i class="bi bi-box-arrow-up-right"></i> <span class="d-none d-sm-inline">Buka di Maps</span>
                    </a>
                </div>
                <div class="campus-map-frame">
                    <iframe
                        src="https://www.google.com/maps?q=-7.5160446,110.5897566&amp;z=17&amp;output=embed"
                        title="Peta lokasi Universitas Boyolali (UBY)"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        allowfullscreen>
                    </iframe>
                </div>
            </div>
        </section>
        @endguest

    </div>
    </main>

    <!-- Footer -->
    <footer class="text-center small pt-4 mt-3 mt-md-4">
        <div class="container">
            &copy; {{ date('Y') }} Sistem Absensi Kampus. Seluruh hak cipta dilindungi.
        </div>
    </footer>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var b = document.getElementById('hero-login-btn');
            if (b && window.bootstrap) new bootstrap.Dropdown(b, { popperConfig: { strategy: 'fixed' } });
        });
    </script>
    <script>
        /* ---------- Tema (kunci sama dengan dashboard) ---------- */
        function updateThemeUI(theme) {
            var icon = document.getElementById('theme-icon');
            var btn  = document.getElementById('btn-theme-toggle');
            if (!icon || !btn) return;
            if (theme === 'dark') {
                icon.className = 'bi bi-sun-fill text-warning';
                btn.title = 'Ganti ke Mode Terang';
            } else {
                icon.className = 'bi bi-moon-stars-fill';
                btn.title = 'Ganti ke Mode Gelap';
            }
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
        });

        /* ---------- Jam WIB ---------- */
        (function () {
            var TZ = 'Asia/Jakarta';
            var serverNow = {{ (int) round(microtime(true) * 1000) }};
            var offset = serverNow - Date.now();

            var timeEl = document.getElementById('wib-time');
            var dateEl = document.getElementById('wib-date');
            var timeElM = document.getElementById('wib-time-m');
            var dateElM = document.getElementById('wib-date-m');

            var timeFmt = new Intl.DateTimeFormat('en-GB', { timeZone: TZ, hour: '2-digit', minute: '2-digit', second: '2-digit', hourCycle: 'h23' });
            var dateFmt = new Intl.DateTimeFormat('id-ID', { timeZone: TZ, weekday: 'long', day: '2-digit', month: 'long', year: 'numeric' });
            function tick() {
                var now = new Date(Date.now() + offset);
                if (timeEl) timeEl.textContent = timeFmt.format(now);
                if (dateEl) dateEl.textContent = dateFmt.format(now);
                if (timeElM) timeElM.textContent = timeFmt.format(now);
                if (dateElM) dateElM.textContent = dateFmt.format(now);
                setTimeout(tick, 1000 - (now.getTime() % 1000) + 5);
            }
            tick();
        })();
    </script>
</body>
</html>