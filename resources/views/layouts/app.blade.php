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

    <style>
        :root {
            --navy: #0b2545;
            --navy-2: #13315c;
            --blue: #2f5bea;
            --blue-soft: rgba(47, 91, 234, 0.1);
            --mint: #13c4a3;
            --bg: #f4f6fb;
            --surface: #ffffff;
            --text: #14213d;
            --muted: #6b7690;
            --line: #e5e9f2;
            --sidebar-w: 268px;
        }

        body {
            font-family: 'Figtree', sans-serif;
            background: var(--bg);
            color: var(--text);
            overflow-x: hidden;
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
            background: linear-gradient(180deg, var(--navy) 0%, #081b33 100%);
            color: #c9d4ea;
            padding: 24px 16px;
            display: flex;
            flex-direction: column;
            z-index: 1045;
            box-shadow: 4px 0 24px rgba(11, 37, 69, 0.08);
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
            padding: 4px 10px 22px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            margin-bottom: 14px;
        }

        .brand-mark {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--mint) 0%, #0ea58a 100%);
            display: grid;
            place-items: center;
            color: var(--navy);
            box-shadow: 0 6px 16px rgba(19, 196, 163, 0.28);
            flex-shrink: 0;
        }

        .sidebar-nav-scroll {
            flex: 1;
            overflow-y: auto;
            padding-right: 2px;
        }

        .sidebar-nav-scroll::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-nav-scroll::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 99px;
        }

        .sidebar .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #c4d1ea;
            padding: 10px 14px;
            border-radius: 11px;
            font-weight: 500;
            font-size: 0.93rem;
            margin-bottom: 3px;
            transition: all .18s ease;
        }

        .sidebar .nav-link:hover {
            background: rgba(255, 255, 255, 0.08);
            color: #fff;
            transform: translateX(2px);
        }

        .sidebar .nav-link.active {
            background: linear-gradient(135deg, var(--blue) 0%, #2449c8 100%);
            color: #fff;
            font-weight: 600;
            box-shadow: 0 6px 16px rgba(47, 91, 234, 0.32);
        }

        .sidebar .nav-label {
            font-size: .72rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            font-weight: 700;
            color: #798cb3;
            padding: 16px 14px 6px;
        }

        .sidebar-footer {
            margin-top: 14px;
            padding: 12px;
            border-radius: 14px;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(6px);
        }

        .sidebar-profile-link {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: #fff;
            padding: 6px;
            border-radius: 10px;
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

        /* ---------- Content area ---------- */
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
            padding: 14px 36px;
            background: rgba(255, 255, 255, 0.86);
            backdrop-filter: blur(12px);
            position: sticky;
            top: 0;
            z-index: 1020;
            border-bottom: 1px solid var(--line);
        }

        .date-pill {
            background: var(--bg);
            border: 1px solid var(--line);
            color: var(--muted);
            padding: 7px 14px;
            border-radius: 99px;
            font-size: 0.82rem;
            font-weight: 500;
        }

        /* User Pill Trigger in Topbar */
        .user-trigger {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 99px;
            padding: 5px 14px 5px 6px;
            transition: all .18s ease;
            cursor: pointer;
        }

        .user-trigger:hover, .user-trigger[aria-expanded="true"] {
            border-color: #cbd5e1;
            background: #f8fafc;
            box-shadow: 0 4px 12px rgba(20, 33, 61, 0.06);
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
            min-width: 240px;
            border: 1px solid var(--line);
            border-radius: 16px;
            padding: 8px;
            box-shadow: 0 16px 36px rgba(11, 37, 69, 0.12);
        }

        .dropdown-menu-modern .dropdown-item {
            border-radius: 10px;
            padding: 9px 12px;
            font-size: 0.9rem;
            font-weight: 500;
            transition: background .15s;
        }

        .dropdown-menu-modern .dropdown-item:hover {
            background: var(--bg);
        }

        main.page {
            padding: 28px 36px 56px;
            flex: 1;
        }

        /* ---------- Shared components ---------- */
        .panel {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 18px;
            box-shadow: 0 2px 10px rgba(20, 33, 61, 0.02);
        }

        .btn-brand {
            background: linear-gradient(135deg, var(--blue) 0%, #2449c8 100%);
            color: #fff;
            border: 0;
            border-radius: 11px;
            padding: 10px 18px;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(47, 91, 234, 0.22);
            transition: all .18s ease;
        }

        .btn-brand:hover {
            background: linear-gradient(135deg, #2449c8 0%, #1d3ba6 100%);
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(47, 91, 234, 0.3);
        }

        .btn-ghost {
            background: var(--surface);
            color: var(--text);
            border: 1px solid var(--line);
            border-radius: 11px;
            padding: 10px 18px;
            font-weight: 600;
            transition: all .15s ease;
        }

        .btn-ghost:hover {
            background: var(--bg);
            border-color: #cfd7e6;
        }

        /* ---------- Mobile & Backdrop ---------- */
        .sidebar-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(11, 37, 69, 0.45);
            backdrop-filter: blur(2px);
            z-index: 1040;
        }

        .sidebar-backdrop.show {
            display: block;
        }

        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform .25s cubic-bezier(0.4, 0, 0.2, 1);
            }
            .sidebar.open {
                transform: none;
            }
            .content-wrap {
                margin-left: 0;
            }
            .topbar, main.page {
                padding-left: 18px;
                padding-right: 18px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            * { animation: none !important; transition: none !important; }
        }
    </style>
    @stack('styles')
</head>

<body>
@php
    $roleKey   = trim(View::yieldContent('role')) ?: (auth()->user()->role ?? 'admin');
    $roleLabel = ['admin' => 'Administrator', 'dosen' => 'Dosen', 'mahasiswa' => 'Mahasiswa'][$roleKey] ?? 'Pengguna';
    $authUser  = auth()->user();$userName  = $authUser->name ?? $roleLabel;
    $userFoto  = !empty($authUser->foto) ? asset('storage/' . $authUser->foto) : null;
    $profilUrl = Route::has('profile.index') ? route('profile.index') : url('/profil');
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

            <div class="d-flex align-items-center gap-3">
                <span class="date-pill d-none d-md-inline-flex align-items-center gap-2">
                    <i class="bi bi-calendar3 text-primary"></i>
                    {{ now()->translatedFormat('l, d F Y') }}
                </span>

                <!-- Dropdown Profil & Logout -->
                <div class="dropdown">
                    <button class="user-trigger" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Menu Akun & Profil">
                        @if($userFoto)
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
                        <li>
                            <a href="{{ $profilUrl }}" class="dropdown-item d-flex align-items-center gap-2 {{ request()->routeIs('profile.*') ? 'active bg-primary text-white' : '' }}">
                                <i class="bi bi-person-circle {{ request()->routeIs('profile.*') ? 'text-white' : 'text-primary' }}"></i>
                                <span>Profil & Foto Saya</span>
                            </a>
                        </li>
                        <li><hr class="dropdown-divider my-1"></li>
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
    </script>
    @stack('scripts')
</body>
</html>