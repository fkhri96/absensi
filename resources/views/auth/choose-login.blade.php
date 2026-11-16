@extends('layouts.auth')

@section('title', 'Pilih Login | Absensi Kampus')
@section('accent', 'blue')

@section('content')
    <div class="auth-head">
        <h1 class="auth-title">Masuk ke Absensi Kampus</h1>
        <p class="auth-sub">Pilih jenis akun untuk melanjutkan.</p>
    </div>

    <div class="role-list">
        <a href="{{ route('mahasiswa.login') }}" class="role-row r-amber">
            <span class="r-ico" aria-hidden="true"><i class="bi bi-mortarboard-fill"></i></span>
            <span>
                <span class="r-name d-block">Mahasiswa</span>
                <span class="r-desc">Lihat jadwal dan lakukan absensi</span>
            </span>
            <i class="bi bi-arrow-right-circle-fill r-go" aria-hidden="true"></i>
        </a>

        <a href="{{ route('dosen.login') }}" class="role-row r-teal">
            <span class="r-ico" aria-hidden="true"><i class="bi bi-person-workspace"></i></span>
            <span>
                <span class="r-name d-block">Dosen</span>
                <span class="r-desc">Kelola jadwal mengajar dan absensi</span>
            </span>
            <i class="bi bi-arrow-right-circle-fill r-go" aria-hidden="true"></i>
        </a>

        <a href="{{ route('admin.login') }}" class="role-row r-blue">
            <span class="r-ico" aria-hidden="true"><i class="bi bi-shield-lock-fill"></i></span>
            <span>
                <span class="r-name d-block">Admin</span>
                <span class="r-desc">Kelola seluruh data dan sistem kampus</span>
            </span>
            <i class="bi bi-arrow-right-circle-fill r-go" aria-hidden="true"></i>
        </a>
    </div>
@endsection