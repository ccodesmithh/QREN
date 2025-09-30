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
    <h1>Buat Jurnal Mengajar</h1>

    <form action="{{ route('guru.journal.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="ajar_id">Pilih Jadwal Mengajar</label>
            <select name="ajar_id" id="ajar_id" class="form-control @error('ajar_id') is-invalid @enderror" required>
                <option value="">Pilih Jadwal</option>
                @foreach($ajars as $ajar)
                    <option value="{{ $ajar->id }}" {{ $selectedAjar && $selectedAjar->id == $ajar->id ? 'selected' : '' }}>
                        {{ $ajar->mapel->nama_mapel ?? '-' }} - {{ $ajar->kelas->kelas ?? '-' }} - {{ $ajar->jurusan->jurusan ?? '-' }} ({{ $ajar->jam_awal }}-{{ $ajar->jam_akhir }})
                    </option>
                @endforeach
            </select>
            @error('ajar_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="date">Tanggal</label>
            <input type="date" name="date" id="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date', $selectedDate) }}" required>
            @error('date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="content">Isi Jurnal</label>
            <textarea name="content" id="content" class="form-control @error('content') is-invalid @enderror" rows="10" placeholder="Deskripsikan pelajaran yang diberikan..." required>{{ old('content') }}</textarea>
            @error('content')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Simpan Jurnal</button>
        <a href="{{ route('guru.journal.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
