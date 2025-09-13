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
        <a class="nav-link" href="#">
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
                        <th>Tanggal</th>
                        <th>Kode QR</th>
                        <th>Guru</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($attendances as $index => $attendance)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $attendance->created_at->format('d-m-Y H:i:s') }}</td>
                        <td>{{ $attendance->qr_code }}</td>
                        <td>{{ $attendance->guru->name ?? '-' }}</td>
                        <td>{{ $attendance->status }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
