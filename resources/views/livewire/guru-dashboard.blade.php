<div>
    @if(count($ajars) > 0)
        <div class="row">
            @foreach($ajars as $ajar)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body text-center">
                            <h6 class="card-title text-primary">{{ $ajar['mapel']['nama_mapel'] ?? 'N/A' }}</h6>
                            <p class="card-text mb-1">
                                <small class="text-muted">
                                    <strong>Kelas:</strong> {{ $ajar['kelas']['kelas'] ?? 'N/A' }}<br>
                                    <strong>Jurusan:</strong> {{ $ajar['jurusan']['jurusan'] ?? 'N/A' }}
                                </small>
                            </p>
                            <p class="card-text mb-2">
                                <small class="text-muted">
                                    <strong>Kode QR:</strong> <span id="qr-code-{{ $ajar['id'] }}">{{ $ajar['qrcode']['code'] ?? 'N/A' }}</span>
                                </small>
                            </p>
                            <div class="qr-wrapper mb-3" id="qr-wrapper-{{ $ajar['id'] }}">
                                {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(120)->generate($ajar['qrcode']['code'] ?? '') !!}
                            </div>
                            <button class="btn btn-outline-success btn-sm" onclick="openFullscreen({{ $ajar['id'] }})">
                                <i class="fas fa-expand"></i> Fullscreen
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
        </div>
    @endif

    <!-- Fullscreen QR Modal -->
    <div id="fullscreenOverlay" class="fullscreen-overlay" style="display: none;">
        <div class="overlay-content">
            <div class="modal-header mb-3">
                <h5 class="modal-title text-white">QR Code Absensi</h5>
                <button type="button" class="btn-close btn-close-white" onclick="closeFullscreen()"></button>
            </div>
            <div id="fullscreenQR" class="text-center mb-3"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeFullscreen()">
                    <i class="fas fa-arrow-left"></i> Kembali
                </button>
            </div>
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

    .overlay-content {
        background: white;
        padding: 2rem;
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        max-width: 90vw;
        max-height: 90vh;
        position: relative;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #dee2e6;
        padding-bottom: 1rem;
    }

    .modal-title {
        margin: 0;
        font-weight: 600;
    }

    .modal-footer {
        border-top: 1px solid #dee2e6;
        padding-top: 1rem;
        text-align: center;
    }

    .btn-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        opacity: 0.7;
    }

    .btn-close:hover {
        opacity: 1;
    }

    .overlay-content svg {
        width: 100% !important;
        height: auto !important;
        max-width: 400px;
        max-height: 400px;
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
        $wire.refreshQrCodes();
    });

    // Also listen for the old event name for backward compatibility
    Livewire.on('refreshQrCode', function(ajarId, newCode, newSvg) {
        // For individual QR code updates (if needed)
        if (ajarId && newCode && newSvg) {
            const qrCodeSpan = document.getElementById('qr-code-' + ajarId);
            const qrWrapper = document.getElementById('qr-wrapper-' + ajarId);
            if (qrCodeSpan && qrWrapper) {
                qrCodeSpan.textContent = newCode;
                qrWrapper.innerHTML = newSvg;
            }
        } else {
            // If no specific parameters, refresh all
            $wire.refreshQrCodes();
        }
    });
    </script>
</div>
