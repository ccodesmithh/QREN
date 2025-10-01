@extends('layouts.dashboard.index')

@section('sidebar')
    <!-- Nav Item - Dashboard -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('siswa.dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>
    <li class="nav-item active">
        <a class="nav-link" href="#">
            <i class="fas fa-fw fa-history"></i>
            <span>History</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('siswa.profile') }}">
            <i class="fas fa-fw fa-user"></i>
            <span>Profil</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('siswa.scan.index') }}">
            <i class="fas fa-fw fa-camera"></i>
            <span>Scan</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Riwayat Absensi</h1>
        </div>

        <!-- DataTales Example -->
        <div class="card shadow mb-4 animated-card">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">History Absensi Anda</h6>
                <p class="m-0">Berikut adalah catatan kehadiran Anda di setiap mata pelajaran.</p>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Guru</th>
                                <th>Mata Pelajaran</th>
                                <th>Jam Awal - Akhir</th>
                                <th>Status</th>
                                <th>Radius (m)</th>
                                <th>Scanned At</th>
                                <th>Journal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($attendances as $index => $attendance)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $attendance->qrcode->ajar->guru->name ?? '-' }}</td>
                                    <td>{{ $attendance->qrcode->ajar->mapel->nama_mapel ?? '-' }}</td>
                                    <td>
                                        <span class="badge badge-primary">{{ $attendance->qrcode->ajar->jam_awal ?? '-' }}</span> - 
                                        <span class="badge badge-primary">{{ $attendance->qrcode->ajar->jam_akhir ?? '-' }}</span>
                                    </td>
                                    <td>
                                        @if($attendance->status == 'Hadir')
                                            <span class="badge badge-success">{{ $attendance->status }}</span>
                                        @elseif($attendance->status == 'Izin')
                                            <span class="badge badge-warning">{{ $attendance->status }}</span>
                                        @else
                                            <span class="badge badge-danger">{{ $attendance->status }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $attendance->distance ? number_format($attendance->distance, 2) : '-' }}</td>
                                    <td>{{ $attendance->scanned_at ? $attendance->scanned_at->format('d-m-Y H:i:s') : '-' }}</td>
                                    <td>
                                        @php
                                            $key = $attendance->qrcode->ajar->id . '-' . ($attendance->scanned_at ? $attendance->scanned_at->toDateString() : '');
                                        @endphp
                                        {!! $journals->has($key) ? $journals[$key]->content : '-' !!}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">Tidak ada data absensi untuk ditampilkan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Animasi untuk card
        anime({
            targets: '.animated-card',
            translateY: [50, 0],
            opacity: [0, 1],
            duration: 800,
            easing: 'easeOutExpo'
        });

        // Animasi untuk baris tabel
        anime({
            targets: '#dataTable tbody tr',
            translateY: [20, 0],
            opacity: [0, 1],
            delay: anime.stagger(50, {start: 400}),
            duration: 600,
            easing: 'easeOutExpo'
        });
    });
</script>
@endpush
