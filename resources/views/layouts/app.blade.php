<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Absensi Kampus')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,600;12..96,700;12..96,800&family=Figtree:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <script>
        // Terapkan tema tersimpan sebelum render untuk mencegah kedipan warna putih
        (function () {
            var savedTheme = 'light';
            try { savedTheme = localStorage.getItem('absensi_theme') || 'light'; } catch (e) {}
            document.documentElement.setAttribute('data-theme', savedTheme);
            document.documentElement.setAttribute('data-bs-theme', savedTheme);
        })();
    </script>

    <style>
        :root {
            --navy: #0b2545;
            --navy-2: #13315c;
            --blue: #3b63f0;
            --blue-600: #2c4fd6;
            --blue-soft: rgba(59, 99, 240, 0.10);
            --mint: #14c9a7;
            --bg: #f3f5fa;
            --surface: #ffffff;
            --surface-2: #f8f9fd;
            --text: #14213d;
            --muted: #66718c;
            --line: #e4e8f1;
            --topbar-bg: rgba(255, 255, 255, 0.78);
            --sidebar-w: 272px;
            --radius-lg: 20px;
            --radius-md: 14px;
            --shadow-sm: 0 1px 2px rgba(20, 33, 61, 0.04), 0 4px 14px rgba(20, 33, 61, 0.04);
            --shadow-md: 0 12px 32px rgba(20, 33, 61, 0.10);
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
            --topbar-bg: rgba(18, 26, 46, 0.80);
            --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.30), 0 4px 14px rgba(0, 0, 0, 0.20);
            --shadow-md: 0 16px 36px rgba(0, 0, 0, 0.45);
        }

        * { -webkit-tap-highlight-color: transparent; }

        body {
            font-family: 'Figtree', sans-serif;
            background: var(--bg);
            color: var(--text);
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
            transition: background-color .2s ease, color .2s ease;
        }

        h1, h2, h3, h4, h5, h6, .display-font {
            font-family: 'Bricolage Grotesque', sans-serif;
            letter-spacing: -0.02em;
        }

        a:focus-visible, button:focus-visible {
            outline: 3px solid var(--mint);
            outline-offset: 2px;
        }

        ::selection { background: var(--blue-soft); }

        /* ================================================================
           PENYESUAIAN MODE GELAP
           ================================================================ */
        [data-theme="dark"] .text-dark,
        [data-theme="dark"] .text-body {
            color: var(--text) !important;
        }

        [data-theme="dark"] .text-muted,
        [data-theme="dark"] .text-secondary {
            color: var(--muted) !important;
        }

        [data-theme="dark"] .bg-light,
        [data-theme="dark"] .bg-white {
            background-color: var(--surface-2) !important;
            color: var(--text) !important;
        }

        /* ---- FIX: angka/judul (mis. "Kelas Hari Ini = 0") tidak terlihat di mode gelap ----
           Penyebab umum: warna angka di-hardcode navy (#0b2545 / var(--navy)) sehingga
           menyatu dengan latar gelap. Aturan di bawah memaksa teks tersebut terang.
           Elemen yang sengaja berwarna (class text-success, text-primary, dll) tidak diubah. */
        [data-theme="dark"] main.page :is(h1, h2, h3, h4, h5, h6, .display-font, .fw-bold, .fw-semibold, strong):not([class*="text-"]),
        [data-theme="dark"] main.page :is(.stat-value, .stat-number, .stat-num, .stat-count, .stat-card .value, .stat-card .number, .kpi, .kpi-value, .metric, .metric-value, .count, .counter, .angka):not([class*="text-"]) {
            color: var(--text);
        }

        [data-theme="dark"] main.page [style*="color: var(--navy"],
        [data-theme="dark"] main.page [style*="color:var(--navy"],
        [data-theme="dark"] main.page [style*="color: #0b2545"],
        [data-theme="dark"] main.page [style*="color:#0b2545"],
        [data-theme="dark"] main.page [style*="color: #13315c"],
        [data-theme="dark"] main.page [style*="color:#13315c"],
        [data-theme="dark"] main.page [style*="color: #14213d"],
        [data-theme="dark"] main.page [style*="color:#14213d"],
        [data-theme="dark"] main.page [style*="color: #000"],
        [data-theme="dark"] main.page [style*="color:#000"] {
            color: var(--text) !important;
        }

        [data-theme="dark"] .table {
            color: var(--text);
            --bs-table-bg: transparent;
            --bs-table-hover-bg: rgba(255, 255, 255, 0.04);
            --bs-table-color: var(--text);
            border-color: var(--line);
        }

        [data-theme="dark"] .table-light,
        [data-theme="dark"] .table thead th {
            background-color: #0e1528 !important;
            color: #cbd5e1 !important;
            border-color: var(--line) !important;
        }

        [data-theme="dark"] .form-control,
        [data-theme="dark"] .form-select {
            background-color: #0e1528;
            color: var(--text);
            border-color: var(--line);
        }

        [data-theme="dark"] .form-control::placeholder { color: #6b7a99; }

        [data-theme="dark"] .form-control:focus,
        [data-theme="dark"] .form-select:focus {
            background-color: #0e1528;
            color: var(--text);
            border-color: var(--blue);
            box-shadow: 0 0 0 .2rem var(--blue-soft);
        }

        [data-theme="dark"] .user-trigger:hover,
        [data-theme="dark"] .user-trigger[aria-expanded="true"] {
            background: var(--surface-2);
            border-color: #33456b;
        }

        [data-theme="dark"] .dropdown-menu-modern {
            background-color: var(--surface);
            border-color: var(--line);
            box-shadow: var(--shadow-md);
        }

        [data-theme="dark"] .dropdown-menu-modern .dropdown-item { color: var(--text); }
        [data-theme="dark"] .dropdown-menu-modern .dropdown-item.text-danger { color: #ff6b7a !important; }
        [data-theme="dark"] .dropdown-menu-modern .dropdown-item:hover { background: var(--surface-2); }

        [data-theme="dark"] .btn-ghost:hover {
            background: var(--surface-2);
            border-color: #35476e;
            color: var(--text);
        }

        [data-theme="dark"] .avatar-img { border-color: var(--surface); }

        /* ================================================================
           SIDEBAR
           ================================================================ */
        .sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            width: var(--sidebar-w);
            background:
                radial-gradient(120% 60% at 0% 0%, rgba(59, 99, 240, 0.22) 0%, transparent 60%),
                linear-gradient(180deg, #0b2545 0%, #071a31 100%);
            color: #c9d4ea;
            padding: 22px 14px 16px;
            display: flex;
            flex-direction: column;
            z-index: 1045;
            border-right: 1px solid rgba(255, 255, 255, 0.04);
        }

        .sidebar .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #fff;
            text-decoration: none;
            font-family: 'Bricolage Grotesque', sans-serif;
            font-weight: 700;
            font-size: 1.22rem;
            padding: 4px 10px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.07);
            margin-bottom: 12px;
        }

        .brand-mark {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--mint) 0%, #0ea58a 100%);
            display: grid;
            place-items: center;
            color: #06302a;
            box-shadow: 0 8px 20px rgba(20, 201, 167, 0.30);
            flex-shrink: 0;
        }

        .sidebar-nav-scroll {
            flex: 1;
            overflow-y: auto;
            padding-right: 2px;
        }

        .sidebar-nav-scroll::-webkit-scrollbar { width: 4px; }
        .sidebar-nav-scroll::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 99px;
        }

        .sidebar .nav-link {
            position: relative;
            display: flex;
            align-items: center;
            gap: 12px;
            color: #b9c6e2;
            padding: 10px 14px;
            border-radius: 12px;
            font-weight: 500;
            font-size: 0.93rem;
            margin-bottom: 3px;
            transition: background .18s ease, color .18s ease, transform .18s ease;
        }

        .sidebar .nav-link i,
        .sidebar .nav-link .ico { opacity: .85; }

        .sidebar .nav-link:hover {
            background: rgba(255, 255, 255, 0.07);
            color: #fff;
            transform: translateX(2px);
        }

        .sidebar .nav-link.active {
            background: linear-gradient(135deg, var(--blue) 0%, var(--blue-600) 100%);
            color: #fff;
            font-weight: 600;
            box-shadow: 0 8px 20px rgba(59, 99, 240, 0.38);
        }

        .sidebar .nav-link.active i,
        .sidebar .nav-link.active .ico { opacity: 1; }

        .sidebar .nav-label {
            font-size: .72rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            font-weight: 700;
            color: #7f92b9;
            padding: 16px 14px 6px;
        }

        .sidebar-footer {
            margin-top: 12px;
            padding: 12px;
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.07);
            backdrop-filter: blur(6px);
        }

        .sidebar-profile-link {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: #fff;
            padding: 6px;
            border-radius: 11px;
            transition: background .15s;
        }

        .sidebar-profile-link:hover {
            background: rgba(255, 255, 255, 0.08);
            color: #fff;
        }

        .ico {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
            fill: none;
            stroke: currentColor;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        /* ================================================================
           AREA KONTEN & TOPBAR
           ================================================================ */
        .content-wrap {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 12px 36px;
            background: var(--topbar-bg);
            backdrop-filter: saturate(160%) blur(14px);
            -webkit-backdrop-filter: saturate(160%) blur(14px);
            position: sticky;
            top: 0;
            z-index: 1020;
            border-bottom: 1px solid var(--line);
            transition: background-color .2s ease, border-color .2s ease;
        }

        /* ---- Jam WIB (sinkron dengan waktu server, zona Asia/Jakarta) ---- */
        .clock-pill {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 99px;
            padding: 6px 16px 6px 12px;
            box-shadow: var(--shadow-sm);
            white-space: nowrap;
        }

        .clock-dot {
            position: relative;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--mint);
            flex-shrink: 0;
        }

        .clock-dot::after {
            content: "";
            position: absolute;
            inset: -4px;
            border-radius: 50%;
            border: 2px solid var(--mint);
            opacity: 0;
            animation: clockPulse 2s ease-out infinite;
        }

        @keyframes clockPulse {
            0%   { transform: scale(.5); opacity: .7; }
            100% { transform: scale(1.4); opacity: 0; }
        }

        .clock-date {
            color: var(--muted);
            font-size: .8rem;
            font-weight: 500;
            padding-right: 10px;
            margin-right: 2px;
            border-right: 1px solid var(--line);
        }

        .clock-time {
            font-family: 'Bricolage Grotesque', sans-serif;
            font-weight: 700;
            font-size: 1.02rem;
            letter-spacing: .01em;
            color: var(--text);
            font-variant-numeric: tabular-nums;
        }

        .clock-zone {
            font-size: .7rem;
            font-weight: 700;
            color: var(--blue);
            background: var(--blue-soft);
            padding: 2px 7px;
            border-radius: 99px;
            letter-spacing: .04em;
        }

        .notif-bell-btn {
            width: 40px;
            height: 40px;
            display: grid;
            place-items: center;
            border-radius: 50%;
            background: var(--surface);
            border: 1px solid var(--line);
            color: var(--text);
            transition: all .18s ease;
            cursor: pointer;
        }

        .notif-bell-btn:hover, .notif-bell-btn.active {
            background: var(--blue-soft);
            border-color: var(--blue);
            color: var(--blue);
        }

        /* Tombol profil di topbar */
        .user-trigger {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: var(--surface);
            border: 1px solid var(--line);
            color: var(--text);
            border-radius: 99px;
            padding: 5px 14px 5px 6px;
            transition: all .18s ease;
            cursor: pointer;
        }

        .user-trigger:hover, .user-trigger[aria-expanded="true"] {
            border-color: #cbd5e1;
            background: var(--surface-2);
            box-shadow: var(--shadow-sm);
        }

        .avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--blue) 0%, var(--navy) 100%);
            color: #fff;
            display: grid;
            place-items: center;
            font-weight: 700;
            font-size: 0.95rem;
            flex-shrink: 0;
        }

        .avatar-img {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #fff;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.12);
            flex-shrink: 0;
        }

        .dropdown-menu-modern {
            min-width: 250px;
            border: 1px solid var(--line);
            border-radius: 18px;
            padding: 8px;
            box-shadow: var(--shadow-md);
            animation: menuIn .16s ease;
        }

        @keyframes menuIn {
            from { opacity: 0; transform: translateY(-4px); }
            to   { opacity: 1; transform: none; }
        }

        .dropdown-menu-modern .dropdown-item {
            border-radius: 11px;
            padding: 9px 12px;
            font-size: 0.9rem;
            font-weight: 500;
            transition: background .15s;
        }

        .dropdown-menu-modern .dropdown-item:hover { background: var(--bg); }

        main.page {
            padding: 28px 36px 56px;
            flex: 1;
            width: 100%;
            max-width: 1560px;
        }

        /* ================================================================
           KOMPONEN UMUM
           ================================================================ */
        .panel {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            transition: background-color .2s ease, border-color .2s ease, box-shadow .2s ease;
        }

        .btn-brand {
            background: linear-gradient(135deg, var(--blue) 0%, var(--blue-600) 100%);
            color: #fff;
            border: 0;
            border-radius: 12px;
            padding: 10px 18px;
            font-weight: 600;
            box-shadow: 0 6px 16px rgba(59, 99, 240, 0.26);
            transition: all .18s ease;
        }

        .btn-brand:hover {
            background: linear-gradient(135deg, var(--blue-600) 0%, #1f3bb0 100%);
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 10px 22px rgba(59, 99, 240, 0.34);
        }

        .btn-ghost {
            background: var(--surface);
            color: var(--text);
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: 10px 18px;
            font-weight: 600;
            transition: all .15s ease;
        }

        .btn-ghost:hover {
            background: var(--bg);
            border-color: #cfd7e6;
        }

        /* ================================================================
           MOBILE
           ================================================================ */
        .sidebar-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(8, 20, 40, 0.5);
            backdrop-filter: blur(3px);
            z-index: 1040;
        }

        .sidebar-backdrop.show { display: block; }

        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform .25s cubic-bezier(0.4, 0, 0.2, 1);
                box-shadow: none;
            }
            .sidebar.open {
                transform: none;
                box-shadow: 12px 0 40px rgba(0, 0, 0, 0.35);
            }
            .content-wrap { margin-left: 0; }
            .topbar, main.page {
                padding-left: 16px;
                padding-right: 16px;
            }
        }

        @media (max-width: 575.98px) {
            .clock-pill { padding: 5px 12px 5px 10px; gap: 8px; }
            .clock-time { font-size: .92rem; }
            .clock-zone { display: none; }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { animation: none !important; transition: none !important; }
        }
    </style>
    @stack('styles')
</head>

<body>
@php
    $roleKey        = trim(View::yieldContent('role')) ?: (auth()->user()->role ?? 'admin');
    $roleLabel      = ['admin' => 'Administrator', 'dosen' => 'Dosen', 'mahasiswa' => 'Mahasiswa'][$roleKey] ?? 'Pengguna';
    $authUser       = auth()->user();
    $userName       = $authUser->name ?? $roleLabel;
    $userFoto       = !empty($authUser->foto) ? asset('storage/' . $authUser->foto) : null;
    $canEditProfile = true; // semua role (admin, dosen, mahasiswa) bisa edit profil
    $profilUrl      = Route::has('profile.index') ? route('profile.index') : url('/profil');
@endphp

    <!-- Backdrop Mobile -->
    <div class="sidebar-backdrop" id="sidebarBackdrop" onclick="toggleSidebar()"></div>

    <aside class="sidebar" id="sidebar">
        <a class="brand" href="{{ url('/') }}">
            <span class="brand-mark" aria-hidden="true">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><path d="M14 14h3v3h-3zM20 14v.01M14 20v.01M20 20h.01M17 20v-1"/></svg>
            </span>
            <span>Absensi Kampus</span>
        </a>

        <div class="sidebar-nav-scroll">
            <nav class="nav flex-column">
                @include('partials.menu.' . $roleKey)
            </nav>
        </div>

        <div class="sidebar-footer">
            @if($canEditProfile)
                <a href="{{ $profilUrl }}" class="sidebar-profile-link mb-2" title="Buka & Edit Profil">
                    @if($userFoto)
                        <img src="{{ $userFoto }}" alt="Foto {{ $userName }}" class="avatar-img" style="width: 36px; height: 36px;">
                    @else
                        <div class="avatar" style="width: 36px; height: 36px; font-size: 0.85rem;">
                            {{ strtoupper(substr($userName, 0, 1)) }}
                        </div>
                    @endif
                    <div class="overflow-hidden flex-grow-1">
                        <div class="fw-semibold text-white text-truncate small lh-sm">{{ $userName }}</div>
                        <div class="text-info d-flex align-items-center gap-1" style="font-size: 0.72rem;">
                            <span>{{ $roleLabel }}</span>
                            <span>•</span>
                            <span class="text-decoration-underline">Edit Profil</span>
                        </div>
                    </div>
                    <i class="bi bi-chevron-right text-white-50 small"></i>
                </a>
            @else
                <div class="d-flex align-items-center gap-2 p-1 mb-2">
                    <div class="avatar" style="width: 36px; height: 36px; font-size: 0.85rem;">
                        {{ strtoupper(substr($userName, 0, 1)) }}
                    </div>
                    <div class="overflow-hidden">
                        <div class="fw-semibold text-white text-truncate small lh-sm">{{ $userName }}</div>
                        <div class="text-white-50" style="font-size: 0.72rem;">{{ $roleLabel }}</div>
                    </div>
                </div>
            @endif

            <form action="{{ route('logout') }}" method="POST" onsubmit="return confirm('Yakin ingin keluar dari sistem?')">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm w-100 d-flex align-items-center justify-content-center gap-2 fw-semibold rounded-3">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    <div class="content-wrap">
        <header class="topbar">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-ghost d-lg-none p-2" type="button" aria-label="Buka menu" onclick="toggleSidebar()">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <div>
                    <h6 class="fw-bold mb-0 display-font fs-5">@yield('page_title', 'Dashboard')</h6>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2 gap-md-3">
                <!-- Jam & Tanggal WIB (live, sinkron dengan waktu server) -->
                <div class="clock-pill" role="timer" aria-label="Waktu Indonesia Barat">
                    <span class="clock-dot" aria-hidden="true"></span>
                    <span class="clock-date d-none d-md-inline" id="wib-date">{{ now('Asia/Jakarta')->translatedFormat('l, d F Y') }}</span>
                    <span class="clock-time" id="wib-time">{{ now('Asia/Jakarta')->format('H:i:s') }}</span>
                    <span class="clock-zone">WIB</span>
                </div>

                <!-- Tombol Toggle Mode Gelap / Terang -->
                <button type="button" id="btn-theme-toggle" class="notif-bell-btn" onclick="toggleTheme()" title="Ganti ke Mode Gelap">
                    <i id="theme-icon" class="bi bi-moon-stars-fill fs-6"></i>
                </button>

                @if($roleKey === 'dosen' && Route::has('dosen.notifikasi.index'))
                    <a href="{{ route('dosen.notifikasi.index') }}"
                       class="notif-bell-btn position-relative text-decoration-none {{ request()->routeIs('dosen.notifikasi.*') ? 'active' : '' }}"
                       title="Notifikasi Perkuliahan">
                        <i class="bi bi-bell fs-6"></i>
                    </a>
                @endif

                <!-- Dropdown Profil & Logout -->
                <div class="dropdown">
                    <button class="user-trigger" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Menu Akun">
                        @if($userFoto && $canEditProfile)
                            <img src="{{ $userFoto }}" alt="Foto {{ $userName }}" class="avatar-img">
                        @else
                            <div class="avatar">
                                {{ strtoupper(substr($userName, 0, 1)) }}
                            </div>
                        @endif
                        <div class="d-none d-sm-block text-start lh-sm">
                            <div class="fw-semibold small text-dark text-truncate" style="max-width: 140px;">{{ $userName }}</div>
                            <span class="text-muted" style="font-size: 0.72rem;">{{ $roleLabel }}</span>
                        </div>
                        <i class="bi bi-chevron-down text-muted small ms-1"></i>
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-modern mt-2">
                        <li class="px-3 py-2 bg-light rounded-3 mb-1">
                            <div class="fw-bold small text-dark text-truncate">{{ $userName }}</div>
                            <div class="text-muted text-truncate" style="font-size: 0.75rem;">{{ $authUser->email ?? $roleLabel }}</div>
                        </li>
                        @if($canEditProfile)
                            <li>
                                <a href="{{ $profilUrl }}" class="dropdown-item d-flex align-items-center gap-2 {{ request()->routeIs('profile.*') ? 'active bg-primary text-white' : '' }}">
                                    <i class="bi bi-person-circle {{ request()->routeIs('profile.*') ? 'text-white' : 'text-primary' }}"></i>
                                    <span>Profil & Foto Saya</span>
                                </a>
                            </li>
                            <li><hr class="dropdown-divider my-1"></li>
                        @endif
                        <li>
                            <form action="{{ route('logout') }}" method="POST" onsubmit="return confirm('Yakin ingin keluar dari sistem?')">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger fw-semibold d-flex align-items-center gap-2">
                                    <i class="bi bi-box-arrow-right"></i>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <main class="page">
            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
            document.getElementById('sidebarBackdrop').classList.toggle('show');
        }

        function updateThemeUI(theme) {
            const icon = document.getElementById('theme-icon');
            const btn  = document.getElementById('btn-theme-toggle');
            if (!icon || !btn) return;

            if (theme === 'dark') {
                icon.className = 'bi bi-sun-fill text-warning fs-6';
                btn.title = 'Ganti ke Mode Terang';
            } else {
                icon.className = 'bi bi-moon-stars-fill fs-6';
                btn.title = 'Ganti ke Mode Gelap';
            }
        }

        function toggleTheme() {
            const current = document.documentElement.getAttribute('data-theme') || 'light';
            const next = current === 'dark' ? 'light' : 'dark';

            document.documentElement.setAttribute('data-theme', next);
            document.documentElement.setAttribute('data-bs-theme', next);
            try { localStorage.setItem('absensi_theme', next); } catch (e) {}
            updateThemeUI(next);
        }

        document.addEventListener('DOMContentLoaded', function () {
            updateThemeUI(document.documentElement.getAttribute('data-theme') || 'light');
        });

        /* ---------- Jam WIB (Asia/Jakarta) ----------
           Disinkronkan dengan waktu server: selisih (offset) antara jam server dan jam
           perangkat dihitung sekali saat halaman dimuat, sehingga jam tetap akurat
           walau jam di HP/laptop pengguna salah. */
        (function () {
            const TZ = 'Asia/Jakarta';
            const serverNow = {{ (int) round(microtime(true) * 1000) }};
            const offset = serverNow - Date.now();

            const timeEl = document.getElementById('wib-time');
            const dateEl = document.getElementById('wib-date');
            if (!timeEl) return;

            const timeFmt = new Intl.DateTimeFormat('en-GB', {
                timeZone: TZ, hour: '2-digit', minute: '2-digit', second: '2-digit', hourCycle: 'h23'
            });
            const dateFmt = new Intl.DateTimeFormat('id-ID', {
                timeZone: TZ, weekday: 'long', day: '2-digit', month: 'long', year: 'numeric'
            });

            function tick() {
                const now = new Date(Date.now() + offset);
                timeEl.textContent = timeFmt.format(now);        // 14:05:09
                if (dateEl) dateEl.textContent = dateFmt.format(now);

                // jadwalkan tepat di pergantian detik berikutnya
                setTimeout(tick, 1000 - (now.getTime() % 1000) + 5);
            }
            tick();
        })();
    </script>
    @stack('scripts')
</body>
</html>