@extends('layouts.auth')

@section('title', 'Login Dosen - Absensi Kampus')
@section('accent', 'teal')
@section('brand_title', 'Buka sesi, mahasiswa tinggal memindai.')
@section('brand_lead', 'Buat QR Code untuk kelas Anda, lalu pantau siapa yang sudah hadir secara langsung.')

@section('content')
    @include('partials.login-form', [
        'icon'             => 'bi-person-workspace',
        'title'            => 'Login Dosen',
        'subtitle'         => 'Masuk ke portal pengajaran dosen',
        'action'           => route('dosen.login.process'),
        'emailLabel'       => 'Email Dosen',
        'emailPlaceholder' => 'dosen@kampus.ac.id',
        'button'           => 'Masuk sebagai Dosen',
    ])
@endsection