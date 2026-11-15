@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Tambah Fakultas</h3>
    @if ($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
    @endif

    <form action="{{ route('admin.fakultas.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Kode Fakultas</label>
            <input type="text" name="kode_fakultas" value="{{ old('kode_fakultas') }}" class="form-control" placeholder="Contoh: FT" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Nama Fakultas</label>
            <input type="text" name="nama_fakultas" value="{{ old('nama_fakultas') }}" class="form-control" placeholder="Contoh: Fakultas Teknik" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Nama Dekan (Opsional)</label>
            <input type="text" name="dekan" value="{{ old('dekan') }}" class="form-control">
        </div>
        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('admin.fakultas.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection