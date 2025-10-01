@extends('layouts.dashboard.index')

@section('sidebar')
    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="{{ route('guru.dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('guru.qrcode.index') }}">
            <i class="fas fa-fw fa-qrcode"></i>
            <span>QR</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('guru.history') }}">
            <i class="fas fa-fw fa-history"></i>
            <span>History</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('guru.journal.index') }}">
            <i class="fas fa-fw fa-book-open"></i>
            <span>Journal</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('guru.profile') }}">
            <i class="fas fa-fw fa-user"></i>
            <span>Profil</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('guru.jadwal') }}">
            <i class="fas fa-fw fa-calendar-alt"></i>
            <span>Jadwal Mengajar</span></a>
    </li>
@endsection

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard Guru</h1>
    </div>

    <!-- Content Row -->
    <div class="row">

        <!-- Left Column -->
        <div class="col-lg-7">

            <!-- Welcome & Stats Card -->
            <div class="card shadow mb-4 animated-card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <i class="fas fa-chalkboard-teacher fa-3x text-gray-300"></i>
                        </div>
                        <div class="col">
                            <h5 class="card-title font-weight-bold text-primary mb-1">Halo, {{ $guru->name }}!</h5>
                            <p class="card-text mb-2">Selamat datang di pusat kendali Anda.</p>
                            <div class="d-flex">
                                <div class="mr-4">
                                    <div class="text-xs font-weight-bold text-uppercase mb-1">Jadwal Mengajar</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $ajars->count() }}</div>
                                </div>
                                <div>
                                    <div class="text-xs font-weight-bold text-uppercase mb-1">Total Jurnal</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $recentJournals->count() }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Journals Card -->
            <div class="card shadow mb-4 animated-card-2">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-book-open fa-fw mr-2"></i>Jurnal Mengajar Terbaru</h6>
                    <a href="{{ route('guru.journal.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </div>
                <div class="card-body">
                    @if($recentJournals->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentJournals as $journal)
                                <a href="{{ route('guru.journal.show', $journal->id) }}" class="list-group-item list-group-item-action px-0">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">{{ $journal->ajar->mapel->nama_mapel }} ({{ $journal->ajar->kelas->kelas }} {{ $journal->ajar->jurusan->jurusan }})</h6>
                                        <small class="text-muted">{{ $journal->date->format('d M Y') }}</small>
                                    </div>
                                    <p class="mb-1 text-muted">{!! Str::limit($journal->content, 120) !!}</p>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-ghost fa-2x text-gray-300 mb-2"></i>
                            <p class="text-muted">Belum ada jurnal terbaru.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Attendances Card -->
            <div class="card shadow mb-4 animated-card-3">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-user-check fa-fw mr-2"></i>Absensi Siswa Terbaru</h6>
                    <a href="{{ route('guru.history') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </div>
                <div class="card-body">
                    @if($recentAttendances->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-borderless table-hover">
                                <tbody>
                                    @foreach($recentAttendances as $attendance)
                                        <tr>
                                            <td class="align-middle">{{ $attendance->siswa->name }}</td>
                                            <td class="align-middle text-muted">{{ $attendance->qrcode->ajar->mapel->nama_mapel }}</td>
                                            <td class="align-middle">
                                                @if($attendance->status == 'Hadir')
                                                    <span class="badge badge-success">{{ $attendance->status }}</span>
                                                @else
                                                    <span class="badge badge-warning">{{ $attendance->status }}</span>
                                                @endif
                                            </td>
                                            <td class="align-middle text-right text-muted"><small>{{ $attendance->scanned_at ? $attendance->scanned_at->diffForHumans() : '-' }}</small></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-user-clock fa-2x text-gray-300 mb-2"></i>
                            <p class="text-muted">Belum ada absensi terbaru.</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>

        <!-- Right Column -->
        <div class="col-lg-5">
            <!-- QR Code Card -->
            <div class="card shadow mb-4 animated-card-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-qrcode fa-fw mr-2"></i>Kode QR Aktif</h6>
                </div>
                <div class="card-body">
                    @livewire('guru-dashboard')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Staggered animation for cards
        anime({
            targets: '.animated-card, .animated-card-2, .animated-card-3, .animated-card-4',
            translateY: [30, 0],
            opacity: [0, 1],
            delay: anime.stagger(100),
            duration: 500,
            easing: 'easeOutExpo'
        });

        // Prevent tab jump on page load
        if(location.hash) {
            $('a[href="' + location.hash + '"]').tab('show');
        }

        // Add hash to URL on tab change
        $('.nav-tabs a').on('shown.bs.tab', function (e) {
            window.location.hash = e.target.hash;
        });
    });
</script>
@endpush