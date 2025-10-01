@extends('layouts.dashboard.index')

@section('sidebar')
    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="{{ route('siswa.dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('siswa.history') }}">
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
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4 animated-card">
                <div class="card-body">
                    <h5 class="card-title">Halo, {{ $siswa->name }}! 👋</h5>
                    <p class="card-text">Selamat datang kembali di dasbor Anda. Di sini Anda dapat melihat ringkasan aktivitas dan mengakses fitur-fitur utama dengan cepat.</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow mb-4 animated-card-2">
                 <div class="card-body text-center">
                    <h6 class="card-title font-weight-bold">Aksi Cepat</h6>
                    <a href="{{ route('siswa.scan.index') }}" class="btn btn-primary btn-icon-split">
                        <span class="icon text-white-50">
                            <i class="fas fa-camera"></i>
                        </span>
                        <span class="text">Scan QR Code</span>
                    </a>
                 </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">

        <!-- Total Kehadiran Card -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2 animated-card-3">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Kehadiran</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalHadir }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Izin Card -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2 animated-card-4">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Total Izin</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalIzin }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Alpha Card -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2 animated-card-5">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Total Alpha</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalAlpha }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
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
            targets: '.animated-card, .animated-card-2, .animated-card-3, .animated-card-4, .animated-card-5',
            translateY: [30, 0],
            opacity: [0, 1],
            delay: anime.stagger(100),
            duration: 500,
            easing: 'easeOutExpo'
        });
    });
</script>
@endpush
