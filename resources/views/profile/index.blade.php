@extends('layouts.app')

@section('title', 'Profil Saya | Absensi Kampus')
@section('page_title', 'Profil Saya')
@section('role', auth()->user()->role ?? 'mahasiswa')

@push('styles')
<style>
    .profile-cover {
        height: 135px;
        background: linear-gradient(120deg, var(--navy) 0%, var(--blue) 65%, var(--mint) 100%);
        border-radius: 18px 18px 0 0;
        position: relative;
    }
    .avatar-wrapper {
        position: relative;
        width: 132px;
        height: 132px;
        margin: -66px auto 14px;
    }
    .avatar-wrapper img {
        width: 132px;
        height: 132px;
        object-fit: cover;
        border-radius: 50%;
        border: 4px solid #fff;
        box-shadow: 0 8px 24px rgba(11, 37, 69, 0.16);
        background: #fff;
    }
    .btn-camera-badge {
        position: absolute;
        bottom: 4px;
        right: 4px;
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: var(--blue);
        color: #fff;
        border: 3px solid #fff;
        display: grid;
        place-items: center;
        cursor: pointer;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.18);
        transition: transform .15s, background .15s;
    }
    .btn-camera-badge:hover {
        background: var(--navy);
        transform: scale(1.08);
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-0">

    @if(session('success'))
        <div class="alert alert-success border-0 rounded-4 shadow-sm mb-4 d-flex align-items-center gap-2">
            <i class="bi bi-check-circle-fill fs-5"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger border-0 rounded-4 shadow-sm mb-4">
            <ul class="mb-0 small">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-4">
        <!-- Kolom Kiri: Kartu Identitas & Foto -->
        <div class="col-lg-4">
            <div class="panel overflow-hidden shadow-sm text-center pb-4">
                <div class="profile-cover"></div>

                @php
                    $fotoUrl = $user->foto
                        ? asset('storage/' . $user->foto)
                        : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=0b2545&color=ffffff&size=240&bold=true';
                @endphp

                <div class="avatar-wrapper">
                    <img id="preview-avatar" src="{{ $fotoUrl }}" alt="Foto Profil {{ $user->name }}">
                    <label for="foto" class="btn-camera-badge" title="Ganti Foto Profil">
                        <i class="bi bi-camera-fill"></i>
                    </label>
                </div>

                <div class="px-4">
                    <h5 class="fw-bold mb-1">{{ $user->name }}</h5>
                    <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary text-uppercase px-3 py-1 mb-3">
                        {{ $user->role ?? 'Pengguna' }}
                    </span>

                    <div class="text-start bg-light rounded-4 p-3 small d-grid gap-2 mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted"><i class="bi bi-envelope me-1"></i> Email</span>
                            <strong class="text-dark text-truncate ms-2" style="max-width: 170px;">{{ $user->email }}</strong>
                        </div>

                        @if($dosen)
                            <div class="d-flex justify-content-between">
                                <span class="text-muted"><i class="bi bi-person-badge me-1"></i> NIDN</span>
                                <strong class="text-dark">{{ $dosen->nidn ?? '-' }}</strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted"><i class="bi bi-award me-1"></i> Jabatan</span>
                                <strong class="text-dark">{{ $dosen->jabatan_fungsional ?? '-' }}</strong>
                            </div>
                        @endif

                        @if($mahasiswa)
                            <div class="d-flex justify-content-between">
                                <span class="text-muted"><i class="bi bi-person-vcard me-1"></i> NIM</span>
                                <strong class="text-dark">{{ $mahasiswa->nim ?? '-' }}</strong>
                            </div>
                        @endif

                        @if(($dosen && $dosen->no_hp) || ($mahasiswa && $mahasiswa->no_hp))
                            <div class="d-flex justify-content-between">
                                <span class="text-muted"><i class="bi bi-whatsapp me-1"></i> No. HP</span>
                                <strong class="text-dark">{{ $dosen->no_hp ?? $mahasiswa->no_hp }}</strong>
                            </div>
                        @endif
                    </div>

                    <p class="text-muted mb-3" style="font-size: 0.78rem;">
                        Klik ikon kamera pada foto untuk memilih gambar baru, lalu tekan <strong>Simpan Perubahan</strong>.
                    </p>

                    @if($user->foto)
                        <form action="{{ route('profile.foto.destroy') }}" method="POST" onsubmit="return confirm('Hapus foto profil saat ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-3 w-100">
                                <i class="bi bi-trash me-1"></i> Hapus Foto Profil
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Form Edit Data & Upload -->
        <div class="col-lg-8">
            <div class="panel p-4 p-md-5 shadow-sm">
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
                        <span class="badge bg-primary bg-opacity-10 text-primary p-2 rounded-3">
                            <i class="bi bi-image fs-6"></i>
                        </span>
                        <div>
                            <h6 class="fw-bold mb-0">Foto Profil Akun</h6>
                            <small class="text-muted">Format JPG, PNG, atau WEBP dengan ukuran maksimal 2 MB.</small>
                        </div>
                    </div>

                    <div class="mb-4">
                        <input type="file" id="foto" name="foto" accept="image/png,image/jpeg,image/webp"
                               class="form-control @error('foto') is-invalid @enderror">
                        @error('foto')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
                        <span class="badge bg-primary bg-opacity-10 text-primary p-2 rounded-3">
                            <i class="bi bi-person-lines-fill fs-6"></i>
                        </span>
                        <div>
                            <h6 class="fw-bold mb-0">Informasi Pribadi</h6>
                            <small class="text-muted">Perbarui nama tampilan, email login, atau nomor telepon aktif.</small>
                        </div>
                    </div>

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

                    <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
                        <span class="badge bg-warning bg-opacity-10 text-warning p-2 rounded-3">
                            <i class="bi bi-shield-lock fs-6"></i>
                        </span>
                        <div>
                            <h6 class="fw-bold mb-0">Keamanan & Kata Sandi</h6>
                            <small class="text-muted">Kosongkan kolom di bawah jika tidak ingin mengubah password.</small>
                        </div>
                    </div>

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

                    <div class="d-flex justify-content-end gap-2">
                        <button type="submit" class="btn btn-brand d-inline-flex align-items-center gap-2 px-4">
                            <i class="bi bi-check2-circle"></i> Simpan Perubahan Profil
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