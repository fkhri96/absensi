@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Manajemen Mata Kuliah</h3>
        <a href="{{ route('admin.matakuliah.create') }}" class="btn btn-primary">+ Tambah Mata Kuliah</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.matakuliah.index') }}" method="GET" class="row g-2 mb-3">
        <div class="col-md-4">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari Kode atau Nama MK...">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-secondary">Cari</button>
        </div>
    </form>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Kode MK</th>
                <th>Nama Mata Kuliah</th>
                <th>SKS</th>
                <th>Semester</th>
                <th>Program Studi</th>
                <th>Dosen Pengampu</th>
                <th width="18%">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($mataKuliahs as $mk)
            <tr>
                <td>{{ $mk->kode_mk }}</td>
                <td>{{ $mk->nama_mk }}</td>
                <td>{{ $mk->sks }} SKS</td>
                <td>Semester {{ $mk->semester }}</td>
                <td>{{ $mk->prodi->nama_prodi ?? '-' }}</td>
                <td>{{ $mk->dosen->user->name ?? '-' }}</td>
                <td>
                    <a href="{{ route('admin.matakuliah.edit', $mk->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('admin.matakuliah.destroy', $mk->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus mata kuliah ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center">Belum ada data mata kuliah.</td></tr>
            @endforelse
        </tbody>
    </table>
    {{ $mataKuliahs->links() }}
</div>
@endsection