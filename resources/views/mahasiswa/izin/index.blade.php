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

    @if(session('error'))
        <div class="alert alert-danger border-0 rounded-4 shadow-sm mb-4">
            <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger border-0 rounded-4 shadow-sm mb-4">
            <ul class="mb-0 small">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-4">
        <!-- Form Pengajuan -->
        <div class="col-lg-5">
            <div class="panel p-4 shadow-sm h-100">
                <h5 class="fw-bold mb-2">Kirim Surat Izin / Sakit</h5>
                <p class="text-muted small mb-4">Surat akan langsung dikirim ke dosen yang mengajar pada mata kuliah & hari yang dipilih.</p>

                <form action="{{ route('mahasiswa.izin.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="jadwal_id" class="form-label fw-semibold small">Mata Kuliah & Dosen Pengampu <span class="text-danger">*</span></label>
                        <select name="jadwal_id" id="jadwal_id" class="form-select" required>
                            <option value="">-- Pilih Mata Kuliah / Jadwal --</option>
                            @foreach($jadwals ?? [] as $jadwal)
                                <option value="{{ $jadwal->id }}">
                                    {{ $jadwal->hari }} | {{ $jadwal->mataKuliah->nama_mk ?? '-' }} ({{ $jadwal->dosen->user->name ?? $jadwal->mataKuliah->dosen->user->name ?? 'Dosen' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label for="jenis" class="form-label fw-semibold small">Jenis <span class="text-danger">*</span></label>
                            <select name="jenis" id="jenis" class="form-select" required>
                                <option value="izin">Izin</option>
                                <option value="sakit">Sakit</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label for="tanggal" class="form-label fw-semibold small">Tanggal Kuliah <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal" id="tanggal" value="{{ date('Y-m-d') }}" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="keterangan" class="form-label fw-semibold small">Alasan / Keterangan <span class="text-danger">*</span></label>
                        <textarea name="keterangan" id="keterangan" rows="3" class="form-control" placeholder="Contoh: Sakit demam dan melampirkan surat dokter..." required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="bukti_file" class="form-label fw-semibold small">Upload Surat Izin / Surat Dokter</label>
                        <input type="file" name="bukti_file" id="bukti_file" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                        <small class="text-muted" style="font-size: 0.75rem;">Format JPG, PNG, atau PDF (Maks. 2MB).</small>
                    </div>

                    <button type="submit" class="btn btn-brand w-100 py-3 fw-semibold rounded-3">
                        <i class="bi bi-send me-1"></i> Kirim Surat ke Dosen
                    </button>
                </form>
            </div>
        </div>

        <!-- Riwayat Pengajuan -->
        <div class="col-lg-7">
            <div class="panel p-4 shadow-sm h-100">
                <h5 class="fw-bold mb-2">Riwayat Pengajuan Saya</h5>
                <p class="text-muted small mb-4">Anda dapat membatalkan pengajuan jika salah ketik selama status masih <strong>Menunggu</strong>.</p>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 small">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Mata Kuliah / Dosen</th>
                                <th>Jenis</th>
                                <th>Surat</th>
                                <th class="text-center">Status</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($izins ?? [] as $izin)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($izin->tanggal)->format('d/m/Y') }}</td>
                                    <td>
                                        <div class="fw-semibold">{{ $izin->jadwal->mataKuliah->nama_mk ?? '-' }}</div>
                                        <small class="text-muted">{{ $izin->dosen->user->name ?? '-' }}</small>
                                    </td>
                                    <td><span class="badge bg-secondary bg-opacity-10 text-dark text-uppercase">{{ $izin->jenis }}</span></td>
                                    <td>
                                        @if($izin->bukti)
                                            <a href="{{ asset('storage/' . $izin->bukti) }}" target="_blank" class="btn btn-sm btn-outline-primary py-0 px-2">
                                                <i class="bi bi-file-earmark-text"></i> Lihat
                                            </a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($izin->status == 'disetujui')
                                            <span class="badge bg-success bg-opacity-10 text-success">Disetujui</span>
                                        @elseif($izin->status == 'ditolak')
                                            <span class="badge bg-danger bg-opacity-10 text-danger">Ditolak</span>
                                        @else
                                            <span class="badge bg-warning bg-opacity-10 text-warning">Menunggu</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        @if($izin->status === 'pending')
                                            <form action="{{ route('mahasiswa.izin.destroy', $izin->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Batalkan pengajuan surat izin ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-2" title="Batalkan Pengajuan">
                                                    <i class="bi bi-x-circle"></i> Batalkan
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-muted fst-italic">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">Belum ada pengajuan izin.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if(method_exists($izins, 'links'))
                    <div class="mt-3">{{ $izins->links() }}</div>
                @endif
            </div>
        </div>
    </div>

</div>
@endsection