@extends('layouts.app')

@section('title', 'Riwayat Absensi | Absensi Kampus')
@section('page_title', 'Riwayat Kehadiran Mahasiswa')
@section('role', 'mahasiswa')

@section('content')
<div class="container-fluid px-0">

    <div class="panel p-4 shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="fw-bold mb-1">Riwayat Kehadiran Perkuliahan</h5>
                <p class="text-muted small mb-0">Daftar lengkap rekam jejak kehadiran presensi Anda.</p>
            </div>
            <a href="{{ route('mahasiswa.laporan.cetak') }}" target="_blank" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1">
                <i class="bi bi-file-pdf"></i> Unduh Rekap PDF
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Mata Kuliah</th>
                        <th>Pertemuan</th>
                        <th>Waktu Presensi</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($absensis ?? [] as $index => $absen)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="fw-semibold">{{ $absen->sesiAbsensi->jadwal->mataKuliah->nama_mk ?? '-' }}</td>
                            <td>Pertemuan ke-{{ $absen->sesiAbsensi->pertemuan_ke ?? '-' }}</td>
                            <td>{{ $absen->created_at ? $absen->created_at->format('d/m/Y H:i') : '-' }}</td>
                            <td class="text-center">
                                <span class="badge bg-success bg-opacity-10 text-success text-uppercase px-2">
                                    {{ $absen->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                <i class="bi bi-clock-history fs-3 d-block mb-2"></i>
                                Belum ada riwayat kehadiran yang tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection