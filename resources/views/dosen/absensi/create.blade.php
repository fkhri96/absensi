@extends('layouts.app')

@section('title', 'Buka Sesi QR Absensi | Absensi Kampus')
@section('page_title', 'Buka Sesi QR Baru')
@section('role', 'dosen')

@section('content')
<div class="container-fluid px-0">

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="panel p-4 p-md-5 shadow-sm">
                <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                    <span class="badge bg-primary bg-opacity-10 text-primary p-3 rounded-4">
                        <i class="bi bi-qr-code-scan fs-4"></i>
                    </span>
                    <div>
                        <h4 class="fw-bold mb-1">Buat Sesi QR Presensi</h4>
                        <p class="text-muted small mb-0">Pilih mata kuliah, tentukan pertemuan ke-, materi, dan durasi aktif QR Code.</p>
                    </div>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger border-0 rounded-4 shadow-sm mb-4">
                        <ul class="mb-0 small">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('dosen.absensi.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="jadwal_id" class="form-label fw-semibold small">Pilih Kelas / Mata Kuliah <span class="text-danger">*</span></label>
                        <select id="jadwal_id" name="jadwal_id" class="form-select @error('jadwal_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Jadwal Mengajar --</option>
                            @foreach($jadwals as $jadwal)
                                <option value="{{ $jadwal->id }}" {{ old('jadwal_id') == $jadwal->id ? 'selected' : '' }}>
                                    {{ $jadwal->mataKuliah->nama_mk ?? '-' }} ({{ $jadwal->hari }}, {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - Ruang {{ $jadwal->ruang }})
                                </option>
                            @endforeach
                        </select>
                        @error('jadwal_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="pertemuan_ke" class="form-label fw-semibold small">Pertemuan Ke- <span class="text-danger">*</span></label>
                            <input type="number" id="pertemuan_ke" name="pertemuan_ke" min="1" max="16"
                                   value="{{ old('pertemuan_ke', 1) }}"
                                   class="form-control @error('pertemuan_ke') is-invalid @enderror" required>
                            @error('pertemuan_ke')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="durasi_menit" class="form-label fw-semibold small">Durasi Aktif QR (Menit) <span class="text-danger">*</span></label>
                            <input type="number" id="durasi_menit" name="durasi_menit" min="5" max="180"
                                   value="{{ old('durasi_menit', 30) }}"
                                   class="form-control @error('durasi_menit') is-invalid @enderror" required>
                            <small class="text-muted" style="font-size: 0.75rem;">Sesi otomatis kedaluwarsa setelah durasi ini.</small>
                            @error('durasi_menit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="materi" class="form-label fw-semibold small">Topik / Materi Perkuliahan (Opsional)</label>
                        <input type="text" id="materi" name="materi"
                               value="{{ old('materi') }}"
                               class="form-control @error('materi') is-invalid @enderror"
                               placeholder="Contoh: Pengantar Controller dan Routing Laravel">
                        @error('materi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                        <a href="{{ route('dosen.absensi.index') }}" class="btn btn-ghost">Batal</a>
                        <button type="submit" class="btn btn-brand d-inline-flex align-items-center gap-2 px-4">
                            <i class="bi bi-broadcast"></i> Buka Sesi & Tampilkan QR
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection