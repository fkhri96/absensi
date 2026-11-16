@extends('layouts.auth')

@section('title', 'Login Mahasiswa - Absensi Kampus')
@section('accent', 'amber')
@section('brand_title', 'Pindai QR di kelas, kehadiran langsung tercatat.')
@section('brand_lead', 'Lihat jadwal kuliah hari ini, lakukan presensi, dan cek riwayat kehadiran Anda.')

@section('content')
    @include('partials.login-form', [
        'icon'             => 'bi-mortarboard-fill',
        'title'            => 'Login Mahasiswa',
        'subtitle'         => 'Masuk ke portal mahasiswa',
        'action'           => route('mahasiswa.login.process'),
        'emailLabel'       => 'Email Mahasiswa',
        'emailPlaceholder' => 'mahasiswa@kampus.ac.id',
        'button'           => 'Masuk sebagai Mahasiswa',
    ])
@endsection