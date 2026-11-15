@extends('layouts.app')

@section('title', 'Daftar Sesi Absensi | Absensi Kampus')
@section('page_title', 'Kelola Sesi Absensi')
@section('role', 'dosen')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Daftar Sesi QR Absensi Kelas</h4>
        <a href="{{ route('dosen.absensi.create') }}" class="btn btn-brand">
            <i class="bi bi-qr-code-scan me-1"></i> Buka Sesi Baru
        </a>
    </div>

    <div class="panel p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Pertemuan</th>
                        <th>Mata Kuliah</th>
                        <th>Tanggal</th>
                        <th>Hadir</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sesis as $sesi)
                        <tr>
                            <td class="fw-bold">Pertemuan ke-{{ $sesi->pertemuan_ke }}</td>
                            <td>{{ $sesi->jadwal->mataKuliah->nama_mk ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($sesi->tanggal)->translatedFormat('d M Y') }}</td>
                            <td><span class="badge bg-primary bg-opacity-10 text-primary">{{ $sesi->absensis_count }} Mahasiswa</span></td>
                            <td>
                                @if($sesi->status === 'buka')
                                    <span class="badge bg-success">Dibuka</span>
                                @else
                                    <span class="badge bg-secondary">Ditutup</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('dosen.absensi.show', $sesi->id) }}" class="btn btn-sm btn-ghost">Kontrol QR</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Belum ada sesi absensi yang dibuat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $sesis->links() }}
        </div>
    </div>
</div>
@endsection