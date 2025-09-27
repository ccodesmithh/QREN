@extends('layouts.dashboard.index')

@section('sidebar')
    <!-- Nav Item - Back to Dashboard -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.dashboard') }}">
            <i class="fas fa-arrow-left"></i>
            <span>Back to Dashboard</span></a>
    </li>
@endsection

@section('content')
<div class="container">
    <h1>Edit Siswa</h1>

    <form method="POST" action="{{ route('admin.siswa.update', $siswa) }}">
        @csrf
        @method('PUT')

        <div class="form-group mb-3">
            <label for="nisn">NISN</label>
            <input type="text" class="form-control" id="nisn" name="nisn" value="{{ $siswa->nisn }}" required>
        </div>

        <div class="form-group mb-3">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $siswa->name }}" required>
        </div>

        <div class="form-group mb-3">
            <label for="password">Password (leave blank to keep current)</label>
            <input type="password" class="form-control" id="password" name="password">
        </div>

        <div class="form-group mb-3">
            <label for="kelas_id">Kelas</label>
            <select class="form-control" id="kelas_id" name="kelas_id" required>
                @foreach($kelas as $k)
                <option value="{{ $k->id }}" {{ $siswa->kelas_id == $k->id ? 'selected' : '' }}>{{ $k->kelas }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group mb-3">
            <label for="jurusan_id">Jurusan</label>
            <select class="form-control" id="jurusan_id" name="jurusan_id" required>
                @foreach($jurusans as $j)
                <option value="{{ $j->id }}" {{ $siswa->jurusan_id == $j->id ? 'selected' : '' }}>{{ $j->jurusan }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
