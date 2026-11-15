@extends('layouts.app')

@section('title', 'Pengajuan Izin & Sakit | Absensi Kampus')
@section('page_title', 'Pengajuan Izin / Sakit Mahasiswa')
@section('role', 'mahasiswa')

@section('content')
<div class="container-fluid px-0">

    @if(session('success'))
        <div class="alert alert-success border-0 rounded-4 shadow-sm mb-4">
            <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
        </div>
    @endif

    <div class="row g-4">
        <!-- Kolom Kiri: Form Pengajuan -->
        <div class="col-lg-5">
            <div class="panel p-4 shadow-sm h-100">
                <h5 class="fw-bold mb-3">Buat Pengajuan Baru</h5>
                <p class="text-muted small mb-4">Ajukan izin atau keterangan sakit dengan melampirkan alasan dan bukti pendukung.</p>

                <form action="{{ route('mahasiswa.izin.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="jenis" class="form-label fw-semibold small">Jenis Pengajuan <span class="text-danger">*</span></label>
                        <select name="jenis" id="jenis" class="form-select" required>
                            <option value="izin">Izin</option>
                            <option value="sakit">Sakit</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="tanggal" class="form-label fw-semibold small">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal" id="tanggal" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="keterangan" class="form-label fw-semibold small">Keterangan / Alasan <span class="text-danger">*</span></label>
                        <textarea name="keterangan" id="keterangan" rows="3" class="form-control" placeholder="Tuliskan keterangan lengkap..." required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="bukti_file" class="form-label fw-semibold small">Bukti Pendukung (Opsional)</label>
                        <input type="file" name="bukti_file" id="bukti_file" class="form-control" accept="image/*">
                        <small class="text-muted" style="font-size: 0.75rem;">Format gambar (JPG, PNG), Maks. 2MB.</small>
                    </div>

                    <button type="submit" class="btn btn-brand w-100 py-3 fw-semibold rounded-3">
                        <i class="bi bi-send me-1"></i> Kirim Pengajuan
                    </button>
                </form>
            </div>
        </div>

        <!-- Kolom Kanan: Riwayat Pengajuan -->
        <div class="col-lg-7">
            <div class="panel p-4 shadow-sm h-100">
                <h5 class="fw-bold mb-3">Riwayat Pengajuan</h5>
                <p class="text-muted small mb-4">Status verifikasi pengajuan izin atau sakit yang pernah Anda kirimkan.</p>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Jenis</th>
                                <th>Keterangan</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($izins ?? [] as $index => $izin)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ \Carbon\Carbon::parse($izin->tanggal)->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="badge bg-secondary bg-opacity-10 text-dark text-uppercase px-2">
                                            {{ $izin->jenis }}
                                        </span>
                                    </td>
                                    <td>{{ Str::limit($izin->keterangan, 30) }}</td>
                                    <td class="text-center">
                                        @if($izin->status == 'disetujui')
                                            <span class="badge bg-success bg-opacity-10 text-success px-2">Disetujui</span>
                                        @elseif($izin->status == 'ditolak')
                                            <span class="badge bg-danger bg-opacity-10 text-danger px-2">Ditolak</span>
                                        @else
                                            <span class="badge bg-warning bg-opacity-10 text-warning px-2">Pending</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        <i class="bi bi-file-earmark-medical fs-3 d-block mb-2"></i>
                                        Belum ada riwayat pengajuan izin atau sakit.
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