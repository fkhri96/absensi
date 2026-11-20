@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Manajemen Mahasiswa</h3>
        <a href="{{ route('admin.mahasiswa.create') }}" class="btn btn-primary">+ Tambah Mahasiswa</a>
    </div>

    <form action="{{ route('admin.mahasiswa.index') }}" method="GET" class="row g-2 mb-3">
        <div class="col-md-4">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari NIM, Nama, atau Email...">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-secondary">Cari</button>
            @if(request('search'))
                <a href="{{ route('admin.mahasiswa.index') }}" class="btn btn-outline-secondary">Reset</a>
            @endif
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>NIM</th>
                    <th>Nama Lengkap</th>
                    <th>Email</th>
                    <th>Program Studi</th>
                    <th>Angkatan</th>
                    <th width="22%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mahasiswas as $mhs)
                <tr>
                    <td>{{ $mhs->nim }}</td>
                    <td>{{ $mhs->user->name }}</td>
                    <td>{{ $mhs->user->email }}</td>
                    <td>{{ $mhs->prodi->nama_prodi ?? 'Belum diatur' }}</td>
                    <td>{{ $mhs->angkatan }}</td>
                    <td>
                        <a href="{{ route('admin.mahasiswa.show', $mhs->id) }}" class="btn btn-sm btn-info">Detail</a>
                        <a href="{{ route('admin.mahasiswa.edit', $mhs->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('admin.mahasiswa.destroy', $mhs->id) }}" method="POST" class="d-inline form-hapus">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">Data mahasiswa tidak ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $mahasiswas->links() }}
</div>
@endsection