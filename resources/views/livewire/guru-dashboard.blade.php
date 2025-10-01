<div>
    @if(count($ajars) > 0)
        <div class="row">
            @foreach($ajars as $ajar)
                <div class="col-12 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-light py-3">
                            <h6 class="m-0 font-weight-bold text-primary">{{ $ajar['mapel']['nama_mapel'] ?? 'N/A' }}</h6>
                        </div>
                        <div class="card-body text-center">
                            <div class="qr-wrapper mb-3" id="qr-wrapper-{{ $ajar['id'] }}">
                                {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(150)->generate($ajar['qrcode']['code'] ?? '') !!}
                            </div>
                            <p class="card-text mb-2">
                                <span class="badge badge-secondary">{{ $ajar['kelas']['kelas'] ?? 'N/A' }} - {{ $ajar['jurusan']['jurusan'] ?? 'N/A' }}</span>
                            </p>
                            <p class="card-text">
                                <small class="text-muted">Kode: <span id="qr-code-{{ $ajar['id'] }}" class="font-weight-bold">{{ $ajar['qrcode']['code'] ?? 'N/A' }}</span></small>
                            </p>
                        </div>
                        <div class="card-footer text-center">
                            <button class="btn btn-primary btn-sm" onclick="openFullscreen({{ $ajar['id'] }})">
                                <i class="fas fa-expand mr-2"></i>Tampilkan
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-qrcode fa-3x text-muted mb-3"></i>
            <p class="text-muted">Tidak ada QR Code tersedia.</p>
            <p><small>Anda bisa membuat QR Code di halaman <a href="{{ route('guru.qrcode.index') }}">QR</a>.</small></p>
        </div>
    @endif

    <!-- Fullscreen QR Modal -->
    <div id="fullscreenOverlay" class="fullscreen-overlay" style="display: none;">
        <div class="overlay-content text-center p-4">
            <div id="fullscreenQR" class="mb-4"></div>
            <button type="button" class="btn btn-light" onclick="closeFullscreen()">
                <i class="fas fa-times mr-2"></i> Tutup
            </button>
        </div>
    </div>

    <style>
    .fullscreen-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.9);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1050;
        backdrop-filter: blur(5px);
    }

    .overlay-content svg {
        width: 100% !important;
        height: auto !important;
        max-width: 60vh;
        max-height: 60vh;
        background: white;
        padding: 20px;
        border-radius: 15px;
    }
    </style>

    <script>
    function openFullscreen(id) {
        let qrContainer = document.getElementById('fullscreenQR');
        let qrWrapper = document.getElementById('qr-wrapper-' + id);
        if (qrWrapper) {
            qrContainer.innerHTML = qrWrapper.innerHTML;
            document.getElementById('fullscreenOverlay').style.display = 'flex';
        }
    }

    function closeFullscreen() {
        document.getElementById('fullscreenOverlay').style.display = 'none';
    }

    // Listen for Livewire event to refresh QR codes
    Livewire.on('qrCodeUpdated', function() {
        // Refresh all QR codes by reloading the Livewire component
        $wire.call('loadAjars');
    });

    // Also listen for the old event name for backward compatibility
    Livewire.on('refreshQrCode', function(data) {
        if (data && data.ajarId) {
             $wire.call('loadAjars');
        } else {
            $wire.call('loadAjars');
        }
    });
    </script>
</div>
