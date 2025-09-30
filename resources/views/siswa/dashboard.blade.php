@extends('layouts.dashboard.index')
@section('sidebar')
    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="{{route('siswa.dashboard')}}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('siswa.history') }}">
            <i class="fas fa-fw fa-chart-area"></i>
            <span>History</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('siswa.profile') }}">
            <i class="fas fa-fw fa-table"></i>
            <span>Profil</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('siswa.scan.index') }}">
            <i class="fas fa-fw fa-camera"></i>
            <span>Scan</span></a>
    </li>
@endsection
@section('content')
<div class="container">
    <h1>Halo, {{ $siswa->name }}</h1>
    <p>Selamat datang di Dashboard Siswa 🎓</p>
</div>
@endsection
