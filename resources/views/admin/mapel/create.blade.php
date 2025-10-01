@extends('layouts.dashboard.index')

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tambah Mata Pelajaran</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Formulir Mata Pelajaran</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.mapel.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="nama_mapel">Nama Mata Pelajaran</label>
                    <input type="text" class="form-control @error('nama_mapel') is-invalid @enderror" id="nama_mapel" name="nama_mapel" value="{{ old('nama_mapel') }}" required>
                    @error('nama_mapel')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('admin.mapel.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>

</div>
@endsection
