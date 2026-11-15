@extends('layouts.app')

@section('title', 'Laporan & Rekap Absensi | Absensi Kampus')
@section('page_title', 'Laporan Absensi')
@section('role', 'dosen')

@section('content')
<div class="container-fluid px-0">

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1">Rekap Laporan Kehadiran Mahasiswa</h4>
            <p class="text-muted small mb-0">Filter berdasarkan mata kuliah untuk melihat dan mencetak rekap kehadiran.</p>
        </div>

        <form action="{{ route('dosen.laporan.cetak') }}" method="GET" target="_blank" class="d-flex gap-2">
            @if(request('mata_kuliah_id'))
                <input type="hidden" name="mata_kuliah_id" value="{{ request('mata_kuliah_id') }}">
            @endif
            <button type="submit" class="btn btn-brand btn-sm d-inline-flex align-items-center gap-2">
                <i class="bi bi-printer"></i> Cetak Laporan PDF
            </button>
        </form>
    </div>

    <!-- Filter Mata Kuliah -->
    <div class="panel p-4 mb-4">
        <form action="{{ route('dosen.laporan.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-6">
                <label for="mata_kuliah_id" class="form-label fw-semibold small">Filter Mata Kuliah</label>
                <select name="mata_kuliah_id" id="mata_kuliah_id" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Semua Mata Kuliah Pengampu --</option>
                    @foreach($mataKuliahs as $mk)
                        <option value="{{ $mk->id }}" {{ $selectedMk == $mk->id ? 'selected' : '' }}>
                            {{ $mk->nama_mk }} ({{ $mk->kode_mk }})
                        </option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>

    <!-- Tabel Rekap Sesi -->
    <div class="panel p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Tanggal & Sesi</th>
                        <th>Mata Kuliah</th>
                        <th>Topik / Materi</th>
                        <th class="text-center">Hadir</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sesis as $sesi)
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ \Carbon\Carbon::parse($sesi->tanggal)->translatedFormat('d M Y') }}</div>
                                <small class="text-muted">Pertemuan ke-{{ $sesi->pertemuan_ke }}</small>
                            </td>
                            <td>{{ $sesi->jadwal->mataKuliah->nama_mk ?? '-' }}</td>
                            <td>{{ $sesi->materi ?? '-' }}</td>
                            <td class="text-center">
                                <span class="badge bg-primary bg-opacity-10 text-primary">
                                    {{ $sesi->absensis->where('status', 'hadir')->count() }} Mahasiswa
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('dosen.absensi.show', $sesi->id) }}" class="btn btn-sm btn-ghost">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">Belum ada data rekap laporan absensi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $sesis->withQueryString()->links() }}
        </div>
    </div>

</div>
@endsection