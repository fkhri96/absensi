<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Absensi Kampus - Portal Akademik</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,600;12..96,700;12..96,800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --navy: #0b2545;
            --blue: #2563eb;
            --blue-dark: #1d4ed8;
            --mint: #10b981;
            --bg: #f4f6fb;
            --surface: #ffffff;
            --text: #0f172a;
            --muted: #64748b;
            --line: #e2e8f0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg);
            color: var(--text);
            background-image:
                radial-gradient(at 0% 0%, rgba(37, 99, 235, 0.06) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(16, 185, 129, 0.06) 0px, transparent 50%);
        }

        h1, h2, h3, h4, h5, .display-font {
            font-family: 'Bricolage Grotesque', sans-serif;
            letter-spacing: -0.025em;
        }

        /* Navbar Glass */
        .navbar-custom {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.85);
        }

        .brand-logo {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--blue) 0%, #1e40af 100%);
            box-shadow: 0 6px 16px rgba(37, 99, 235, 0.25);
        }

        /* Hero Modern */
        .hero-banner {
            background: linear-gradient(135deg, #091933 0%, #16336b 55%, #2563eb 100%);
            border-radius: 28px;
            color: #ffffff;
            position: relative;
            overflow: hidden;
            box-shadow: 0 20px 45px rgba(11, 37, 69, 0.16);
        }

        .hero-banner::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: radial-gradient(rgba(255, 255, 255, 0.12) 1px, transparent 1px);
            background-size: 24px 24px;
            opacity: 0.45;
            pointer-events: none;
        }

        .hero-banner::after {
            content: '';
            position: absolute;
            width: 420px;
            height: 420px;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.22) 0%, transparent 70%);
            top: -120px;
            right: -80px;
            border-radius: 50%;
            pointer-events: none;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        /* Role Cards (Guest) */
        .role-btn-card {
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(10px);
            border-radius: 18px;
            border: 1px solid rgba(255, 255, 255, 0.6);
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
            color: var(--text);
            display: block;
            box-shadow: 0 8px 24px rgba(9, 25, 51, 0.12);
        }

        .role-btn-card:hover {
            transform: translateY(-4px) scale(1.01);
            background: #ffffff;
            box-shadow: 0 16px 32px rgba(9, 25, 51, 0.2);
        }

        .icon-box {
            width: 50px;
            height: 50px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            flex-shrink: 0;
        }

        /* Active Session Card (Auth) */
        .session-glass-card {
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.22);
            border-radius: 22px;
            color: #ffffff;
        }

        .status-dot {
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background-color: #34d399;
            display: inline-block;
            box-shadow: 0 0 0 4px rgba(52, 211, 153, 0.25);
        }

        .active-role-pill {
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            color: #065f46;
            padding: 6px 14px;
            border-radius: 99px;
            font-size: 0.82rem;
            font-weight: 600;
        }

        /* Stat & Content Cards */
        .stat-card {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 28px rgba(15, 23, 42, 0.07);
        }

        .content-card {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 22px;
            box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03);
        }

        .btn-login-nav {
            background: linear-gradient(135deg, var(--blue) 0%, var(--blue-dark) 100%);
            color: #fff;
            border: 0;
            border-radius: 12px;
            padding: 9px 20px;
            font-weight: 600;
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.25);
            transition: all 0.2s;
        }

        .btn-login-nav:hover {
            background: linear-gradient(135deg, var(--blue-dark) 0%, #1e3a8a 100%);
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(37, 99, 235, 0.35);
        }

        .btn-dashboard-cta {
            background: #ffffff;
            color: #0f172a;
            border: 0;
            border-radius: 14px;
            padding: 13px 26px;
            font-weight: 700;
            box-shadow: 0 10px 24px rgba(0, 0, 0, 0.18);
            transition: all 0.2s ease;
        }

        .btn-dashboard-cta:hover {
            background: #f8fafc;
            color: var(--blue);
            transform: translateY(-2px);
            box-shadow: 0 14px 28px rgba(0, 0, 0, 0.24);
        }
    </style>
</head>
<body>

@php
    $isLoggedIn = auth()->check();
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

    $userAvatar = !empty($currentUser?->foto)
        ? asset('storage/' . $currentUser->foto)
        : null;
@endphp

    <!-- Navbar Atas -->
    <nav class="navbar navbar-expand-lg navbar-custom sticky-top py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2 fw-bold text-dark" href="{{ route('home') }}">
                <span class="brand-logo d-flex align-items-center justify-content-center text-white">
                    <i class="bi bi-qr-code-scan fs-5"></i>
                </span>
                <span class="display-font fs-5">Absensi Kampus</span>
            </a>

            <div class="d-flex align-items-center gap-2 gap-md-3">
                <span class="text-secondary small d-none d-lg-inline-flex align-items-center gap-1 bg-white border rounded-pill px-3 py-1">
                    <i class="bi bi-calendar3 text-primary"></i> {{ now()->translatedFormat('l, d F Y') }}
                </span>

                @auth
                    <!-- Status Sedang Login + Tombol Langsung ke Dashboard -->
                    <span class="active-role-pill d-none d-sm-inline-flex align-items-center gap-2">
                        <span class="status-dot"></span>
                        <span>Sedang login: <strong>{{ $roleTitle }}</strong></span>
                    </span>

                    <a href="{{ $dashboardUrl }}" class="btn btn-login-nav d-inline-flex align-items-center gap-2">
                        <i class="bi bi-speedometer2"></i>
                        <span>Dashboard {{ $roleTitle }}</span>
                    </a>
                @else
                    <!-- Dropdown Tombol Login (Hanya Muncul Saat Belum Login) -->
                    <div class="dropdown">
                        <button class="btn btn-login-nav dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-box-arrow-in-right"></i> Masuk Portal
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-4 p-2 mt-2" style="min-width: 245px;">
                            <li>
                                <a class="dropdown-item rounded-3 py-2 d-flex align-items-center gap-2" href="{{ route('mahasiswa.login') }}">
                                    <i class="bi bi-mortarboard-fill fs-5" style="color:#d18a16;"></i>
                                    <div>
                                        <div class="fw-semibold">Login Mahasiswa</div>
                                        <small class="text-muted">Presensi & riwayat kelas</small>
                                    </div>
                                </a>
                            </li>
                            <li><hr class="dropdown-divider my-1"></li>
                            <li>
                                <a class="dropdown-item rounded-3 py-2 d-flex align-items-center gap-2" href="{{ route('dosen.login') }}">
                                    <i class="bi bi-person-workspace fs-5" style="color:#0f766e;"></i>
                                    <div>
                                        <div class="fw-semibold">Login Dosen</div>
                                        <small class="text-muted">Kelola sesi & rekap</small>
                                    </div>
                                </a>
                            </li>
                            <li><hr class="dropdown-divider my-1"></li>
                            <li>
                                <a class="dropdown-item rounded-3 py-2 d-flex align-items-center gap-2" href="{{ route('admin.login') }}">
                                    <i class="bi bi-shield-lock-fill fs-5" style="color:#2563eb;"></i>
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

    <!-- Konten Utama -->
    <div class="container py-4 py-md-5">

        <!-- Hero Section -->
        <div class="hero-banner p-4 p-md-5 mb-4">
            <div class="hero-content row align-items-center g-4">
                <div class="col-lg-7">
                    @auth
                        <div class="d-inline-flex align-items-center gap-2 bg-white bg-opacity-15 border border-white border-opacity-25 rounded-pill px-3 py-2 mb-3">
                            <span class="status-dot"></span>
                            <span class="small fw-semibold">Sedang Login sebagai {{ $roleTitle }} • {{ $currentUser->name }}</span>
                        </div>
                    @else
                        <span class="badge bg-white bg-opacity-10 text-white border border-white border-opacity-25 rounded-pill px-3 py-2 mb-3">
                            <i class="bi bi-broadcast me-1 text-warning"></i> Portal Presensi Akademik Terintegrasi
                        </span>
                    @endauth

                    <h1 class="fw-extrabold display-6 mb-3">
                        Pantau Kehadiran & Aktivitas Perkuliahan Secara Real-Time
                    </h1>
                    <p class="text-white-50 mb-4 fs-6">
                        Sistem absensi digital kampus untuk memudahkan mahasiswa melakukan presensi QR Code, dosen mengelola pertemuan, dan admin memantau rekap akademik.
                    </p>

                    @auth
                        <!-- Jika Sudah Login: Hanya Tombol Masuk ke Dashboard Sesuai Role -->
                        <div class="d-flex flex-wrap align-items-center gap-3">
                            <a href="{{ $dashboardUrl }}" class="btn btn-dashboard-cta btn-lg d-inline-flex align-items-center gap-2">
                                <i class="bi bi-grid-1x2-fill text-primary"></i>
                                <span>Masuk ke Dashboard {{ $roleTitle }}</span>
                                <i class="bi bi-arrow-right"></i>
                            </a>

                            <form action="{{ route('logout') }}" method="POST" onsubmit="return confirm('Keluar dari akun {{ $roleTitle }}?')">
                                @csrf
                                <button type="submit" class="btn btn-outline-light btn-lg rounded-4 px-4 fw-semibold">
                                    <i class="bi bi-box-arrow-right me-1"></i> Ganti Akun / Logout
                                </button>
                            </form>
                        </div>
                    @else
                        <!-- Jika Belum Login: Tampilkan 3 Tombol Portal -->
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('mahasiswa.login') }}" class="btn btn-warning btn-lg fw-bold px-4 rounded-4 text-dark shadow-sm">
                                <i class="bi bi-mortarboard-fill me-1"></i> Portal Mahasiswa
                            </a>
                            <a href="{{ route('dosen.login') }}" class="btn btn-light btn-lg fw-semibold px-4 rounded-4 text-dark shadow-sm">
                                <i class="bi bi-person-workspace me-1 text-success"></i> Portal Dosen
                            </a>
                            <a href="{{ route('admin.login') }}" class="btn btn-outline-light btn-lg fw-semibold px-4 rounded-4">
                                <i class="bi bi-shield-lock-fill me-1"></i> Admin
                            </a>
                        </div>
                    @endauth
                </div>

                <!-- Sisi Kanan Hero -->
                <div class="col-lg-5">
                    @auth
                        <!-- Kartu Sesi Aktif Pengguna yang Sedang Login -->
                        <div class="session-glass-card p-4">
                            <div class="d-flex align-items-center gap-3 mb-3 pb-3 border-bottom border-white border-opacity-15">
                                @if($userAvatar)
                                    <img src="{{ $userAvatar }}" alt="{{ $currentUser->name }}"
                                         class="rounded-circle object-fit-cover border border-2 border-white"
                                         style="width: 58px; height: 58px;">
                                @else
                                    <div class="rounded-circle bg-white text-primary fw-bold d-flex align-items-center justify-content-center fs-4"
                                         style="width: 58px; height: 58px;">
                                        {{ strtoupper(substr($currentUser->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div class="overflow-hidden">
                                    <span class="badge bg-success bg-opacity-75 rounded-pill mb-1">
                                        <i class="bi bi-check-circle-fill me-1"></i> Sesi Aktif ({{ $roleTitle }})
                                    </span>
                                    <h5 class="fw-bold mb-0 text-truncate">{{ $currentUser->name }}</h5>
                                    <small class="text-white-50 text-truncate d-block">{{ $currentUser->email }}</small>
                                </div>
                            </div>

                            <p class="small text-white-50 mb-3">
                                Anda sudah masuk ke dalam sistem. Klik tombol di bawah untuk langsung melanjutkan aktivitas pada panel <strong>{{ $roleTitle }}</strong>.
                            </p>

                            <div class="d-grid gap-2">
                                <a href="{{ $dashboardUrl }}" class="btn btn-light fw-bold text-primary rounded-3 py-2">
                                    Lanjut ke Dashboard {{ $roleTitle }} <i class="bi bi-arrow-right-short fs-5 align-middle"></i>
                                </a>
                                @if(Route::has('profile.index'))
                                    <a href="{{ route('profile.index') }}" class="btn btn-outline-light btn-sm rounded-3 py-2">
                                        <i class="bi bi-person-circle me-1"></i> Pengaturan Profil & Foto
                                    </a>
                                @endif
                            </div>
                        </div>
                    @else
                        <!-- Kartu Pilihan Login Cepat (Hanya Muncul Saat Belum Login) -->
                        <div class="d-flex flex-column gap-3">
                            <a href="{{ route('mahasiswa.login') }}" class="role-btn-card p-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="icon-box" style="background:#fff0d9; color:#d18a16;">
                                            <i class="bi bi-mortarboard-fill"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-0">Masuk sebagai Mahasiswa</h6>
                                            <small class="text-secondary">Scan QR kehadiran & cek riwayat absen</small>
                                        </div>
                                    </div>
                                    <i class="bi bi-arrow-right-circle-fill fs-4" style="color:#d18a16;"></i>
                                </div>
                            </a>

                            <a href="{{ route('dosen.login') }}" class="role-btn-card p-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="icon-box" style="background:#e0f5f1; color:#0f766e;">
                                            <i class="bi bi-person-workspace"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-0">Masuk sebagai Dosen</h6>
                                            <small class="text-secondary">Buka sesi QR kelas & validasi kehadiran</small>
                                        </div>
                                    </div>
                                    <i class="bi bi-arrow-right-circle-fill fs-4" style="color:#0f766e;"></i>
                                </div>
                            </a>

                            <a href="{{ route('admin.login') }}" class="role-btn-card p-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="icon-box" style="background:#e8eeff; color:#2563eb;">
                                            <i class="bi bi-shield-lock-fill"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-0">Masuk sebagai Administrator</h6>
                                            <small class="text-secondary">Kelola data master, jadwal & laporan</small>
                                        </div>
                                    </div>
                                    <i class="bi bi-arrow-right-circle-fill fs-4" style="color:#2563eb;"></i>
                                </div>
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Ringkasan Statistik Kampus (Live) -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="card stat-card p-3 p-md-4 h-100">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-secondary small fw-semibold">Total Mahasiswa</span>
                        <span class="badge bg-primary bg-opacity-10 text-primary p-2 rounded-3">
                            <i class="bi bi-people-fill fs-6"></i>
                        </span>
                    </div>
                    <h3 class="fw-bold mb-0">{{ $stats['mahasiswa'] }}</h3>
                    <small class="text-muted">Mahasiswa aktif terdaftar</small>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="card stat-card p-3 p-md-4 h-100">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-secondary small fw-semibold">Total Dosen</span>
                        <span class="badge bg-success bg-opacity-10 text-success p-2 rounded-3">
                            <i class="bi bi-person-badge-fill fs-6"></i>
                        </span>
                    </div>
                    <h3 class="fw-bold mb-0">{{ $stats['dosen'] }}</h3>
                    <small class="text-muted">Dosen pengampu mata kuliah</small>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="card stat-card p-3 p-md-4 h-100">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-secondary small fw-semibold">Mata Kuliah</span>
                        <span class="badge bg-info bg-opacity-10 text-info p-2 rounded-3">
                            <i class="bi bi-journal-bookmark-fill fs-6"></i>
                        </span>
                    </div>
                    <h3 class="fw-bold mb-0">{{ $stats['matkul'] }}</h3>
                    <small class="text-muted">Kurikulum semester berjalan</small>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="card stat-card p-3 p-md-4 h-100">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-secondary small fw-semibold">Sesi Absen Dibuka</span>
                        <span class="badge bg-warning bg-opacity-10 text-warning p-2 rounded-3">
                            <i class="bi bi-broadcast fs-6"></i>
                        </span>
                    </div>
                    <h3 class="fw-bold mb-0">{{ $stats['sesi_buka'] }}</h3>
                    <small class="text-muted">Kelas sedang berlangsung</small>
                </div>
            </div>
        </div>

        <!-- Jadwal Hari Ini & Panduan Singkat -->
        <div class="row g-4">
            <div class="col-lg-7">
                <div class="card content-card p-4 h-100">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0">
                            <i class="bi bi-clock-history text-primary me-2"></i>Jadwal Kuliah Hari Ini
                        </h5>
                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-1">Semester Aktif</span>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Jam</th>
                                    <th>Mata Kuliah</th>
                                    <th>Dosen</th>
                                    <th>Ruang</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($jadwalHariIni as $jadwal)
                                <tr>
                                    <td class="fw-semibold text-nowrap">
                                        {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $jadwal->mataKuliah->nama_mk ?? '-' }}</div>
                                        <small class="text-muted">{{ $jadwal->mataKuliah->prodi->nama_prodi ?? '' }}</small>
                                    </td>
                                    <td>{{ $jadwal->mataKuliah->dosen->user->name ?? '-' }}</td>
                                    <td><span class="badge bg-primary bg-opacity-10 text-primary">{{ $jadwal->ruang }}</span></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">
                                        <i class="bi bi-calendar-x fs-4 d-block mb-1"></i>
                                        Belum ada jadwal perkuliahan untuk hari ini.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card content-card p-4 h-100">
                    <h5 class="fw-bold mb-3">
                        <i class="bi bi-info-circle text-primary me-2"></i>Alur Penggunaan Sistem
                    </h5>
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex gap-3 align-items-start">
                            <span class="badge rounded-circle bg-primary d-flex align-items-center justify-content-center flex-shrink-0" style="width:32px; height:32px;">1</span>
                            <div>
                                <div class="fw-semibold">Masuk Sesuai Peran</div>
                                <small class="text-secondary">Login menggunakan akun Mahasiswa, Dosen, atau Administrator untuk membuka panel kerja.</small>
                            </div>
                        </div>
                        <div class="d-flex gap-3 align-items-start">
                            <span class="badge rounded-circle bg-primary d-flex align-items-center justify-content-center flex-shrink-0" style="width:32px; height:32px;">2</span>
                            <div>
                                <div class="fw-semibold">Buka Sesi QR & Presensi Kelas</div>
                                <small class="text-secondary">Dosen menampilkan QR Code sesi perkuliahan, lalu mahasiswa memindai QR untuk mencatat kehadiran.</small>
                            </div>
                        </div>
                        <div class="d-flex gap-3 align-items-start">
                            <span class="badge rounded-circle bg-primary d-flex align-items-center justify-content-center flex-shrink-0" style="width:32px; height:32px;">3</span>
                            <div>
                                <div class="fw-semibold">Rekap & Laporan Otomatis</div>
                                <small class="text-secondary">Seluruh data kehadiran tersimpan otomatis untuk rekap harian maupun cetak laporan akhir semester.</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Footer -->
    <footer class="text-center text-secondary small py-4 border-top bg-white mt-4">
        <div class="container">
            &copy; {{ date('Y') }} Sistem Absensi Kampus. Seluruh hak cipta dilindungi.
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>