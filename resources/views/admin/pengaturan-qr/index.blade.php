@extends('layouts.app')

@section('title', 'Pengaturan QR Code | Absensi Kampus')
@section('page_title', 'Pengaturan QR Code')
@section('role', 'admin')

@section('content')
<div class="container-fluid px-0">

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h4 class="fw-bold mb-1">Pengaturan Sistem QR Code</h4>
            <p class="text-muted small mb-0">Atur masa berlaku kode QR, pembaruan otomatis, dan validasi radius lokasi kampus.</p>
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
        $formAction = Route::has('admin.pengaturan-qr.update')
            ? route('admin.pengaturan-qr.update')
            : url('/admin/pengaturan-qr');
    @endphp

    <form action="{{ $formAction }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row g-4">
            <!-- Kolom Kiri: Form Pengaturan -->
            <div class="col-lg-8">
                <div class="panel p-4 p-md-5 shadow-sm">
                    <h6 class="fw-bold text-uppercase text-muted small mb-3 pb-2 border-bottom">
                        <i class="bi bi-qr-code-scan text-primary me-1"></i> Konfigurasi Durasi & Refresh QR
                    </h6>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="durasi_detik" class="form-label fw-semibold small">Durasi Berlaku QR (Detik)</label>
                            <div class="input-group">
                                <input type="number" id="durasi_detik" name="durasi_detik" min="5" max="3600"
                                       value="{{ old('durasi_detik', $setting->durasi_detik ?? 60) }}"
                                       class="form-control @error('durasi_detik') is-invalid @enderror" required>
                                <span class="input-group-text">Detik</span>
                                @error('durasi_detik')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="text-muted">Default sistem adalah 60 detik per siklus token.</small>
                        </div>

                        <div class="col-md-6 d-flex align-items-center">
                            <div class="form-check form-switch mt-md-3">
                                <input class="form-check-input" type="checkbox" role="switch" id="refresh_otomatis" name="refresh_otomatis"
                                       {{ !empty($setting->refresh_otomatis) ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold small" for="refresh_otomatis">
                                    Aktifkan Refresh QR Otomatis
                                </label>
                                <div class="text-muted small">Kode QR berganti otomatis sesuai durasi detik.</div>
                            </div>
                        </div>
                    </div>

                    <h6 class="fw-bold text-uppercase text-muted small mb-3 pb-2 border-bottom">
                        <i class="bi bi-geo-alt text-primary me-1"></i> Validasi Lokasi Kampus (Geofencing)
                    </h6>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" role="switch" id="validasi_lokasi" name="validasi_lokasi"
                               {{ !empty($setting->validasi_lokasi) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold small" for="validasi_lokasi">
                            Wajibkan mahasiswa berada dalam radius titik koordinat kampus
                        </label>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="latitude" class="form-label fw-semibold small">Latitude</label>
                            <input type="text" id="latitude" name="latitude"
                                   value="{{ old('latitude', $setting->latitude ?? '') }}"
                                   class="form-control @error('latitude') is-invalid @enderror"
                                   placeholder="Contoh: -7.536064">
                            @error('latitude')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="longitude" class="form-label fw-semibold small">Longitude</label>
                            <input type="text" id="longitude" name="longitude"
                                   value="{{ old('longitude', $setting->longitude ?? '') }}"
                                   class="form-control @error('longitude') is-invalid @enderror"
                                   placeholder="Contoh: 110.602051">
                            @error('longitude')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="radius_meter" class="form-label fw-semibold small">Radius Maksimal</label>
                            <div class="input-group">
                                <input type="number" id="radius_meter" name="radius_meter" min="10" max="5000"
                                       value="{{ old('radius_meter', $setting->radius_meter ?? 100) }}"
                                       class="form-control @error('radius_meter') is-invalid @enderror" required>
                                <span class="input-group-text">Meter</span>
                                @error('radius_meter')
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

            <!-- Kolom Kanan: Ringkasan Status -->
            <div class="col-lg-4">
                <div class="panel p-4 shadow-sm text-center">
                    <span class="badge bg-primary bg-opacity-10 text-primary mb-3">Pratinjau QR Code</span>
                    <div class="p-3 bg-white border rounded-4 d-inline-block mb-3">
                        <div id="preview-qr"></div>
                    </div>
                    <div class="fw-bold small mb-2">Status Konfigurasi Tersimpan</div>
                    <ul class="list-unstyled small text-muted text-start bg-light p-3 rounded-3 mb-0 d-grid gap-1">
                        <li>Durasi QR: <strong class="text-dark">{{ $setting->durasi_detik ?? 60 }} detik</strong></li>
                        <li>Refresh Otomatis: <strong class="text-dark">{{ !empty($setting->refresh_otomatis) ? 'Aktif' : 'Nonaktif' }}</strong></li>
                        <li>Validasi Lokasi: <strong class="text-dark">{{ !empty($setting->validasi_lokasi) ? 'Aktif (' . ($setting->radius_meter ?? 100) . ' m)' : 'Nonaktif' }}</strong></li>
                    </ul>
                </div>
            </div>
        </div>
    </form>

</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    new QRCode(document.getElementById("preview-qr"), {
        text: "ABSENSI-KAMPUS-PREVIEW",
        width: 180,
        height: 180,
        colorDark: "#0b2545",
        colorLight: "#ffffff",
        correctLevel: QRCode.CorrectLevel.H
    });
</script>
@endpush