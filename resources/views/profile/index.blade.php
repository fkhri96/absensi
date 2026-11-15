@extends('layouts.app')

@section('title', 'Profil Saya | Absensi Kampus')
@section('page_title', 'Profil Pengguna')
@section('role', auth()->user()->role ?? 'mahasiswa')

@section('content')
<div class="container-fluid px-0">

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h4 class="fw-bold mb-1">Pengaturan Profil & Foto</h4>
            <p class="text-muted small mb-0">Perbarui foto profil, informasi kontak, dan kata sandi akun Anda.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 rounded-3 shadow-sm mb-4">
            <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger border-0 rounded-3 shadow-sm mb-4">
            <ul class="mb-0 small">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-4">
        <!-- Kolom Kiri: Kartu Foto Profil -->
        <div class="col-lg-4">
            <div class="panel p-4 shadow-sm text-center">
                @php
                    $fotoUrl = $user->foto
                        ? asset('storage/' . $user->foto)
                        : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=0b2545&color=ffffff&size=200';
                @endphp

                <div class="position-relative d-inline-block mb-3">
                    <img id="preview-avatar"
                         src="{{ $fotoUrl }}"
                         alt="Foto Profil {{ $user->name }}"
                         class="rounded-circle border border-3 border-light shadow-sm object-fit-cover"
                         style="width: 140px; height: 140px;">
                </div>

                <h5 class="fw-bold mb-1">{{ $user->name }}</h5>
                <span class="badge bg-primary bg-opacity-10 text-primary text-uppercase px-3 py-1 mb-3">
                    {{ $user->role ?? 'User' }}
                </span>

                <div class="text-start bg-light rounded-3 p-3 small d-grid gap-2 mb-3">
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Email:</span>
                        <strong class="text-dark">{{ $user->email }}</strong>
                    </div>

                    @if($dosen)
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">NIDN:</span>
                            <strong class="text-dark">{{ $dosen->nidn ?? '-' }}</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Jabatan:</span>
                            <strong class="text-dark">{{ $dosen->jabatan_fungsional ?? '-' }}</strong>
                        </div>
                    @endif

                    @if($mahasiswa)
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">NIM:</span>
                            <strong class="text-dark">{{ $mahasiswa->nim ?? '-' }}</strong>
                        </div>
                    @endif
                </div>

                @if($user->foto)
                    <form action="{{ route('profile.foto.destroy') }}" method="POST" onsubmit="return confirm('Hapus foto profil saat ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                            <i class="bi bi-trash me-1"></i> Hapus Foto Profil
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Kolom Kanan: Form Edit Profil & Upload Foto -->
        <div class="col-lg-8">
            <div class="panel p-4 p-md-5 shadow-sm">
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <h6 class="fw-bold text-uppercase text-muted small mb-3 pb-2 border-bottom">
                        <i class="bi bi-camera text-primary me-1"></i> Unggah Foto Profil
                    </h6>

                    <div class="mb-4">
                        <label for="foto" class="form-label fw-semibold small">Pilih File Foto <span class="text-muted fw-normal">(JPG, PNG, WEBP • Maks. 2MB)</span></label>
                        <input type="file" id="foto" name="foto" accept="image/png,image/jpeg,image/webp"
                               class="form-control @error('foto') is-invalid @enderror">
                        @error('foto')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <h6 class="fw-bold text-uppercase text-muted small mb-3 pb-2 border-bottom">
                        <i class="bi bi-person-lines-fill text-primary me-1"></i> Informasi Akun
                    </h6>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="name" class="form-label fw-semibold small">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" id="name" name="name"
                                   value="{{ old('name', $user->name) }}"
                                   class="form-control @error('name') is-invalid @enderror" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label fw-semibold small">Alamat Email <span class="text-danger">*</span></label>
                            <input type="email" id="email" name="email"
                                   value="{{ old('email', $user->email) }}"
                                   class="form-control @error('email') is-invalid @enderror" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @if($dosen || $mahasiswa)
                        <div class="col-md-6">
                            <label for="no_hp" class="form-label fw-semibold small">Nomor HP / WhatsApp</label>
                            <input type="text" id="no_hp" name="no_hp"
                                   value="{{ old('no_hp', $dosen->no_hp ?? $mahasiswa->no_hp ?? '') }}"
                                   class="form-control @error('no_hp') is-invalid @enderror"
                                   placeholder="Contoh: 081234567890">
                            @error('no_hp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        @endif
                    </div>

                    <h6 class="fw-bold text-uppercase text-muted small mb-3 pb-2 border-bottom">
                        <i class="bi bi-shield-lock text-primary me-1"></i> Ubah Kata Sandi <span class="text-muted fw-normal text-lowercase">(kosongkan jika tidak ingin mengganti)</span>
                    </h6>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="password" class="form-label fw-semibold small">Password Baru</label>
                            <input type="password" id="password" name="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="Minimal 6 karakter">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label fw-semibold small">Konfirmasi Password Baru</label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                   class="form-control"
                                   placeholder="Ulangi password baru">
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-brand d-inline-flex align-items-center gap-2">
                            <i class="bi bi-save2"></i> Simpan Perubahan Profil
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    document.getElementById('foto').addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (file) {
            document.getElementById('preview-avatar').src = URL.createObjectURL(file);
        }
    });
</script>
@endpush