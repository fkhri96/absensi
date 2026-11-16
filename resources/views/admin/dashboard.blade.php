@extends('layouts.app')

@section('title', 'Dashboard Admin | Absensi Kampus')
@section('page_title', 'Dashboard Admin')
@section('role', 'admin')

@push('styles')
<style>
    /* Semua warna memakai variabel dari layouts/app.blade.php,
       jadi otomatis mengikuti mode terang / gelap. */

    /* ---------- Sapaan ---------- */
    .welcome-line { color: var(--muted); }
    .welcome-line strong { color: var(--text); font-weight: 700; }

    .admin-avatar {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid var(--blue);
        background: var(--surface);
        box-shadow: 0 4px 12px rgba(11, 37, 69, 0.12);
        transition: transform .15s ease;
    }
    .admin-avatar:hover {
        transform: scale(1.06);
    }

    .role-badge {
        display: inline-block;
        padding: 3px 11px;
        border-radius: 99px;
        font-size: .74rem;
        font-weight: 700;
        background: var(--blue-soft);
        color: var(--blue);
    }

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

    .hero h2 { color: #fff; font-size: clamp(1.5rem, 3vw, 2.2rem); font-weight: 800; line-height: 1.15; max-width: 24ch; }
    .hero p { color: rgba(255, 255, 255, 0.78); max-width: 52ch; }

    .btn-mint {
        background: var(--mint);
        color: #06302a;
        border: 0;
        border-radius: 12px;
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
        height: 100%;
        padding: 20px;
    }

    .stat-card .stat-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 14px;
    }

    .stat-card .stat-title { color: var(--muted); font-size: .86rem; font-weight: 600; }

    .stat-card .stat-icon {
        width: 42px;
        height: 42px;
        border-radius: 13px;
        display: grid;
        place-items: center;
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .stat-card.tone-blue  .stat-icon { background: rgba(59, 99, 240, 0.12);  color: #3b63f0; }
    .stat-card.tone-mint  .stat-icon { background: rgba(20, 201, 167, 0.14); color: #0ea58a; }
    .stat-card.tone-sky   .stat-icon { background: rgba(14, 165, 233, 0.13); color: #0284c7; }
    .stat-card.tone-amber .stat-icon { background: rgba(245, 158, 11, 0.15); color: #d97706; }

    [data-theme="dark"] .stat-card.tone-blue  .stat-icon { background: rgba(91, 130, 255, 0.20); color: #8fa8ff; }
    [data-theme="dark"] .stat-card.tone-mint  .stat-icon { background: rgba(20, 201, 167, 0.20); color: #3fe0c1; }
    [data-theme="dark"] .stat-card.tone-sky   .stat-icon { background: rgba(56, 189, 248, 0.20); color: #7dd3fc; }
    [data-theme="dark"] .stat-card.tone-amber .stat-icon { background: rgba(245, 158, 11, 0.20); color: #fbbf24; }

    /* Angka selalu memakai var(--text) supaya terlihat di mode gelap */
    .stat-card .stat-value {
        font-family: 'Bricolage Grotesque', sans-serif;
        font-weight: 800;
        font-size: 2rem;
        line-height: 1;
        letter-spacing: -0.02em;
        color: var(--text);
        margin: 0 0 8px;
        font-variant-numeric: tabular-nums;
    }
    .stat-card .stat-value small { font-family: 'Figtree', sans-serif; font-size: .9rem; font-weight: 500; color: var(--muted); letter-spacing: 0; }
    .stat-card .stat-note { color: var(--muted); font-size: .8rem; }

    /* ---------- Panel dengan header ---------- */
    .panel-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        padding: 18px 22px;
        border-bottom: 1px solid var(--line);
    }
    .panel-head h5 { margin: 0; font-size: 1.05rem; font-weight: 700; color: var(--text); display: flex; align-items: center; gap: 10px; }
    .panel-head h5 i { color: var(--blue); }
    .panel-head h5 i.is-mint { color: #0ea58a; }
    [data-theme="dark"] .panel-head h5 i.is-mint { color: #3fe0c1; }
    .panel-body { padding: 18px 22px 22px; }

    /* ---------- Modul admin ---------- */
    .module-link {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 11px 12px;
        border-radius: 14px;
        border: 1px solid var(--line);
        background: var(--surface);
        color: var(--text);
        font-weight: 600;
        font-size: .9rem;
        text-decoration: none;
        height: 100%;
        transition: border-color .15s ease, background .15s ease, transform .15s ease;
    }
    .module-link:hover { transform: translateY(-1px); color: var(--text); background: var(--surface-2); border-color: var(--blue); }

    .module-link .m-ico {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: grid;
        place-items: center;
        flex-shrink: 0;
        font-size: 1rem;
    }

    .module-link.m-blue  .m-ico { background: rgba(59, 99, 240, 0.12);  color: #3b63f0; }
    .module-link.m-mint  .m-ico { background: rgba(20, 201, 167, 0.14); color: #0ea58a; }
    .module-link.m-slate .m-ico { background: rgba(100, 116, 139, 0.16); color: #475569; }

    [data-theme="dark"] .module-link.m-blue  .m-ico { background: rgba(91, 130, 255, 0.20); color: #8fa8ff; }
    [data-theme="dark"] .module-link.m-mint  .m-ico { background: rgba(20, 201, 167, 0.20); color: #3fe0c1; }
    [data-theme="dark"] .module-link.m-slate .m-ico { background: rgba(148, 163, 184, 0.20); color: #cbd5e1; }

    /* ---------- Tabel aktivitas ---------- */
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
    .table-clean tbody td strong { color: var(--text); }
    .table-clean tbody td .text-muted { color: var(--muted) !important; }
    .table-clean tbody tr:hover td { background: var(--blue-soft); }

    .badge-soft {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 99px;
        font-size: .74rem;
        font-weight: 700;
        letter-spacing: .02em;
        background: var(--blue-soft);
        color: var(--blue);
    }
    .badge-soft.b-hadir { background: rgba(20, 201, 167, 0.14); color: #0b8a73; }
    .badge-soft.b-izin  { background: rgba(14, 165, 233, 0.14); color: #0369a1; }
    .badge-soft.b-sakit { background: rgba(245, 158, 11, 0.16); color: #b45309; }
    .badge-soft.b-alpa  { background: rgba(244, 63, 94, 0.13);  color: #be123c; }

    [data-theme="dark"] .badge-soft.b-hadir { background: rgba(20, 201, 167, 0.20); color: #3fe0c1; }
    [data-theme="dark"] .badge-soft.b-izin  { background: rgba(56, 189, 248, 0.20); color: #7dd3fc; }
    [data-theme="dark"] .badge-soft.b-sakit { background: rgba(245, 158, 11, 0.20); color: #fbbf24; }
    [data-theme="dark"] .badge-soft.b-alpa  { background: rgba(244, 63, 94, 0.22);  color: #fb7185; }

    @media (max-width: 575.98px) {
        .hero { padding: 28px 22px; border-radius: 20px; }
        .panel-head, .panel-body { padding-left: 16px; padding-right: 16px; }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-0">

    @php
        $currentAdmin = auth()->user();
        if (!empty($currentAdmin?->foto)) {
            $rawAdminFoto = str_starts_with($currentAdmin->foto, 'uploads/')
                ? asset(ltrim($currentAdmin->foto, '/'))
                : asset('storage/' . ltrim($currentAdmin->foto, '/'));
            $avatarAdminUrl = $rawAdminFoto . '?v=' . optional($currentAdmin->updated_at)->timestamp;
        } else {
            $avatarAdminUrl = 'https://ui-avatars.com/api/?name=' . urlencode($currentAdmin->name ?? 'Admin') . '&background=0b2545&color=ffffff&size=120&bold=true';
        }
    @endphp

    {{-- Sapaan + Foto Profil --}}
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ Route::has('profile.index') ? route('profile.index') : url('/profil') }}" title="Edit Foto Profil">
                <img src="{{ $avatarAdminUrl }}" alt="Foto Profil {{ $currentAdmin->name ?? 'Admin' }}" class="admin-avatar">
            </a>
            <div class="d-flex flex-wrap align-items-center gap-2">
                <span class="welcome-line small">Selamat datang kembali,</span>
                <strong class="welcome-line">{{ $currentAdmin->name }}</strong>
                <span class="role-badge">Administrator</span>
            </div>
        </div>
        <a href="{{ Route::has('profile.index') ? route('profile.index') : url('/profil') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
            <i class="bi bi-person-gear me-1"></i> Edit Profil
        </a>
    </div>

    {{-- Hero --}}
    <section class="hero mb-4">
        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <h2 class="mb-2">Kehadiran seluruh kampus, dalam satu layar.</h2>
                <p class="mb-4">
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
                <div class="qr-card">
                    @include('partials.qr')
                </div>
            </div>
        </div>
    </section>

    {{-- 4 kartu statistik utama --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3">
            <div class="panel stat-card tone-blue">
                <div class="stat-top">
                    <span class="stat-title">Total Mahasiswa</span>
                    <span class="stat-icon" aria-hidden="true"><i class="bi bi-mortarboard-fill"></i></span>
                </div>
                <h3 class="stat-value">{{ $stats['total_mahasiswa'] }}</h3>
                <span class="stat-note">Mahasiswa aktif terdaftar</span>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="panel stat-card tone-mint">
                <div class="stat-top">
                    <span class="stat-title">Total Dosen</span>
                    <span class="stat-icon" aria-hidden="true"><i class="bi bi-person-workspace"></i></span>
                </div>
                <h3 class="stat-value">{{ $stats['total_dosen'] }}</h3>
                <span class="stat-note">Dosen pengampu aktif</span>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="panel stat-card tone-sky">
                <div class="stat-top">
                    <span class="stat-title">Mata Kuliah / Prodi</span>
                    <span class="stat-icon" aria-hidden="true"><i class="bi bi-journal-bookmark-fill"></i></span>
                </div>
                <h3 class="stat-value">{{ $stats['total_matkul'] }} <small>/ {{ $stats['total_prodi'] }} Prodi</small></h3>
                <span class="stat-note">{{ $stats['total_fakultas'] }} Fakultas &bull; {{ $stats['total_jadwal'] }} Jadwal</span>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="panel stat-card tone-amber">
                <div class="stat-top">
                    <span class="stat-title">Hadir Hari Ini</span>
                    <span class="stat-icon" aria-hidden="true"><i class="bi bi-check-circle-fill"></i></span>
                </div>
                <h3 class="stat-value">{{ $stats['hadir_hari_ini'] }}</h3>
                <span class="stat-note">{{ $stats['sesi_aktif'] }} sesi kelas sedang dibuka</span>
            </div>
        </div>
    </div>

    {{-- Pusat manajemen --}}
    <div class="panel mb-4">
        <div class="panel-head">
            <h5><i class="bi bi-grid-fill"></i> Pusat Manajemen Sistem</h5>
        </div>
        <div class="panel-body">
            <div class="row g-2">
                <div class="col-md-4 col-lg-3">
                    <a href="{{ route('admin.mahasiswa.index') }}" class="module-link m-blue">
                        <span class="m-ico"><i class="bi bi-people-fill"></i></span> Data Mahasiswa
                    </a>
                </div>
                <div class="col-md-4 col-lg-3">
                    <a href="{{ route('admin.dosen.index') }}" class="module-link m-blue">
                        <span class="m-ico"><i class="bi bi-person-badge-fill"></i></span> Data Dosen
                    </a>
                </div>
                <div class="col-md-4 col-lg-3">
                    <a href="{{ route('admin.matakuliah.index') }}" class="module-link m-blue">
                        <span class="m-ico"><i class="bi bi-book-fill"></i></span> Mata Kuliah
                    </a>
                </div>
                <div class="col-md-4 col-lg-3">
                    <a href="{{ route('admin.fakultas.index') }}" class="module-link m-blue">
                        <span class="m-ico"><i class="bi bi-building"></i></span> Data Fakultas
                    </a>
                </div>
                <div class="col-md-4 col-lg-3">
                    <a href="{{ route('admin.prodi.index') }}" class="module-link m-blue">
                        <span class="m-ico"><i class="bi bi-diagram-3-fill"></i></span> Program Studi
                    </a>
                </div>
                <div class="col-md-4 col-lg-3">
                    <a href="{{ route('admin.jadwal.index') }}" class="module-link m-blue">
                        <span class="m-ico"><i class="bi bi-calendar-week-fill"></i></span> Jadwal Kuliah
                    </a>
                </div>
                <div class="col-md-4 col-lg-3">
                    <a href="{{ route('admin.absensi.index') }}" class="module-link m-mint">
                        <span class="m-ico"><i class="bi bi-clipboard-check-fill"></i></span> Manajemen Absensi
                    </a>
                </div>
                <div class="col-md-4 col-lg-3">
                    <a href="{{ route('admin.laporan.index') }}" class="module-link m-mint">
                        <span class="m-ico"><i class="bi bi-file-earmark-spreadsheet-fill"></i></span> Laporan Absensi
                    </a>
                </div>
                <div class="col-md-4 col-lg-3">
                    <a href="{{ route('admin.qr.index') }}" class="module-link m-slate">
                        <span class="m-ico"><i class="bi bi-qr-code-scan"></i></span> Pengaturan QR Code
                    </a>
                </div>
                <div class="col-md-4 col-lg-3">
                    <a href="{{ route('admin.sistem.index') }}" class="module-link m-slate">
                        <span class="m-ico"><i class="bi bi-gear-fill"></i></span> Pengaturan Sistem
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Aktivitas presensi terbaru --}}
    <div class="panel">
        <div class="panel-head">
            <h5><i class="bi bi-activity is-mint"></i> Aktivitas Presensi Terbaru</h5>
            <a href="{{ route('admin.absensi.index') }}" class="btn btn-ghost btn-sm py-1 px-3">Lihat Semua</a>
        </div>
        <div class="panel-body table-responsive">
            <table class="table table-clean align-middle">
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
                                    'hadir' => 'b-hadir',
                                    'izin'  => 'b-izin',
                                    'sakit' => 'b-sakit',
                                    default => 'b-alpa',
                                };
                            @endphp
                            <span class="badge-soft {{ $badge }}">{{ strtoupper($item->status) }}</span>
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