@extends('layouts.app')

@section('title', 'Edit Dosen | Absensi Kampus')
@section('page_title', 'Edit Data Dosen')
@section('role', 'admin')

@section('content')
<div class="container-fluid px-0">
    <div class="panel p-4 shadow-sm">
        <h5 class="fw-bold mb-3">Edit Dosen: {{ $dosen->user->name ?? '-' }}</h5>

        @if ($errors->any())
            <div class="alert alert-danger border-0 rounded-4">
                <ul class="mb-0 small">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.dosen.update', $dosen->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <label for="name" class="form-label fw-semibold small">Nama Lengkap & Gelar <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $dosen->user->name ?? '') }}" required>
                </div>

                <div class="col-md-6">
                    <label for="email" class="form-label fw-semibold small">Email Login <span class="text-danger">*</span></label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $dosen->user->email ?? '') }}" required>
                </div>

                <div class="col-md-6">
                    <label for="nidn" class="form-label fw-semibold small">NIDN / NIP <span class="text-danger">*</span></label>
                    <input type="text" name="nidn" id="nidn" class="form-control" value="{{ old('nidn', $dosen->nidn ?? '') }}" required>
                </div>

                <div class="col-md-6">
                    <label for="password" class="form-label fw-semibold small">Password Baru (Opsional)</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah password">
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-brand">
                    <i class="bi bi-save me-1"></i> Simpan Perubahan
                </button>
                <a href="{{ route('admin.dosen.index') }}" class="btn btn-ghost">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection