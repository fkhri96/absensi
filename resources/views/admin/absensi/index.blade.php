@extends('layouts.app')

@section('title', 'Monitoring Absensi | Absensi Kampus')
@section('page_title', 'Monitoring & Rekap Absensi')
@section('role', 'admin')

@section('content')
<div class="container-fluid px-0">

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h4 class="fw-bold mb-1">Monitoring Sesi & Kehadiran Kuliah</h4>
            <p class="text-muted small mb-0">Pantau seluruh sesi perkuliahan yang dibuka dosen beserta jumlah kehadiran mahasiswa.</p>
        </div>
    </div>

    <div class="panel p-4 shadow-sm">
        <!-- Filter Pencarian -->
        <form action="{{ route('admin.absensi.index') }}" method="GET" class="row g-2 mb-4">
            <div class="col-md-4">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari mata kuliah atau nama dosen...">
            </div>
            <div class="col-md-3">
                <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="form-control">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-brand">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
                @if(request('search') || request('tanggal'))
                    <a href="{{ route('admin.absensi.index') }}" class="btn btn-ghost">Reset</a>
                @endif
            </div>
        </form>

        <!-- Tabel Rekap Sesi Absensi -->
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Tanggal</th>
                        <th>Mata Kuliah</th>
                        <th>Dosen Pengampu</th>
                        <th>Pertemuan</th>
                        <th>Jumlah Hadir</th>
                        <th>Status Sesi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sesis as $sesi)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($sesi->tanggal)->translatedFormat('d M Y') }}</td>
                        <td class="fw-semibold">{{ $sesi->jadwal->mataKuliah->nama_mk ?? '-' }}</td>
                        <td>{{ $sesi->jadwal->mataKuliah->dosen->user->name ?? '-' }}</td>
                        <td>Ke-{{ $sesi->pertemuan_ke }}</td>
                        <td>
                            <span class="badge bg-info text-dark">{{ $sesi->absensis_count ?? 0 }} Mahasiswa</span>
                        </td>
                        <td>
                            <span class="badge {{ $sesi->status == 'buka' ? 'bg-success' : 'bg-secondary' }}">
                                {{ strtoupper($sesi->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">Belum ada data sesi absensi yang tercatat.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($sesis, 'links'))
            <div class="mt-3">
                {{ $sesis->withQueryString()->links() }}
            </div>
        @endif
    </div>

</div>
@endsection