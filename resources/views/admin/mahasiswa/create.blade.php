@extends('layouts.app')
@section('content')
<div class="container">
    <h3>Tambah Mahasiswa</h3>
    @if ($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
    @endif
    <form action="{{ route('admin.mahasiswa.store') }}" method="POST">
        @csrf
        @include('admin.mahasiswa._form')
        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('admin.mahasiswa.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection