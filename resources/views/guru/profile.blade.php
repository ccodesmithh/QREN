@extends('layouts.dashboard.index')
@section('sidebar')
    <!-- Nav Item - Dashboard -->
    <li class="nav-item">
        <a class="nav-link" href="{{route('guru.dashboard')}}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('guru.qrcode.index') }}">
            <i class="fas fa-fw fa-camera"></i>
            <span>QR</span></a>
    </li>
    <li class="nav-item active">
        <a class="nav-link" href="{{ route('guru.profile') }}">
            <i class="fas fa-fw fa-table"></i>
            <span>Profil</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('guru.jadwal') }}">
            <i class="fas fa-fw fa-book"></i>
            <span>Jadwal Mengajar</span></a>
    </li>

@endsection
@section('content')
    <div class="container">
        <div class="content">
            <h1>Profil Guru</h1>
            <hr>
            <p><strong>Nama:</strong> {{ $guru->name }}</p>
            <p><strong>IDGURU:</strong> {{ $guru->idguru }}</p>
            <hr>
            <p>Terdapat kesalahan? Silakan hubungi operator untuk merubah data.</p>
        </div>
    </div>
@endsection
