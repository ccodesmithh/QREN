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
    <li class="nav-item">
        <a class="nav-link" href="{{ route('guru.profile') }}">
            <i class="fas fa-fw fa-table"></i>
            <span>Profil</span></a>
    </li>
        <li class="nav-item active">
        <a class="nav-link" href="{{ route('guru.jadwal') }}">
            <i class="fas fa-fw fa-book"></i>
            <span>Jadwal Mengajar</span></a> 
    </li>
@endsection
@section('content')
<div class="container">
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <h1>Jadwal Mengajar</h1>
    <p>Berikut adalah jadwal mengajar Anda.</p>
    @if ($jadwals->count() == 0)
        <p>Anda tidak memiliki jadwal mengajar. Silakan tambahkan jadwal anda</p>
    @else
    <div class="content" style="white-space:nowrap; overflow-x: scroll">
        <table class="table" style="display:inline-block">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Mata Pelajaran</th>
                    <th>Kelas</th>
                    <th>Jurusan</th>
                    <th>Jam</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($jadwals as $jadwal)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $jadwal->mapel->nama_mapel }}</td>
                        <td>{{ $jadwal->kelas->kelas }}</td>
                        <td>{{ $jadwal->jurusan->jurusan }}</td>
                        <td>{{ $jadwal->jam_awal }}-{{ $jadwal->jam_akhir }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
    <a href="{{ route('guru.ajar') }}" class="btn btn-primary">Tambah Jadwal</a>
</div>
@endsection