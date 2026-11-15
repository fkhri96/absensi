@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Detail Mahasiswa</h3>
        <a href="{{ route('admin.mahasiswa.index') }}" class="btn btn-secondary">Kembali</a>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <table class="table table-borderless mb-0">
                <tr><th width="20%">NIM</th><td>: {{ $mahasiswa->nim }}</td></tr>
                <tr><th>Nama Lengkap</th><td>: {{ $mahasiswa->user->name }}</td></tr>
                <tr><th>Email</th><td>: {{ $mahasiswa->user->email }}</td></tr>
                <tr><th>Program Studi</th><td>: {{ $mahasiswa->prodi->nama_prodi ?? '-' }}</td></tr>
                <tr><th>Angkatan</th><td>: {{ $mahasiswa->angkatan }}</td></tr>
                <tr><th>Jenis Kelamin</th><td>: {{ $mahasiswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td></tr>
                <tr><th>No. HP</th><td>: {{ $mahasiswa->no_hp ?? '-' }}</td></tr>
                <tr><th>Alamat</th><td>: {{ $mahasiswa->alamat ?? '-' }}</td></tr>
            </table>
        </div>
    </div>
</div>
@endsection