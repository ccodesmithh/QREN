@extends('layouts.app')
@section('sidebar')
    <!-- Nav Item - Dashboard -->
    <li class="nav-item">
        <a class="nav-link" href="{{route('guru.dashboard')}}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>
    <li class="nav-item active">
        <a class="nav-link" href="{{ route('guru.generate') }}">
            <i class="fas fa-fw fa-chart-area"></i>
            <span>QR</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#">
            <i class="fas fa-fw fa-table"></i>
            <span>Profil</span></a>
    </li>
@endsection

@section('content')
<div class="container">
    <h1>QREN Generator</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($qr)
        <div class="card p-3">
            <h5>Kode: {{ $qr->code }}</h5>
            {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(250)->generate($qr->code) !!}
        </div>
    @else
        <p>Belum ada QR Code. Klik tombol untuk membuat.</p>
    @endif
</div>
<form action="{{ route('guru.generate') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label for="guru_id" class="form-label">Pilih Guru Ini cuma buat tes</label>
        <select name="guru_id" id="guru_id" class="form-select">
            @foreach(\App\Models\Guru::all() as $guru)
                <option value="{{ $guru->id }}">{{ $guru->name }}</option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="btn btn-primary mb-3">Generate QR Baru</button>
</form>

@endsection
