@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Tambah Mata Kuliah</h3>
    @if ($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
    @endif

    <form action="{{ route('admin.matakuliah.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Kode Mata Kuliah</label>
                <input type="text" name="kode_mk" value="{{ old('kode_mk') }}" class="form-control" placeholder="Contoh: IF101" required>
            </div>
            <div class="col-md-8 mb-3">
                <label class="form-label">Nama Mata Kuliah</label>
                <input type="text" name="nama_mk" value="{{ old('nama_mk') }}" class="form-control" placeholder="Contoh: Pemrograman Web" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Program Studi</label>
                <select name="prodi_id" class="form-select" required>
                    <option value="">-- Pilih Program Studi --</option>
                    @foreach($prodis as $prodi)
                        <option value="{{ $prodi->id }}" {{ old('prodi_id') == $prodi->id ? 'selected' : '' }}>
                            {{ $prodi->nama_prodi }} ({{ $prodi->jenjang }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Dosen Pengampu</label>
                <select name="dosen_id" class="form-select" required>
                    <option value="">-- Pilih Dosen Pengampu --</option>
                    @foreach($dosens as $dosen)
                        <option value="{{ $dosen->id }}" {{ old('dosen_id') == $dosen->id ? 'selected' : '' }}>
                            {{ $dosen->user->name }} (NIDN: {{ $dosen->nidn }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Jumlah SKS</label>
                <input type="number" name="sks" value="{{ old('sks', 3) }}" min="1" max="6" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Semester</label>
                <input type="number" name="semester" value="{{ old('semester', 1) }}" min="1" max="8" class="form-control" required>
            </div>
        </div>
        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('admin.matakuliah.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection