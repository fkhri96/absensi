@extends('layouts.app')

@section('title', 'Detail Mata Kuliah | Absensi Kampus')
@section('page_title', 'Detail Mata Kuliah')
@section('role', 'dosen')

@section('content')
<div class="container-fluid px-0">

    <!-- Tombol Kembali & Aksi -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <a href="{{ route('dosen.matakuliah.index') }}" class="btn btn-ghost btn-sm d-inline-flex align-items-center gap-1">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar Mata Kuliah
        </a>

        <div class="d-flex gap-2">
            <a href="{{ route('dosen.absensi.create') }}" class="btn btn-brand btn-sm d-inline-flex align-items-center gap-2">
                <i class="bi bi-qr-code-scan"></i> Buka Sesi QR Pertemuan
            </a>
        </div>
    </div>

    <!-- Header Info Mata Kuliah -->
    <div class="panel p-4 p-md-5 mb-4">
        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-3 fw-bold">
                        {{ $mataKuliah->kode_mk ?? 'MK-' . $mataKuliah->id }}
                    </span>
                    <span class="badge bg-light text-dark border px-3 py-2 rounded-3">
                        {{ $mataKuliah->sks ?? 2 }} SKS • Semester {{ $mataKuliah->semester ?? '-' }}
                    </span>
                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-3">
                        {{ $mataKuliah->prodi->nama_prodi ?? 'Semua Prodi' }}
                    </span>
                </div>
                <h3 class="fw-bold mb-2">{{ $mataKuliah->nama_mk }}</h3>
                <p class="text-muted small mb-0">
                    Dosen Pengampu: <strong class="text-dark">{{ auth()->user()->name }}</strong> •
                    Total Pertemuan Terlaksana: <strong class="text-dark">{{ $totalSesi }} Sesi</strong>
                </p>
            </div>

            <div class="col-lg-4">
                <div class="bg-light rounded-4 p-3 small">
                    <div class="fw-bold text-dark mb-2"><i class="bi bi-calendar-week text-primary me-1"></i> Jadwal Mengajar</div>
                    @forelse($mataKuliah->jadwals as $j)
                        <div class="d-flex justify-content-between py-1 border-bottom border-white">
                            <span>{{ $j->hari }} ({{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }})</span>
                            <strong class="text-primary">Ruang {{ $j->ruang }}</strong>
                        </div>
                    @empty
                        <span class="text-muted">Belum ada jadwal diatur.</span>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Tab Navigasi: Daftar Mahasiswa vs Riwayat Pertemuan -->
    <ul class="nav nav-pills gap-2 mb-3" id="mkTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active rounded-pill fw-semibold px-4" id="mhs-tab" data-bs-toggle="tab" data-bs-target="#mhs-pane" type="button" role="tab">
                <i class="bi bi-people-fill me-1"></i> Mahasiswa Kelas ({{ $mahasiswas->count() }})
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link rounded-pill fw-semibold px-4" id="sesi-tab" data-bs-toggle="tab" data-bs-target="#sesi-pane" type="button" role="tab">
                <i class="bi bi-clock-history me-1"></i> Riwayat Pertemuan ({{ $sesiAbsensis->count() }})
            </button>
        </li>
    </ul>

    <div class="tab-content" id="mkTabContent">
        <!-- Tab 1: Rekap Kehadiran Mahasiswa -->
        <div class="tab-pane fade show active" id="mhs-pane" role="tabpanel">
            <div class="panel p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="50">No</th>
                                <th>NIM</th>
                                <th>Nama Mahasiswa</th>
                                <th class="text-center">Hadir</th>
                                <th class="text-center">Izin</th>
                                <th class="text-center">Sakit</th>
                                <th class="text-center">Alpha</th>
                                <th width="180">Persentase</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($mahasiswas as $index => $mhs)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td class="fw-semibold text-nowrap">{{ $mhs->nim ?? '-' }}</td>
                                    <td>
                                        <div class="fw-semibold">{{ $mhs->user->name ?? $mhs->nama ?? '-' }}</div>
                                        <small class="text-muted">{{ $mhs->user->email ?? '' }}</small>
                                    </td>
                                    <td class="text-center"><span class="badge bg-success bg-opacity-10 text-success px-2">{{ $mhs->hadir }}</span></td>
                                    <td class="text-center"><span class="badge bg-info bg-opacity-10 text-info px-2">{{ $mhs->izin }}</span></td>
                                    <td class="text-center"><span class="badge bg-warning bg-opacity-10 text-warning px-2">{{ $mhs->sakit }}</span></td>
                                    <td class="text-center"><span class="badge bg-danger bg-opacity-10 text-danger px-2">{{ $mhs->alpha }}</span></td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="progress flex-grow-1" style="height: 7px;">
                                                <div class="progress-bar {{ $mhs->persentase >= 75 ? 'bg-success' : 'bg-warning' }}"
                                                     style="width: {{ $mhs->persentase }}%"></div>
                                            </div>
                                            <span class="small fw-bold" style="width: 38px;">{{ $mhs->persentase }}%</span>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">
                                        Belum ada data mahasiswa pada program studi mata kuliah ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tab 2: Riwayat Sesi Pertemuan -->
        <div class="tab-pane fade" id="sesi-pane" role="tabpanel">
            <div class="panel p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Pertemuan</th>
                                <th>Tanggal</th>
                                <th>Materi / Topik</th>
                                <th>Total Hadir</th>
                                <th>Status</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sesiAbsensis as $sesi)
                                <tr>
                                    <td class="fw-bold">Pertemuan ke-{{ $sesi->pertemuan_ke }}</td>
                                    <td>{{ \Carbon\Carbon::parse($sesi->tanggal)->translatedFormat('d M Y') }}</td>
                                    <td>{{ $sesi->materi ?? '-' }}</td>
                                    <td><span class="badge bg-primary bg-opacity-10 text-primary">{{ $sesi->absensis_count }} Mahasiswa</span></td>
                                    <td>
                                        @if($sesi->status === 'buka')
                                            <span class="badge bg-success">Dibuka</span>
                                        @else
                                            <span class="badge bg-secondary">Ditutup</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('dosen.absensi.show', $sesi->id) }}" class="btn btn-sm btn-ghost">
                                            Detail & Koreksi
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        Belum ada sesi pertemuan yang dibuka untuk mata kuliah ini.
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