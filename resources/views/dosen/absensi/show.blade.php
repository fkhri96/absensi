@extends('layouts.app')

@section('title', 'Sesi QR Aktif | Absensi Kampus')
@section('page_title', 'Kontrol Sesi Absensi')
@section('role', 'dosen')

@section('content')
<div class="container-fluid px-0">

    @if(session('success'))
        <div class="alert alert-success border-0 rounded-4 shadow-sm mb-4">
            <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
        </div>
    @endif

    <div class="row g-4">
        <!-- Kolom Kiri: Tampilan QR Code Dinamis & Countdown -->
        <div class="col-lg-5">
            <div class="panel p-4 shadow-sm text-center">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="badge bg-primary bg-opacity-10 text-primary">Pertemuan ke-{{ $sesi->pertemuan_ke }}</span>
                    @if($sesi->status === 'buka')
                        <span class="badge bg-success">Sesi Dibuka</span>
                    @else
                        <span class="badge bg-secondary">Sesi Ditutup</span>
                    @endif
                </div>

                <h5 class="fw-bold mb-1">{{ $sesi->jadwal->mataKuliah->nama_mk ?? 'Mata Kuliah' }}</h5>
                <p class="text-muted small mb-3">{{ $sesi->materi ?? 'Perkuliahan Reguler' }}</p>

                <!-- Wadah QR Code -->
                <div class="bg-light p-4 rounded-4 border d-inline-block mb-3">
                    <div id="qrcode"></div>
                </div>

                <div class="alert alert-warning py-2 small mb-3">
                    <i class="bi bi-clock me-1"></i> Berakhir pada: <strong>{{ \Carbon\Carbon::parse($sesi->jam_selesai)->format('H:i') }}</strong>
                </div>

                <!-- Tombol Tutup / Buka Sesi -->
                <form action="{{ route('dosen.absensi.update-status', $sesi->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    @if($sesi->status === 'buka')
                        <input type="hidden" name="status" value="tutup">
                        <button type="submit" class="btn btn-danger w-100 rounded-3 fw-semibold" onclick="return confirm('Tutup sesi absensi ini sekarang?')">
                            <i class="bi bi-stop-circle me-1"></i> Tutup Sesi QR
                        </button>
                    @else
                        <input type="hidden" name="status" value="buka">
                        <button type="submit" class="btn btn-success w-100 rounded-3 fw-semibold">
                            <i class="bi bi-play-circle me-1"></i> Buka Kembali Sesi QR
                        </button>
                    @endif
                </form>
            </div>
        </div>

        <!-- Kolom Kanan: Live Daftar Mahasiswa yang Berhasil Absen -->
        <div class="col-lg-7">
            <div class="panel p-4 shadow-sm">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0">Mahasiswa Tercatat Hadir</h6>
                    <span class="badge bg-primary rounded-pill px-3 py-1">{{ $sesi->absensis->count() }} Mahasiswa</span>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 small">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>NIM</th>
                                <th>Nama Mahasiswa</th>
                                <th>Waktu Scan</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sesi->absensis as $index => $absen)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td class="fw-semibold">{{ $absen->mahasiswa->nim ?? '-' }}</td>
                                    <td>{{ $absen->mahasiswa->user->name ?? '-' }}</td>
                                    <td>{{ $absen->waktu_absen ? \Carbon\Carbon::parse($absen->waktu_absen)->format('H:i:s') : '-' }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-success bg-opacity-10 text-success text-uppercase px-2">
                                            {{ $absen->status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        Belum ada mahasiswa yang melakukan scan QR.
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    const tokenQr = "{{ $sesi->token_qr }}";
    
    // Generate QR Code otomatis
    new QRCode(document.getElementById("qrcode"), {
        text: tokenQr,
        width: 200,
        height: 200,
        colorDark: "#0b2545",
        colorLight: "#ffffff",
        correctLevel: QRCode.CorrectLevel.H
    });

    // Auto-refresh halaman setiap 15 detik agar daftar mahasiswa yang hadir ter-update secara real-time
    setTimeout(function() {
        location.reload();
    }, 15000);
</script>
@endpush