@extends('layouts.dashboard.index')
@section('sidebar')
    <!-- Nav Item - Dashboard -->
    <li class="nav-item">
        <a class="nav-link" href="{{route('guru.dashboard')}}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>
    <li class="nav-item active">
        <a class="nav-link" href="{{ route('guru.qrcode.index') }}">
            <i class="fas fa-fw fa-camera"></i>
            <span>QR</span></a>
    </li>
    <li class="nav-item">
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
    <h1>QREN Generator</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <h3>Jadwal Mengajar</h3>
    @if($ajars->count() > 0)
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Mata Pelajaran</th>
                        <th>Kelas</th>
                        <th>Jurusan</th>
                        <th>Jam Awal</th>
                        <th>Jam Akhir</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ajars as $ajar)
                        <tr>
                            <td>{{ $ajar->mapel->nama_mapel ?? 'N/A' }}</td>
                            <td>{{ $ajar->kelas->kelas ?? 'N/A' }}</td>
                            <td>{{ $ajar->jurusan->jurusan ?? 'N/A' }}</td>
                            <td>{{ $ajar->jam_awal }}</td>
                            <td>{{ $ajar->jam_akhir }}</td>
                            <td>
                                @if($ajar->qrcode)
                                    <div class="card p-2 text-center">
                                        <h6>Kode: {{ $ajar->qrcode->code }}</h6>
                                        <div class="qr-wrapper">
                                            {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(150)->generate($ajar->qrcode->code) !!}
                                        </div>
                                        <div id="qr-full-{{ $ajar->id }}" style="display: none;">{!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(400)->generate($ajar->qrcode->code) !!}</div>
                                        <button class="btn btn-sm btn-success mt-2" onclick="openFullscreen({{ $ajar->id }})">
                                            Fullscreen
                                        </button>
                                    </div>
                                @else
                                    <div id="location-section-{{ $ajar->id }}">
                                        <button type="button" class="btn btn-warning btn-sm" onclick="enableLocation({{ $ajar->id }})">Aktifkan Lokasi</button>
                                        <p id="location-status-{{ $ajar->id }}" class="text-muted small mt-1">Lokasi belum diaktifkan</p>
                                        <div id="manual-location-{{ $ajar->id }}" class="mt-3" style="display: none;">
                                            <label class="form-label">Masukkan Koordinat Manual (untuk testing desktop):</label>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <input type="number" step="any" id="manual-lat-{{ $ajar->id }}" class="form-control" placeholder="Latitude (contoh: -6.2088)">
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="number" step="any" id="manual-lng-{{ $ajar->id }}" class="form-control" placeholder="Longitude (contoh: 106.8456)">
                                                </div>
                                            </div>
                                            <button type="button" onclick="useManualLocation({{ $ajar->id }})" class="btn btn-secondary mt-2">Gunakan Koordinat Ini</button>
                                        </div>
                                    </div>
                                    <div id="generate-section-{{ $ajar->id }}" style="display: none;">
                                        <form action="{{ route('guru.qrcode.generate') }}" method="POST" style="display:inline;" id="form-{{ $ajar->id }}">
                                            @csrf
                                            <input type="hidden" name="ajar_id" value="{{ $ajar->id }}">
                                            <input type="hidden" name="teacher_lat" id="lat-{{ $ajar->id }}">
                                            <input type="hidden" name="teacher_lng" id="lng-{{ $ajar->id }}">
                                            <button type="submit" class="btn btn-primary btn-sm">Generate QR</button>
                                        </form>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p>Tidak ada jadwal mengajar.</p>
    @endif
</div>

<div id="fullscreenOverlay" class="fullscreen-overlay" style="display: none;">
    <div class="overlay-content text-center">
        <div id="fullscreenQR"></div>
        <button class="btn btn-danger mt-3" onclick="closeFullscreen()">Back</button>
    </div>
</div>

<style>
.fullscreen-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: white;
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1050;
}

.overlay-content {
    text-align: center;
}

.overlay-content svg {
    width: 80vw !important;
    height: 80vw !important;
    max-width: 500px;
    max-height: 500px;
}
</style>

<script>
function openFullscreen(id) {
    let qrContainer = document.getElementById('fullscreenQR');
    qrContainer.innerHTML = document.getElementById('qr-full-' + id).innerHTML;
    document.getElementById('fullscreenOverlay').style.display = 'flex';
}

function closeFullscreen() {
    document.getElementById('fullscreenOverlay').style.display = 'none';
}

function enableLocation(ajarId) {
    if (navigator.geolocation) {
        const options = {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 0
        };
        navigator.geolocation.getCurrentPosition(function(position) {
            document.getElementById('lat-' + ajarId).value = position.coords.latitude;
            document.getElementById('lng-' + ajarId).value = position.coords.longitude;
            document.getElementById('location-status-' + ajarId).innerText = 'Lokasi berhasil diaktifkan';
            document.getElementById('location-section-' + ajarId).style.display = 'none';
            document.getElementById('generate-section-' + ajarId).style.display = 'block';
        }, function(error) {
            let message = 'Error getting location: ';
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    message += 'Izin lokasi ditolak. Silakan izinkan akses lokasi di browser Anda.';
                    break;
                case error.POSITION_UNAVAILABLE:
                    message += 'Lokasi tidak tersedia. Pastikan GPS atau layanan lokasi diaktifkan.';
                    break;
                case error.TIMEOUT:
                    message += 'Waktu habis mendapatkan lokasi. Coba lagi.';
                    break;
                default:
                    message += error.message;
            }
            alert(message);
        }, options);
    } else {
        alert('Geolocation tidak didukung oleh browser ini.');
    }
}
</script>

@endsection
