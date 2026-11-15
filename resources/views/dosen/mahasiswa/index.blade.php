@extends('layouts.app')

@section('title', 'Daftar Mahasiswa | Absensi Kampus')
@section('page_title', 'Daftar Mahasiswa')
@section('role', 'dosen')

@push('styles')
<style>
    .mhs-card {
        background: #ffffff;
        border: 1px solid var(--line);
        border-radius: 16px;
        transition: all 0.2s ease;
    }
    .mhs-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 24px rgba(11, 37, 69, 0.06);
        border-color: #cbd5e1;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-0">

    <!-- Header & Pencarian -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1">Daftar Mahasiswa Program Studi</h4>
            <p class="text-muted small mb-0">
                Daftar mahasiswa aktif yang berada dalam lingkup program studi mata kuliah pengampu Anda.
            </p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('dosen.matakuliah.index') }}" class="btn btn-ghost d-inline-flex align-items-center gap-2">
                <i class="bi bi-journal-bookmark"></i> Lihat Mata Kuliah
            </a>
        </div>
    </div>

    <!-- Ringkasan Cepat -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-6">
            <div class="panel p-3 p-md-4">
                <div class="text-muted small fw-semibold mb-1">Total Mahasiswa Terdaftar</div>
                <div class="d-flex align-items-center justify-content-between">
                    <h3 class="fw-bold mb-0">{{ $mahasiswas->count() }} <span class="fs-6 fw-normal text-muted">Mahasiswa</span></h3>
                    <span class="badge bg-primary bg-opacity-10 text-primary p-2 rounded-3">
                        <i class="bi bi-people-fill fs-5"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="panel p-3 p-md-4">
                <div class="text-muted small fw-semibold mb-1">Status Akademik</div>
                <div class="d-flex align-items-center justify-content-between">
                    <h3 class="fw-bold mb-0 text-success">Aktif</h3>
                    <span class="badge bg-success bg-opacity-10 text-success p-2 rounded-3">
                        <i class="bi bi-shield-check fs-5"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Daftar Mahasiswa -->
    <div class="panel p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="60">No</th>
                        <th>NIM</th>
                        <th>Nama Mahasiswa</th>
                        <th>Program Studi</th>
                        <th>Email Kampus</th>
                        <th class="text-end">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mahasiswas as $index => $mhs)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="fw-bold text-primary">{{ $mhs->nim ?? '-' }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar" style="width: 34px; height: 34px; font-size: 0.8rem;">
                                        {{ strtoupper(substr($mhs->user->name ?? 'M', 0, 1)) }}
                                    </div>
                                    <div class="fw-semibold">{{ $mhs->user->name ?? $mhs->nama ?? '-' }}</div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">
                                    {{ $mhs->prodi->nama_prodi ?? 'Program Studi Umum' }}
                                </span>
                            </td>
                            <td class="text-muted">{{ $mhs->user->email ?? '-' }}</td>
                            <td class="text-end">
                                <span class="badge bg-success bg-opacity-10 text-success px-3 py-1">Aktif</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-people fs-3 d-block mb-2"></i>
                                Belum ada data mahasiswa yang terdaftar pada program studi Anda.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection