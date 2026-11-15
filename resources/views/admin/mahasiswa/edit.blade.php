@extends('layouts.app')
@section('content')
<div class="container">
    <h3>Edit Mahasiswa: {{ $mahasiswa->user->name }}</h3>
    @if ($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
    @endif
    <form action="{{ route('admin.mahasiswa.update', $mahasiswa->id) }}" method="POST">
        @csrf
        @method('PUT')
        @include('admin.mahasiswa._form')
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('admin.mahasiswa.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection