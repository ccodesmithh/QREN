@extends('layouts.dashboard.index')

@section('sidebar')
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>
    <hr class="sidebar-divider">
    <div class="sidebar-heading">Manajemen Data</div>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.manage') }}#siswa">
            <i class="fas fa-fw fa-user-graduate"></i>
            <span>Siswa</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.manage') }}#guru">
            <i class="fas fa-fw fa-chalkboard-teacher"></i>
            <span>Guru</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.manage') }}#kelas">
            <i class="fas fa-fw fa-building"></i>
            <span>Kelas</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.manage') }}#jurusan">
            <i class="fas fa-fw fa-graduation-cap"></i>
            <span>Jurusan</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.manage') }}#mapel">
            <i class="fas fa-fw fa-book"></i>
            <span>Mata Pelajaran</span></a>
    </li>
    <div class="sidebar-heading mt-3">Sistem</div>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.manage') }}#settings">
            <i class="fas fa-fw fa-cog"></i>
            <span>Settings</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.manage') }}#attendance">
            <i class="fas fa-fw fa-clipboard-list"></i>
            <span>Attendance</span></a>
    </li>
    <hr class="sidebar-divider d-none d-md-block">
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.logout') }}">
            <i class="fas fa-fw fa-sign-out-alt"></i>
            <span>Logout</span></a>
    </li>
@endsection

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Manajemen Data</h1>
    <p class="mb-4">Kelola data siswa, guru, kelas, jurusan, dan pengaturan sistem.</p>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <ul class="nav nav-tabs card-header-tabs" id="adminTabs" role="tablist">
                <li class="nav-item"><a class="nav-link active" data-toggle="tab" id="settings-tab-link" href="#settings">Settings</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" id="siswa-tab-link" href="#siswa">Siswa</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" id="guru-tab-link" href="#guru">Guru</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" id="kelas-tab-link" href="#kelas">Kelas</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" id="jurusan-tab-link" href="#jurusan">Jurusan</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" id="mapel-tab-link" href="#mapel">Mata Pelajaran</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" id="attendance-tab-link" href="#attendance">Attendance</a></li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="adminTabsContent">
                <div class="tab-pane fade show active" id="settings" role="tabpanel">
                    @include('admin.partials.settings')
                </div>
                <div class="tab-pane fade" id="siswa" role="tabpanel">
                    @include('admin.partials.siswa')
                </div>
                <div class="tab-pane fade" id="guru" role="tabpanel">
                    @include('admin.partials.guru')
                </div>
                <div class="tab-pane fade" id="kelas" role="tabpanel">
                    @include('admin.partials.kelas')
                </div>
                <div class="tab-pane fade" id="jurusan" role="tabpanel">
                    @include('admin.partials.jurusan')
                </div>
                <div class="tab-pane fade" id="mapel" role="tabpanel">
                    @include('admin.partials.mapel')
                </div>
                <div class="tab-pane fade" id="attendance" role="tabpanel">
                    @include('admin.partials.attendance')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(function(){
        function showTabFromHash() {
            let hash = window.location.hash;
            if (hash) {
                let tabLink = $('#adminTabs a[href="' + hash + '"]');
                if (tabLink.length) {
                    tabLink.tab('show');
                }
            }
        }

        // Show tab on initial page load
        showTabFromHash();

        // Show tab when hash changes (e.g., from sidebar click)
        $(window).on('hashchange', function() {
            showTabFromHash();
        });

        // Update URL hash when a tab is clicked directly
        $('#adminTabs a').on('shown.bs.tab', function (e) {
            if (history.pushState) {
                history.pushState(null, null, e.target.hash);
            } else {
                window.location.hash = e.target.hash;
            }
        });
    });
</script>
@endpush
