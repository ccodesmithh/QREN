@extends('layouts.dashboard.index')
@section('sidebar')
    <!-- Nav Item - Dashboard -->
    <li class="nav-item">
        <a class="nav-link" href="{{route('siswa.dashboard')}}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('siswa.history') }}">
            <i class="fas fa-fw fa-chart-area"></i>
            <span>History</span></a>
    </li>
    <li class="nav-item active">
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
        <div class="content">
            <h1>Profil Siswa</h1>
            <hr>
            <p><strong>Nama:</strong> {{ $siswa->name }}</p>
            <p><strong>NIS:</strong> {{ $siswa->nisn }}</p>
            <p><strong>Kelas:</strong> {{ $siswa->kelas->kelas }}</p>
            <p><strong>Jurusan:</strong> {{ $siswa->jurusan->jurusan }}</p>
            <hr>
            <p>Terdapat kesalahan? Silakan hubungi operator untuk merubah data.</p>
        </div>
    </div>
@endsection
