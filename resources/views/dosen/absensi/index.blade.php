@extends('layouts.app')

@section('title', 'Kelola Absensi | Absensi Kampus')
@section('page_title', 'Kelola Sesi & QR Absensi')
@section('role', 'dosen')

@section('content')
<div class="container-fluid px-0">

    @if(session('success'))
        <div class="alert alert-success border-0 rounded-4 shadow-sm mb-4">
            <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
        </div>
    @endif

    <div class="panel p-4 shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="fw-bold mb-1">Daftar Sesi Perkuliahan</h5>
                <p class="text-muted small mb-0">Kelola status aktif/mati QR Code dan pantau rekap kehadiran mahasiswa.</p>
            </div>
            <a href="{{ route('dosen.absensi.create') }}" class="btn btn-brand">
                <i class="bi bi-qr-code me-1"></i> Buat QR Baru
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 small">
                <thead class="table-light">
                    <tr>
                        <th>Tanggal</th>
                        <th>Mata Kuliah</th>
                        <th>Pertemuan</th>
                        <th class="text-center">Hadir</th>
                        <th class="text-center">Status QR</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sesis as $sesi)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($sesi->tanggal)->format('d/m/Y') }}</td>
                            <td>
                                <div class="fw-semibold">{{ $sesi->jadwal->mataKuliah->nama_mk ?? '-' }}</div>
                                <small class="text-muted">{{ $sesi->materi ?? 'Tanpa keterangan materi' }}</small>
                            </td>
                            <td>Ke-{{ $sesi->pertemuan_ke }}</td>
                            <td class="text-center">
                                <span class="badge bg-primary bg-opacity-10 text-primary">{{ $sesi->absensis->count() }} Mhs</span>
                            </td>
                            <td class="text-center">
                                @if($sesi->status === 'buka')
                                    <span class="badge bg-success bg-opacity-10 text-success">HIDUP</span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger">MATI</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-1">
                                    <a href="{{ route('dosen.absensi.show', $sesi->id) }}" class="btn btn-sm btn-primary">
                                        <i class="bi bi-qr-code"></i> QR
                                    </a>

                                    <form action="{{ route('dosen.absensi.toggle', $sesi->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        @if($sesi->status === 'buka')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Matikan QR">
                                                <i class="bi bi-power"></i> Matikan
                                            </button>
                                        @else
                                            <button type="submit" class="btn btn-sm btn-outline-success" title="Hidupkan QR">
                                                <i class="bi bi-play-fill"></i> Hidupkan
                                            </button>
                                        @endif
                                    </form>

                                    <form action="{{ route('dosen.absensi.destroy', $sesi->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus sesi absensi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
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

        <div class="mt-3">{{ $sesis->links() }}</div>
    </div>

</div>
@endsection