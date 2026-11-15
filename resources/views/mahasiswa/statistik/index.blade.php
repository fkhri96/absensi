@extends('layouts.app')

@section('title', 'Statistik Kehadiran | Absensi Kampus')
@section('page_title', 'Statistik Kehadiran Mahasiswa')
@section('role', 'mahasiswa')

@section('content')
<div class="container-fluid px-0">

    <div class="row g-4 mb-4">
        <!-- Hadir -->
        <div class="col-md-3">
            <div class="panel p-4 shadow-sm d-flex align-items-center gap-3">
                <div class="bg-success bg-opacity-10 text-success p-3 rounded-4 fs-4">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <div>
                    <small class="text-muted d-block">Hadir</small>
                    <h4 class="fw-bold mb-0 text-dark">{{ $hadir ?? 0 }} Sesi</h4>
                </div>
            </div>
        </div>

        <!-- Izin -->
        <div class="col-md-3">
            <div class="panel p-4 shadow-sm d-flex align-items-center gap-3">
                <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-4 fs-4">
                    <i class="bi bi-info-circle-fill"></i>
                </div>
                <div>
                    <small class="text-muted d-block">Izin</small>
                    <h4 class="fw-bold mb-0 text-dark">{{ $izin ?? 0 }} Sesi</h4>
                </div>
            </div>
        </div>

        <!-- Sakit -->
        <div class="col-md-3">
            <div class="panel p-4 shadow-sm d-flex align-items-center gap-3">
                <div class="bg-warning bg-opacity-10 text-warning p-3 rounded-4 fs-4">
                    <i class="bi bi-bandaid-fill"></i>
                </div>
                <div>
                    <small class="text-muted d-block">Sakit</small>
                    <h4 class="fw-bold mb-0 text-dark">{{ $sakit ?? 0 }} Sesi</h4>
                </div>
            </div>
        </div>

        <!-- Alpa -->
        <div class="col-md-3">
            <div class="panel p-4 shadow-sm d-flex align-items-center gap-3">
                <div class="bg-danger bg-opacity-10 text-danger p-3 rounded-4 fs-4">
                    <i class="bi bi-x-circle-fill"></i>
                </div>
                <div>
                    <small class="text-muted d-block">Alpa / Tanpa Keterangan</small>
                    <h4 class="fw-bold mb-0 text-dark">{{ $alpa ?? 0 }} Sesi</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="panel p-4 shadow-sm">
        <h5 class="fw-bold mb-2">Ringkasan Grafik Kehadiran</h5>
        <p class="text-muted small mb-4">Akumulasi rekam jejak kehadiran Anda selama mengikuti kegiatan perkuliahan.</p>

        @php
            $total = ($hadir ?? 0) + ($izin ?? 0) + ($sakit ?? 0) + ($alpa ?? 0);
            $persenHadir = $total > 0 ? round((($hadir ?? 0) / $total) * 100, 1) : 0;
        @endphp

        <div class="mb-3">
            <div class="d-flex justify-content-between mb-1 small fw-semibold">
                <span>Persentase Kehadiran Efektif (Hadir)</span>
                <span>{{ $persenHadir }}%</span>
            </div>
            <div class="progress" style="height: 12px; border-radius: 99px;">
                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $persenHadir }}%;" aria-valuenow="{{ $persenHadir }}" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
    </div>

</div>
@endsection