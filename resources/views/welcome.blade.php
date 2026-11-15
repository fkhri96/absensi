<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Absensi Kampus - Portal Akademik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f4f6fb;
            color: #1e293b;
        }
        .navbar-custom {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid #e2e8f0;
        }
        .hero-banner {
            background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 60%, #2563eb 100%);
            border-radius: 24px;
            color: #ffffff;
            position: relative;
            overflow: hidden;
        }
        .hero-banner::after {
            content: '';
            position: absolute;
            width: 340px;
            height: 340px;
            background: radial-gradient(circle, rgba(255,255,255,0.12) 0%, transparent 70%);
            top: -80px;
            right: -60px;
            border-radius: 50%;
            pointer-events: none;
        }
        .role-btn-card {
            background: #ffffff;
            border-radius: 18px;
            border: 1px solid #e2e8f0;
            transition: all 0.25s ease;
            text-decoration: none;
            color: #1e293b;
            display: block;
        }
        .role-btn-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 14px 30px rgba(15, 23, 42, 0.10);
        }
        .icon-box {
            width: 54px;
            height: 54px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }
        .stat-card {
            background: #ffffff;
            border: none;
            border-radius: 18px;
            box-shadow: 0 4px 20px rgba(15, 23, 42, 0.04);
        }
        .content-card {
            background: #ffffff;
            border: none;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(15, 23, 42, 0.04);
        }
        .btn-login-nav {
            background: #2563eb;
            color: #fff;
            border-radius: 12px;
            padding: 8px 20px;
            font-weight: 600;
            transition: all 0.2s;
        }
        .btn-login-nav:hover {
            background: #1d4ed8;
            color: #fff;
            transform: translateY(-1px);
        }
    </style>
</head>
<body>

    <!-- Navbar Atas -->
    <nav class="navbar navbar-expand-lg navbar-custom sticky-top py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2 fw-bold text-dark" href="{{ route('home') }}">
                <span class="d-flex align-items-center justify-content-center rounded-3 text-white"
                      style="width:38px; height:38px; background:#2563eb;">
                    <i class="bi bi-qr-code-scan"></i>
                </span>
                <span>Absensi Kampus</span>
            </a>

            <div class="d-flex align-items-center gap-3">
                <span class="text-secondary small d-none d-md-inline">
                    <i class="bi bi-calendar3 me-1"></i> {{ now()->translatedFormat('l, d F Y') }}
                </span>

                <!-- Dropdown Tombol Login -->
                <div class="dropdown">
                    <button class="btn btn-login-nav dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-box-arrow-in-right"></i> Masuk Portal
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-4 p-2 mt-2" style="min-width: 230px;">
                        <li>
                            <a class="dropdown-item rounded-3 py-2 d-flex align-items-center gap-2" href="{{ route('mahasiswa.login') }}">
                                <i class="bi bi-mortarboard-fill" style="color:#d18a16;"></i>
                                <div>
                                    <div class="fw-semibold">Login Mahasiswa</div>
                                    <small class="text-muted">Presensi & riwayat kelas</small>
                                </div>
                            </a>
                        </li>
                        <li><hr class="dropdown-divider my-1"></li>
                        <li>
                            <a class="dropdown-item rounded-3 py-2 d-flex align-items-center gap-2" href="{{ route('dosen.login') }}">
                                <i class="bi bi-person-workspace" style="color:#0f766e;"></i>
                                <div>
                                    <div class="fw-semibold">Login Dosen</div>
                                    <small class="text-muted">Kelola sesi & rekap</small>
                                </div>
                            </a>
                        </li>
                        <li><hr class="dropdown-divider my-1"></li>
                        <li>
                            <a class="dropdown-item rounded-3 py-2 d-flex align-items-center gap-2" href="{{ route('admin.login') }}">
                                <i class="bi bi-shield-lock-fill" style="color:#3157d5;"></i>
                                <div>
                                    <div class="fw-semibold">Login Admin</div>
                                    <small class="text-muted">Panel manajemen kampus</small>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Konten Utama Dashboard Publik -->
    <div class="container py-4 py-md-5">

        <!-- Hero Section + 3 Tombol Portal Login -->
        <div class="hero-banner p-4 p-md-5 mb-4 shadow-sm">
            <div class="row align-items-center g-4">
                <div class="col-lg-7">
                    <span class="badge bg-white bg-opacity-10 text-white border border-white border-opacity-25 rounded-pill px-3 py-2 mb-3">
                        <i class="bi bi-broadcast me-1 text-warning"></i> Portal Presensi Akademik Terintegrasi
                    </span>
                    <h1 class="fw-extrabold display-6 mb-3">
                        Pantau Kehadiran & Aktivitas Perkuliahan Secara Real-Time
                    </h1>
                    <p class="text-white-50 mb-4 fs-6">
                        Sistem absensi digital kampus untuk memudahkan mahasiswa melakukan presensi kelas, dosen mengelola pertemuan, dan admin memantau rekap akademik.
                    </p>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('mahasiswa.login') }}" class="btn btn-warning btn-lg fw-bold px-4 rounded-3 text-dark">
                            <i class="bi bi-mortarboard-fill me-1"></i> Portal Mahasiswa
                        </a>
                        <a href="{{ route('dosen.login') }}" class="btn btn-light btn-lg fw-semibold px-4 rounded-3 text-dark">
                            <i class="bi bi-person-workspace me-1 text-success"></i> Portal Dosen
                        </a>
                        <a href="{{ route('admin.login') }}" class="btn btn-outline-light btn-lg fw-semibold px-4 rounded-3">
                            <i class="bi bi-shield-lock-fill me-1"></i> Admin
                        </a>
                    </div>
                </div>

                <!-- Kartu Pilihan Login Cepat di Sisi Kanan Hero -->
                <div class="col-lg-5">
                    <div class="d-flex flex-column gap-3">
                        <a href="{{ route('mahasiswa.login') }}" class="role-btn-card p-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="icon-box" style="background:#fff0d9; color:#d18a16;">
                                        <i class="bi bi-mortarboard-fill"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-0">Masuk sebagai Mahasiswa</h6>
                                        <small class="text-secondary">Isi kehadiran & cek persentase absen</small>
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
                                        <small class="text-secondary">Buka sesi kelas & validasi kehadiran</small>
                                    </div>
                                </div>
                                <i class="bi bi-arrow-right-circle-fill fs-4" style="color:#0f766e;"></i>
                            </div>
                        </a>

                        <a href="{{ route('admin.login') }}" class="role-btn-card p-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="icon-box" style="background:#e8eeff; color:#3157d5;">
                                        <i class="bi bi-shield-lock-fill"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-0">Masuk sebagai Administrator</h6>
                                        <small class="text-secondary">Kelola data master, jadwal & laporan</small>
                                    </div>
                                </div>
                                <i class="bi bi-arrow-right-circle-fill fs-4" style="color:#3157d5;"></i>
                            </div>
                        </a>
                    </div>
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
                            <i class="bi bi-people-fill"></i>
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
                            <i class="bi bi-person-badge-fill"></i>
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
                            <i class="bi bi-journal-bookmark-fill"></i>
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
                            <i class="bi bi-broadcast"></i>
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
                        <span class="badge bg-light text-dark border">Semester Aktif</span>
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
                        <div class="d-flex gap-3">
                            <span class="badge rounded-circle bg-primary d-flex align-items-center justify-content-center" style="width:30px; height:30px;">1</span>
                            <div>
                                <div class="fw-semibold">Pilih Portal Sesuai Peran</div>
                                <small class="text-secondary">Klik tombol masuk untuk Mahasiswa, Dosen, atau Admin menggunakan email kampus terdaftar.</small>
                            </div>
                        </div>
                        <div class="d-flex gap-3">
                            <span class="badge rounded-circle bg-primary d-flex align-items-center justify-content-center" style="width:30px; height:30px;">2</span>
                            <div>
                                <div class="fw-semibold">Buka Sesi & Presensi Kelas</div>
                                <small class="text-secondary">Dosen membuka sesi pertemuan, lalu mahasiswa mencatat kehadiran (Hadir, Izin, atau Sakit).</small>
                            </div>
                        </div>
                        <div class="d-flex gap-3">
                            <span class="badge rounded-circle bg-primary d-flex align-items-center justify-content-center" style="width:30px; height:30px;">3</span>
                            <div>
                                <div class="fw-semibold">Rekap & Laporan Otomatis</div>
                                <small class="text-secondary">Seluruh data kehadiran tersimpan otomatis untuk rekap harian maupun akhir semester.</small>
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