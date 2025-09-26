@extends('layouts.dashboard.index')
@section('sidebar')
    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
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
    <li class="nav-item">
        <a class="nav-link" href="{{ route('guru.jadwal') }}">
            <i class="fas fa-fw fa-book"></i>
            <span>Jadwal Mengajar</span></a>
    </li>
@endsection

@section('content')
<div class="container">
    <h1>Halo, {{ $guru->name }}</h1>
    <p>Selamat datang di Dashboard Guru 👨‍🏫</p>

    <div class="card mt-4">
        <div class="card-header">
            <h5>Jadwal Mengajar</h5>
        </div>
        <div class="card-body">
            @if($ajars->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Mata Pelajaran</th>
                                <th>Kelas</th>
                                <th>Jurusan</th>
                                <th>Waktu</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ajars as $ajar)
                                <tr>
                                    <td>{{ $ajar->mapel->nama_mapel }}</td>
                                    <td>{{ $ajar->kelas->kelas }}</td>
                                    <td>{{ $ajar->jurusan->jurusan }}</td>
                                    <td>{{ $ajar->jam_awal }} - {{ $ajar->jam_akhir }}</td>
                                    <td>
                                        <a href="{{ route('guru.attendance', $ajar->id) }}" class="btn btn-primary btn-sm">Lihat Absensi</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p>Anda belum memiliki jadwal mengajar.</p>
            @endif
        </div>
    </div>
</div>
@endsection
