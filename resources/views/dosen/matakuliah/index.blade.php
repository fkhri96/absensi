@extends('layouts.app')

@section('title', 'Mata Kuliah Diampu | Absensi Kampus')
@section('page_title', 'Mata Kuliah Diampu')
@section('role', 'dosen')

@push('styles')
<style>
    .mk-card {
        background: #ffffff;
        border: 1px solid var(--line);
        border-radius: 18px;
        transition: all 0.22s ease;
        display: flex;
        flex-direction: column;
        height: 100%;
    }
    .mk-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 14px 30px rgba(11, 37, 69, 0.08);
        border-color: #cbd5e1;
    }
    .mk-badge-code {
        background: var(--blue-soft);
        color: var(--blue);
        font-weight: 700;
        font-size: 0.78rem;
        padding: 5px 12px;
        border-radius: 8px;
        letter-spacing: 0.03em;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-0">

    <!-- Header & Pencarian -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1">Daftar Mata Kuliah Pengampu</h4>
            <p class="text-muted small mb-0">
                Kelola perkuliahan, jadwal kelas, dan pantau daftar kehadiran mahasiswa per mata kuliah.
            </p>
        </div>

        <form action="{{ route('dosen.matakuliah.index') }}" method="GET" class="d-flex gap-2">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="q" value="{{ request('q') }}"
                       class="form-control border-start-0 ps-0"
                       placeholder="Cari kode / nama MK...">
            </div>
            @if(request('q'))
                <a href="{{ route('dosen.matakuliah.index') }}" class="btn btn-ghost">Reset</a>
            @endif
        </form>
    </div>

    <!-- Ringkasan Cepat -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-4">
            <div class="panel p-3 p-md-4">
                <div class="text-muted small fw-semibold mb-1">Total Mata Kuliah</div>
                <div class="d-flex align-items-center justify-content-between">
                    <h3 class="fw-bold mb-0">{{ $mataKuliahs->count() }}</h3>
                    <span class="badge bg-primary bg-opacity-10 text-primary p-2 rounded-3">
                        <i class="bi bi-journal-bookmark-fill fs-5"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div class="panel p-3 p-md-4">
                <div class="text-muted small fw-semibold mb-1">Total SKS Diampu</div>
                <div class="d-flex align-items-center justify-content-between">
                    <h3 class="fw-bold mb-0">{{ $mataKuliahs->sum('sks') }} <span class="fs-6 fw-normal text-muted">SKS</span></h3>
                    <span class="badge bg-success bg-opacity-10 text-success p-2 rounded-3">
                        <i class="bi bi-award-fill fs-5"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="panel p-3 p-md-4">
                <div class="text-muted small fw-semibold mb-1">Total Sesi Pertemuan</div>
                <div class="d-flex align-items-center justify-content-between">
                    <h3 class="fw-bold mb-0">{{ $mataKuliahs->sum('total_sesi') }} <span class="fs-6 fw-normal text-muted">Pertemuan</span></h3>
                    <span class="badge bg-warning bg-opacity-10 text-warning p-2 rounded-3">
                        <i class="bi bi-qr-code-scan fs-5"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Grid Kartu Mata Kuliah -->
    <div class="row g-4">
        @forelse($mataKuliahs as $mk)
            <div class="col-md-6 col-xl-4">
                <div class="mk-card p-4">
                    <div class="d-flex justify-content-between align-items-start gap-2 mb-3">
                        <span class="mk-badge-code">{{ $mk->kode_mk ?? 'MK-' . $mk->id }}</span>
                        <div class="d-flex align-items-center gap-1">
                            @if($mk->sesi_buka > 0)
                                <span class="badge rounded-pill bg-success">
                                    <i class="bi bi-broadcast me-1"></i> Sesi Aktif
                                </span>
                            @endif
                            <span class="badge rounded-pill bg-light text-dark border">
                                {{ $mk->sks ?? 2 }} SKS • Smt {{ $mk->semester ?? '-' }}
                            </span>
                        </div>
                    </div>

                    <h5 class="fw-bold mb-1">{{ $mk->nama_mk }}</h5>
                    <p class="text-muted small mb-3">
                        <i class="bi bi-mortarboard me-1"></i>
                        {{ $mk->prodi->nama_prodi ?? 'Program Studi Umum' }}
                    </p>

                    <!-- Info Jadwal Kelas -->
                    <div class="bg-light rounded-3 p-3 mb-3 small">
                        <div class="fw-semibold text-dark mb-1">
                            <i class="bi bi-clock text-primary me-1"></i> Jadwal Perkuliahan:
                        </div>
                        @forelse($mk->jadwals as $j)
                            <div class="text-muted d-flex justify-content-between">
                                <span>{{ $j->hari }}, {{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }}</span>
                                <span class="fw-semibold text-dark">R. {{ $j->ruang }}</span>
                            </div>
                        @empty
                            <span class="text-muted fst-italic">Belum ada jadwal terdaftar</span>
                        @endforelse
                    </div>

                    <div class="mt-auto pt-3 border-top d-flex align-items-center justify-content-between gap-2">
                        <span class="small text-muted">
                            <strong class="text-dark">{{ $mk->total_sesi }}</strong> Pertemuan tercatat
                        </span>

                        <div class="d-flex gap-2">
                            <a href="{{ route('dosen.absensi.create') }}" class="btn btn-sm btn-ghost" title="Buka Sesi QR">
                                <i class="bi bi-qr-code"></i>
                            </a>
                            <a href="{{ route('dosen.matakuliah.show', $mk->id) }}" class="btn btn-sm btn-brand d-inline-flex align-items-center gap-1">
                                <span>Detail Kelas</span>
                                <i class="bi bi-arrow-right-short"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="panel p-5 text-center">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-light text-muted mb-3"
                         style="width: 64px; height: 64px;">
                        <i class="bi bi-journal-x fs-3"></i>
                    </div>
                    <h6 class="fw-bold mb-1">Belum Ada Mata Kuliah</h6>
                    <p class="text-muted small mb-0">
                        Belum ada mata kuliah yang ditugaskan ke akun dosen Anda. Hubungi Administrator jika terjadi ketidaksesuaian data.
                    </p>
                </div>
            </div>
        @endforelse
    </div>

</div>
@endsection