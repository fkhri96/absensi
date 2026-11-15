@extends('layouts.app')

@section('title', 'Dashboard Mahasiswa | Absensi Kampus')
@section('page_title', 'Dashboard Mahasiswa')
@section('role', 'mahasiswa')

@push('styles')
    @include('partials.dashboard-styles')
@endpush

@section('content')

@php
    $persen = (int) ($persenKehadiran ?? 0);
@endphp

{{-- Hero --}}
<section class="hero mb-4">
    <div class="row align-items-center g-5">
        <div class="col-lg-7">
            <p class="greeting mb-0">Halo, {{ auth()->user()->name ?? 'Mahasiswa' }}</p>
            <h1 class="mb-3">Pindai QR di kelas untuk mencatat kehadiran.</h1>
            <p class="mb-4">
                Arahkan kamera ke QR Code yang ditampilkan dosen.
                Kehadiran Anda langsung tercatat.
            </p>
            <div class="d-flex flex-wrap gap-2">
                <a href="#" class="btn btn-mint">Pindai QR sekarang</a>
                <a href="#" class="btn btn-outline-light-soft">Riwayat kehadiran</a>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="scanner" role="img" aria-label="Ilustrasi pemindai QR Code">
                <span class="corner tl"></span>
                <span class="corner tr"></span>
                <span class="corner bl"></span>
                <span class="corner br"></span>
                <span class="scan-line" aria-hidden="true"></span>
            </div>
        </div>
    </div>
</section>

{{-- Ringkasan kehadiran --}}
<div class="row g-4 mb-4">
    <div class="col-lg-5">
        <div class="panel p-4 h-100 d-flex align-items-center gap-4">
            <div class="ring" style="--p: {{ $persen }}" role="img" aria-label="Kehadiran {{ $persen }} persen">
                <span>{{ $persen }}%</span>
            </div>
            <div>
                <h5 class="fw-bold mb-1">Kehadiran semester ini</h5>
                <p class="text-secondary mb-0">Dari seluruh pertemuan yang sudah berjalan.</p>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="row g-4 h-100">
            <div class="col-sm-4">
                @include('partials.stat', [
                    'tone'  => 'mint',
                    'icon'  => '<path d="M9 11l3 3 8-8"/><path d="M20 12v7a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h9"/>',
                    'value' => $totalHadir ?? 0,
                    'label' => 'Hadir',
                ])
            </div>
            <div class="col-sm-4">
                @include('partials.stat', [
                    'tone'  => 'amber',
                    'icon'  => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
                    'value' => $totalIzin ?? 0,
                    'label' => 'Izin / sakit',
                ])
            </div>
            <div class="col-sm-4">
                @include('partials.stat', [
                    'tone'  => 'rose',
                    'icon'  => '<circle cx="12" cy="12" r="9"/><path d="M15 9l-6 6M9 9l6 6"/>',
                    'value' => $totalAlpa ?? 0,
                    'label' => 'Alpa',
                ])
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- Jadwal hari ini --}}
    <div class="col-lg-5">
        <div class="panel p-4 h-100">
            <h5 class="fw-bold mb-2">Jadwal kuliah hari ini</h5>

            @if (! empty($jadwalHariIni) && count($jadwalHariIni))
                @foreach ($jadwalHariIni as $j)
                    <div class="schedule-item">
                        <div class="schedule-time">{{ $j['jam'] }}</div>
                        <div>
                            <div class="fw-semibold">{{ $j['mata_kuliah'] }}</div>
                            <div class="text-secondary small">{{ $j['ruang'] }} &bull; {{ $j['dosen'] }}</div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="empty-state">
                    <p class="mb-1 fw-semibold" style="color: var(--text);">Tidak ada kuliah hari ini</p>
                    <p class="mb-0">Jadwal kuliah Anda akan tampil di sini.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Riwayat --}}
    <div class="col-lg-7">
        <div class="panel p-4 h-100">
            <h5 class="fw-bold mb-3">Riwayat kehadiran</h5>

            @if (! empty($riwayat) && count($riwayat))
                <div class="table-responsive">
                    <table class="table table-clean">
                        <thead>
                            <tr>
                                <th>Mata kuliah</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($riwayat as $r)
                                <tr>
                                    <td class="fw-semibold">{{ $r['mata_kuliah'] }}</td>
                                    <td class="text-secondary">{{ $r['tanggal'] }}</td>
                                    <td><span class="badge-soft b-{{ strtolower($r['status']) }}">{{ ucfirst($r['status']) }}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <p class="mb-1 fw-semibold" style="color: var(--text);">Belum ada riwayat</p>
                    <p class="mb-3">Pindai QR di kelas pertama Anda untuk mulai mencatat kehadiran.</p>
                    <a href="#" class="btn btn-brand">Pindai QR sekarang</a>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection