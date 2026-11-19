@extends('layouts.app')

@section('title', 'Notifikasi Perkuliahan | Absensi Kampus')
@section('page_title', 'Notifikasi Perkuliahan')
@section('role', 'dosen')

@push('styles')
<style>
    .notif-item {
        border: 1px solid var(--line);
        border-radius: 16px;
        background: #ffffff;
        transition: all 0.2s ease;
    }
    .notif-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 24px rgba(11, 37, 69, 0.06);
        border-color: #cbd5e1;
    }
    .notif-icon {
        width: 46px;
        height: 46px;
        border-radius: 14px;
        display: grid;
        place-items: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }
    .filter-pill {
        border-radius: 99px;
        padding: 7px 16px;
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        border: 1px solid var(--line);
        background: #ffffff;
        color: var(--muted);
        transition: all 0.15s ease;
    }
    .filter-pill:hover {
        background: var(--bg);
        color: var(--text);
    }
    .filter-pill.active {
        background: var(--blue);
        border-color: var(--blue);
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(47, 91, 234, 0.25);
    }
</style>
@include('partials.dark-fix')
@endpush

@section('content')
<div class="container-fluid px-0">

    <!-- Header & Tombol Cepat -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1">Pusat Notifikasi & Aktivitas Kelas</h4>
            <p class="text-muted small mb-0">
                Pantau pengingat jadwal hari <strong>{{ $hariIni }}</strong>, sesi QR yang masih aktif, dan presensi terbaru mahasiswa.
            </p>
        </div>

        <a href="{{ route('dosen.absensi.create') }}" class="btn btn-brand d-inline-flex align-items-center gap-2">
            <i class="bi bi-qr-code-scan"></i> Buka Sesi Absensi Baru
        </a>
    </div>

    <!-- Filter Kategori -->
    <div class="d-flex flex-wrap align-items-center gap-2 mb-4">
        <a href="{{ route('dosen.notifikasi.index') }}"
           class="filter-pill {{ $filter === 'semua' ? 'active' : '' }}">
            Semua <span class="badge rounded-pill {{ $filter === 'semua' ? 'bg-white text-primary' : 'bg-light text-dark' }} ms-1">{{ $ringkasan['semua'] }}</span>
        </a>

        <a href="{{ route('dosen.notifikasi.index', ['kategori' => 'sesi']) }}"
           class="filter-pill {{ $filter === 'sesi' ? 'active' : '' }}">
            <i class="bi bi-broadcast me-1"></i> Sesi Aktif
            <span class="badge rounded-pill {{ $filter === 'sesi' ? 'bg-white text-primary' : 'bg-warning bg-opacity-25 text-dark' }} ms-1">{{ $ringkasan['sesi'] }}</span>
        </a>

        <a href="{{ route('dosen.notifikasi.index', ['kategori' => 'jadwal']) }}"
           class="filter-pill {{ $filter === 'jadwal' ? 'active' : '' }}">
            <i class="bi bi-calendar-event me-1"></i> Jadwal Hari Ini
            <span class="badge rounded-pill {{ $filter === 'jadwal' ? 'bg-white text-primary' : 'bg-light text-dark' }} ms-1">{{ $ringkasan['jadwal'] }}</span>
        </a>

        <a href="{{ route('dosen.notifikasi.index', ['kategori' => 'mahasiswa']) }}"
           class="filter-pill {{ $filter === 'mahasiswa' ? 'active' : '' }}">
            <i class="bi bi-people me-1"></i> Presensi Mahasiswa
            <span class="badge rounded-pill {{ $filter === 'mahasiswa' ? 'bg-white text-primary' : 'bg-light text-dark' }} ms-1">{{ $ringkasan['mahasiswa'] }}</span>
        </a>
    </div>

    <!-- Daftar Notifikasi -->
    <div class="d-flex flex-column gap-3">
        @forelse($notifikasis as $item)
            @php
                $badgeStyle = match($item->prioritas) {
                    'warning' => 'bg-warning bg-opacity-25 text-warning',
                    'success' => 'bg-success bg-opacity-25 text-success',
                    'primary' => 'bg-primary bg-opacity-25 text-primary',
                    'info'    => 'bg-info bg-opacity-25 text-info',
                    default   => 'bg-secondary bg-opacity-25 text-secondary',
                };
            @endphp

            <div class="notif-item p-3 p-md-4">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                    <div class="d-flex align-items-start gap-3">
                        <div class="notif-icon {{ $badgeStyle }}">
                            <i class="bi {{ $item->ikon }}"></i>
                        </div>
                        <div>
                            <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                                <h6 class="fw-bold mb-0">{{ $item->judul }}</h6>
                                @if($item->kategori === 'sesi')
                                    <span class="badge rounded-pill bg-warning text-dark">Perlu Perhatian</span>
                                @elseif($item->prioritas === 'primary')
                                    <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary">Hari Ini</span>
                                @endif
                            </div>
                            <p class="text-muted small mb-1">{{ $item->pesan }}</p>
                            <small class="text-secondary d-inline-flex align-items-center gap-1" style="font-size: 0.78rem;">
                                <i class="bi bi-clock"></i> {{ $item->waktu }}
                            </small>
                        </div>
                    </div>

                    <div class="ms-md-3 flex-shrink-0">
                        <a href="{{ $item->aksi_url }}" class="btn btn-sm btn-ghost d-inline-flex align-items-center gap-1">
                            <span>{{ $item->aksi_label }}</span>
                            <i class="bi bi-arrow-right-short fs-5"></i>
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="panel p-5 text-center">
                <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-light text-muted mb-3"
                     style="width: 64px; height: 64px;">
                    <i class="bi bi-bell-slash fs-3"></i>
                </div>
                <h6 class="fw-bold mb-1">Belum Ada Notifikasi</h6>
                <p class="text-muted small mb-0">
                    Tidak ada pengingat jadwal, sesi terbuka, maupun aktivitas presensi baru pada kategori ini.
                </p>
            </div>
        @endforelse
    </div>

</div>
@endsection