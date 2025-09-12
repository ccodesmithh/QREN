@extends('layouts.app')
@section('sidebar')
    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="{{route('guru.dashboard')}}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('guru.generate') }}">
            <i class="fas fa-fw fa-chart-area"></i>
            <span>QR</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#">
            <i class="fas fa-fw fa-table"></i>
            <span>Profil</span></a>
    </li>
@endsection

@section('content')
<div class="container">
    <h1>Halo, {{ $guru->name }}</h1>
    <p>Selamat datang di Dashboard Guru 👨‍🏫</p>
</div>
@endsection
