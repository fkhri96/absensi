@extends('layouts.app')

@section('title', 'Dashboard Admin | Absensi Kampus')
@section('page_title', 'Dashboard Admin')
@section('role', 'admin')

@push('styles')
    @include('partials.dashboard-styles')
@endpush

@section('content')
<div class="container-fluid px-0">

    <!-- Top Greeting & Tombol Logout -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
            <span class="text-muted small">Selamat datang kembali,</span>
            <strong class="ms-1">{{ auth()->user()->name }}</strong>
            <span class="badge bg-primary ms-1">Administrator</span>
        </div>
        <form action="{{ route('logout') }}" method="POST" onsubmit="return confirm('Yakin ingin keluar dari akun Admin?')">
            @csrf
            <button type="submit" class="btn btn-outline-danger btn-sm fw-semibold">
                <i class="bi bi-box-arrow-right me-1"></i> Logout
            </button>
        </form>
    </div>

    <!-- Hero Banner -->
    <section class="hero mb-4 shadow-sm">
        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <h2 class="fw-bold mb-2">Kehadiran seluruh kampus, dalam satu layar.</h2>
                <p class="mb-4 text-white-50">
                    Pantau absensi mahasiswa dan dosen, kelola master data akademik, atur QR Code presensi, dan unduh laporan kapan saja.
                </p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('admin.laporan.index') }}" class="btn btn-mint px-4 py-2">
                        <i class="bi bi-file-earmark-bar-graph me-1"></i> Lihat Laporan
                    </a>
                    <a href="{{ route('admin.mahasiswa.index') }}" class="btn btn-outline-light-soft px-4 py-2">
                        <i class="bi bi-people me-1"></i> Kelola Mahasiswa
                    </a>
                    <a href="{{ route('admin.qr.index') }}" class="btn btn-outline-light-soft px-4 py-2">
                        <i class="bi bi-qr-code me-1"></i> Pengaturan QR
                    </a>
                </div>
            </div>
            <div class="col-lg-4">
                @include('partials.qr')
            </div>
        </div>
    </section>

    <!-- 4 Kartu Statistik Utama -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3">
            <div class="stat-card p-3 p-md-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-secondary small fw-semibold">Total Mahasiswa</span>
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                        <i class="bi bi-mortarboard-fill"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-1">{{ $stats['total_mahasiswa'] }}</h3>
                <small class="text-muted">Mahasiswa aktif terdaftar</small>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="stat-card p-3 p-md-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-secondary small fw-semibold">Total Dosen</span>
                    <div class="stat-icon bg-success bg-opacity-10 text-success">
                        <i class="bi bi-person-workspace"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-1">{{ $stats['total_dosen'] }}</h3>
                <small class="text-muted">Dosen pengampu aktif</small>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="stat-card p-3 p-md-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-secondary small fw-semibold">Mata Kuliah / Prodi</span>
                    <div class="stat-icon bg-info bg-opacity-10 text-info">
                        <i class="bi bi-journal-bookmark-fill"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-1">{{ $stats['total_matkul'] }} <span class="fs-6 text-muted fw-normal">/ {{ $stats['total_prodi'] }} Prodi</span></h3>
                <small class="text-muted">{{ $stats['total_fakultas'] }} Fakultas &bull; {{ $stats['total_jadwal'] }} Jadwal</small>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="stat-card p-3 p-md-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-secondary small fw-semibold">Hadir Hari Ini</span>
                    <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-1">{{ $stats['hadir_hari_ini'] }}</h3>
                <small class="text-muted">{{ $stats['sesi_aktif'] }} sesi kelas sedang dibuka</small>
            </div>
        </div>
    </div>

    <!-- Akses Cepat 10 Fitur Admin -->
    <div class="dashboard-card card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="bi bi-grid-fill text-primary me-2"></i>Pusat Manajemen Sistem (10 Modul Admin)</span>
        </div>
        <div class="card-body">
            <div class="row g-2">
                <div class="col-md-4 col-lg-3">
                    <a href="{{ route('admin.mahasiswa.index') }}" class="btn btn-outline-primary quick-action-btn w-100 text-start">
                        <i class="bi bi-people-fill me-2"></i>2. Data Mahasiswa
                    </a>
                </div>
                <div class="col-md-4 col-lg-3">
                    <a href="{{ route('admin.dosen.index') }}" class="btn btn-outline-primary quick-action-btn w-100 text-start">
                        <i class="bi bi-person-badge-fill me-2"></i>3. Data Dosen
                    </a>
                </div>
                <div class="col-md-4 col-lg-3">
                    <a href="{{ route('admin.matakuliah.index') }}" class="btn btn-outline-primary quick-action-btn w-100 text-start">
                        <i class="bi bi-book-fill me-2"></i>4. Mata Kuliah
                    </a>
                </div>
                <div class="col-md-4 col-lg-3">
                    <a href="{{ route('admin.fakultas.index') }}" class="btn btn-outline-primary quick-action-btn w-100 text-start">
                        <i class="bi bi-building me-2"></i>5a. Data Fakultas
                    </a>
                </div>
                <div class="col-md-4 col-lg-3">
                    <a href="{{ route('admin.prodi.index') }}" class="btn btn-outline-primary quick-action-btn w-100 text-start">
                        <i class="bi bi-diagram-3-fill me-2"></i>5b. Program Studi
                    </a>
                </div>
                <div class="col-md-4 col-lg-3">
                    <a href="{{ route('admin.jadwal.index') }}" class="btn btn-outline-primary quick-action-btn w-100 text-start">
                        <i class="bi bi-calendar-week-fill me-2"></i>6. Jadwal Kuliah
                    </a>
                </div>
                <div class="col-md-4 col-lg-3">
                    <a href="{{ route('admin.absensi.index') }}" class="btn btn-outline-success quick-action-btn w-100 text-start">
                        <i class="bi bi-clipboard-check-fill me-2"></i>7. Manajemen Absensi
                    </a>
                </div>
                <div class="col-md-4 col-lg-3">
                    <a href="{{ route('admin.laporan.index') }}" class="btn btn-outline-success quick-action-btn w-100 text-start">
                        <i class="bi bi-file-earmark-spreadsheet-fill me-2"></i>8. Laporan Absensi
                    </a>
                </div>
                <div class="col-md-4 col-lg-3">
                    <a href="{{ route('admin.qr.index') }}" class="btn btn-outline-dark quick-action-btn w-100 text-start">
                        <i class="bi bi-qr-code-scan me-2"></i>9. Pengaturan QR Code
                    </a>
                </div>
                <div class="col-md-4 col-lg-3">
                    <a href="{{ route('admin.sistem.index') }}" class="btn btn-outline-dark quick-action-btn w-100 text-start">
                        <i class="bi bi-gear-fill me-2"></i>10. Pengaturan Sistem
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Aktivitas Presensi Terbaru -->
    <div class="dashboard-card card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="bi bi-activity text-success me-2"></i>Aktivitas Presensi Terbaru</span>
            <a href="{{ route('admin.absensi.index') }}" class="btn btn-sm btn-light border">Lihat Semua</a>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>NIM & Nama Mahasiswa</th>
                        <th>Mata Kuliah</th>
                        <th>Pertemuan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($aktivitasTerbaru as $item)
                    <tr>
                        <td class="text-nowrap">{{ $item->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <strong>{{ $item->mahasiswa->user->name ?? '-' }}</strong><br>
                            <small class="text-muted">{{ $item->mahasiswa->nim ?? '-' }}</small>
                        </td>
                        <td>{{ $item->sesiAbsensi->jadwal->mataKuliah->nama_mk ?? '-' }}</td>
                        <td>Ke-{{ $item->sesiAbsensi->pertemuan_ke ?? '-' }}</td>
                        <td>
                            @php
                                $badge = match($item->status) {
                                    'hadir' => 'bg-success',
                                    'izin'  => 'bg-info',
                                    'sakit' => 'bg-warning text-dark',
                                    default => 'bg-danger',
                                };
                            @endphp
                            <span class="badge {{ $badge }}">{{ strtoupper($item->status) }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">Belum ada aktivitas presensi yang tercatat.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection