<div class="nav-label">Menu Utama</div>
<a class="nav-link {{ request()->routeIs('dosen.dashboard') ? 'active' : '' }}" href="{{ route('dosen.dashboard') }}">
    <i class="bi bi-grid-1x2-fill"></i>
    <span>Dashboard Dosen</span>
</a>
<a class="nav-link {{ request()->routeIs('dosen.notifikasi.*') ? 'active' : '' }}" href="{{ route('dosen.notifikasi.index') }}">
    <i class="bi bi-bell-fill"></i>
    <span class="flex-grow-1">Notifikasi</span>
    @php
        $sesiAktifCount = \App\Models\SesiAbsensi::where('status', 'buka')->count();
    @endphp
    @if($sesiAktifCount > 0)
        <span class="badge rounded-pill bg-warning text-dark">{{ $sesiAktifCount }}</span>
    @endif
</a>
@php
    $dosenAuth = auth()->user() && auth()->user()->role === 'dosen'
        ? \App\Models\Dosen::where('user_id', auth()->id())->first()
        : null;
    $jumlahIzinPending =$dosenAuth
        ? \App\Models\Perizinan::where('dosen_id', $dosenAuth->id)->where('status', 'pending')->count()
        : 0;
@endphp

@if(Route::has('dosen.izin.index'))
<a href="{{ route('dosen.izin.index') }}" class="nav-link d-flex justify-content-between align-items-center {{ request()->routeIs('dosen.izin.*') ? 'active' : '' }}">
    <span class="d-flex align-items-center gap-2">
        <i class="bi bi-envelope-paper fs-6"></i>
        <span>Surat Izin Mahasiswa</span>
    </span>
    @if($jumlahIzinPending > 0)
        <span class="badge bg-danger rounded-pill">{{ $jumlahIzinPending }}</span>
    @endif
</a>
@endif

<div class="nav-label">Perkuliahan</div>
<a class="nav-link {{ request()->routeIs('dosen.matakuliah.*') ? 'active' : '' }}" href="{{ route('dosen.matakuliah.index') }}">
    <i class="bi bi-journal-bookmark-fill"></i>
    <span>Mata Kuliah</span>
</a>
<a class="nav-link {{ request()->routeIs('dosen.jadwal.*') ? 'active' : '' }}" href="{{ route('dosen.jadwal.index') }}">
    <i class="bi bi-calendar2-week-fill"></i>
    <span>Jadwal Mengajar</span>
</a>
<a class="nav-link {{ request()->routeIs('dosen.mahasiswa.*') ? 'active' : '' }}" href="{{ route('dosen.mahasiswa.index') }}">
    <i class="bi bi-people-fill"></i>
    <span>Daftar Mahasiswa</span>
</a>

<div class="nav-label">Presensi & Laporan</div>
<a class="nav-link {{ request()->routeIs('dosen.absensi.create') ? 'active' : '' }}" href="{{ route('dosen.absensi.create') }}">
    <i class="bi bi-qr-code"></i>
    <span>Buat QR Absensi</span>
</a>
<a class="nav-link {{ request()->routeIs('dosen.absensi.index', 'dosen.absensi.show') ? 'active' : '' }}" href="{{ route('dosen.absensi.index') }}">
    <i class="bi bi-clipboard2-check-fill"></i>
    <span>Kelola Absensi</span>
</a>
<a class="nav-link {{ request()->routeIs('dosen.laporan.*') ? 'active' : '' }}" href="{{ route('dosen.laporan.index') }}">
    <i class="bi bi-file-earmark-bar-graph-fill"></i>
    <span>Rekap & Laporan</span>
</a>