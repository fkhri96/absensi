<ul class="nav flex-column gap-1">
    <li class="nav-item">
        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active fw-bold' : '' }}">
            1. Dashboard Admin
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.mahasiswa.index') }}" class="nav-link {{ request()->routeIs('admin.mahasiswa.*') ? 'active fw-bold' : '' }}">
            2. Manajemen Mahasiswa
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.dosen.index') }}" class="nav-link {{ request()->routeIs('admin.dosen.*') ? 'active fw-bold' : '' }}">
            3. Manajemen Dosen
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.matakuliah.index') }}" class="nav-link {{ request()->routeIs('admin.matakuliah.*') ? 'active fw-bold' : '' }}">
            4. Manajemen Mata Kuliah
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.fakultas.index') }}" class="nav-link {{ request()->routeIs('admin.fakultas.*') ? 'active fw-bold' : '' }}">
            5a. Manajemen Fakultas
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.prodi.index') }}" class="nav-link {{ request()->routeIs('admin.prodi.*') ? 'active fw-bold' : '' }}">
            5b. Manajemen Prodi
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.jadwal.index') }}" class="nav-link {{ request()->routeIs('admin.jadwal.*') ? 'active fw-bold' : '' }}">
            6. Manajemen Jadwal
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.absensi.index') }}" class="nav-link {{ request()->routeIs('admin.absensi.*') ? 'active fw-bold' : '' }}">
            7. Manajemen Absensi
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.laporan.index') }}" class="nav-link {{ request()->routeIs('admin.laporan.*') ? 'active fw-bold' : '' }}">
            8. Laporan Absensi
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.qr.index') }}" class="nav-link {{ request()->routeIs('admin.qr.*') ? 'active fw-bold' : '' }}">
            9. Pengaturan QR Code
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.sistem.index') }}" class="nav-link {{ request()->routeIs('admin.sistem.*') ? 'active fw-bold' : '' }}">
            10. Pengaturan Sistem
        </a>
    </li>
</ul>