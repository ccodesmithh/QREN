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
        <a class="nav-link" href="{{ route('guru.history') }}">
            <i class="fas fa-fw fa-chart-area"></i>
            <span>History</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('guru.journal.index') }}">
            <i class="fas fa-fw fa-book-open"></i>
            <span>Journal</span></a>
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
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1>Halo, {{ $guru->name }}</h1>
            <p>Selamat datang di Dashboard Guru 👨‍🏫</p>
        </div>
    </div>

    <!-- QR Codes Section -->
    <div class="card mb-4">
        <div class="card-header">
            <h5>Kode QR Absensi</h5>
        </div>
        <div class="card-body">
            @livewire('guru-dashboard')
        </div>
    </div>

    <div class="card mb-4">
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
                                        <a href="{{ route('guru.journal.create', ['ajar_id' => $ajar->id]) }}" class="btn btn-success btn-sm ml-2">Buat Journal</a>
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

    <!-- Recent Attendances -->
    <div class="card mb-4">
        <div class="card-header">
            <h5>Absensi Terbaru</h5>
        </div>
        <div class="card-body">
            @if($recentAttendances->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nama Siswa</th>
                                <th>Mata Pelajaran</th>
                                <th>Status</th>
                                <th>Radius (m)</th>
                                <th>Scanned At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentAttendances as $attendance)
                                <tr>
                                    <td>{{ $attendance->siswa->name }}</td>
                                    <td>{{ $attendance->qrcode->ajar->mapel->nama_mapel }}</td>
                                    <td>{{ $attendance->status }}</td>
                                    <td>{{ $attendance->distance ? number_format($attendance->distance, 2) : '-' }}</td>
                                    <td>{{ $attendance->scanned_at ? $attendance->scanned_at->format('d/m/Y H:i') : '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p>Belum ada absensi terbaru.</p>
            @endif
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        @if($ajarsWithQr->count() > 0)
            // Start periodic location update for all ajars with QR codes
            startPeriodicLocationUpdateForAll({{ $ajarsWithQr->pluck('id')->toJson() }}, {{ (int) \App\Models\Setting::getValue('geolocation_update_interval', 5) }});
        @endif
    });
    </script>
@endsection
