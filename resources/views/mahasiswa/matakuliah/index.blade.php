@extends('layouts.app')

@section('title', 'Mata Kuliah Saya | Absensi Kampus')
@section('page_title', 'Daftar Mata Kuliah Saya')
@section('role', 'mahasiswa')

@section('content')
<div class="container-fluid px-0">

    <div class="panel p-4 shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="fw-bold mb-1">Mata Kuliah Semester Ini</h5>
                <p class="text-muted small mb-0">Daftar mata kuliah yang diikuti beserta informasi dosen pengampu dan SKS.</p>
            </div>
            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                {{ isset($mataKuliahs) ? $mataKuliahs->count() : 0 }} Mata Kuliah
            </span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Kode MK</th>
                        <th>Nama Mata Kuliah</th>
                        <th>SKS</th>
                        <th>Program Studi</th>
                        <th>Dosen Pengampu</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mataKuliahs ?? [] as $index => $mk)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="fw-semibold">{{ $mk->kode_mk ?? '-' }}</td>
                            <td>{{ $mk->nama_mk ?? '-' }}</td>
                            <td>
                                <span class="badge bg-secondary bg-opacity-10 text-dark px-2">{{ $mk->sks ?? 3 }} SKS</span>
                            </td>
                            <td>{{ $mk->prodi->nama_prodi ?? '-' }}</td>
                            <td>{{ $mk->dosen->user->name ?? 'Belum ditentukan' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="bi bi-book fs-3 d-block mb-2"></i>
                                Belum ada mata kuliah yang tersedia untuk program studi Anda.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection