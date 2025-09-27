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
    <h1>Edit Guru</h1>

    <form method="POST" action="{{ route('admin.guru.update', $guru) }}">
        @csrf
        @method('PUT')

        <div class="form-group mb-3">
            <label for="idguru">ID Guru</label>
            <input type="text" class="form-control" id="idguru" name="idguru" value="{{ $guru->idguru }}" required>
        </div>

        <div class="form-group mb-3">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $guru->name }}" required>
        </div>

        <div class="form-group mb-3">
            <label for="password">Password (leave blank to keep current)</label>
            <input type="password" class="form-control" id="password" name="password">
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
