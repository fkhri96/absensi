@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Manajemen Fakultas</h3>
        <div>
            <a href="{{ route('admin.prodi.index') }}" class="btn btn-outline-primary me-1">Kelola Prodi</a>
            <a href="{{ route('admin.fakultas.create') }}" class="btn btn-primary">+ Tambah Fakultas</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Kode</th>
                <th>Nama Fakultas</th>
                <th>Dekan</th>
                <th>Jumlah Prodi</th>
                <th width="18%">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($fakultasList as $fak)
            <tr>
                <td>{{ $fak->kode_fakultas }}</td>
                <td>{{ $fak->nama_fakultas }}</td>
                <td>{{ $fak->dekan ?? '-' }}</td>
                <td><span class="badge bg-info">{{ $fak->prodis_count }} Prodi</span></td>
                <td>
                    <a href="{{ route('admin.fakultas.edit', $fak->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('admin.fakultas.destroy', $fak->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus fakultas ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center">Belum ada data fakultas.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection