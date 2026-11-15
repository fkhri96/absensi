<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Absensi Kampus')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,600;12..96,700;12..96,800&family=Figtree:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --navy: #0b2545;
            --navy-2: #13315c;
            --blue: #2f5bea;
            --mint: #13c4a3;
            --bg: #f2f4f8;
            --surface: #ffffff;
            --text: #14213d;
            --muted: #6b7690;
            --line: #e4e8f0;
            --sidebar-w: 260px;
        }

        body {
            font-family: 'Figtree', sans-serif;
            background: var(--bg);
            color: var(--text);
        }

        h1, h2, h3, h4, h5, .display-font {
            font-family: 'Bricolage Grotesque', sans-serif;
            letter-spacing: -0.02em;
        }

        a:focus-visible, button:focus-visible {
            outline: 3px solid var(--mint);
            outline-offset: 2px;
        }

        /* ---------- Sidebar ---------- */
        .sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            width: var(--sidebar-w);
            background: var(--navy);
            color: #c9d4ea;
            padding: 28px 18px;
            display: flex;
            flex-direction: column;
            z-index: 1040;
        }

        .sidebar .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #fff;
            text-decoration: none;
            font-family: 'Bricolage Grotesque', sans-serif;
            font-weight: 700;
            font-size: 1.25rem;
            padding: 0 10px 28px;
        }

        .brand-mark {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: var(--mint);
            display: grid;
            place-items: center;
            color: var(--navy);
        }

        .sidebar .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #c9d4ea;
            padding: 11px 14px;
            border-radius: 10px;
            font-weight: 500;
            transition: background .15s, color .15s;
        }

        .sidebar .nav-link:hover { background: rgba(255,255,255,.07); color: #fff; }

        .sidebar .nav-link.active {
            background: var(--blue);
            color: #fff;
        }

        .sidebar .nav-label {
            font-size: .78rem;
            color: #7f90b3;
            padding: 18px 14px 8px;
        }

        .sidebar-footer {
            margin-top: auto;
            padding: 14px;
            border-radius: 12px;
            background: var(--navy-2);
            font-size: .85rem;
            color: #aab8d6;
        }

        .ico {
            width: 18px; height: 18px; flex-shrink: 0;
            fill: none; stroke: currentColor; stroke-width: 2;
            stroke-linecap: round; stroke-linejoin: round;
        }

        /* ---------- Content area ---------- */
        .content-wrap { margin-left: var(--sidebar-w); min-height: 100vh; }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 18px 40px;
            background: rgba(242,244,248,.85);
            backdrop-filter: blur(8px);
            position: sticky;
            top: 0;
            z-index: 1000;
            border-bottom: 1px solid var(--line);
        }

        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--blue);
            color: #fff;
            display: grid;
            place-items: center;
            font-weight: 600;
        }

        main.page { padding: 32px 40px 56px; }

        /* ---------- Shared components ---------- */
        .panel {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 16px;
        }

        .btn-brand {
            background: var(--blue);
            color: #fff;
            border: 0;
            border-radius: 10px;
            padding: 10px 18px;
            font-weight: 600;
        }
        .btn-brand:hover { background: #2449c8; color: #fff; }

        .btn-ghost {
            background: transparent;
            color: var(--text);
            border: 1px solid var(--line);
            border-radius: 10px;
            padding: 10px 18px;
            font-weight: 600;
        }
        .btn-ghost:hover { background: var(--bg); }

        /* ---------- Mobile ---------- */
        @media (max-width: 991.98px) {
            .sidebar { transform: translateX(-100%); transition: transform .25s; }
            .sidebar.open { transform: none; }
            .content-wrap { margin-left: 0; }
            .topbar, main.page { padding-left: 20px; padding-right: 20px; }
        }

        @media (prefers-reduced-motion: reduce) {
            * { animation: none !important; transition: none !important; }
        }
    </style>
    @stack('styles')
</head>

<body>
@php
    $roleKey = trim(View::yieldContent('role')) ?: 'admin';
    $roleLabel = ['admin' => 'Administrator', 'dosen' => 'Dosen', 'mahasiswa' => 'Mahasiswa'][$roleKey] ?? 'Pengguna';
@endphp

    <aside class="sidebar" id="sidebar">
        <a class="brand" href="/">
            <span class="brand-mark" aria-hidden="true">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><path d="M14 14h3v3h-3zM20 14v.01M14 20v.01M20 20h.01M17 20v-1"/></svg>
            </span>
            Absensi Kampus
        </a>

        <nav class="nav flex-column">
            @include('partials.menu.' . $roleKey)
        </nav>

<div class="sidebar-footer">
            <div class="mb-2">
                Masuk sebagai<br>
                <strong class="text-white">{{ $roleLabel }}</strong>
            </div>
            <form action="{{ route('logout') }}" method="POST" onsubmit="return confirm('Yakin ingin keluar dari sistem?')">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm w-100 d-flex align-items-center justify-content-center gap-2 fw-semibold">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    <div class="content-wrap">
        <header class="topbar">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-ghost d-lg-none p-2" type="button" aria-label="Buka menu"
                        onclick="document.getElementById('sidebar').classList.toggle('open')">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <span class="fw-semibold">@yield('page_title', 'Dashboard')</span>
            </div>

            <div class="d-flex align-items-center gap-3">
                <span class="text-secondary small d-none d-md-inline">{{ now()->translatedFormat('l, d F Y') }}</span>
                
                <!-- Dropdown Avatar & Logout -->
                <div class="dropdown">
                    <button class="avatar border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Menu Akun">
                        {{ strtoupper(substr(auth()->user()->name ?? $roleLabel, 0, 1)) }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2">
                        <li class="px-3 py-2">
                            <div class="fw-semibold small">{{ auth()->user()->name ?? $roleLabel }}</div>
                            <div class="text-muted" style="font-size: 0.75rem;">{{ $roleLabel }}</div>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST" onsubmit="return confirm('Yakin ingin keluar dari sistem?')">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger fw-semibold d-flex align-items-center gap-2">
                                    <i class="bi bi-box-arrow-right"></i> Logout
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
    @stack('scripts')
</body>
</html>