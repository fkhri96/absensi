@extends('layouts.app')

@section('title', 'Surat Izin Mahasiswa | Absensi Kampus')
@section('page_title', 'Daftar Surat Izin / Sakit Mahasiswa')
@section('role', 'dosen')

@section('content')
<div class="container-fluid px-0">

    <div class="panel p-4 shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="fw-bold mb-1">Surat Izin & Sakit Masuk</h5>
                <p class="text-muted small mb-0">Daftar pengajuan izin mahasiswa pada mata kuliah yang Anda ampu.</p>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 small">
                <thead class="table-light">
                    <tr>
                        <th>Tanggal</th>
                        <th>Mahasiswa</th>
                        <th>Mata Kuliah</th>
                        <th>Jenis & Alasan</th>
                        <th>Lampiran Surat</th>
                        <th class="text-center">Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($perizinans ?? [] as $izin)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($izin->tanggal)->format('d/m/Y') }}</td>
                            <td>
                                <div class="fw-semibold">{{ $izin->mahasiswa->nama ?? $izin->mahasiswa->user->name ?? '-' }}</div>
                                <small class="text-muted">NIM: {{ $izin->mahasiswa->nim ?? '-' }}</small>
                            </td>
                            <td>{{ $izin->jadwal->mataKuliah->nama_mk ?? '-' }}</td>
                            <td>
                                <span class="badge bg-info bg-opacity-10 text-info text-uppercase mb-1">{{ $izin->jenis }}</span>
                                <div class="text-muted">{{ $izin->keterangan }}</div>
                            </td>
                            <td>
                                @if($izin->bukti)
                                    <a href="{{ asset('storage/' . $izin->bukti) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-file-earmark-pdf"></i> Buka Surat
                                    </a>
                                @else
                                    <span class="text-muted">Tanpa lampiran</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($izin->status == 'disetujui')
                                    <span class="badge bg-success bg-opacity-10 text-success">Disetujui</span>
                                @elseif($izin->status == 'ditolak')
                                    <span class="badge bg-danger bg-opacity-10 text-danger">Ditolak</span>
                                @else
                                    <span class="badge bg-warning bg-opacity-10 text-warning">Pending</span>
                                @endif
                            </td>
                            <td class="text-end">
                                @if($izin->status == 'pending')
                                    <div class="d-inline-flex gap-1">
                                        <form action="{{ route('dosen.izin.update', $izin->id) }}" method="POST" class="form-izin-setuju">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="disetujui">
                                            <button type="submit" class="btn btn-sm btn-success" title="Setujui">
                                                <i class="bi bi-check-lg"></i> Setujui
                                            </button>
                                        </form>
                                        <form action="{{ route('dosen.izin.update', $izin->id) }}" method="POST" class="form-izin-tolak">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="ditolak">
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Tolak">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-muted fst-italic">Selesai</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">Belum ada surat izin masuk dari mahasiswa.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('submit', function (e) {
    const form = e.target;

    if (form && form.classList.contains('form-izin-setuju')) {
        e.preventDefault();
        Swal.fire({
            title: 'Setujui pengajuan izin?',
            text: 'Status kehadiran mahasiswa akan dicatat sebagai izin/sakit.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Setujui',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) form.submit();
        });
    }

    if (form && form.classList.contains('form-izin-tolak')) {
        e.preventDefault();
        Swal.fire({
            title: 'Tolak pengajuan izin?',
            text: 'Pengajuan surat izin mahasiswa ini akan ditolak.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Tolak',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) form.submit();
        });
    }
});
</script>
@endpush