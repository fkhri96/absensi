@extends('layouts.app')

@section('title', 'Notifikasi | Absensi Kampus')
@section('page_title', 'Notifikasi Mahasiswa')
@section('role', 'mahasiswa')

@section('content')
<div class="container-fluid px-0">

    <div class="panel p-4 shadow-sm">
        <h5 class="fw-bold mb-3">Pemberitahuan & Informasi Sistem</h5>
        <p class="text-muted small mb-4">Daftar informasi penting terkait jadwal kuliah, pengumuman presensi, dan status perizinan.</p>

        @php
            $mhsNotif = \App\Models\Mahasiswa::where('user_id', auth()->id())->first();
            $izinNotifs = $mhsNotif
                ? \App\Models\Perizinan::with('jadwal.mataKuliah')
                    ->where('mahasiswa_id', $mhsNotif->id)
                    ->whereIn('status', ['disetujui', 'ditolak'])
                    ->latest('updated_at')
                    ->take(10)
                    ->get()
                : collect();
        @endphp

        <div class="list-group list-group-flush">
            @foreach($izinNotifs as $notif)
                <div class="list-group-item px-0 py-3 d-flex align-items-start gap-3">
                    <span class="badge {{ $notif->status == 'disetujui' ? 'bg-success text-success' : 'bg-danger text-danger' }} bg-opacity-10 p-3 rounded-4 fs-5">
                        <i class="bi {{ $notif->status == 'disetujui' ? 'bi-check-circle-fill' : 'bi-x-circle-fill' }}"></i>
                    </span>
                    <div>
                        <h6 class="fw-bold mb-1">
                            Pengajuan {{ ucfirst($notif->jenis) }} {{ ucfirst($notif->status) }}
                        </h6>
                        <p class="text-muted small mb-1">
                            Surat {{ $notif->jenis }} Anda untuk mata kuliah <strong>{{ $notif->jadwal->mataKuliah->nama_mk ?? '-' }}</strong> tanggal {{ \Carbon\Carbon::parse($notif->tanggal)->format('d/m/Y') }} telah <strong>{{ $notif->status }}</strong> oleh dosen.
                        </p>
                        <small class="text-secondary" style="font-size: 0.75rem;">{{ $notif->updated_at->diffForHumans() }}</small>
                    </div>
                </div>
            @endforeach

            <div class="list-group-item px-0 py-3 d-flex align-items-start gap-3">
                <span class="badge bg-primary bg-opacity-10 text-primary p-3 rounded-4 fs-5">
                    <i class="bi bi-bell-fill"></i>
                </span>
                <div>
                    <h6 class="fw-bold mb-1">Selamat Datang di Sistem Absensi Kampus</h6>
                    <p class="text-muted small mb-1">Sistem presensi berbasis QR Code dan manajemen akademik mahasiswa telah aktif sepenuhnya.</p>
                    <small class="text-secondary" style="font-size: 0.75rem;">Hari ini</small>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection