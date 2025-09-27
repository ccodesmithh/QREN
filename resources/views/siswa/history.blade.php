@extends('layouts.dashboard.index')
@section('sidebar')
    <!-- Nav Item - Dashboard -->
    <li class="nav-item">
        <a class="nav-link" href="{{route('siswa.dashboard')}}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>
    <li class="nav-item active">
        <a class="nav-link" href="#">
            <i class="fas fa-fw fa-chart-area"></i>
            <span>History</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('siswa.profile') }}">
            <i class="fas fa-fw fa-table"></i>
            <span>Profil</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('scan.index') }}">
            <i class="fas fa-fw fa-camera"></i>
            <span>Scan</span></a>
    </li>
@endsection
@section('content')
<div class="container">
    <p>Selamat datang di Histori Siswa 🎓</p>

    <div class="wrapper">
        <div class="title">
            History Absensi
        </div>
        <div class="table">
            <table class="table table-bordered overflow-x:auto;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Guru</th>
                        <th>Mata Pelajaran</th>
                        <th>Jam Awal - Akhir</th>
                        <th>Status</th>
                        <th>Radius (m)</th>
                        <th>Scanned At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($attendances as $index => $attendance)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $attendance->qrcode->ajar->guru->name ?? '-' }}</td>
                        <td>{{ $attendance->qrcode->ajar->mapel->nama_mapel ?? '-' }}</td>
                        <td>{{ $attendance->qrcode->ajar->jam_awal ?? '-' }} - {{ $attendance->qrcode->ajar->jam_akhir ?? '-' }}</td>
                        <td>{{ $attendance->status }}</td>
                        <td>{{ $attendance->distance ? number_format($attendance->distance, 2) : '-' }}</td>
                        <td>{{ $attendance->scanned_at ? $attendance->scanned_at->format('d-m-Y H:i:s') : '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
