@extends('layouts.app')

@section('title', 'Dashboard Dosen | Absensi Kampus')
@section('page_title', 'Dashboard Dosen')
@section('role', 'dosen')

@push('styles')
    @include('partials.dashboard-styles')
@endpush

@section('content')
<div class="container-fluid px-0">

    <!-- Top Greeting & Tombol Logout -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
            <span class="text-muted small">Portal Pengajaran Dosen,</span>
            <strong class="ms-1">{{ auth()->user()->name }}</strong>
            @if($dosen)
                <span class="badge bg-success ms-1">NIDN: {{ $dosen->nidn }}</span>
            @endif
        </div>
        <form action="{{ route('logout') }}" method="POST" onsubmit="return confirm('Yakin ingin keluar dari akun Dosen?')">
            @csrf
            <button type="submit" class="btn btn-outline-danger btn-sm fw-semibold">
                <i class="bi bi-box-arrow-right me-1"></i> Logout
            </button>
        </form>
    </div>

    <!-- Hero Banner Dosen -->
    <section class="hero mb-4 shadow-sm" style="background: linear-gradient(135deg, #064e3b 0%, #0f766e 60%, #14b8a6 100%);">
        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <h2 class="fw-bold mb-2">Kelola perkuliahan & presensi kelas dengan mudah.</h2>
                <p class="mb-4 text-white-50">
                    Buka sesi pertemuan hari ini, tampilkan QR Code presensi untuk mahasiswa, dan pantau rekap kehadiran setiap pertemuan.
                </p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('dosen.absensi.create') }}" class="btn btn-light text-dark fw-bold px-4 py-2 rounded-3">
                        <i class="bi bi-plus-circle-fill text-success me-1"></i> Buka Sesi Absensi Baru
                    </a>
                    <a href="{{ route('dosen.absensi.index') }}" class="btn btn-outline-light-soft px-4 py-2">
                        <i class="bi bi-list-check me-1"></i> Daftar Sesi & Rekap
                    </a>
                </div>
            </div>
            <div class="col-lg-4">
                @include('partials.qr')
            </div>
        </div>
    </section>

    <!-- 4 Kartu Statistik Dosen -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3">
            <div class="stat-card p-3 p-md-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-secondary small fw-semibold">Mata Kuliah Diampu</span>
                    <div class="stat-icon bg-success bg-opacity-10 text-success">
                        <i class="bi bi-journal-text"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-1">{{ $stats['total_matkul'] }}</h3>
                <small class="text-muted">Mata kuliah semester ini</small>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="stat-card p-3 p-md-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-secondary small fw-semibold">Jadwal Mengajar</span>
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                        <i class="bi bi-calendar2-week-fill"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-1">{{ $stats['total_jadwal'] }}</h3>
                <small class="text-muted">Kelas terjadwal per minggu</small>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="stat-card p-3 p-md-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-secondary small fw-semibold">Sesi Sedang Dibuka</span>
                    <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                        <i class="bi bi-broadcast"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-1">{{ $stats['sesi_aktif'] }}</h3>
                <small class="text-muted">Sesi aktif menerima presensi</small>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="stat-card p-3 p-md-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-secondary small fw-semibold">Total Pertemuan</span>
                    <div class="stat-icon bg-info bg-opacity-10 text-info">
                        <i class="bi bi-check2-square"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-1">{{ $stats['total_sesi'] }}</h3>
                <small class="text-muted">Riwayat sesi perkuliahan</small>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Jadwal Mengajar Dosen -->
        <div class="col-lg-6">
            <div class="dashboard-card card h-100">
                <div class="card-header">
                    <i class="bi bi-clock-fill text-success me-2"></i>Jadwal Mengajar Saya
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Hari & Jam</th>
                                <th>Mata Kuliah</th>
                                <th>Ruang</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($jadwals as $jadwal)
                            <tr>
                                <td>
                                    <strong>{{ $jadwal->hari }}</strong><br>
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
                                    </small>
                                </td>
                                <td>{{ $jadwal->mataKuliah->nama_mk ?? '-' }}</td>
                                <td><span class="badge bg-dark">{{ $jadwal->ruang }}</span></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-4 text-muted">Belum ada jadwal mengajar yang ditugaskan Admin.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sesi Absensi Terbaru -->
        <div class="col-lg-6">
            <div class="dashboard-card card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-clipboard-data-fill text-primary me-2"></i>Sesi Absensi Terbaru</span>
                    <a href="{{ route('dosen.absensi.index') }}" class="btn btn-sm btn-light border">Semua Sesi</a>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Mata Kuliah</th>
                                <th>Pertemuan</th>
                                <th>Hadir</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sesis as $sesi)
                            <tr>
                                <td>
                                    <strong>{{ $sesi->jadwal->mataKuliah->nama_mk ?? '-' }}</strong><br>
                                    <small class="text-muted">{{ $sesi->tanggal }}</small>
                                </td>
                                <td>Ke-{{ $sesi->pertemuan_ke }}</td>
                                <td><span class="badge bg-info">{{ $sesi->absensis_count }} Mhs</span></td>
                                <td>
                                    <span class="badge {{ $sesi->status == 'buka' ? 'bg-success' : 'bg-secondary' }}">
                                        {{ strtoupper($sesi->status) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">Belum ada sesi absensi yang dibuat.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection