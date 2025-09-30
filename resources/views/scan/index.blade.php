@extends('layouts.scan')

@push('styles')
<style>
    #reader {
        width: 100%;
        border-radius: 5px;
        display: none; /* Hidden by default */
    }
    .result-container {
        margin-top: 15px;
    }
    #scan-fab {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 70px;
        height: 70px;
        background-color: #4e73df;
        border-radius: 50%;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        display: flex;
        justify-content: center;
        align-items: center;
        color: white;
        font-size: 28px;
        cursor: pointer;
        transition: all 0.3s ease;
        z-index: 1050;
    }
    #scan-fab:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 20px rgba(0,0,0,0.3);
    }
    .scanner-active #scan-fab {
        display: none;
    }
    .scanner-active #reader {
        display: block;
    }
    .scanner-active #welcome-message {
        display: none;
    }
</style>
@endpush

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <div class="p-5">
                    <div id="welcome-message" class="text-center">
                        <h1 class="h4 text-gray-900 mb-4">QREN</h1>
                        <p>Selamat datang, {{ Auth::user()->name }}! Tekan tombol di kanan bawah untuk mulai scanning.</p>
                    </div>

                    <div id="reader"></div>

                    <div class="text-center result-container">
                        <div id="result" class="mt-3"></div>
                    </div>

                    <hr>

                    <div class="text-center">
                        <a href="{{ route('siswa.dashboard') }}" class="btn btn-secondary">Masuk ke Dashboard</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-lg mb-5">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Debug Log (Developer)</h6>
            </div>
            <div class="card-body">
                <pre id="debug" class="small" style="max-height: 200px; overflow-y: auto; background-color: #f8f9fa; padding: 10px; border-radius: 5px;"></pre>
            </div>
        </div>
    </div>
</div>

<div id="scan-fab">
    <i class="fas fa-camera"></i>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const html5QrCode = new Html5Qrcode("reader");
        const scanFab = document.getElementById('scan-fab');
        const resultDiv = document.getElementById('result');
        const debugPre = document.getElementById('debug');
        const body = document.body;
        let isProcessing = false;

        function appendDebug(message) {
            console.log(message);
            debugPre.textContent += message + '\n';
            debugPre.scrollTop = debugPre.scrollHeight;
        }

        scanFab.addEventListener('click', function(){
            startCamera();
        });

        function startCamera() {
            body.classList.add('scanner-active');
            scanFab.style.display = 'none'; // Hide the button immediately
            appendDebug('Starting camera...');

            html5QrCode.start(
                { facingMode: "environment" }, 
                {
                    fps: 10,
                    qrbox: (videoWidth, videoHeight) => {
                        const size = Math.min(videoWidth, videoHeight) * 0.75;
                        return { width: size, height: size };
                    }
                },
                onScanSuccess,
                onScanFailure
            ).then(() => {
                appendDebug('Camera started successfully.');
            }).catch(err => {
                appendDebug('Error starting camera: ' + err);
                resultDiv.innerHTML = `<div class="alert alert-danger">Error starting camera: ${err}</div>`;
                body.classList.remove('scanner-active');
                scanFab.style.display = 'flex';
            });
        }

        function onScanSuccess(decodedText, decodedResult) {
            if (isProcessing) return;
            isProcessing = true;

            resultDiv.innerHTML = `<div class="alert alert-info">Processing...</div>`;
            appendDebug(`Scan successful: ${decodedText}`);

            const payload = {
                code: decodedText,
                lat: null,
                lng: null
            };

            const geolocationOptions = {
                enableHighAccuracy: {{ \App\Models\Setting::getValue('enable_high_accuracy', 'true') === 'true' ? 'true' : 'false' }},
                timeout: {{ \App\Models\Setting::getValue('geolocation_timeout', '10000') }},
                maximumAge: {{ \App\Models\Setting::getValue('max_age', '0') }}
            };

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    position => {
                        payload.lat = position.coords.latitude;
                        payload.lng = position.coords.longitude;
                        sendScanData(payload);
                    },
                    error => {
                        appendDebug('Error getting location: ' + error.message);
                        resultDiv.innerHTML = `<div class="alert alert-danger">Error getting location: ${error.message}</div>`;
                        resetScanner();
                    },
                    geolocationOptions
                );
            } else {
                resultDiv.innerHTML = `<div class="alert alert-danger">Geolocation is not supported by this browser.</div>`;
                resetScanner();
            }
        }

        function sendScanData(payload) {
            appendDebug('Sending data: ' + JSON.stringify(payload));

            fetch('{{ route("siswa.scan.submit") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(payload)
            })
            .then(response => response.json())
            .then(data => {
                appendDebug('Response received: ' + JSON.stringify(data));
                if (data.success) {
                    resultDiv.innerHTML = `<div class="alert alert-success">${data.message} (Distance: ${data.distance}m)</div>`;
                } else {
                    resultDiv.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
                }
                resetScanner();
            })
            .catch(error => {
                appendDebug('Fetch error: ' + error);
                resultDiv.innerHTML = `<div class="alert alert-danger">An error occurred.</div>`;
                resetScanner();
            });
        }

        function onScanFailure(error) {
            // appendDebug('Scan failure: ' + error);
        }

        function resetScanner() {
            const cooldown = {{ \App\Models\Setting::getValue('scan_cooldown', '10') }} * 1000;
            setTimeout(() => {
                isProcessing = false;
                resultDiv.innerHTML = '';
            }, cooldown);
        }
    });
</script>
@endpush
