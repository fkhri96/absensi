@extends('layouts.app')

@section('title', 'Jadwal Kuliah | Absensi Kampus')
@section('page_title', 'Jadwal Perkuliahan')
@section('role', 'mahasiswa')

@section('content')
<div class="container-fluid px-0">

    <div class="panel p-4 shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="fw-bold mb-1">Jadwal Kuliah Mahasiswa</h5>
                <p class="text-muted small mb-0">Daftar jadwal perkuliahan berdasarkan hari, jam, dan ruangan.</p>
            </div>
            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                {{ isset($jadwals) ? $jadwals->count() : 0 }} Jadwal
            </span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Hari</th>
                        <th>Jam</th>
                        <th>Mata Kuliah</th>
                        <th>Ruangan</th>
                        <th>Dosen Pengampu</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jadwals ?? [] as $index => $jadwal)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <span class="badge bg-primary bg-opacity-10 text-primary px-2">{{ $jadwal->hari }}</span>
                            </td>
                            <td class="fw-semibold">
                                {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->selesai ?? $jadwal->jam_selesai)->format('H:i') }}
                            </td>
                            <td>{{ $jadwal->mataKuliah->nama_mk ?? '-' }}</td>
                            <td>
                                <span class="badge bg-secondary bg-opacity-10 text-dark px-2">Ruang {{ $jadwal->ruang }}</span>
                            </td>
                            <td>{{ $jadwal->mataKuliah->dosen->user->name ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="bi bi-calendar-week fs-3 d-block mb-2"></i>
                                Belum ada jadwal perkuliahan yang tersedia.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection