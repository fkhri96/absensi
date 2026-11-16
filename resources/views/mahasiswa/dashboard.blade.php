@extends('layouts.app')

@section('title', 'Dashboard Mahasiswa | Absensi Kampus')
@section('page_title', 'Dashboard Mahasiswa')
@section('role', 'mahasiswa')

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
        padding: 40px;
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
        transition: transform .15s ease;
    }
    .hero-avatar:hover {
        transform: scale(1.06);
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

    /* ---------- Ilustrasi pemindai QR ---------- */
    .scanner {
        position: relative;
        width: 100%;
        max-width: 260px;
        aspect-ratio: 1;
        margin-inline: auto;
        border-radius: 24px;
        background: rgba(255, 255, 255, 0.07);
        border: 1px solid rgba(255, 255, 255, 0.14);
        overflow: hidden;
    }

    .scanner .corner {
        position: absolute;
        width: 38px;
        height: 38px;
        border: 4px solid var(--mint);
    }
    .scanner .tl { top: 18px;    left: 18px;  border-right: 0; border-bottom: 0; border-top-left-radius: 12px; }
    .scanner .tr { top: 18px;    right: 18px; border-left: 0;  border-bottom: 0; border-top-right-radius: 12px; }
    .scanner .bl { bottom: 18px; left: 18px;  border-right: 0; border-top: 0;    border-bottom-left-radius: 12px; }
    .scanner .br { bottom: 18px; right: 18px; border-left: 0;  border-top: 0;    border-bottom-right-radius: 12px; }

    .scanner .scan-line {
        position: absolute;
        left: 28px;
        right: 28px;
        top: 28px;
        height: 3px;
        border-radius: 99px;
        background: linear-gradient(90deg, transparent, var(--mint), transparent);
        box-shadow: 0 0 16px 2px rgba(20, 201, 167, 0.55);
        animation: scanMove 2.6s ease-in-out infinite alternate;
    }

    @keyframes scanMove {
        from { transform: translateY(0); }
        to   { transform: translateY(calc(260px - 62px)); }
    }

    /* ---------- Cincin persentase kehadiran ---------- */
    .ring {
        --size: 112px;
        position: relative;
        width: var(--size);
        height: var(--size);
        border-radius: 50%;
        flex-shrink: 0;
        display: grid;
        place-items: center;
        background: conic-gradient(var(--mint) calc(var(--p) * 1%), var(--line) 0);
    }
    .ring::before {
        content: "";
        position: absolute;
        inset: 11px;
        border-radius: 50%;
        background: var(--surface);
    }
    .ring span {
        position: relative;
        font-family: 'Bricolage Grotesque', sans-serif;
        font-weight: 800;
        font-size: 1.55rem;
        letter-spacing: -0.02em;
        color: var(--text);
        font-variant-numeric: tabular-nums;
    }

    /* ---------- Kartu statistik ---------- */
    .stat-card {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        justify-content: center;
        gap: 14px;
        padding: 20px;
        height: 100%;
    }

    .stat-card .chip-icon {
        width: 46px;
        height: 46px;
        border-radius: 14px;
        display: grid;
        place-items: center;
        flex-shrink: 0;
    }
    .stat-card .chip-icon svg { width: 22px; height: 22px; fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }

    .stat-card.tone-mint  .chip-icon { background: rgba(20, 201, 167, 0.14); color: #0ea58a; }
    .stat-card.tone-amber .chip-icon { background: rgba(245, 158, 11, 0.15); color: #d97706; }
    .stat-card.tone-rose  .chip-icon { background: rgba(244, 63, 94, 0.12);  color: #e11d48; }

    [data-theme="dark"] .stat-card.tone-mint  .chip-icon { background: rgba(20, 201, 167, 0.20); color: #3fe0c1; }
    [data-theme="dark"] .stat-card.tone-amber .chip-icon { background: rgba(245, 158, 11, 0.20); color: #fbbf24; }
    [data-theme="dark"] .stat-card.tone-rose  .chip-icon { background: rgba(244, 63, 94, 0.20);  color: #fb7185; }

    /* Angka selalu memakai var(--text) supaya terlihat di mode gelap */
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

    /* ---------- Tabel riwayat ---------- */
    .table-clean { --bs-table-bg: transparent; --bs-table-color: var(--text); margin-bottom: 0; }
    .table-clean thead th {
        background: var(--surface-2);
        color: var(--muted);
        font-size: .78rem;
        font-weight: 700;
        letter-spacing: .03em;
        border-bottom: 1px solid var(--line);
        padding: 10px 14px;
        white-space: nowrap;
    }
    .table-clean tbody td {
        padding: 13px 14px;
        border-color: var(--line);
        color: var(--text);
        vertical-align: middle;
    }
    .table-clean tbody td.text-secondary { color: var(--muted) !important; }
    .table-clean tbody tr:hover td { background: var(--blue-soft); }

    .badge-soft {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 99px;
        font-size: .78rem;
        font-weight: 700;
        background: var(--blue-soft);
        color: var(--blue);
    }
    .badge-soft.b-hadir     { background: rgba(20, 201, 167, 0.14); color: #0b8a73; }
    .badge-soft.b-izin,
    .badge-soft.b-sakit,
    .badge-soft.b-terlambat { background: rgba(245, 158, 11, 0.16); color: #b45309; }
    .badge-soft.b-alpa      { background: rgba(244, 63, 94, 0.13);  color: #be123c; }

    [data-theme="dark"] .badge-soft.b-hadir     { background: rgba(20, 201, 167, 0.20); color: #3fe0c1; }
    [data-theme="dark"] .badge-soft.b-izin,
    [data-theme="dark"] .badge-soft.b-sakit,
    [data-theme="dark"] .badge-soft.b-terlambat { background: rgba(245, 158, 11, 0.20); color: #fbbf24; }
    [data-theme="dark"] .badge-soft.b-alpa      { background: rgba(244, 63, 94, 0.22);  color: #fb7185; }

    .panel h5 { color: var(--text); }

    @media (max-width: 575.98px) {
        .hero { padding: 28px 22px; border-radius: 20px; }
        .schedule-item { flex-wrap: wrap; }
    }
</style>
@endpush

@section('content')

@php
    $currentMhs = auth()->user();
    if (!empty($currentMhs?->foto)) {
        $rawMhsFoto = str_starts_with($currentMhs->foto, 'uploads/')
            ? asset(ltrim($currentMhs->foto, '/'))
            : asset('storage/' . ltrim($currentMhs->foto, '/'));
        // Cache-busting agar foto terbaru langsung terbaca setelah user mengganti PP
        $avatarMhsUrl = $rawMhsFoto . '?v=' . optional($currentMhs->updated_at)->timestamp;
    } else {
        $avatarMhsUrl = 'https://ui-avatars.com/api/?name=' . urlencode($currentMhs->name ?? 'Mahasiswa') . '&background=14c9a7&color=06302a&size=150&bold=true';
    }

    $persen = max(0, min(100, (int) ($persenKehadiran ?? 0)));

    $stats = [
        [
            'tone'  => 'mint',
            'icon'  => '<path d="M9 11l3 3 8-8"/><path d="M20 12v7a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h9"/>',
            'value' => $totalHadir ?? 0,
            'label' => 'Hadir',
        ],
        [
            'tone'  => 'amber',
            'icon'  => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
            'value' => $totalIzin ?? 0,
            'label' => 'Izin / sakit',
        ],
        [
            'tone'  => 'rose',
            'icon'  => '<circle cx="12" cy="12" r="9"/><path d="M15 9l-6 6M9 9l6 6"/>',
            'value' => $totalAlpa ?? 0,
            'label' => 'Alpa',
        ],
    ];
@endphp

{{-- Hero --}}
<section class="hero mb-4">
    <div class="row align-items-center g-5">
        <div class="col-lg-7">
            <div class="d-flex align-items-center gap-3 mb-3">
                <a href="{{ Route::has('profile.index') ? route('profile.index') : url('/profil') }}" title="Ubah Foto Profil">
                    <img src="{{ $avatarMhsUrl }}" alt="Foto Profil {{ $currentMhs->name ?? 'Mahasiswa' }}" class="hero-avatar">
                </a>
                <div>
                    <p class="greeting mb-0">Halo, {{ $currentMhs->name ?? 'Mahasiswa' }}</p>
                    <small style="color: rgba(255,255,255,0.68);">Klik foto untuk mengganti profil</small>
                </div>
            </div>

            <h1 class="mb-3">Pindai QR di kelas untuk mencatat kehadiran.</h1>
            <p class="mb-4">
                Arahkan kamera ke QR Code yang ditampilkan dosen.
                Kehadiran Anda langsung tercatat.
            </p>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('mahasiswa.absensi.index') }}" class="btn btn-mint">Pindai QR sekarang</a>
                <a href="{{ route('mahasiswa.riwayat.index') }}" class="btn btn-outline-light-soft">Riwayat kehadiran</a>
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
            @foreach ($stats as $s)
                <div class="col-sm-4">
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
                    <a href="{{ route('mahasiswa.absensi.index') }}" class="btn btn-brand">Pindai QR sekarang</a>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection