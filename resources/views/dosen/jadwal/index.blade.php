@extends('layouts.app')

@section('title', 'Jadwal Mengajar | Absensi Kampus')
@section('page_title', 'Jadwal Mengajar')
@section('role', 'dosen')

@push('styles')
<style>
    .jadwal-card {
        background: #ffffff;
        border: 1px solid var(--line);
        border-radius: 16px;
        transition: all 0.2s ease;
    }
    .jadwal-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 24px rgba(11, 37, 69, 0.06);
        border-color: #cbd5e1;
    }
    .hari-badge {
        width: 100px;
        font-weight: 700;
        text-align: center;
        border-radius: 10px;
        padding: 8px 12px;
        font-size: 0.85rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-0">

    <!-- Header & Info Hari Ini -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1">Jadwal Mengajar Semester Ini</h4>
            <p class="text-muted small mb-0">
                Daftar jadwal perkuliahan, jam mulai dan selesai, serta ruang kelas pengampu Anda.
            </p>
        </div>

        <a href="{{ route('dosen.absensi.create') }}" class="btn btn-brand d-inline-flex align-items-center gap-2">
            <i class="bi bi-qr-code-scan"></i> Buka Sesi QR Absensi
        </a>
    </div>

    <!-- Ringkasan Cepat -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-4">
            <div class="panel p-3 p-md-4">
                <div class="text-muted small fw-semibold mb-1">Total Jadwal Mengajar</div>
                <div class="d-flex align-items-center justify-content-between">
                    <h3 class="fw-bold mb-0">{{ $jadwals->count() }} <span class="fs-6 fw-normal text-muted">Kelas</span></h3>
                    <span class="badge bg-primary bg-opacity-10 text-primary p-2 rounded-3">
                        <i class="bi bi-calendar-range fs-5"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div class="panel p-3 p-md-4">
                <div class="text-muted small fw-semibold mb-1">Hari Ini</div>
                <div class="d-flex align-items-center justify-content-between">
                    <h3 class="fw-bold mb-0 text-primary">{{ now()->translatedFormat('l') }}</h3>
                    <span class="badge bg-success bg-opacity-10 text-success p-2 rounded-3">
                        <i class="bi bi-clock-history fs-5"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="panel p-3 p-md-4">
                <div class="text-muted small fw-semibold mb-1">Aksi Cepat</div>
                <div class="d-flex align-items-center gap-2 pt-1">
                    <a href="{{ route('dosen.matakuliah.index') }}" class="btn btn-sm btn-ghost w-100">
                        <i class="bi bi-journal-bookmark me-1"></i> Lihat Mata Kuliah
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Jadwal -->
    <div class="d-flex flex-column gap-3">
        @forelse($jadwals as $jadwal)
            @php
                $hariIniMap = [
                    'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
                    'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
                ];
                $isHariIni = ($jadwal->hari === ($hariIniMap[now()->format('l')] ?? ''));
            @endphp

            <div class="jadwal-card p-4 {{ $isHariIni ? 'border-primary border-2 shadow-sm' : '' }}">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="hari-badge {{ $isHariIni ? 'bg-primary text-white shadow-sm' : 'bg-light text-dark border' }}">
                            {{ $jadwal->hari }}
                        </div>
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <h5 class="fw-bold mb-0">{{ $jadwal->mataKuliah->nama_mk ?? 'Mata Kuliah' }}</h5>
                                @if($isHariIni)
                                    <span class="badge rounded-pill bg-success bg-opacity-10 text-success">Jadwal Hari Ini</span>
                                @endif
                            </div>
                            <p class="text-muted small mb-0">
                                <i class="bi bi-mortarboard me-1"></i> {{ $jadwal->mataKuliah->prodi->nama_prodi ?? 'Program Studi Umum' }}
                                <span class="mx-1">•</span>
                                <span class="badge bg-light text-dark border">{{ $jadwal->mataKuliah->sks ?? 2 }} SKS</span>
                            </p>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap align-items-center gap-3 ms-md-auto">
                        <div class="text-md-end bg-light px-3 py-2 rounded-3">
                            <div class="fw-bold text-dark d-flex align-items-center gap-1">
                                <i class="bi bi-clock text-primary"></i>
                                {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
                            </div>
                            <small class="text-muted">Ruang: <strong class="text-dark">{{ $jadwal->ruang }}</strong></small>
                        </div>

                        <div class="d-flex gap-2">
                            <a href="{{ route('dosen.absensi.create') }}" class="btn btn-sm btn-brand d-inline-flex align-items-center gap-1">
                                <i class="bi bi-qr-code-scan"></i> Buka Sesi QR
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="panel p-5 text-center">
                <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-light text-muted mb-3"
                     style="width: 64px; height: 64px;">
                    <i class="bi bi-calendar-x fs-3"></i>
                </div>
                <h6 class="fw-bold mb-1">Belum Ada Jadwal Mengajar</h6>
                <p class="text-muted small mb-0">
                    Belum ada jadwal perkuliahan yang diatur untuk akun dosen Anda. Hubungi Administrator kampus jika terdapat kesalahan.
                </p>
            </div>
        @endforelse
    </div>

</div>
@endsection