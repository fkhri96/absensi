@extends('layouts.app')

@section('title', 'Pengaturan QR Code | Absensi Kampus')
@section('page_title', 'Pengaturan QR Code')
@section('role', 'admin')

@section('content')
<div class="container-fluid px-0">

    @if(session('success'))
        <div class="alert alert-success border-0 rounded-4 shadow-sm mb-4">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger border-0 rounded-4 shadow-sm mb-4">
            <ul class="mb-0 small">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-4">
        <!-- Kolom Kiri: Form Konfigurasi Durasi & Lokasi GPS -->
        <div class="col-lg-8">
            <div class="panel p-4 p-md-5 shadow-sm">
                <form action="{{ route('admin.qr.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- SECTION 1: KONFIGURASI DURASI & REFRESH QR -->
                    <div class="fw-bold text-primary small text-uppercase mb-3">
                        <i class="bi bi-qr-code-scan me-1"></i> KONFIGURASI DURASI & REFRESH QR
                    </div>

                    <div class="row g-3 align-items-center mb-4 pb-4 border-bottom">
                        <div class="col-md-6">
                            <label for="durasi_qr" class="form-label small fw-semibold">Durasi Berlaku QR (Detik)</label>
                            <div class="input-group">
                                <input type="number" id="durasi_qr" name="durasi_qr" class="form-control"
                                       min="10" max="7200"
                                       value="{{ old('durasi_qr', $setting->durasi_qr ?? 60) }}" required>
                                <span class="input-group-text">Detik</span>
                            </div>
                            <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">
                                Default sistem adalah 60 detik per siklus token.
                            </small>
                        </div>

                        <div class="col-md-6">
                            <div class="form-check form-switch mt-md-3">
                                <input class="form-check-input" type="checkbox" id="refresh_otomatis" name="refresh_otomatis" value="1"
                                       {{ old('refresh_otomatis', $setting->refresh_otomatis ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold small" for="refresh_otomatis">
                                    Aktifkan Refresh QR Otomatis
                                </label>
                                <small class="text-muted d-block" style="font-size: 0.75rem;">
                                    Kode QR berganti otomatis sesuai durasi detik.
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 2: VALIDASI LOKASI KAMPUS (GEOFENCING) -->
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                        <div class="fw-bold text-primary small text-uppercase">
                            <i class="bi bi-geo-alt-fill me-1"></i> VALIDASI LOKASI KAMPUS (GEOFENCING)
                        </div>

                        <!-- TOMBOL SEKALI KLIK AKSES LOKASI TERKINI -->
                        <button type="button" id="btn-lokasi-terkini" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                            <i class="bi bi-crosshair me-1"></i> Gunakan Lokasi Terkini
                        </button>
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="wajib_lokasi" name="wajib_lokasi" value="1"
                               {{ old('wajib_lokasi', $setting->wajib_lokasi ?? ($setting->wajib_gps ?? false)) ? 'checked' : '' }}>
                        <label class="form-check-label small fw-semibold" for="wajib_lokasi">
                            Wajibkan mahasiswa berada dalam radius titik koordinat kampus
                        </label>
                    </div>

                    <div class="row g-3 mb-2">
                        <div class="col-md-4">
                            <label for="input-latitude" class="form-label small fw-semibold">Latitude</label>
                            <input type="text" id="input-latitude" name="latitude" class="form-control"
                                   placeholder="Contoh: -7.536064"
                                   value="{{ old('latitude', $setting->latitude ?? '') }}">
                        </div>

                        <div class="col-md-4">
                            <label for="input-longitude" class="form-label small fw-semibold">Longitude</label>
                            <input type="text" id="input-longitude" name="longitude" class="form-control"
                                   placeholder="Contoh: 110.60205"
                                   value="{{ old('longitude', $setting->longitude ?? '') }}">
                        </div>

                        <div class="col-md-4">
                            <label for="input-radius" class="form-label small fw-semibold">Radius Maksimal</label>
                            <div class="input-group">
                                <input type="number" id="input-radius" name="radius_meter" class="form-control"
                                       min="10" max="5000"
                                       value="{{ old('radius_meter', $setting->radius_meter ?? 100) }}">
                                <span class="input-group-text">Meter</span>
                            </div>
                        </div>
                    </div>

                    <!-- Status GPS & Link Pratinjau Google Maps -->
                    <div id="info-gps-admin" class="small text-muted mb-4"></div>

                    <div class="text-end pt-3 border-top">
                        <button type="submit" class="btn btn-primary px-4 rounded-3 fw-semibold">
                            <i class="bi bi-save me-1"></i> Simpan Pengaturan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Kolom Kanan: Pratinjau QR Code & Ringkasan Status -->
        <div class="col-lg-4">
            <div class="panel p-4 shadow-sm text-center">
                <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2 mb-3">
                    Pratinjau QR Code
                </span>

                <div class="p-3 bg-white border rounded-4 d-inline-block shadow-sm mb-3">
                    <div id="preview-qr-admin" class="d-flex justify-content-center align-items-center" style="width: 190px; height: 190px;"></div>
                </div>

                <h6 class="fw-bold mb-3">Status Konfigurasi Tersimpan</h6>

                <div class="bg-light rounded-4 p-3 text-start small">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Durasi QR:</span>
                        <span class="fw-bold">{{ $setting->durasi_qr ?? 60 }} detik</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Refresh Otomatis:</span>
                        <span class="fw-bold {{ ($setting->refresh_otomatis ?? true) ? 'text-success' : 'text-danger' }}">
                            {{ ($setting->refresh_otomatis ?? true) ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Validasi Lokasi:</span>
                        @php $aktifLokasi = $setting->wajib_lokasi ?? ($setting->wajib_gps ?? false); @endphp
                        <span class="fw-bold {{ $aktifLokasi ? 'text-success' : 'text-danger' }}">
                            {{ $aktifLokasi ? 'Aktif (' . ($setting->radius_meter ?? 100) . 'm)' : 'Nonaktif' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Render Pratinjau QR Token Pendek
    const qrContainer = document.getElementById('preview-qr-admin');
    if (qrContainer && typeof QRCode !== 'undefined') {
        new QRCode(qrContainer, {
            text: "ABS-PREVIEW",
            width: 190,
            height: 190,
            colorDark: "#000000",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.L
        });
    }

    // 2. Logika Tombol "Gunakan Lokasi Terkini"
    const btnLokasi = document.getElementById('btn-lokasi-terkini');
    const inputLat = document.getElementById('input-latitude');
    const inputLng = document.getElementById('input-longitude');
    const switchWajib = document.getElementById('wajib_lokasi');
    const infoGps = document.getElementById('info-gps-admin');

    function tampilkanLinkMaps(lat, lng) {
        if (lat && lng) {
            infoGps.innerHTML = `
                <span class="text-success"><i class="bi bi-check-circle-fill me-1"></i>Titik koordinat siap disimpan.</span>
                <a href="https://www.google.com/maps?q=${lat},${lng}" target="_blank" class="ms-2 text-decoration-none fw-semibold">
                    <i class="bi bi-box-arrow-up-right"></i> Cek titik di Google Maps
                </a>
            `;
        }
    }

    tampilkanLinkMaps(inputLat.value, inputLng.value);

    btnLokasi.addEventListener('click', function () {
        if (!navigator.geolocation) {
            infoGps.innerHTML = '<span class="text-danger">Browser Anda tidak mendukung akses lokasi GPS.</span>';
            return;
        }

        const labelAwal = btnLokasi.innerHTML;
        btnLokasi.disabled = true;
        btnLokasi.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Mencari GPS...';
        infoGps.innerHTML = '<span class="text-primary">Sedang mendeteksi koordinat lokasi Anda saat ini...</span>';

        navigator.geolocation.getCurrentPosition(
            function (position) {
                const lat = position.coords.latitude.toFixed(6);
                const lng = position.coords.longitude.toFixed(6);

                inputLat.value = lat;
                inputLng.value = lng;

                if (switchWajib) {
                    switchWajib.checked = true;
                }

                tampilkanLinkMaps(lat, lng);
                btnLokasi.disabled = false;
                btnLokasi.innerHTML = '<i class="bi bi-check2-circle me-1"></i> Lokasi Terisi';
            },
            function (error) {
                let pesan = 'Gagal mengambil lokasi.';
                if (error.code === 1) {
                    pesan = 'Akses lokasi ditolak browser. Pastikan membuka lewat HTTPS/localhost dan izinkan Location.';
                } else if (error.code === 2) {
                    pesan = 'Sinyal GPS tidak tersedia. Aktifkan GPS perangkat.';
                } else if (error.code === 3) {
                    pesan = 'Waktu pencarian lokasi habis. Silakan klik ulang tombol.';
                }

                infoGps.innerHTML = `<span class="text-danger"><i class="bi bi-exclamation-triangle-fill me-1"></i>${pesan}</span>`;
                btnLokasi.disabled = false;
                btnLokasi.innerHTML = labelAwal;
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            }
        );
    });
});
</script>
@endpush