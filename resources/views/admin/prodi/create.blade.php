@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Tambah Program Studi</h3>
    @if ($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
    @endif

    <form action="{{ route('admin.prodi.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Fakultas</label>
            <select name="fakultas_id" class="form-select" required>
                <option value="">-- Pilih Fakultas --</option>
                @foreach($fakultasList as $fak)
                    <option value="{{ $fak->id }}" {{ old('fakultas_id') == $fak->id ? 'selected' : '' }}>{{ $fak->nama_fakultas }}</option>
                @endforeach
            </select>
        </div>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Kode Prodi</label>
                <input type="text" name="kode_prodi" value="{{ old('kode_prodi') }}" class="form-control" placeholder="Contoh: TI" required>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Jenjang</label>
                <select name="jenjang" class="form-select" required>
                    @foreach(['D3', 'D4', 'S1', 'S2'] as $j)
                        <option value="{{ $j }}" {{ old('jenjang', 'S1') == $j ? 'selected' : '' }}>{{ $j }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Nama Prodi</label>
                <input type="text" name="nama_prodi" value="{{ old('nama_prodi') }}" class="form-control" placeholder="Contoh: Teknik Informatika" required>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Ketua Program Studi (Opsional)</label>
            <input type="text" name="kaprodi" value="{{ old('kaprodi') }}" class="form-control">
        </div>
        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('admin.prodi.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection