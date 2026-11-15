@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Edit Jadwal Kuliah</h3>
    @if ($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
    @endif

    <form action="{{ route('admin.jadwal.update', $jadwal->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Mata Kuliah & Dosen Pengampu</label>
            <select name="mata_kuliah_id" class="form-select" required>
                @foreach($mataKuliahs as $mk)
                    <option value="{{ $mk->id }}" {{ old('mata_kuliah_id', $jadwal->mata_kuliah_id) == $mk->id ? 'selected' : '' }}>
                        [{{ $mk->kode_mk }}] {{ $mk->nama_mk }} - {{ $mk->dosen->user->name ?? '-' }} ({{ $mk->prodi->nama_prodi ?? '' }})
                    </option>
                @endforeach
            </select>
        </div>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Hari</label>
                <select name="hari" class="form-select" required>
                    @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $h)
                        <option value="{{ $h }}" {{ old('hari', $jadwal->hari) == $h ? 'selected' : '' }}>{{ $h }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Jam Mulai</label>
                <input type="time" name="jam_mulai" value="{{ old('jam_mulai', \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i')) }}" class="form-control" required>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Jam Selesai</label>
                <input type="time" name="jam_selesai" value="{{ old('jam_selesai', \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i')) }}" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Ruang Kuliah</label>
                <input type="text" name="ruang" value="{{ old('ruang', $jadwal->ruang) }}" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Tahun Akademik</label>
                <input type="text" name="tahun_akademik" value="{{ old('tahun_akademik', $jadwal->tahun_akademik) }}" class="form-control" required>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('admin.jadwal.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection