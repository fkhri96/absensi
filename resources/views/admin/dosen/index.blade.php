@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Manajemen Dosen</h3>
        <a href="{{ route('admin.dosen.create') }}" class="btn btn-primary">+ Tambah Dosen</a>
    </div>

    <form action="{{ route('admin.dosen.index') }}" method="GET" class="row g-2 mb-3">
        <div class="col-md-4">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari NIDN atau Nama Dosen...">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-secondary">Cari</button>
        </div>
    </form>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>NIDN</th>
                <th>Nama & Gelar</th>
                <th>Email</th>
                <th>Fakultas</th>
                <th>Jabatan</th>
                <th width="18%">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($dosens as $dosen)
            <tr>
                <td>{{ $dosen->nidn }}</td>
                <td>{{ $dosen->user->name }}</td>
                <td>{{ $dosen->user->email }}</td>
                <td>{{ $dosen->fakultas->nama_fakultas ?? '-' }}</td>
                <td>{{ $dosen->jabatan_fungsional ?? '-' }}</td>
                <td>
                    <a href="{{ route('admin.dosen.edit', $dosen->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('admin.dosen.destroy', $dosen->id) }}" method="POST" class="d-inline form-hapus">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Data dosen belum tersedia.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    {{ $dosens->links() }}
</div>
@endsection