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
        <!-- Kolom Kiri: Tampilan QR Code Dinamis -->
        <div class="col-lg-5">
            <div class="panel p-4 shadow-sm text-center">
                <span class="badge rounded-pill {{ $sesi->status === 'buka' ? 'bg-success' : 'bg-danger' }} px-3 py-2 mb-3">
                    STATUS QR: {{ $sesi->status === 'buka' ? 'HIDUP (AKTIF)' : 'MATI (DITUTUP)' }}
                </span>

                <h5 class="fw-bold mb-1">{{ $sesi->jadwal->mataKuliah->nama_mk ?? 'Mata Kuliah' }}</h5>
                <p class="text-muted small mb-3">Pertemuan Ke-{{ $sesi->pertemuan_ke }} • {{ $sesi->tanggal }}</p>

                @if($sesi->status === 'buka')
                    <div class="p-3 bg-white border rounded-4 d-inline-block shadow-sm mb-2">
                        <div id="qrcode-container" class="d-flex justify-content-center align-items-center" style="width: 260px; height: 260px;">
                            <div class="spinner-border text-primary" role="status" id="qr-loading">
                                <span class="visually-hidden">Memuat QR...</span>
                            </div>
                        </div>
                    </div>

                    <!-- Indikator Countdown Pergantian Token -->
                    <div class="mb-3">
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill">
                            <i class="bi bi-arrow-repeat me-1"></i> QR berganti otomatis dalam <strong id="qr-countdown">15</strong> detik
                        </span>
                    </div>
                @else
                    <div class="p-5 bg-light border rounded-4 text-muted mb-3">
                        <i class="bi bi-qr-code display-1 opacity-25"></i>
                        <p class="mt-2 mb-0 small">QR Code sedang dimatikan.</p>
                    </div>
                @endif

                <div class="bg-light rounded-3 p-2 mb-3">
                    <small class="text-muted d-block">Kode Token Saat Ini:</small>
                    <strong id="teks-token-manual" class="fs-5 text-primary user-select-all">{{ $sesi->token_qr ?? 'TOKEN KOSONG' }}</strong>
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
                    <h6 class="fw-bold mb-0">Mahasiswa Hadir (<span id="jumlah-hadir">{{ $sesi->absensis->count() }}</span>)</h6>
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
                            @forelse($sesi->absensis as $index => $absen)
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

@if($sesi->status === 'buka')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const container = document.getElementById("qrcode-container");
            const teksTokenManual = document.getElementById("teks-token-manual");
            const countdownEl = document.getElementById("qr-countdown");
            const jumlahHadirEl = document.getElementById("jumlah-hadir");

            const intervalDetik = 9000; // Ganti token setiap 15 detik
            let sisaWaktu = intervalDetik;
            let currentToken = "{{ $sesi->token_qr }}";

            function renderQRCode(tokenText) {
                container.innerHTML = ""; // Bersihkan QR lama
                if (!tokenText) {
                    container.innerHTML = '<small class="text-danger">Error: Token QR kosong.</small>';
                    return;
                }

                if (typeof QRCode !== "undefined") {
                    new QRCode(container, {
                        text: tokenText,
                        width: 260,
                        height: 260,
                        colorDark: "#000000",
                        colorLight: "#ffffff",
                        correctLevel: QRCode.CorrectLevel.H
                    });
                } else {
                    container.innerHTML = `<img src="https://quickchart.io/qr?size=260&text=${encodeURIComponent(tokenText)}" alt="QR Code Absensi" class="img-fluid" style="width:260px; height:260px;">`;
                }
            }

            async function mintaTokenBaru() {
                try {
                    const response = await fetch("{{ route('dosen.absensi.refresh_token', $sesi->id) }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Accept": "application/json"
                        }
                    });

                    if (!response.ok) return;

                    const data = await response.json();
                    if (data.status === "ok" && data.token) {
                        currentToken = data.token;
                        teksTokenManual.textContent = currentToken;
                        renderQRCode(currentToken);

                        if (jumlahHadirEl && data.jumlah_hadir !== undefined) {
                            jumlahHadirEl.textContent = data.jumlah_hadir;
                        }
                    }
                } catch (err) {
                    console.error("Gagal memperbarui token QR:", err);
                }
            }

            // Gambar QR pertama kali
            renderQRCode(currentToken);

            // Timer otomatis setiap 1 detik
            setInterval(function() {
                sisaWaktu--;
                if (sisaWaktu <= 0) {
                    sisaWaktu = intervalDetik;
                    mintaTokenBaru();
                }
                countdownEl.textContent = sisaWaktu;
            }, 1000);
        });
    </script>
@endif
@endsection