<ul class="nav flex-column gap-1">
    <li class="nav-item">
        <a href="{{ route('mahasiswa.dashboard') }}" class="nav-link {{ request()->routeIs('mahasiswa.dashboard') ? 'active fw-bold' : '' }}">
            Dashboard Mahasiswa
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('mahasiswa.absensi.index') }}" class="nav-link {{ request()->routeIs('mahasiswa.absensi.*') ? 'active fw-bold' : '' }}">
            Presensi & Riwayat
        </a>
    </li>
    <div class="nav-label">Menu Utama</div>
    <a href="{{ route('mahasiswa.matakuliah.index') }}" class="nav-link {{ request()->routeIs('mahasiswa.matakuliah.*') ? 'active' : '' }}">
        <i class="bi bi-book-fill ico"></i> Mata Kuliah Saya
    </a>
    <a href="{{ route('mahasiswa.jadwal.index') }}" class="nav-link {{ request()->routeIs('mahasiswa.jadwal.*') ? 'active' : '' }}">
        <i class="bi bi-calendar-week-fill ico"></i> Jadwal Kuliah
    </a>

    <div class="nav-label">Presensi & Akademik</div>
    <a href="{{ route('mahasiswa.absensi.index') }}" class="nav-link {{ request()->routeIs('mahasiswa.absensi.*') ? 'active' : '' }}">
        <i class="bi bi-qr-code-scan ico"></i> Scan QR Absensi
    </a>
    <a href="{{ route('mahasiswa.riwayat.index') }}" class="nav-link {{ request()->routeIs('mahasiswa.riwayat.*') ? 'active' : '' }}">
        <i class="bi bi-clock-history ico"></i> Riwayat Absensi
    </a>
    <a href="{{ route('mahasiswa.statistik.index') }}" class="nav-link {{ request()->routeIs('mahasiswa.statistik.*') ? 'active' : '' }}">
        <i class="bi bi-pie-chart-fill ico"></i> Statistik Kehadiran
    </a>
    <a href="{{ route('mahasiswa.izin.index') }}" class="nav-link {{ request()->routeIs('mahasiswa.izin.*') ? 'active' : '' }}">
        <i class="bi bi-file-earmark-medical-fill ico"></i> Pengajuan Izin / Sakit
    </a>
    <a href="{{ route('mahasiswa.laporan.cetak') }}" target="_blank" class="nav-link">
        <i class="bi bi-file-pdf-fill ico"></i> Unduh Rekap PDF
    </a>

    <div class="nav-label">Informasi</div>
    <a href="{{ route('mahasiswa.notifikasi.index') }}" class="nav-link {{ request()->routeIs('mahasiswa.notifikasi.*') ? 'active' : '' }}">
        <i class="bi bi-bell-fill ico"></i> Notifikasi
    </a>
    <a href="{{ route('mahasiswa.profil.index') }}" class="nav-link {{ request()->routeIs('mahasiswa.profil.*') ? 'active' : '' }}">
        <i class="bi bi-person-fill ico"></i> Profil Saya
    </a>
</ul>