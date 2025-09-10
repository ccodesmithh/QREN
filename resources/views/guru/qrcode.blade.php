@extends('layouts.app')

@section('content')
<div class="container">
    <h1>QR Code Kehadiran</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- <form action="{{ route('guru.qrcode.generate') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-primary mb-3">Generate QR Baru</button>
    </form> -->

    @if($qr)
        <div class="card p-3">
            <h5>Kode: {{ $qr->code }}</h5>
            {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(250)->generate($qr->code) !!}
        </div>
    @else
        <p>Belum ada QR Code. Klik tombol untuk membuat.</p>
    @endif
</div>
<form action="{{ route('guru.qrcode.generate') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label for="guru_id" class="form-label">Pilih Guru Ini cuma buat tes</label>
        <select name="guru_id" id="guru_id" class="form-select">
            @foreach(\App\Models\User::all() as $guru)
                <option value="{{ $guru->id }}">{{ $guru->name }}</option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="btn btn-primary mb-3">Generate QR Baru</button>
</form>

@endsection
