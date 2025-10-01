@extends('layouts.dashboard.index')

@section('sidebar')
    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="{{ route('admin.dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <div class="sidebar-heading">
        Manajemen Data
    </div>

    <!-- Siswa -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.manage') }}#siswa">
            <i class="fas fa-fw fa-user-graduate"></i>
            <span>Siswa</span>
        </a>
    </li>

    <!-- Guru -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.manage') }}#guru">
            <i class="fas fa-fw fa-chalkboard-teacher"></i>
            <span>Guru</span>
        </a>
    </li>

    <!-- Kelas -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.manage') }}#kelas">
            <i class="fas fa-fw fa-building"></i>
            <span>Kelas</span>
        </a>
    </li>

    <!-- Jurusan -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.manage') }}#jurusan">
            <i class="fas fa-fw fa-graduation-cap"></i>
            <span>Jurusan</span>
        </a>
    </li>

    <!-- Mapel -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.manage') }}#mapel">
            <i class="fas fa-fw fa-book"></i>
            <span>Mata Pelajaran</span>
        </a>
    </li>

    <div class="sidebar-heading mt-3">
        Sistem
    </div>

    <!-- Settings -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.manage') }}#settings">
            <i class="fas fa-fw fa-cog"></i>
            <span>Settings</span>
        </a>
    </li>

    <!-- Attendance -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.manage') }}#attendance">
            <i class="fas fa-fw fa-clipboard-list"></i>
            <span>Attendance</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Logout -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.logout') }}">
            <i class="fas fa-fw fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
    </li>
@endsection

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Admin Dashboard</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Stat Cards Row -->
    <div class="row">
        <!-- Total Siswa Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Siswa</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ count($siswas) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-graduate fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Guru Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Guru</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ count($gurus) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chalkboard-teacher fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Kelas Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Kelas</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ count($kelas) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-building fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Jurusan Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Jurusan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ count($jurusans) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-graduation-cap fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Aktivitas Absensi Terbaru</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Siswa</th>
                                    <th>Status</th>
                                    <th>Mapel</th>
                                    <th>Guru</th>
                                    <th>Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($attendances->take(10) as $att)
                                    <tr>
                                        <td>{{ $att->siswa->name ?? 'N/A' }}</td>
                                        <td>
                                            @if($att->status == 'Hadir')
                                                <span class="badge badge-success">{{ $att->status }}</span>
                                            @else
                                                <span class="badge badge-warning">{{ $att->status }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $att->qrcode->ajar->mapel->nama_mapel ?? 'N/A' }}</td>
                                        <td>{{ $att->qrcode->ajar->guru->name ?? 'N/A' }}</td>
                                        <td>{{ $att->scanned_at ? $att->scanned_at->diffForHumans() : '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Tidak ada aktivitas absensi.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
