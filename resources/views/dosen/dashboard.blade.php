@extends('layouts.app')

@section('title', 'Dashboard Dosen | Absensi Kampus')
@section('page_title', 'Dashboard Dosen')
@section('role', 'dosen')

@push('styles')
    @include('partials.dashboard-styles')
@endpush

@section('content')

{{-- Hero --}}
<section class="hero mb-4">
    <div class="row align-items-center g-5">
        <div class="col-lg-7">
            <p class="greeting mb-0">Halo, {{ auth()->user()->name ?? 'Dosen' }}</p>
            <h1 class="mb-3">Buka sesi, mahasiswa tinggal memindai.</h1>
            <p class="mb-4">
                Buat QR Code untuk kelas Anda, lalu lihat siapa yang sudah hadir
                secara langsung.
            </p>
            <div class="d-flex flex-wrap gap-2">
                <a href="#" class="btn btn-mint">Buat QR sesi</a>
                <a href="#" class="btn btn-outline-light-soft">Rekap kehadiran</a>
            </div>
        </div>
        <div class="col-lg-5">
            @include('partials.qr')
        </div>
    </div>
</section>

{{-- Statistik --}}
<div class="row g-4 mb-4">
    <div class="col-md-4">
        @include('partials.stat', [
            'tone'  => 'blue',
            'icon'  => '<rect x="3" y="5" width="18" height="16" rx="2"/><path d="M3 10h18M8 3v4M16 3v4"/>',
            'value' => $kelasHariIni ?? 0,
            'label' => 'Kelas hari ini',
        ])
    </div>
    <div class="col-md-4">
        @include('partials.stat', [
            'tone'  => 'mint',
            'icon'  => '<path d="M9 11l3 3 8-8"/><path d="M20 12v7a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h9"/>',
            'value' => $mahasiswaHadir ?? 0,
            'label' => 'Mahasiswa hadir hari ini',
        ])
    </div>
    <div class="col-md-4">
        @include('partials.stat', [
            'tone'  => 'amber',
            'icon'  => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
            'value' => $pertemuanBulanIni ?? 0,
            'label' => 'Pertemuan bulan ini',
        ])
    </div>
</div>

<div class="row g-4">
    {{-- Jadwal hari ini --}}
    <div class="col-lg-8">
        <div class="panel p-4 h-100">
            <h5 class="fw-bold mb-2">Jadwal mengajar hari ini</h5>

            @if (! empty($jadwalHariIni) && count($jadwalHariIni))
                @foreach ($jadwalHariIni as $j)
                    <div class="schedule-item">
                        <div class="schedule-time">{{ $j['jam'] }}</div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold">{{ $j['mata_kuliah'] }}</div>
                            <div class="text-secondary small">{{ $j['ruang'] }} &bull; {{ $j['hadir'] ?? 0 }}/{{ $j['total'] ?? 0 }} hadir</div>
                        </div>
                        <a href="#" class="btn btn-brand btn-sm">Buka sesi</a>
                    </div>
                @endforeach
            @else
                <div class="empty-state">
                    <p class="mb-1 fw-semibold" style="color: var(--text);">Tidak ada kelas hari ini</p>
                    <p class="mb-0">Jadwal mengajar Anda akan tampil di sini.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Aksi cepat --}}
    <div class="col-lg-4">
        <div class="panel p-3 h-100">
            <h5 class="fw-bold px-2 pt-2 mb-2">Aksi cepat</h5>

            <a href="#" class="action-row">
                <span class="chip" aria-hidden="true"><svg class="ico" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg></span>
                Buat QR sesi
            </a>
            <a href="#" class="action-row">
                <span class="chip" aria-hidden="true"><svg class="ico" viewBox="0 0 24 24"><path d="M9 11l3 3 8-8"/><path d="M20 12v7a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h9"/></svg></span>
                Rekap per kelas
            </a>
            <a href="#" class="action-row">
                <span class="chip" aria-hidden="true"><svg class="ico" viewBox="0 0 24 24"><path d="M12 3v12m0 0l-4-4m4 4l4-4M5 21h14"/></svg></span>
                Unduh rekap
            </a>
        </div>
    </div>
</div>

@endsection