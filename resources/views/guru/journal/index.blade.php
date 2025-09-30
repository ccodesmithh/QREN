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
        <a class="nav-link" href="{{ route('guru.history') }}">
            <i class="fas fa-fw fa-chart-area"></i>
            <span>History</span></a>
    </li>
    <li class="nav-item active">
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
<div class="container">
    <h1>Jurnal Mengajar</h1>
    <a href="{{ route('guru.journal.create') }}" class="btn btn-primary mb-3">Buat Jurnal Baru</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($journals->count() > 0)
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Mata Pelajaran</th>
                    <th>Kelas</th>
                    <th>Jurusan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($journals as $index => $journal)
                <tr>
                    <td>{{ $journals->firstItem() + $index }}</td>
                    <td>{{ $journal->date->format('d-m-Y') }}</td>
                    <td>{{ $journal->ajar->mapel->nama_mapel ?? '-' }}</td>
                    <td>{{ $journal->ajar->kelas->kelas ?? '-' }}</td>
                    <td>{{ $journal->ajar->jurusan->jurusan ?? '-' }}</td>
                    <td>
                        <a href="{{ route('guru.journal.show', $journal) }}" class="btn btn-info btn-sm">Lihat</a>
                        <a href="{{ route('guru.journal.edit', $journal) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('guru.journal.destroy', $journal) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus jurnal ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{ $journals->links() }}
    @else
        <p>Tidak ada jurnal mengajar.</p>
    @endif
</div>
@endsection
