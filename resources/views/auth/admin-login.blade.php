@extends('layouts.auth')

@section('title', 'Login Admin - Absensi Kampus')
@section('accent', 'blue')
@section('brand_title', 'Kendalikan data akademik dalam satu panel.')
@section('brand_lead', 'Kelola master data, jadwal, QR Code presensi, dan unduh laporan kehadiran kapan saja.')

@section('content')
    @include('partials.login-form', [
        'icon'             => 'bi-shield-lock-fill',
        'title'            => 'Login Admin',
        'subtitle'         => 'Masuk ke panel administrator',
        'action'           => route('admin.login.process'),
        'emailLabel'       => 'Email Admin',
        'emailPlaceholder' => 'admin@kampus.ac.id',
        'button'           => 'Masuk sebagai Admin',
    ])
@endsection