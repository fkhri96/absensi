@extends('layouts.app')

@section('title', 'Laporan Absensi | Absensi Kampus')
@section('page_title', 'Laporan Kehadiran')
@section('role', 'admin')

@section('content')
<div class="container-fluid px-0">

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h4 class="fw-bold mb-1">Laporan & Rekapitulasi Kehadiran</h4>
            <p class="text-muted small mb-0">Filter riwayat kehadiran mahasiswa berdasarkan mata kuliah, rentang tanggal, atau status.</p>
        </div>
        <button type="button" onclick="window.print()" class="btn btn-brand d-inline-flex align-items-center gap-2">
            <i class="bi bi-printer"></i> Cetak Laporan
        </button>
    </div>

    <!-- Kartu Ringkasan -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="panel p-3 shadow-sm">
                <div class="text-muted small fw-semibold">Total Record</div>
                <h3 class="fw-bold mb-0 mt-1">{{ $ringkasan['total'] ?? 0 }}</h3>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="panel p-3 shadow-sm">
                <div class="text-success small fw-semibold">Hadir</div>
                <h3 class="fw-bold text-success mb-0 mt-1">{{ $ringkasan['hadir'] ?? 0 }}</h3>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="panel p-3 shadow-sm">
                <div class="text-warning small fw-semibold">Izin / Sakit</div>
                <h3 class="fw-bold text-warning mb-0 mt-1">{{ ($ringkasan['izin'] ?? 0) + ($ringkasan['sakit'] ?? 0) }}</h3>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="panel p-3 shadow-sm">
                <div class="text-danger small fw-semibold">Alpha</div>
                <h3 class="fw-bold text-danger mb-0 mt-1">{{ $ringkasan['alpha'] ?? 0 }}</h3>
            </div>
        </div>
    </div>

    <!-- Panel Filter & Tabel -->
    <div class="panel p-4 shadow-sm">
        <form action="{{ route('admin.laporan.index') }}" method="GET" class="row g-2 align-items-end mb-4">
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Mata Kuliah</label>
                <select name="mata_kuliah_id" class="form-select">
                    <option value="">-- Semua Mata Kuliah --</option>
                    @foreach($mataKuliahs as $mk)
                        <option value="{{ $mk->id }}" {{ request('mata_kuliah_id') == $mk->id ? 'selected' : '' }}>
                            {{ $mk->nama_mk }} ({{ $mk->dosen->user->name ?? '-' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label small fw-semibold">Dari Tanggal</label>
                <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}" class="form-control">
            </div>

            <div class="col-md-2">
                <label class="form-label small fw-semibold">Sampai Tanggal</label>
                <input type="date" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}" class="form-control">
            </div>

            <div class="col-md-2">
                <label class="form-label small fw-semibold">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    @foreach(['hadir', 'izin', 'sakit', 'alpha'] as $st)
                        <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>
                            {{ ucfirst($st) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-brand w-100">Filter</button>
                @if(request()->hasAny(['mata_kuliah_id', 'tanggal_mulai', 'tanggal_selesai', 'status']))
                    <a href="{{ route('admin.laporan.index') }}" class="btn btn-ghost">Reset</a>
                @endif
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Tanggal</th>
                        <th>NIM</th>
                        <th>Nama Mahasiswa</th>
                        <th>Mata Kuliah</th>
                        <th>Dosen Pengampu</th>
                        <th>Pertemuan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($laporans as $item)
                    <tr>
                        <td>{{ $item->sesiAbsensi->tanggal ?? '-' }}</td>
                        <td>{{ $item->mahasiswa->nim ?? '-' }}</td>
                        <td class="fw-semibold">{{ $item->mahasiswa->user->name ?? '-' }}</td>
                        <td>{{ $item->sesiAbsensi->jadwal->mataKuliah->nama_mk ?? '-' }}</td>
                        <td>{{ $item->sesiAbsensi->jadwal->mataKuliah->dosen->user->name ?? '-' }}</td>
                        <td>Ke-{{ $item->sesiAbsensi->pertemuan_ke ?? '-' }}</td>
                        <td>
                            @php
                                $badgeColor = match(strtolower($item->status ?? '')) {
                                    'hadir' => 'bg-success',
                                    'izin', 'sakit' => 'bg-warning text-dark',
                                    default => 'bg-danger',
                                };
                            @endphp
                            <span class="badge {{ $badgeColor }}">{{ strtoupper($item->status ?? '-') }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">Belum ada data kehadiran yang sesuai dengan filter.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($laporans, 'links'))
            <div class="mt-3">
                {{ $laporans->withQueryString()->links() }}
            </div>
        @endif
    </div>

</div>
@endsection