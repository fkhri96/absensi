@extends('layouts.app')

@section('title', 'QR Code Absensi | Absensi Kampus')
@section('page_title', 'QR Code Presensi')
@section('role', 'dosen')

@section('content')
@php
    $pengaturan = \Illuminate\Support\Facades\Schema::hasTable('pengaturan_lokasis')
        ? \App\Models\PengaturanLokasi::first()
        : null;
    $durasiDetik =$pengaturan->durasi_qr ?? 60;
    $autoRefresh =$pengaturan->refresh_otomatis ?? true;
    $urlScanLangsung = route('mahasiswa.absensi.scan_langsung', ['token' =>$sesi->token_qr ?? 'KOSONG']);
@endphp

<div class="container-fluid px-0">
    @if(session('success'))
        <div class="alert alert-success border-0 rounded-4 shadow-sm mb-4">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        </div>
    @endif

    <div class="row g-4">
        <!-- Kolom Kiri: Tampilan QR Code Super Tajam -->
        <div class="col-lg-5">
            <div class="panel p-4 shadow-sm text-center">
                <span class="badge rounded-pill {{ $sesi->status === 'buka' ? 'bg-success' : 'bg-danger' }} px-3 py-2 mb-3">
                    STATUS QR: {{ $sesi->status === 'buka' ? 'HIDUP (AKTIF)' : 'MATI (DITUTUP)' }}
                </span>

                <h5 class="fw-bold mb-1">{{ $sesi->jadwal->mataKuliah->nama_mk ?? 'Mata Kuliah' }}</h5>
                <p class="text-muted small mb-3">Pertemuan Ke-{{ $sesi->pertemuan_ke }} • {{$sesi->tanggal }}</p>

                @if($sesi->status === 'buka')
                    <!-- Bingkai putih luas (p-4) wajib ada agar kamera HP langsung mengunci QR -->
                    <div class="p-4 bg-white border border-2 rounded-4 d-inline-block shadow-sm mb-3">
                        <img id="img-qr-dosen"
                             src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&margin=10&ecc=L&data={{ urlencode($urlScanLangsung) }}"
                             alt="QR Code Presensi"
                             style="width: 260px; height: 260px; display: block;">
                    </div>

                    @if($autoRefresh)
                        <div class="mb-3">
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill">
                                <i class="bi bi-arrow-repeat me-1"></i> QR berganti dalam <strong id="qr-countdown">{{ $durasiDetik }}</strong> detik
                            </span>
                        </div>
                    @endif
                @else
                    <div class="p-5 bg-light border rounded-4 text-muted mb-3">
                        <i class="bi bi-qr-code display-1 opacity-25"></i>
                        <p class="mt-2 mb-0 small">QR Code sedang dimatikan.</p>
                    </div>
                @endif

                <div class="bg-light rounded-3 p-2 mb-3">
                    <small class="text-muted d-block">Kode Token Manual:</small>
                    <strong id="teks-token-manual" class="fs-4 text-primary user-select-all">{{ $sesi->token_qr ?? 'TOKEN KOSONG' }}</strong>
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
                    <div>
                        <h6 class="fw-bold mb-0">Mahasiswa Hadir (<span id="jumlah-hadir">{{ $sesi->absensis->count() }}</span>)</h6>
                        <small class="text-muted" style="font-size: 0.75rem;">
                            <i class="bi bi-circle-fill text-success me-1" style="font-size: 0.5rem;"></i> Diperbarui otomatis setiap 4 detik
                        </small>
                    </div>
                    <button type="button" onclick="window.location.reload()" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-arrow-clockwise me-1"></i> Refresh
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
                        <tbody id="tbody-absensi">
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

@if($sesi->status === 'buka')
<script>
document.addEventListener("DOMContentLoaded", function() {
    const imgQr = document.getElementById("img-qr-dosen");
    const teksTokenManual = document.getElementById("teks-token-manual");
    const countdownEl = document.getElementById("qr-countdown");
    const jumlahHadirEl = document.getElementById("jumlah-hadir");

    const baseUrlScan = "{{ url('/mahasiswa/absensi/scan-langsung') }}/";
    const autoRefresh = {{ $autoRefresh ? 'true' : 'false' }};
    const intervalDetik = {{ (int) $durasiDetik }};
    let sisaWaktu = intervalDetik;

    function updateGambarQR(tokenBaru) {
        if (!imgQr || !tokenBaru) return;
        const fullUrl = baseUrlScan + encodeURIComponent(tokenBaru);
        imgQr.src = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&margin=10&ecc=L&data=" + encodeURIComponent(fullUrl);
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
                teksTokenManual.textContent = data.token;
                updateGambarQR(data.token);
            }
        } catch (err) {}
    }

    async function refreshDaftarHadir() {
        try {
            const res = await fetch(window.location.href, {
                headers: { "X-Requested-With": "XMLHttpRequest" }
            });
            if (!res.ok) return;
            const doc = new DOMParser().parseFromString(await res.text(), "text/html");
            const newTbody = doc.getElementById("tbody-absensi");
            const newJumlah = doc.getElementById("jumlah-hadir");
            if (newTbody) document.getElementById("tbody-absensi").innerHTML = newTbody.innerHTML;
            if (newJumlah && jumlahHadirEl) jumlahHadirEl.textContent = newJumlah.textContent;
        } catch (e) {}
    }

    if (autoRefresh && countdownEl) {
        setInterval(function() {
            sisaWaktu--;
            if (sisaWaktu <= 0) {
                sisaWaktu = intervalDetik;
                mintaTokenBaru();
            }
            countdownEl.textContent = sisaWaktu;
        }, 1000);
    }

    setInterval(refreshDaftarHadir, 4000);
});
</script>
@endif
@endsection