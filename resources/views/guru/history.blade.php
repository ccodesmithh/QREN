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
    <li class="nav-item active">
        <a class="nav-link" href="{{ route('guru.history') }}">
            <i class="fas fa-fw fa-chart-area"></i>
            <span>History</span></a>
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
    <h1>History Absensi Guru</h1>
    <p>Selamat datang di Histori Absensi Siswa yang Anda ajar 🎓</p>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-header">
            <h5>Filter</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('guru.history') }}">
                <div class="row">
                    <div class="col-md-3">
                        <label for="kelas_id">Kelas</label>
                        <select name="kelas_id" id="kelas_id" class="form-control">
                            <option value="">Semua Kelas</option>
                            @foreach($kelas as $k)
                                <option value="{{ $k->id }}" {{ $request->kelas_id == $k->id ? 'selected' : '' }}>{{ $k->kelas }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="jurusan_id">Jurusan</label>
                        <select name="jurusan_id" id="jurusan_id" class="form-control">
                            <option value="">Semua Jurusan</option>
                            @foreach($jurusans as $j)
                                <option value="{{ $j->id }}" {{ $request->jurusan_id == $j->id ? 'selected' : '' }}>{{ $j->jurusan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="nama_siswa">Nama Siswa</label>
                        <input type="text" name="nama_siswa" id="nama_siswa" class="form-control" value="{{ $request->nama_siswa }}" placeholder="Cari nama siswa">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary mr-2">Filter</button>
                        <a href="{{ route('guru.history') }}" class="btn btn-secondary">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Grouped Data with Accordions -->
    @if($grouped->isNotEmpty())
        @php $kelasCounter = 0; @endphp
        <!-- Kelas Accordion -->
        <div class="accordion" id="kelasAccordion">
            @foreach($grouped as $kelasName => $byJurusan)
                <div class="card">
                    <div class="card-header" id="heading-{{ $kelasCounter }}">
                        <h2 class="mb-1">
                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapse-{{ $kelasCounter }}" aria-expanded="{{ $kelasCounter == 0 ? 'true' : 'false' }}" aria-controls="collapse-{{ $kelasCounter }}">
                                {{ $kelasName }}
                            </button>
                        </h2>
                    </div>
                    <div id="collapse-{{ $kelasCounter }}" class="collapse {{ $kelasCounter == 0 ? 'show' : '' }}" aria-labelledby="heading-{{ $kelasCounter }}" data-parent="#kelasAccordion">
                        <div class="card-body">
                            @php 
                                $jurusanCounter = 0; 
                                $kelasId = $kelasCounter;
                            @endphp
                            <!-- Jurusan Accordion -->
                            <div class="accordion" id="jurusanAccordion-{{ $kelasId }}">
                                @foreach($byJurusan as $jurusanName => $byDate)
                                    <div class="card">
                                        <div class="card-header" id="heading-jurusan-{{ $kelasId }}-{{ $jurusanCounter }}">
                                            <h3 class="mb-0">
                                                <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapse-jurusan-{{ $kelasId }}-{{ $jurusanCounter }}" aria-expanded="{{ $jurusanCounter == 0 ? 'true' : 'false' }}" aria-controls="collapse-jurusan-{{ $kelasId }}-{{ $jurusanCounter }}">
                                                    {{ $jurusanName }}
                                                </button>
                                            </h3>
                                        </div>
                                        <div id="collapse-jurusan-{{ $kelasId }}-{{ $jurusanCounter }}" class="collapse {{ $jurusanCounter == 0 ? 'show' : '' }}" aria-labelledby="heading-jurusan-{{ $kelasId }}-{{ $jurusanCounter }}" data-parent="#jurusanAccordion-{{ $kelasId }}">
                                            <div class="card-body">
                                                @php 
                                                    $dateCounter = 0; 
                                                    $jurusanId = $jurusanCounter;
                                                @endphp
                                                <!-- Date Accordion -->
                                                <div class="accordion" id="dateAccordion-{{ $kelasId }}-{{ $jurusanId }}">
                                                    @foreach($byDate as $dateString => $attendances)
                                                        <div class="card">
                                                            <div class="card-header" id="heading-date-{{ $kelasId }}-{{ $jurusanId }}-{{ $dateCounter }}">
                                                                <h4 class="mb-0">
                                                                    <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapse-date-{{ $kelasId }}-{{ $jurusanId }}-{{ $dateCounter }}" aria-expanded="{{ $dateCounter == 0 ? 'true' : 'false' }}" aria-controls="collapse-date-{{ $kelasId }}-{{ $jurusanId }}-{{ $dateCounter }}">
                                                                        {{ \Carbon\Carbon::parse($dateString)->format('d-m-Y') }}
                                                                    </button>
                                                                </h4>
                                                            </div>
                                                            <div id="collapse-date-{{ $kelasId }}-{{ $jurusanId }}-{{ $dateCounter }}" class="collapse {{ $dateCounter == 0 ? 'show' : '' }}" aria-labelledby="heading-date-{{ $kelasId }}-{{ $jurusanId }}-{{ $dateCounter }}" data-parent="#dateAccordion-{{ $kelasId }}-{{ $jurusanId }}">
                                                                <div class="card-body">
                                                                    <div class="table-responsive">
                                                                        <table class="table table-bordered">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>No</th>
                                                                                    <th>Nama Siswa</th>
                                                                                    <th>Mata Pelajaran</th>
                                                                                    <th>Jam Awal - Akhir</th>
                                                                                    <th>Status</th>
                                                                                    <th>Scanned At</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach($attendances as $index => $attendance)
                                                                                <tr>
                                                                                    <td>{{ $index + 1 }}</td>
                                                                                    <td>{{ $attendance->siswa->name ?? '-' }}</td>
                                                                                    <td>{{ $attendance->qrcode->ajar->mapel->nama_mapel ?? '-' }}</td>
                                                                                    <td>{{ $attendance->qrcode->ajar->jam_awal ?? '-' }} - {{ $attendance->qrcode->ajar->jam_akhir ?? '-' }}</td>
                                                                                    <td>{{ $attendance->status }}</td>
                                                                                    <td>{{ $attendance->scanned_at ? $attendance->scanned_at->format('H:i:s') : '-' }}</td>
                                                                                </tr>
                                                                                @endforeach
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @php $dateCounter++; @endphp
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @php $jurusanCounter++; @endphp
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                @php $kelasCounter++; @endphp
            @endforeach
        </div>
    @else
        <div class="alert alert-info">
            Tidak ada data absensi yang ditemukan.
        </div>
    @endif
</div>
@endsection
