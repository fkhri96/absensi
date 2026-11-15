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
</ul>