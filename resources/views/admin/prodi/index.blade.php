@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Manajemen Program Studi</h3>
        <div>
            <a href="{{ route('admin.fakultas.index') }}" class="btn btn-outline-secondary me-1">Data Fakultas</a>
            <a href="{{ route('admin.prodi.create') }}" class="btn btn-primary">+ Tambah Prodi</a>
        </div>
    </div>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Kode</th>
                <th>Program Studi</th>
                <th>Jenjang</th>
                <th>Fakultas</th>
                <th>Kaprodi</th>
                <th width="18%">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($prodis as $prodi)
            <tr>
                <td>{{ $prodi->kode_prodi }}</td>
                <td>{{ $prodi->nama_prodi }}</td>
                <td><span class="badge bg-secondary">{{ $prodi->jenjang }}</span></td>
                <td>{{ $prodi->fakultas->nama_fakultas ?? '-' }}</td>
                <td>{{ $prodi->kaprodi ?? '-' }}</td>
                <td>
                    <a href="{{ route('admin.prodi.edit', $prodi->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('admin.prodi.destroy', $prodi->id) }}" method="POST" class="d-inline form-hapus">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center">Belum ada data program studi.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection