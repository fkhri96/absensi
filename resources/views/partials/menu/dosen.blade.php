<ul class="nav flex-column gap-1">
    <li class="nav-item">
        <a href="{{ route('dosen.dashboard') }}" class="nav-link {{ request()->routeIs('dosen.dashboard') ? 'active fw-bold' : '' }}">
            Dashboard Dosen
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('dosen.absensi.index') }}" class="nav-link {{ request()->routeIs('dosen.absensi.*') ? 'active fw-bold' : '' }}">
            Kelola Sesi Absensi
        </a>
    </li>
</ul>