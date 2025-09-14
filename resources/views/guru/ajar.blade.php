@extends('layouts.dashboard.index')
@section('sidebar')
    <!-- Nav Item - Dashboard -->
    <li class="nav-item">
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
    <li class="nav-item active">
        <a class="nav-link" href="{{ route('guru.jadwal') }}">
            <i class="fas fa-fw fa-book"></i>
            <span>Jadwal Mengajar</span></a>
    </li>
@endsection

@section('content')
<div class="container">
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    
    @elseif (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <h1>Ajar</h1>
    <p>Silakan set jadwal mengajar anda.</p>
    <div class="content">
        <form action="{{ route('guru.ajar.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="mapel_id" class="form-label">Mata Pelajaran</label>
                <select name="mapel_id" id="mapel_id" class="form-control">
                    @foreach(\App\Models\Mapel::all() as $mapel)
                        <option value="{{ $mapel->id }}">{{ $mapel->nama_mapel }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="kelas_id" class="form-label">Kelas</label>
                <select name="kelas_id" id="kelas_id" class="form-control">
                    @foreach(\App\Models\Kelas::all() as $kelas)
                        <option value="{{ $kelas->id }}">{{ $kelas->kelas }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="jurusan_id" class="form-label">Jurusan</label>
                <select name="jurusan_id" id="jurusan_id" class="form-control">
                    @foreach(\App\Models\Jurusan::all() as $jurusan)
                        <option value="{{ $jurusan->id }}">{{ $jurusan->jurusan }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="jam_awal" class="form-label">Jam Awal</label>
                <select name="jam_awal" id="jam_awal" class="form-control">
                    @foreach(range(1, 10) as $hour)
                        <option value="{{ $hour }}">{{ $hour }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="jam_akhir" class="form-label">Jam Akhir</label>
                <select name="jam_akhir" id="jam_akhir" class="form-control">
                    @foreach(range(1, 10) as $hour)
                        <option value="{{ $hour }}">{{ $hour }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Simpan Jadwal</button>
        </form>
    </div>
</div>
@endsection
