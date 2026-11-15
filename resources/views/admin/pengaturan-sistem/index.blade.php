@extends('layouts.app')

@section('title', 'Pengaturan Sistem | Absensi Kampus')
@section('page_title', 'Pengaturan Sistem')
@section('role', 'admin')

@section('content')
<div class="container-fluid px-0">

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h4 class="fw-bold mb-1">Pengaturan Umum Sistem</h4>
            <p class="text-muted small mb-0">Kelola identitas institusi, periode tahun akademik aktif, dan kebijakan kehadiran.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 rounded-3 shadow-sm mb-4">
            <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger border-0 rounded-3 shadow-sm mb-4">
            <ul class="mb-0 small">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @php
        $formAction = Route::has('admin.pengaturan-sistem.update')
            ? route('admin.pengaturan-sistem.update')
            : url('/admin/pengaturan-sistem');
    @endphp

    <form action="{{ $formAction }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row g-4">
            <!-- Kolom Kiri: Form Pengaturan -->
            <div class="col-lg-8">
                <div class="panel p-4 p-md-5 shadow-sm">
                    <h6 class="fw-bold text-uppercase text-muted small mb-3 pb-2 border-bottom">
                        <i class="bi bi-building text-primary me-1"></i> Identitas Aplikasi & Kampus
                    </h6>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="nama_aplikasi" class="form-label fw-semibold small">Nama Aplikasi <span class="text-danger">*</span></label>
                            <input type="text" id="nama_aplikasi" name="nama_aplikasi"
                                   value="{{ old('nama_aplikasi', $setting->nama_aplikasi ?? 'Absensi Kampus') }}"
                                   class="form-control @error('nama_aplikasi') is-invalid @enderror" required>
                            @error('nama_aplikasi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="nama_kampus" class="form-label fw-semibold small">Nama Perguruan Tinggi <span class="text-danger">*</span></label>
                            <input type="text" id="nama_kampus" name="nama_kampus"
                                   value="{{ old('nama_kampus', $setting->nama_kampus ?? '') }}"
                                   class="form-control @error('nama_kampus') is-invalid @enderror" required>
                            @error('nama_kampus')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="alamat_kampus" class="form-label fw-semibold small">Alamat Kampus</label>
                            <input type="text" id="alamat_kampus" name="alamat_kampus"
                                   value="{{ old('alamat_kampus', $setting->alamat_kampus ?? '') }}"
                                   class="form-control @error('alamat_kampus') is-invalid @enderror"
                                   placeholder="Masukkan alamat lengkap kampus (opsional)">
                            @error('alamat_kampus')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <h6 class="fw-bold text-uppercase text-muted small mb-3 pb-2 border-bottom">
                        <i class="bi bi-calendar-check text-primary me-1"></i> Periode Akademik & Aturan Kehadiran
                    </h6>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="tahun_akademik" class="form-label fw-semibold small">Tahun Akademik Aktif <span class="text-danger">*</span></label>
                            <input type="text" id="tahun_akademik" name="tahun_akademik"
                                   value="{{ old('tahun_akademik', $setting->tahun_akademik ?? '2026/2027') }}"
                                   class="form-control @error('tahun_akademik') is-invalid @enderror"
                                   placeholder="Contoh: 2026/2027" required>
                            @error('tahun_akademik')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="semester" class="form-label fw-semibold small">Semester Aktif <span class="text-danger">*</span></label>
                            <select id="semester" name="semester" class="form-select @error('semester') is-invalid @enderror" required>
                                <option value="Ganjil" {{ old('semester', $setting->semester ?? 'Ganjil') == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                                <option value="Genap" {{ old('semester', $setting->semester ?? '') == 'Genap' ? 'selected' : '' }}>Genap</option>
                            </select>
                            @error('semester')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="toleransi_terlambat" class="form-label fw-semibold small">Toleransi Keterlambatan</label>
                            <div class="input-group">
                                <input type="number" id="toleransi_terlambat" name="toleransi_terlambat" min="0" max="120"
                                       value="{{ old('toleransi_terlambat', $setting->toleransi_terlambat ?? 15) }}"
                                       class="form-control @error('toleransi_terlambat') is-invalid @enderror" required>
                                <span class="input-group-text">Menit</span>
                                @error('toleransi_terlambat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="minimal_kehadiran" class="form-label fw-semibold small">Syarat Minimal Kehadiran Ujian</label>
                            <div class="input-group">
                                <input type="number" id="minimal_kehadiran" name="minimal_kehadiran" min="0" max="100"
                                       value="{{ old('minimal_kehadiran', $setting->minimal_kehadiran ?? 75) }}"
                                       class="form-control @error('minimal_kehadiran') is-invalid @enderror" required>
                                <span class="input-group-text">%</span>
                                @error('minimal_kehadiran')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-brand d-inline-flex align-items-center gap-2">
                            <i class="bi bi-save2"></i> Simpan Pengaturan
                        </button>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Ringkasan Status Sistem -->
            <div class="col-lg-4">
                <div class="panel p-4 shadow-sm">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="badge bg-success bg-opacity-10 text-success">Sistem Aktif</span>
                        <span class="small text-muted">Ringkasan Periode</span>
                    </div>
                    <h5 class="fw-bold mb-1">{{ $setting->nama_kampus ?? 'Absensi Kampus' }}</h5>
                    <p class="small text-muted mb-3">{{ $setting->nama_aplikasi ?? 'Sistem Presensi QR' }}</p>

                    <ul class="list-unstyled small text-muted bg-light p-3 rounded-3 mb-0 d-grid gap-2">
                        <li class="d-flex justify-content-between">
                            <span>Tahun Akademik:</span>
                            <strong class="text-dark">{{ $setting->tahun_akademik ?? '2026/2027' }}</strong>
                        </li>
                        <li class="d-flex justify-content-between">
                            <span>Semester:</span>
                            <strong class="text-dark">{{ $setting->semester ?? 'Ganjil' }}</strong>
                        </li>
                        <li class="d-flex justify-content-between">
                            <span>Toleransi Terlambat:</span>
                            <strong class="text-dark">{{ $setting->toleransi_terlambat ?? 15 }} Menit</strong>
                        </li>
                        <li class="d-flex justify-content-between">
                            <span>Minimal Kehadiran:</span>
                            <strong class="text-dark">{{ $setting->minimal_kehadiran ?? 75 }}%</strong>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </form>

</div>
@endsection