@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Edit Mata Kuliah</h3>

    <form action="{{ route('admin.matakuliah.update', $matakuliah->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Kode Mata Kuliah</label>
                <input type="text" name="kode_mk" value="{{ old('kode_mk', $matakuliah->kode_mk) }}" class="form-control" required>
            </div>
            <div class="col-md-8 mb-3">
                <label class="form-label">Nama Mata Kuliah</label>
                <input type="text" name="nama_mk" value="{{ old('nama_mk', $matakuliah->nama_mk) }}" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Program Studi</label>
                <select name="prodi_id" class="form-select" required>
                    @foreach($prodis as $prodi)
                        <option value="{{ $prodi->id }}" {{ old('prodi_id', $matakuliah->prodi_id) == $prodi->id ? 'selected' : '' }}>
                            {{ $prodi->nama_prodi }} ({{ $prodi->jenjang }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Dosen Pengampu</label>
                <select name="dosen_id" class="form-select" required>
                    @foreach($dosens as $dosen)
                        <option value="{{ $dosen->id }}" {{ old('dosen_id', $matakuliah->dosen_id) == $dosen->id ? 'selected' : '' }}>
                            {{ $dosen->user->name }} (NIDN: {{ $dosen->nidn }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Jumlah SKS</label>
                <input type="number" name="sks" value="{{ old('sks', $matakuliah->sks) }}" min="1" max="6" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Semester</label>
                <input type="number" name="semester" value="{{ old('semester', $matakuliah->semester) }}" min="1" max="8" class="form-control" required>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('admin.matakuliah.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection