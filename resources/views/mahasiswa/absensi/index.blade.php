@extends('layouts.app')

@section('title', 'Scan QR Absensi | Absensi Kampus')
@section('page_title', 'Scan QR Presensi')
@section('role', 'mahasiswa')

@section('content')
<div class="container-fluid px-0">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">

            @if(session('success'))
                <div class="alert alert-success border-0 rounded-4 shadow-sm mb-3">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger border-0 rounded-4 shadow-sm mb-3">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                </div>
            @endif

            @if(session('warning'))
                <div class="alert alert-warning border-0 rounded-4 shadow-sm mb-3">
                    <i class="bi bi-info-circle-fill me-2"></i>{{ session('warning') }}
                </div>
            @endif

            <div class="panel p-4 shadow-sm text-center">
                <h5 class="fw-bold mb-1">Scan QR Code Dosen</h5>
                <p class="text-muted small mb-2">Arahkan kamera ke layar dosen. Presensi otomatis masuk saat QR terdeteksi.</p>

                <!-- Status GPS -->
                <div id="status-gps" class="badge bg-secondary-subtle text-secondary mb-3 px-3 py-2 rounded-pill">
                    <i class="bi bi-geo-alt me-1"></i> Mendeteksi lokasi GPS...
                </div>

                <!-- Tombol Kamera Belakang & Depan -->
                <div class="btn-group w-100 mb-3" role="group">
                    <button type="button" id="btn-cam-back" class="btn btn-primary btn-sm">
                        <i class="bi bi-camera-rear-fill me-1"></i> Kamera Belakang
                    </button>
                    <button type="button" id="btn-cam-front" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-camera-front-fill me-1"></i> Kamera Depan
                    </button>
                </div>

                <!-- Area Kamera -->
                <div class="position-relative rounded-4 overflow-hidden border bg-dark mb-3 mx-auto" style="width: 100%; max-width: 400px; aspect-ratio: 1 / 1;">
                    <video id="camera-video" playsinline autoplay muted style="width: 100%; height: 100%; object-fit: cover;"></video>
                    <canvas id="scan-canvas" class="d-none"></canvas>
                    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 230px; height: 230px; border: 3px solid #22c55e; border-radius: 18px; pointer-events: none; box-shadow: 0 0 0 9999px rgba(0,0,0,0.35);"></div>
                </div>

                <div id="status-kamera" class="small text-muted mb-3">Menyalakan kamera...</div>

                <!-- Form Utama (Auto-Submit) -->
                <form id="form-scan-qr" action="{{ route('mahasiswa.absensi.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="token_qr" id="input-token-qr">
                    <input type="hidden" name="latitude" id="input-latitude">
                    <input type="hidden" name="longitude" id="input-longitude">
                </form>

                <!-- Cadangan Input Token Manual & Foto Kamera HP -->
                <div class="bg-light rounded-4 p-3 text-start">
                    <label class="form-label small fw-bold text-dark mb-1">
                        <i class="bi bi-keyboard me-1 text-primary"></i> Ketik Kode Token Manual:
                    </label>
                    <form action="{{ route('mahasiswa.absensi.store') }}" method="POST" class="d-flex gap-2 mb-3">
                        @csrf
                        <input type="hidden" name="latitude" id="manual-latitude">
                        <input type="hidden" name="longitude" id="manual-longitude">
                        <input type="text" name="token_qr" class="form-control form-control-sm text-uppercase fw-bold" placeholder="Ketik token di layar dosen" required>
                        <button type="submit" class="btn btn-primary btn-sm px-3 text-nowrap">Absen</button>
                    </form>

                    <label for="qr-input-file" class="form-label small fw-bold text-dark mb-1">
                        <i class="bi bi-camera me-1 text-primary"></i> Atau foto QR pakai kamera bawaan HP:
                    </label>
                    <input type="file" id="qr-input-file" class="form-control form-control-sm" accept="image/*" capture="environment">
                    <div id="status-upload-qr" class="small mt-1"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const video = document.getElementById('camera-video');
    const canvas = document.getElementById('scan-canvas');
    const ctx = canvas.getContext('2d', { willReadFrequently: true });

    const statusText = document.getElementById('status-kamera');
    const statusGps = document.getElementById('status-gps');
    const btnCamBack = document.getElementById('btn-cam-back');
    const btnCamFront = document.getElementById('btn-cam-front');
    const formScan = document.getElementById('form-scan-qr');
    const inputToken = document.getElementById('input-token-qr');
    const fileInput = document.getElementById('qr-input-file');
    const statusUpload = document.getElementById('status-upload-qr');

    let currentStream = null;
    let scanTimer = null;
    let sudahTerbaca = false;

    // 1. Ambil GPS Mahasiswa
    if (navigator.geolocation) {
        navigator.geolocation.watchPosition(
            (pos) => {
                const lat = pos.coords.latitude;
                const lng = pos.coords.longitude;
                document.getElementById('input-latitude').value = lat;
                document.getElementById('input-longitude').value = lng;
                document.getElementById('manual-latitude').value = lat;
                document.getElementById('manual-longitude').value = lng;

                statusGps.className = "badge bg-success-subtle text-success mb-3 px-3 py-2 rounded-pill";
                statusGps.innerHTML = `<i class="bi bi-geo-alt-fill me-1"></i> GPS Terkunci (${lat.toFixed(5)}, ${lng.toFixed(5)})`;
            },
            () => {
                statusGps.className = "badge bg-warning-subtle text-warning mb-3 px-3 py-2 rounded-pill";
                statusGps.innerHTML = `<i class="bi bi-exclamation-triangle me-1"></i> GPS tidak aktif / izin ditolak`;
            },
            { enableHighAccuracy: true, maximumAge: 10000 }
        );
    }

    function submitHasilScan(rawText) {
        if (sudahTerbaca || !rawText) return;
        sudahTerbaca = true;

        if (navigator.vibrate) navigator.vibrate(200);
        if (scanTimer) clearInterval(scanTimer);

        // Jika hasil scan berupa URL penuh, ambil bagian akhirnya sebagai token; controller juga otomatis mem-parsingnya
        inputToken.value = rawText.trim();
        statusText.innerHTML = `<span class="text-success fw-bold"><i class="bi bi-check-circle-fill me-1"></i>QR Terdeteksi! Menyimpan presensi...</span>`;

        setTimeout(() => formScan.submit(), 100);
    }

    // 2. Dual-Engine Scanner (Hardware BarcodeDetector + jsQR)
    async function mulaiPemindaian() {
        if (scanTimer) clearInterval(scanTimer);

        let nativeDetector = null;
        if ('BarcodeDetector' in window) {
            try {
                nativeDetector = new BarcodeDetector({ formats: ['qr_code'] });
            } catch (e) {}
        }

        scanTimer = setInterval(async () => {
            if (sudahTerbaca || video.readyState !== video.HAVE_ENOUGH_DATA) return;

            // Mesin A: Native Hardware Detector (Super cepat di Android/Chrome)
            if (nativeDetector) {
                try {
                    const barcodes = await nativeDetector.detect(video);
                    if (barcodes.length > 0 && barcodes[0].rawValue) {
                        submitHasilScan(barcodes[0].rawValue);
                        return;
                    }
                } catch (e) {}
            }

            // Mesin B: jsQR Canvas (Cadangan universal semua browser)
            const w = 500;
            const h = Math.round(video.videoHeight * (w / video.videoWidth));
            canvas.width = w;
            canvas.height = h;
            ctx.drawImage(video, 0, 0, w, h);

            const imageData = ctx.getImageData(0, 0, w, h);
            const code = jsQR(imageData.data, w, h, { inversionAttempts: "attemptBoth" });
            if (code && code.data) {
                submitHasilScan(code.data);
            }
        }, 150);
    }

    // 3. Kontrol Kamera Depan & Belakang
    async function bukaKamera(mode = "environment") {
        btnCamBack.className = mode === "environment" ? "btn btn-primary btn-sm" : "btn btn-outline-primary btn-sm";
        btnCamFront.className = mode === "user" ? "btn btn-primary btn-sm" : "btn btn-outline-primary btn-sm";
        video.style.transform = (mode === "user") ? "scaleX(-1)" : "scaleX(1)";

        if (currentStream) {
            currentStream.getTracks().forEach(t => t.stop());
        }

        statusText.innerHTML = '<span class="text-primary">Menghubungkan ke kamera...</span>';

        try {
            currentStream = await navigator.mediaDevices.getUserMedia({
                video: { facingMode: { ideal: mode }, width: { ideal: 1280 }, height: { ideal: 720 } },
                audio: false
            });
            video.srcObject = currentStream;
            await video.play();

            statusText.innerHTML = `<span class="text-success fw-semibold">${mode === "environment" ? "Kamera Belakang" : "Kamera Depan"} aktif. Arahkan ke QR Code.</span>`;
            mulaiPemindaian();
        } catch (err) {
            if (mode === "environment") {
                bukaKamera("user");
            } else {
                statusText.innerHTML = '<span class="text-danger">Gagal membuka kamera. Gunakan foto kamera HP atau input token manual di bawah.</span>';
            }
        }
    }

    btnCamBack.addEventListener('click', () => bukaKamera("environment"));
    btnCamFront.addEventListener('click', () => bukaKamera("user"));
    bukaKamera("environment");

    // 4. Scan dari Foto Kamera Bawaan HP
    fileInput.addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (!file) return;

        statusUpload.innerHTML = '<span class="text-primary">Membaca QR dari foto...</span>';
        const img = new Image();
        img.onload = function () {
            const maxDim = 900;
            let w = img.width, h = img.height;
            if (w > maxDim || h > maxDim) {
                if (w > h) { h = Math.round(h * maxDim / w); w = maxDim; }
                else { w = Math.round(w * maxDim / h); h = maxDim; }
            }
            canvas.width = w;
            canvas.height = h;
            ctx.drawImage(img, 0, 0, w, h);

            const imageData = ctx.getImageData(0, 0, w, h);
            const code = jsQR(imageData.data, w, h, { inversionAttempts: "attemptBoth" });
            if (code && code.data) {
                submitHasilScan(code.data);
            } else {
                statusUpload.innerHTML = '<span class="text-danger">QR tidak terbaca di foto. Pastikan fokus jelas.</span>';
                fileInput.value = '';
            }
        };
        img.src = URL.createObjectURL(file);
    });
});
</script>
@endpush