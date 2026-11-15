@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Edit Fakultas</h3>
    @if ($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
    @endif

    <form action="{{ route('admin.fakultas.update', $fakultas->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Kode Fakultas</label>
            <input type="text" name="kode_fakultas" value="{{ old('kode_fakultas', $fakultas->kode_fakultas) }}" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Nama Fakultas</label>
            <input type="text" name="nama_fakultas" value="{{ old('nama_fakultas', $fakultas->nama_fakultas) }}" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Nama Dekan (Opsional)</label>
            <input type="text" name="dekan" value="{{ old('dekan', $fakultas->dekan) }}" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('admin.fakultas.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection