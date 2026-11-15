@extends('layouts.app')

@section('title', 'Presensi & Riwayat | Absensi Kampus')
@section('page_title', 'Presensi & Riwayat Kehadiran')
@section('role', 'mahasiswa')

@section('content')
<div class="container-fluid px-0">

    @if(session('success'))
        <div class="alert alert-success border-0 rounded-4 shadow-sm mb-4">
            <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger border-0 rounded-4 shadow-sm mb-4">
            <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ session('error') }}
        </div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning border-0 rounded-4 shadow-sm mb-4">
            <i class="bi bi-info-circle-fill me-1"></i> {{ session('warning') }}
        </div>
    @endif

    <div class="row g-4">
        <!-- Kolom Kiri: Kamera Scanner QR & Input Manual -->
        <div class="col-lg-5">
            <div class="panel p-4 shadow-sm h-100">
                <div class="d-flex align-items-center gap-3 mb-3 pb-3 border-bottom">
                    <span class="badge bg-primary bg-opacity-10 text-primary p-3 rounded-4">
                        <i class="bi bi-qr-code-scan fs-4"></i>
                    </span>
                    <div>
                        <h5 class="fw-bold mb-1">Scan Kamera HP</h5>
                        <p class="text-muted small mb-0">Arahkan kamera ke QR Code dosen.</p>
                    </div>
                </div>

                <!-- Wadah Kamera Scanner -->
                <div id="reader" class="rounded-3 overflow-hidden border mb-3 bg-light" style="width: 100%; min-height: 250px;"></div>

                <!-- Form untuk mengirim token hasil scan / input manual -->
                <form action="{{ route('mahasiswa.absensi.store') }}" method="POST" id="form-absensi">
                    @csrf
                    <div class="mb-3">
                        <label for="token_qr" class="form-label fw-semibold small">Token QR / Hasil Scan <span class="text-danger">*</span></label>
                        <input type="text" id="token_qr" name="token_qr" class="form-control text-center font-monospace" placeholder="Akan terisi otomatis dari kamera atau ketik manual..." required>
                    </div>
                    <button type="submit" class="btn btn-brand w-100 py-3 fw-semibold rounded-3">
                        <i class="bi bi-check-circle me-1"></i> Kirim Presensi Kehadiran
                    </button>
                </form>
            </div>
        </div>

        <!-- Kolom Kanan: Riwayat Kehadiran Mahasiswa -->
        <div class="col-lg-7">
            <div class="panel p-4 shadow-sm h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0">Riwayat Kehadiran Terbaru</h6>
                    <a href="{{ route('mahasiswa.laporan.cetak') }}" target="_blank" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1">
                        <i class="bi bi-file-pdf"></i> Unduh Rekap PDF
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 small">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Mata Kuliah</th>
                                <th>Pertemuan</th>
                                <th>Waktu Scan</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $userAuth = auth()->user();
                                $mhs = $userAuth ? \App\Models\Mahasiswa::where('user_id', $userAuth->id)->first() : null;
                                $riwayatAbsen = $mhs ? \App\Models\Absensi::with(['sesiAbsensi.jadwal.mataKuliah'])
                                    ->where('mahasiswa_id', $mhs->id)
                                    ->latest()
                                    ->take(10)
                                    ->get() : collect();
                            @endphp

                            @forelse($riwayatAbsen as $index => $absen)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td class="fw-semibold">{{ optional(optional(optional($absen->sesiAbsensi)->jadwal)->mataKuliah)->nama_mk ?? '-' }}</td>
                                    <td>Pertemuan ke-{{ optional($absen->sesiAbsensi)->pertemuan_ke ?? '-' }}</td>
                                    <td>{{ $absen->created_at ? $absen->created_at->format('d/m/Y H:i') : '-' }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-success bg-opacity-10 text-success text-uppercase px-2">
                                            {{ $absen->status ?? 'hadir' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        Belum ada riwayat kehadiran yang tercatat.
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

@push('scripts')
<!-- Library HTML5 QR Code Scanner -->
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        function onScanSuccess(decodedText, decodedResult) {
            document.getElementById('token_qr').value = decodedText;
            if (typeof html5QrcodeScanner !== 'undefined') {
                html5QrcodeScanner.clear().then(_ => {
                    document.getElementById('form-absensi').submit();
                }).catch(error => {
                    document.getElementById('form-absensi').submit();
                });
            } else {
                document.getElementById('form-absensi').submit();
            }
        }

        function onScanFailure(error) {
            // Mengabaikan error pemindaian frame kosong agar console bersih
        }

        try {
            let html5QrcodeScanner = new Html5QrcodeScanner(
                "reader",
                { fps: 10, qrbox: { width: 250, height: 250 } },
                false
            );
            html5QrcodeScanner.render(onScanSuccess, onScanFailure);
        } catch (e) {
            console.error("Scanner kamera gagal diinisialisasi:", e);
        }
    });
</script>
@endpush