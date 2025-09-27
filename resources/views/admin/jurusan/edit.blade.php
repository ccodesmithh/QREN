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
    <h1>Edit Jurusan</h1>

    <form method="POST" action="{{ route('admin.jurusan.update', $jurusan) }}">
        @csrf
        @method('PUT')

        <div class="form-group mb-3">
            <label for="jurusan">Jurusan</label>
            <input type="text" class="form-control" id="jurusan" name="jurusan" value="{{ $jurusan->jurusan }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
