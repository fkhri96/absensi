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
                <p class="text-muted small mb-3">Arahkan kamera HP ke QR Code di layar dosen atau unggah gambar QR.</p>

                <!-- Pilihan Kamera -->
                <div class="mb-3 text-start">
                    <select id="camera-select" class="form-select form-select-sm"></select>
                </div>

                <!-- Kotak Kamera -->
                <div id="reader" class="rounded-4 overflow-hidden border bg-dark mb-3" style="width: 100%; min-height: 280px;"></div>

                <div id="status-kamera" class="small text-muted mb-3">Memuat kamera...</div>

                <!-- Form Utama (Mengirim token_qr hasil scan kamera maupun upload gambar) -->
                <form id="form-scan-qr" action="{{ route('mahasiswa.absensi.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="token_qr" id="input-token-qr">
                </form>

                <hr class="my-4">

                <!-- OPSI UPLOAD GAMBAR QR CODE -->
                <div class="text-start">
                    <label for="qr-input-file" class="form-label small fw-semibold text-muted">
                        Atau pilih / unggah gambar QR Code dari galeri:
                    </label>
                    <input type="file" id="qr-input-file" class="form-control" accept="image/*">
                    <div id="status-upload-qr" class="small mt-2"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const html5QrCode = new Html5Qrcode("reader");
    const cameraSelect = document.getElementById('camera-select');
    const statusText = document.getElementById('status-kamera');
    const formScan = document.getElementById('form-scan-qr');
    const inputToken = document.getElementById('input-token-qr');

    // Elemen Upload File QR
    const fileInput = document.getElementById('qr-input-file');
    const statusUpload = document.getElementById('status-upload-qr');

    let sudahTerbaca = false;
    let kameraAktif = false;

    function kirimTokenAbsensi(decodedText) {
        if (sudahTerbaca) return;
        sudahTerbaca = true;

        inputToken.value = decodedText.trim();

        if (kameraAktif) {
            html5QrCode.stop().finally(() => {
                formScan.submit();
            });
        } else {
            formScan.submit();
        }
    }

    function onScanSuccess(decodedText) {
        statusText.innerHTML = '<span class="text-success fw-bold">QR Terbaca! Menyimpan presensi...</span>';
        kirimTokenAbsensi(decodedText);
    }

    function jalankanKamera(cameraId) {
        html5QrCode.start(
            cameraId,
            {
                fps: 15,
                qrbox: { width: 240, height: 240 },
                aspectRatio: 1.0
            },
            onScanSuccess,
            () => {}
        ).then(() => {
            kameraAktif = true;
            statusText.innerText = 'Kamera aktif. Silakan arahkan ke QR Code.';
        }).catch(() => {
            kameraAktif = false;
            statusText.innerHTML = '<span class="text-danger">Kamera tidak aktif. Gunakan upload gambar QR di bawah.</span>';
        });
    }

    // Helper: Resize gambar besar dari kamera HP agar decoder QR mudah membacanya
    function optimalkanGambarUntukScan(file) {
        return new Promise((resolve) => {
            const img = new Image();
            img.onload = function () {
                const maxDim = 1000; // Batasi maksimal 1000px agar tajam tapi tidak terlalu berat
                let width = img.width;
                let height = img.height;

                if (width <= maxDim && height <= maxDim) {
                    resolve(file);
                    return;
                }

                if (width > height) {
                    height = Math.round((height * maxDim) / width);
                    width = maxDim;
                } else {
                    width = Math.round((width * maxDim) / height);
                    height = maxDim;
                }

                const canvas = document.createElement('canvas');
                canvas.width = width;
                canvas.height = height;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0, width, height);

                canvas.toBlob((blob) => {
                    resolve(new File([blob], file.name, { type: 'image/jpeg' }));
                }, 'image/jpeg', 0.92);
            };
            img.src = URL.createObjectURL(file);
        });
    }

    // Proses Scan File Gambar yang Di-upload
    fileInput.addEventListener('change', async function (e) {
        const file = e.target.files[0];
        if (!file) return;

        statusUpload.innerHTML = '<span class="text-primary"><i class="bi bi-hourglass-split me-1"></i>Membaca kode QR dari gambar...</span>';

        try {
            // Hentikan kamera sementara karena html5QrCode tidak bisa scan kamera & file bersamaan
            if (kameraAktif) {
                await html5QrCode.stop();
                kameraAktif = false;
            }

            const fileOptimal = await optimalkanGambarUntukScan(file);
            const decodedText = await html5QrCode.scanFile(fileOptimal, false);

            statusUpload.innerHTML = '<span class="text-success fw-bold"><i class="bi bi-check-circle me-1"></i>QR Terbaca! Menyimpan presensi...</span>';
            kirimTokenAbsensi(decodedText);

        } catch (err) {
            statusUpload.innerHTML = '<span class="text-danger"><i class="bi bi-x-circle me-1"></i>Kode QR tidak ditemukan pada gambar. Pastikan gambar jelas, tidak buram, dan tidak terpotong.</span>';
            fileInput.value = '';

            // Nyalakan kembali kamera jika sebelumnya aktif
            if (cameraSelect.value) {
                jalankanKamera(cameraSelect.value);
            }
        }
    });

    Html5Qrcode.getCameras().then(devices => {
        if (devices && devices.length > 0) {
            devices.forEach((device, i) => {
                const opt = document.createElement('option');
                opt.value = device.id;
                opt.text = device.label || `Kamera ${i + 1}`;
                cameraSelect.appendChild(opt);
            });

            const kameraBelakang = devices.find(d =>
                d.label.toLowerCase().includes('back') ||
                d.label.toLowerCase().includes('rear') ||
                d.label.toLowerCase().includes('belakang') ||
                d.label.toLowerCase().includes('environment')
            );

            if (kameraBelakang) {
                cameraSelect.value = kameraBelakang.id;
            } else if (devices.length > 1) {
                cameraSelect.value = devices[devices.length - 1].id;
            }

            jalankanKamera(cameraSelect.value);
        } else {
            statusText.innerHTML = '<span class="text-danger">Kamera tidak ditemukan. Silakan unggah gambar QR di bawah.</span>';
        }
    }).catch(() => {
        statusText.innerHTML = '<span class="text-danger">Akses kamera diblokir. Silakan unggah gambar QR di bawah.</span>';
    });

    cameraSelect.addEventListener('change', function () {
        if (kameraAktif) {
            html5QrCode.stop().then(() => {
                jalankanKamera(this.value);
            }).catch(() => {
                jalankanKamera(this.value);
            });
        } else {
            jalankanKamera(this.value);
        }
    });
});
</script>
@endpush