@extends('layouts.app')

@section('title', 'Tambah Dosen | Absensi Kampus')
@section('page_title', 'Tambah Data Dosen')
@section('role', 'admin')

@section('content')
<div class="panel p-4 shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold mb-0">Form Tambah Dosen</h5>
        <a href="{{ route('admin.dosen.index') }}" class="btn btn-ghost btn-sm">Kembali</a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.dosen.store') }}" method="POST">
        @csrf
        @include('admin.dosen._form')

        <div class="mt-4 d-flex justify-content-end gap-2">
            <a href="{{ route('admin.dosen.index') }}" class="btn btn-ghost">Batal</a>
            <button type="submit" class="btn btn-brand">Simpan Data Dosen</button>
        </div>
    </form>
</div>
@endsection