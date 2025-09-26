@extends('layouts.dashboard.index')
@section('sidebar')
    <!-- Nav Item - Dashboard -->
    <li class="nav-item">
        <a class="nav-link" href="{{route('guru.dashboard')}}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>
    <li class="nav-item active">
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
    <h1>QREN Generator</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <h3>Jadwal Mengajar</h3>
    @if($ajars->count() > 0)
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Mata Pelajaran</th>
                        <th>Kelas</th>
                        <th>Jurusan</th>
                        <th>Jam Awal</th>
                        <th>Jam Akhir</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ajars as $ajar)
                        <tr>
                            <td>{{ $ajar->mapel->nama_mapel ?? 'N/A' }}</td>
                            <td>{{ $ajar->kelas->kelas ?? 'N/A' }}</td>
                            <td>{{ $ajar->jurusan->jurusan ?? 'N/A' }}</td>
                            <td>{{ $ajar->jam_awal }}</td>
                            <td>{{ $ajar->jam_akhir }}</td>
                            <td>
                                @if($ajar->qrcode)
                                    <div class="card p-2">
                                        <h6>Kode: {{ $ajar->qrcode->code }}</h6>
                                        {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(150)->generate($ajar->qrcode->code) !!}
                                    </div>
                                @else
                                    <form action="{{ route('guru.qrcode.generate') }}" method="POST" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="ajar_id" value="{{ $ajar->id }}">
                                        <button type="submit" class="btn btn-primary btn-sm">Generate QR</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p>Tidak ada jadwal mengajar.</p>
    @endif
</div>

@endsection
