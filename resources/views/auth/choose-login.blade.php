
@extends('layouts.app')

@section('title', 'Pilih Login | Absensi Kampus')

@section('content')

<div class="text-center mb-5">
    <h2 class="fw-bold">Absensi Kampus</h2>

    <p class="text-secondary">
        Pilih jenis akun untuk masuk ke sistem
    </p>
</div>

<div class="row justify-content-center g-4">

    <div class="col-md-4">
        <div class="card main-card p-4 text-center h-100">

            <div class="mb-3"
                 style="font-size:55px;color:#3157d5">
                <i class="bi bi-shield-lock-fill"></i>
            </div>

            <h4 class="fw-bold">Admin</h4>

            <p class="text-secondary">
                Kelola seluruh data dan sistem kampus.
            </p>

            <a href="{{ route('admin.login') }}"
               class="btn btn-primary mt-auto">
                Login Admin
            </a>

        </div>
    </div>

    <div class="col-md-4">
        <div class="card main-card p-4 text-center h-100">

            <div class="mb-3"
                 style="font-size:55px;color:#009e83">
                <i class="bi bi-person-workspace"></i>
            </div>

            <h4 class="fw-bold">Dosen</h4>

            <p class="text-secondary">
                Kelola jadwal mengajar dan absensi.
            </p>

            <a href="{{ route('dosen.login') }}"
               class="btn mt-auto text-white"
               style="background:#009e83">
                Login Dosen
            </a>

        </div>
    </div>

    <div class="col-md-4">
        <div class="card main-card p-4 text-center h-100">

            <div class="mb-3"
                 style="font-size:55px;color:#d18a16">
                <i class="bi bi-mortarboard-fill"></i>
            </div>

            <h4 class="fw-bold">Mahasiswa</h4>

            <p class="text-secondary">
                Lihat jadwal dan lakukan absensi.
            </p>

            <a href="{{ route('mahasiswa.login') }}"
               class="btn mt-auto text-white"
               style="background:#d18a16">
                Login Mahasiswa
            </a>

        </div>
    </div>

</div>

@endsection
