@extends('layouts.app')

@section('title', 'Dashboard Dosen | Absensi Kampus')
@section('page_title', 'Dashboard Dosen')
@section('role', 'dosen')

@push('styles')
<style>
    /* Semua warna memakai variabel dari layouts/app.blade.php,
       jadi otomatis mengikuti mode terang / gelap. */

    /* ---------- Hero ---------- */
    .hero {
        position: relative;
        overflow: hidden;
        color: #fff;
        border-radius: 24px;
        padding: 40px 40px;
        background:
            radial-gradient(70% 90% at 100% 0%, rgba(20, 201, 167, 0.28) 0%, transparent 60%),
            linear-gradient(135deg, #0b2545 0%, #13315c 100%);
        box-shadow: var(--shadow-md);
    }

    [data-theme="dark"] .hero {
        background:
            radial-gradient(70% 90% at 100% 0%, rgba(20, 201, 167, 0.20) 0%, transparent 60%),
            linear-gradient(135deg, #14264a 0%, #0f1c38 100%);
        border: 1px solid var(--line);
    }

    .hero-avatar {
        width: 58px;
        height: 58px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid rgba(255, 255, 255, 0.28);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.24);
        background: #fff;
        flex-shrink: 0;
    }

    .hero .greeting { color: #8fe9d7; font-weight: 600; font-size: .95rem; }
    .hero h1 { color: #fff; font-size: clamp(1.6rem, 3.2vw, 2.4rem); font-weight: 800; line-height: 1.15; max-width: 22ch; }
    .hero p:not(.greeting) { color: rgba(255, 255, 255, 0.78); max-width: 46ch; }

    .btn-mint {
        background: var(--mint);
        color: #06302a;
        border: 0;
        border-radius: 12px;
        padding: 10px 20px;
        font-weight: 700;
        box-shadow: 0 8px 20px rgba(20, 201, 167, 0.30);
        transition: transform .18s ease, box-shadow .18s ease;
    }
    .btn-mint:hover { color: #06302a; transform: translateY(-1px); box-shadow: 0 12px 26px rgba(20, 201, 167, 0.38); }

    .btn-outline-light-soft {
        background: rgba(255, 255, 255, 0.08);
        color: #fff;
        border: 1px solid rgba(255, 255, 255, 0.25);
        border-radius: 12px;
        padding: 10px 20px;
        font-weight: 600;
        transition: background .18s ease;
    }
    .btn-outline-light-soft:hover { background: rgba(255, 255, 255, 0.16); color: #fff; }

    /* Kartu QR: latar putih dipertahankan agar QR tetap bisa dipindai di mode gelap */
    .qr-card {
        background: #fff;
        color: #14213d;
        border-radius: 20px;
        padding: 18px;
        max-width: 280px;
        margin-inline: auto;
        box-shadow: 0 16px 40px rgba(0, 0, 0, 0.28);
    }
    .qr-card img, .qr-card svg { max-width: 100%; height: auto; }

    /* ---------- Kartu statistik ---------- */
    .stat-card {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 20px;
        height: 100%;
    }

    .stat-card .chip-icon {
        width: 52px;
        height: 52px;
        border-radius: 15px;
        display: grid;
        place-items: center;
        flex-shrink: 0;
    }
    .stat-card .chip-icon svg { width: 24px; height: 24px; fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }

    .stat-card.tone-blue  .chip-icon { background: rgba(59, 99, 240, 0.12);  color: #3b63f0; }
    .stat-card.tone-mint  .chip-icon { background: rgba(20, 201, 167, 0.14); color: #0ea58a; }
    .stat-card.tone-amber .chip-icon { background: rgba(245, 158, 11, 0.15); color: #d97706; }

    [data-theme="dark"] .stat-card.tone-blue  .chip-icon { background: rgba(91, 130, 255, 0.20); color: #8fa8ff; }
    [data-theme="dark"] .stat-card.tone-mint  .chip-icon { background: rgba(20, 201, 167, 0.20); color: #3fe0c1; }
    [data-theme="dark"] .stat-card.tone-amber .chip-icon { background: rgba(245, 158, 11, 0.20); color: #fbbf24; }

    .stat-card .stat-value {
        font-family: 'Bricolage Grotesque', sans-serif;
        font-weight: 800;
        font-size: 2rem;
        line-height: 1;
        letter-spacing: -0.02em;
        color: var(--text);
        font-variant-numeric: tabular-nums;
    }
    .stat-card .stat-label { color: var(--muted); font-size: .88rem; font-weight: 500; margin-top: 6px; }

    /* ---------- Jadwal ---------- */
    .schedule-item {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 14px 4px;
        border-bottom: 1px solid var(--line);
    }
    .schedule-item:last-child { border-bottom: 0; }

    .schedule-time {
        min-width: 92px;
        padding: 6px 10px;
        border-radius: 10px;
        background: var(--blue-soft);
        color: var(--blue);
        font-weight: 700;
        font-size: .85rem;
        text-align: center;
        font-variant-numeric: tabular-nums;
    }

    .schedule-item .fw-semibold { color: var(--text); }

    .empty-state {
        text-align: center;
        padding: 40px 16px;
        border: 1px dashed var(--line);
        border-radius: 16px;
        background: var(--surface-2);
        color: var(--muted);
        margin-top: 8px;
    }

    /* ---------- Aksi cepat ---------- */
    .action-row {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px;
        border-radius: 14px;
        color: var(--text);
        font-weight: 600;
        text-decoration: none;
        transition: background .15s ease, transform .15s ease;
    }
    .action-row:hover { background: var(--blue-soft); color: var(--text); transform: translateX(2px); }

    .action-row .chip {
        width: 38px;
        height: 38px;
        border-radius: 11px;
        display: grid;
        place-items: center;
        background: var(--blue-soft);
        color: var(--blue);
        flex-shrink: 0;
    }

    .panel h5 { color: var(--text); }

    @media (max-width: 575.98px) {
        .hero { padding: 28px 22px; border-radius: 20px; }
        .schedule-item { flex-wrap: wrap; }
    }
</style>
@endpush

@section('content')

@php
    $currentUser = auth()->user();
    if (!empty($currentUser?->foto)) {
        $rawPath = str_starts_with($currentUser->foto, 'uploads/')
            ? asset(ltrim($currentUser->foto, '/'))
            : asset('storage/' . ltrim($currentUser->foto, '/'));
        // Tambahkan timestamp updated_at agar browser langsung memuat foto terbaru saat diganti
        $avatarDosenUrl = $rawPath . '?v=' . optional($currentUser->updated_at)->timestamp;
    } else {
        $avatarDosenUrl = 'https://ui-avatars.com/api/?name=' . urlencode($currentUser->name ?? 'Dosen') . '&background=14c9a7&color=06302a&size=150&bold=true';
    }
@endphp

{{-- Hero --}}
<section class="hero mb-4">
    <div class="row align-items-center g-5">
        <div class="col-lg-7">
            <div class="d-flex align-items-center gap-3 mb-3">
                <a href="{{ Route::has('profile.index') ? route('profile.index') : url('/profil') }}" title="Ubah Foto Profil">
                    <img src="{{ $avatarDosenUrl }}" alt="Foto Profil {{ $currentUser->name ?? 'Dosen' }}" class="hero-avatar">
                </a>
                <div>
                    <p class="greeting mb-0">Halo, {{ $currentUser->name ?? 'Dosen' }}</p>
                    <small style="color: rgba(255,255,255,0.68);">Klik foto untuk mengganti profil</small>
                </div>
            </div>

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
            <div class="qr-card">
                @include('partials.qr')
            </div>
        </div>
    </div>
</section>

{{-- Statistik --}}
@php
    $stats = [
        [
            'tone'  => 'blue',
            'icon'  => '<rect x="3" y="5" width="18" height="16" rx="2"/><path d="M3 10h18M8 3v4M16 3v4"/>',
            'value' => $kelasHariIni ?? 0,
            'label' => 'Kelas hari ini',
        ],
        [
            'tone'  => 'mint',
            'icon'  => '<path d="M9 11l3 3 8-8"/><path d="M20 12v7a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h9"/>',
            'value' => $mahasiswaHadir ?? 0,
            'label' => 'Mahasiswa hadir hari ini',
        ],
        [
            'tone'  => 'amber',
            'icon'  => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
            'value' => $pertemuanBulanIni ?? 0,
            'label' => 'Pertemuan bulan ini',
        ],
    ];
@endphp

<div class="row g-4 mb-4">
    @foreach ($stats as $s)
        <div class="col-md-4">
            <div class="panel stat-card tone-{{ $s['tone'] }}">
                <span class="chip-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24">{!! $s['icon'] !!}</svg>
                </span>
                <div>
                    <div class="stat-value">{{ $s['value'] }}</div>
                    <div class="stat-label">{{ $s['label'] }}</div>
                </div>
            </div>
        </div>
    @endforeach
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
            <a href="{{ Route::has('profile.index') ? route('profile.index') : url('/profil') }}" class="action-row">
                <span class="chip" aria-hidden="true"><svg class="ico" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/></svg></span>
                Edit profil
            </a>
        </div>
    </div>
</div>

@endsection