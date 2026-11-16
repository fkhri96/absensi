<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#091933">
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

        /* Kartu pilihan peran (tamu) */
        .role-btn-card {
            background: var(--surface);
            border-radius: 18px;
            border: 1px solid var(--line);
            text-decoration: none;
            color: var(--text);
            display: block;
            padding: .95rem 1.05rem;
            box-shadow: 0 10px 26px rgba(9, 25, 51, 0.16);
            transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
        }
        .role-btn-card:active { transform: scale(.99); }
        .role-btn-card h6 { font-size: 1rem; color: var(--text); }

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
            .role-btn-card:hover { transform: translateY(-3px); border-color: var(--blue); box-shadow: 0 18px 36px rgba(9, 25, 51, 0.24); }
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
            .role-btn-card h6 { font-size: .95rem; }
            .icon-box { width: 42px; height: 42px; font-size: 19px; }
            .clock-pill { padding: 5px 12px 5px 10px; gap: 8px; }
            .clock-time { font-size: .92rem; }
            .clock-zone { display: none; }
        }

        @media (prefers-reduced-motion: reduce) {
            html { scroll-behavior: auto; }
            *, *::before, *::after { animation: none !important; transition: none !important; }
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

    // Waktu acuan: WIB
    $nowWib = now('Asia/Jakarta');

    // Baris jadwal + status (berlangsung / akan / selesai) dihitung berdasarkan jam WIB
    $rows = collect($jadwalHariIni ?? [])->map(function ($j) use ($nowWib) {
        $mulai   = \Carbon\Carbon::parse($j->jam_mulai, 'Asia/Jakarta');
        $selesai = \Carbon\Carbon::parse($j->jam_selesai, 'Asia/Jakarta');

        $status = $nowWib->lt($mulai) ? 'akan' : ($nowWib->lte($selesai) ? 'berlangsung' : 'selesai');

        return [
            'mulai'    => $mulai->format('H:i'),
            'selesai'  => $selesai->format('H:i'),
            'm_menit'  => $mulai->hour * 60 + $mulai->minute,
            's_menit'  => $selesai->hour * 60 + $selesai->minute,
            'status'   => $status,
            'mk'       => $j->mataKuliah->nama_mk ?? '-',
            'prodi'    => $j->mataKuliah->prodi->nama_prodi ?? '',
            'dosen'    => $j->mataKuliah->dosen->user->name ?? '-',
            'ruang'    => $j->ruang,
        ];
    })->sortBy('m_menit')->values();

    $statusLabel = ['berlangsung' => 'Berlangsung', 'akan' => 'Akan datang', 'selesai' => 'Selesai'];
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
                <div class="clock-pill" role="timer" aria-label="Waktu Indonesia Barat">
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
                            <li><hr class="dropdown-divider my-1"></li>
                            <li>
                                <a class="dropdown-item rounded-3 py-2 d-flex align-items-center gap-3" href="{{ route('admin.login') }}">
                                    <i class="bi bi-shield-lock-fill fs-5" style="color:#3b63f0;"></i>
                                    <div>
                                        <div class="fw-semibold">Login Admin</div>
                                        <small class="text-muted">Panel manajemen kampus</small>
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
                            <i class="bi bi-broadcast text-warning"></i> Portal Presensi Akademik Terintegrasi
                        </span>
                    @endauth

                    <h1 class="hero-title">Pantau Kehadiran & Aktivitas Perkuliahan Secara Real-Time</h1>
                    <p class="hero-lead">
                        Sistem absensi digital kampus untuk memudahkan mahasiswa melakukan presensi QR Code, dosen mengelola pertemuan, dan admin memantau rekap akademik.
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
                        <div class="d-flex flex-wrap gap-2">
                            <span class="feature-chip"><i class="bi bi-qr-code-scan"></i> Presensi scan QR</span>
                            <span class="feature-chip"><i class="bi bi-lightning-charge-fill"></i> Data real-time</span>
                            <span class="feature-chip"><i class="bi bi-file-earmark-bar-graph-fill"></i> Rekap otomatis</span>
                        </div>
                    @endauth
                </div>

                <!-- Sisi kanan hero -->
                <div class="col-lg-5">
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
                        <div class="d-flex flex-column gap-3">
                            <a href="{{ route('mahasiswa.login') }}" class="role-btn-card">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="icon-box ib-amber"><i class="bi bi-mortarboard-fill"></i></div>
                                    <div class="min-w-0 flex-grow-1">
                                        <h6 class="fw-bold mb-0">Masuk sebagai Mahasiswa</h6>
                                        <small class="text-secondary">Scan QR kehadiran & cek riwayat absen</small>
                                    </div>
                                    <i class="bi bi-arrow-right-circle-fill fs-4 flex-shrink-0" style="color:#d18a16;"></i>
                                </div>
                            </a>

                            <a href="{{ route('dosen.login') }}" class="role-btn-card">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="icon-box ib-teal"><i class="bi bi-person-workspace"></i></div>
                                    <div class="min-w-0 flex-grow-1">
                                        <h6 class="fw-bold mb-0">Masuk sebagai Dosen</h6>
                                        <small class="text-secondary">Buka sesi QR kelas & validasi kehadiran</small>
                                    </div>
                                    <i class="bi bi-arrow-right-circle-fill fs-4 flex-shrink-0" style="color:#0f9f86;"></i>
                                </div>
                            </a>

                            <a href="{{ route('admin.login') }}" class="role-btn-card">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="icon-box ib-blue"><i class="bi bi-shield-lock-fill"></i></div>
                                    <div class="min-w-0 flex-grow-1">
                                        <h6 class="fw-bold mb-0">Masuk sebagai Administrator</h6>
                                        <small class="text-secondary">Kelola data master, jadwal & laporan</small>
                                    </div>
                                    <i class="bi bi-arrow-right-circle-fill fs-4 flex-shrink-0" style="color:#3b63f0;"></i>
                                </div>
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </section>

        <!-- Ringkasan Statistik Kampus -->
        <section class="row g-3 mb-3 mb-md-4" aria-label="Ringkasan statistik kampus">
            <div class="col-6 col-lg-3">
                <div class="stat-card tone-blue">
                    <div class="stat-head">
                        <span class="stat-label">Total Mahasiswa</span>
                        <span class="stat-icon" aria-hidden="true"><i class="bi bi-people-fill"></i></span>
                    </div>
                    <div class="stat-number">{{ $stats['mahasiswa'] }}</div>
                    <div class="stat-note d-none d-sm-block">Mahasiswa aktif terdaftar</div>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="stat-card tone-mint">
                    <div class="stat-head">
                        <span class="stat-label">Total Dosen</span>
                        <span class="stat-icon" aria-hidden="true"><i class="bi bi-person-badge-fill"></i></span>
                    </div>
                    <div class="stat-number">{{ $stats['dosen'] }}</div>
                    <div class="stat-note d-none d-sm-block">Dosen pengampu mata kuliah</div>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="stat-card tone-sky">
                    <div class="stat-head">
                        <span class="stat-label">Mata Kuliah</span>
                        <span class="stat-icon" aria-hidden="true"><i class="bi bi-journal-bookmark-fill"></i></span>
                    </div>
                    <div class="stat-number">{{ $stats['matkul'] }}</div>
                    <div class="stat-note d-none d-sm-block">Kurikulum semester berjalan</div>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="stat-card tone-amber">
                    <div class="stat-head">
                        <span class="stat-label">Sesi Absen Dibuka</span>
                        <span class="stat-icon" aria-hidden="true"><i class="bi bi-broadcast"></i></span>
                    </div>
                    <div class="stat-number d-flex align-items-center gap-2">
                        {{ $stats['sesi_buka'] }}
                        @if((int) $stats['sesi_buka'] > 0)
                            <span class="live-dot" title="Ada kelas berlangsung"></span>
                        @endif
                    </div>
                    <div class="stat-note d-none d-sm-block">Kelas sedang berlangsung</div>
                </div>
            </div>
        </section>

        <!-- Jadwal Hari Ini & Alur Penggunaan -->
        <div class="row g-3 g-md-4">
            <div class="col-lg-7">
                <section class="content-card h-100">
                    <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
                        <div>
                            <h2 class="h5 fw-bold mb-1">
                                <i class="bi bi-clock-history me-2"></i>Jadwal Kuliah Hari Ini
                            </h2>
                            <small class="text-secondary">{{ $nowWib->translatedFormat('l, d F Y') }} &bull; WIB</small>
                        </div>
                        <span class="date-chip">Semester Aktif</span>
                    </div>

                    @if($rows->isNotEmpty())
                        <ul class="sched-list" id="sched-list">
                            @foreach($rows as $row)
                                <li class="sched"
                                    data-status="{{ $row['status'] }}"
                                    data-mulai="{{ $row['m_menit'] }}"
                                    data-selesai="{{ $row['s_menit'] }}">
                                    <div class="sched-time">
                                        <strong>{{ $row['mulai'] }}</strong>
                                        <span>{{ $row['selesai'] }}</span>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="sched-title">{{ $row['mk'] }}</div>
                                        <div class="sched-sub">
                                            <i class="bi bi-person me-1"></i>{{ $row['dosen'] }}
                                            @if($row['prodi']) &bull; {{ $row['prodi'] }} @endif
                                        </div>
                                    </div>
                                    <div class="sched-meta">
                                        <span class="room-badge"><i class="bi bi-geo-alt"></i>{{ $row['ruang'] }}</span>
                                        <span class="st-badge st-{{ $row['status'] }}" data-role="status">{{ $statusLabel[$row['status']] }}</span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="empty-state">
                            <i class="bi bi-calendar-x"></i>
                            <div class="fw-semibold">Belum ada kuliah hari ini</div>
                            <small>Jadwal akan muncul di sini saat ada perkuliahan.</small>
                        </div>
                    @endif
                </section>
            </div>

            <div class="col-lg-5">
                <section class="content-card h-100">
                    <h2 class="h5 fw-bold mb-4">
                        <i class="bi bi-info-circle me-2"></i>Alur Penggunaan Sistem
                    </h2>
                    <div>
                        <div class="step-item">
                            <span class="step-num">1</span>
                            <div class="fw-semibold">Masuk Sesuai Peran</div>
                            <small class="text-secondary">Login menggunakan akun Mahasiswa, Dosen, atau Administrator untuk membuka panel kerja.</small>
                        </div>
                        <div class="step-item">
                            <span class="step-num">2</span>
                            <div class="fw-semibold">Buka Sesi QR & Presensi Kelas</div>
                            <small class="text-secondary">Dosen menampilkan QR Code sesi perkuliahan, lalu mahasiswa memindai QR untuk mencatat kehadiran.</small>
                        </div>
                        <div class="step-item">
                            <span class="step-num">3</span>
                            <div class="fw-semibold">Rekap & Laporan Otomatis</div>
                            <small class="text-secondary">Seluruh data kehadiran tersimpan otomatis untuk rekap harian maupun cetak laporan akhir semester.</small>
                        </div>
                    </div>
                </section>
            </div>
        </div>

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

        /* ---------- Jam WIB + status jadwal ---------- */
        (function () {
            var TZ = 'Asia/Jakarta';
            var serverNow = {{ (int) round(microtime(true) * 1000) }};
            var offset = serverNow - Date.now();

            var timeEl = document.getElementById('wib-time');
            var dateEl = document.getElementById('wib-date');
            var rows = document.querySelectorAll('#sched-list .sched');

            var timeFmt = new Intl.DateTimeFormat('en-GB', { timeZone: TZ, hour: '2-digit', minute: '2-digit', second: '2-digit', hourCycle: 'h23' });
            var dateFmt = new Intl.DateTimeFormat('id-ID', { timeZone: TZ, weekday: 'long', day: '2-digit', month: 'long', year: 'numeric' });
            var LABEL = { berlangsung: 'Berlangsung', akan: 'Akan datang', selesai: 'Selesai' };
            var lastMinute = -1;

            function updateSchedule(now) {
                var parts = timeFmt.format(now).split(':');
                var minutes = parseInt(parts[0], 10) * 60 + parseInt(parts[1], 10);
                if (minutes === lastMinute) return;
                lastMinute = minutes;

                rows.forEach(function (li) {
                    var start = parseInt(li.dataset.mulai, 10);
                    var end   = parseInt(li.dataset.selesai, 10);
                    var status = minutes < start ? 'akan' : (minutes <= end ? 'berlangsung' : 'selesai');
                    li.dataset.status = status;

                    var badge = li.querySelector('[data-role="status"]');
                    if (badge) {
                        badge.className = 'st-badge st-' + status;
                        badge.textContent = LABEL[status];
                    }
                });
            }

            function tick() {
                var now = new Date(Date.now() + offset);
                if (timeEl) timeEl.textContent = timeFmt.format(now);
                if (dateEl) dateEl.textContent = dateFmt.format(now);
                updateSchedule(now);
                setTimeout(tick, 1000 - (now.getTime() % 1000) + 5);
            }
            tick();
        })();
    </script>
</body>
</html>