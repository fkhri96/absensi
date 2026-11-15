@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Manajemen Jadwal Kuliah</h3>
        <a href="{{ route('admin.jadwal.create') }}" class="btn btn-primary">+ Tambah Jadwal</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.jadwal.index') }}" method="GET" class="row g-2 mb-3">
        <div class="col-md-3">
            <select name="hari" class="form-select">
                <option value="">-- Semua Hari --</option>
                @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $h)
                    <option value="{{ $h }}" {{ request('hari') == $h ? 'selected' : '' }}>{{ $h }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-secondary">Filter</button>
            @if(request('hari'))
                <a href="{{ route('admin.jadwal.index') }}" class="btn btn-outline-secondary">Reset</a>
            @endif
        </div>
    </form>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Hari & Jam</th>
                <th>Mata Kuliah</th>
                <th>Dosen Pengampu</th>
                <th>Ruang</th>
                <th>Tahun Akademik</th>
                <th width="18%">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jadwals as $jadwal)
            <tr>
                <td>
                    <strong>{{ $jadwal->hari }}</strong><br>
                    <small>{{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}</small>
                </td>
                <td>
                    {{ $jadwal->mataKuliah->nama_mk ?? '-' }}<br>
                    <small class="text-muted">{{ $jadwal->mataKuliah->prodi->nama_prodi ?? '' }}</small>
                </td>
                <td>{{ $jadwal->mataKuliah->dosen->user->name ?? '-' }}</td>
                <td><span class="badge bg-dark">{{ $jadwal->ruang }}</span></td>
                <td>{{ $jadwal->tahun_akademik }}</td>
                <td>
                    <a href="{{ route('admin.jadwal.edit', $jadwal->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('admin.jadwal.destroy', $jadwal->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus jadwal ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center">Belum ada jadwal kuliah.</td></tr>
            @endforelse
        </tbody>
    </table>
    {{ $jadwals->links() }}
</div>
@endsection