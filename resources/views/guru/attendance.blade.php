@extends('layouts.dashboard.index')

@section('sidebar')
    <!-- Nav Item - Dashboard -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('guru.dashboard') }}">
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
    <h1>Absensi untuk {{ $ajar->mapel->nama_mapel }} - {{ $ajar->kelas->nama_kelas }}</h1>
    <p>Jurusan: {{ $ajar->jurusan->jurusan }} | Waktu: {{ $ajar->jam_awal }} - {{ $ajar->jam_akhir }}</p>

    <!-- Manual Entry Form -->
    <div class="card mb-4">
        <div class="card-header">
            <h5>Tambah Absensi Manual</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('guru.attendance.manual', $ajar->id) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="nisn">NISN Siswa</label>
                    <input type="text" class="form-control" id="nisn" name="nisn" required>
                </div>
                <button type="submit" class="btn btn-primary">Tambah Absensi</button>
            </form>
        </div>
    </div>

    <!-- Attendance List -->
    <div class="card">
        <div class="card-header">
            <h5>Daftar Siswa yang Hadir</h5>
        </div>
        <div class="card-body">
            @if($attendances->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>NISN</th>
                                <th>Nama Siswa</th>
                                <th>Status</th>
                                <th>Waktu Scan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($attendances as $attendance)
                                <tr>
                                    <td>{{ $attendance->siswa->nisn }}</td>
                                    <td>{{ $attendance->siswa->name }}</td>
                                    <td>{{ $attendance->status }}</td>
                                    <td>{{ $attendance->scanned_at ? $attendance->scanned_at->format('d/m/Y H:i') : 'Manual' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p>Belum ada siswa yang melakukan absensi.</p>
            @endif
        </div>
    </div>

    <a href="{{ route('guru.dashboard') }}" class="btn btn-secondary mt-3">Kembali ke Dashboard</a>
</div>
@endsection
