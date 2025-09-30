@extends('layouts.dashboard.index')

@section('sidebar')
    <li class="nav-item">
        <a class="nav-link" href="{{ route('guru.dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>
    <li class="nav-item active">
        <a class="nav-link" href="{{ route('guru.journal.index') }}">
            <i class="fas fa-fw fa-book"></i>
            <span>Jurnal Mengajar</span>
        </a>
    </li>
@endsection

@section('content')
<div class="container">
    <h1>Jurnal Mengajar</h1>

    <div class="card">
        <div class="card-header">
            <h5>{{ $journal->ajar->mapel->nama_mapel ?? '-' }} - {{ $journal->ajar->kelas->kelas ?? '-' }} - {{ $journal->ajar->jurusan->jurusan ?? '-' }}</h5>
            <small class="text-muted">Tanggal: {{ $journal->date->format('d-m-Y') }}</small>
        </div>
        <div class="card-body">
            <h6>Isi Jurnal:</h6>
            <p>{{ $journal->content }}</p>
        </div>
    </div>

    <a href="{{ route('guru.journal.index') }}" class="btn btn-secondary mt-3">Kembali</a>
</div>
@endsection
