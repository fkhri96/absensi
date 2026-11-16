@extends('layouts.app')

@section('title', 'QR Code Absensi | Absensi Kampus')
@section('page_title', 'QR Code Presensi')
@section('role', 'dosen')

@section('content')
<div class="container-fluid px-0">
    @if(session('success'))
        <div class="alert alert-success border-0 rounded-4 shadow-sm mb-4">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        </div>
    @endif

    <div class="row g-4">
        <!-- Kolom Kiri: Tampilan QR Code -->
        <div class="col-lg-5">
            <div class="panel p-4 shadow-sm text-center">
                <span class="badge rounded-pill {{ $sesi->status === 'buka' ? 'bg-success' : 'bg-danger' }} px-3 py-2 mb-3">
                    STATUS QR: {{ $sesi->status === 'buka' ? 'HIDUP (AKTIF)' : 'MATI (DITUTUP)' }}
                </span>

                <h5 class="fw-bold mb-1">{{ $sesi->jadwal->mataKuliah->nama_mk ?? 'Mata Kuliah' }}</h5>
                <p class="text-muted small mb-3">Pertemuan Ke-{{ $sesi->pertemuan_ke }} • {{$sesi->tanggal }}</p>

                @if($sesi->status === 'buka')
                    <div class="p-3 bg-white border rounded-4 d-inline-block shadow-sm mb-3">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=260x260&margin=10&data={{ urlencode($sesi->token_qr) }}"
                             alt="QR Code Absensi"
                             class="img-fluid"
                             style="width: 260px; height: 260px;">
                    </div>
                @else
                    <div class="p-5 bg-light border rounded-4 text-muted mb-3">
                        <i class="bi bi-qr-code display-1 opacity-25"></i>
                        <p class="mt-2 mb-0 small">QR Code sedang dimatikan.</p>
                    </div>
                @endif

                <div class="bg-light rounded-3 p-2 mb-3">
                    <small class="text-muted d-block">Kode Token Manual:</small>
                    <strong class="fs-5 text-primary user-select-all">{{ $sesi->token_qr }}</strong>
                </div>

                <div class="d-grid gap-2">
                    <form action="{{ route('dosen.absensi.toggle', $sesi->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn {{ $sesi->status === 'buka' ? 'btn-warning' : 'btn-success' }} w-100 fw-semibold">
                            <i class="bi {{ $sesi->status === 'buka' ? 'bi-pause-circle' : 'bi-play-circle' }} me-1"></i>
                            {{ $sesi->status === 'buka' ? 'Matikan QR Code' : 'Hidupkan QR Code' }}
                        </button>
                    </form>

                    <a href="{{ route('dosen.absensi.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Sesi
                    </a>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Daftar Mahasiswa yang Sudah Scan -->
        <div class="col-lg-7">
            <div class="panel p-4 shadow-sm">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0">Mahasiswa Hadir ({{ $sesi->absensis->count() }})</h6>
                    <button type="button" onclick="window.location.reload()" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-arrow-clockwise me-1"></i> Refresh Data
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle small">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>NIM</th>
                                <th>Nama Mahasiswa</th>
                                <th>Waktu Scan</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sesi->absensis as $index =>$absen)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $absen->mahasiswa->nim ?? '-' }}</td>
                                    <td class="fw-semibold">{{ $absen->mahasiswa->user->name ?? '-' }}</td>
                                    <td>{{ $absen->created_at ? $absen->created_at->format('H:i:s') : '-' }}</td>
                                    <td><span class="badge bg-success text-uppercase">{{ $absen->status }}</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        Belum ada mahasiswa yang melakukan scan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection