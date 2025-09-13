<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QREN</title>
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <style>
        body { font-family: sans-serif; text-align: center; padding: 20px; }
        #reader { width: 300px; margin: auto; }
        .result { margin-top: 15px; font-size: 1.2rem; font-weight: bold; }
        #debug { text-align: left; max-height: 240px; overflow: auto; background: #f7f7f7; padding: 10px; border: 1px solid #ddd; margin: 15px auto; width: 90%; white-space: pre-wrap; font-family: monospace; font-size: 0.8rem; }
        #preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 9999;
            background-color: #fff;
            display: flex; 
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        body {
            font-family: sans-serif;
            text-align: center;
            padding: 20px;
        }
        #reader {
            width: 300px;
            margin: auto;
        }
        .result {
            margin-top: 15px;
            font-size: 1.2rem;
            font-weight: bold;
        }
        #debug {
            text-align: left;
            max-height: 240px;
            overflow: auto;
            background: #f7f7f7;
            padding: 10px;
            border: 1px solid #ddd;
            margin: 15px auto;
            width: 90%;
            white-space: pre-wrap;
            font-family: monospace;
            font-size: 0.8rem;
        }
        #preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 9999;
            background-color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        
        #camera-trigger {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1000;
            background: linear-gradient(135deg, #667eea 0%, #0f2555ff 100%);
            border: none;
            border-radius: 50%;
            width: 120px;
            height: 120px;
            cursor: pointer;
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
        }
        
        #camera-trigger:hover {
            transform: translate(-50%, -50%) scale(1.05);
            box-shadow: 0 12px 35px rgba(0,0,0,0.4);
            text-shadow: 0 12px 35px rgba(255, 255, 255, 0.2);
        }
        
        #camera-trigger:active {
            transform: translate(-50%, -50%) scale(0.95);
        }
        
        #camera-trigger i {
            font-size: 40px;
            margin-bottom: 5px;
        }
        
        #camera-trigger span {
            font-size: 12px;
            font-weight: bold;
        }
        
        #camera-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.7);
            /* backdrop-filter: blur(10px); */
            z-index: 999;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        #camera-overlay img {
            width: 200px;
            height: auto;
            margin-bottom: 10rem;
        }
                
        #camera-overlay p {
            color: rgba(255,255,255,0.8);
            margin-bottom: 40px;
            font-size: 16px;
        }
        
        .hidden {
            display: none !important;
        }

        .loading-spinner {
            width: 40px;
            height: 40px;
            border: 4px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
            margin-bottom: 10px;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
    <link href='https://cdn.boxicons.com/fonts/brands/boxicons-brands.min.css' rel='stylesheet'>
    <link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="{{ asset('css/scan.css') }}">
</head>
<body>
    <div id="preloader">
        <div class="loading">
            <div class="spinner-border text-primary" role="status"></div>
        </div>
        <span>Loading...</span> 
        <br>
        <p>Programmed by Yudha Prasetiya</p>
    </div>
    <div id="camera-overlay">
        <img src="{{ asset('img/type2-white.png') }}" alt="" loading="lazy" style="width: 200px; height: auto;">
        <p>&copy 2025 PancaIT All Rights Reserved</p>
        <button id="camera-trigger">
            <i class='bx bx-camera'></i>
            <span>Mulai Ngab</span>
        </button>
    </div>

    <div class="container">
        <div class="navbar-container">
            <div class="navbar-wrapper">
                <i class='bx  bx-help-circle'  ></i> 
                <i class='bx  bx-bolt'  ></i> 
                <i class='bx  bx-camera-flip'  ></i> 
            </div>
        </div>
        <div class="header">
            <h1>QREN - QR Code Scanner</h1>
            <p>Selamat datang, {{ Auth::user()->name }}! Scan QR Code untuk absensi</p>
        </div>
        <div class="camera-container">
            <div class="camera-wrapper" id="reader">
                
            </div>
            <p id="error"></p>
        </div>
        <div class="result">
            Hasil Scan: <span id="resultText">-</span>
        </div>
        <p>Debug Log:</p>
        <p>Programmed by Yudha Prasetiya</p>
        <div id="debug">
            <h1>Debug log:</h1>
            <pre></pre>
        </div>
        <!-- <div class="logout">
            <form method="GET" action="{{ route('siswa.logout') }}">
                @csrf
                <button type="submit">Logout</button>
            </form>
        </div> -->
        <div class="low-navbar-container">
            <div class="low-navbar-wrapper">
                <a href="#"><i class='bx  bx-home'  ></i></a>
                <a href="{{ route('siswa.history') }}"><i class='bx  bx-calendar'  ></i> </a>
                <a href="{{ route('siswa.dashboard') }}"><i class='bx  bx-user'  ></i> </a>
                <!-- <div class="logout">
                    <form method="GET" action="{{ route('siswa.logout') }}">
                        @csrf
                        <button type="submit">Logout</button>
                    </form>
                </div> -->
            </div>
        </div>
    </div>
<script>
        let html5QrCode;
        let cameraStarted = false;

        // Preloader
        document.addEventListener("DOMContentLoaded", function() {
            setTimeout(function() {
                const preloader = document.getElementById("preloader");
                preloader.style.display = "none";
            }, 1000);
        });

        document.getElementById('camera-trigger').addEventListener('click', function() {
            startCamera();
        });

        function startCamera() {
            if (cameraStarted) return;
            
            const button = document.getElementById('camera-trigger');
            const overlay = document.getElementById('camera-overlay');
            
            button.innerHTML = `
                <div class="loading-spinner"></div>
                <span>LOADING...</span>
            `;
            button.disabled = true;
            
            appendDebug('Starting camera...');
            
            html5QrCode = new Html5Qrcode("reader");
            html5QrCode.start(
                { facingMode: "environment" },
                { fps: 10, qrbox: 250 },
                onScanSuccess,
                onScanFailure
            ).then(() => {
                appendDebug('Camera started successfully');
                overlay.classList.add('hidden');
                cameraStarted = true;
            }).catch(err => {
                appendDebug('Failed to start scanner:', err);
                document.getElementById('error').innerText = "Gagal memuat kamera, error: " + err;
                
                // Reset button
                button.innerHTML = `
                    <i class='bx bx-camera'></i>
                    <span>TRY AGAIN</span>
                `;
                button.disabled = false;
            });
        }

        // helper to append debug messages (and console.log)
    function appendDebug(...parts) {
        const pre = document.getElementById('debug');
        const text = parts.map(p => {
            try { return typeof p === 'string' ? p : JSON.stringify(p, null, 2); } catch(e) { return String(p); }
        }).join(' ');
        pre.textContent += text + "\n";
        pre.scrollTop = pre.scrollHeight;
        console.log(...parts);
    }

    function onScanSuccess(decodedText, decodedResult) {
        document.getElementById('resultText').innerText = decodedText;
        appendDebug('Scan success:', decodedText);

        const url = "{{ route('scan.submit') }}";
        const payload = {
            siswa_id: 1, // untuk tes
            code: decodedText
        };

        appendDebug('Sending POST to', url, 'payload:', payload);

        fetch(url, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify(payload)
        })
        .then(async res => {
            appendDebug('HTTP status:', res.status, res.statusText);
            appendDebug('Response Content-Type:', res.headers.get('content-type'));
            const text = await res.text();
            appendDebug('Raw response body:', text);
            try {
                const json = JSON.parse(text || '{}');
                appendDebug('Parsed JSON response:', json);
                // pass along parsed JSON and status for next then()
                return { ok: res.ok, status: res.status, json };
            } catch (err) {
                appendDebug('Failed to parse JSON:', err.message);
                // still return raw text in case server returned HTML/error page
                return { ok: res.ok, status: res.status, raw: text };
            }
        })
        .then(data => {
            if (!data) {
                appendDebug('No response data received');
                alert('No response: check debug panel');
                return;
            }
            if (data.json) {
                appendDebug('Final JSON payload:', data.json);
                const message = data.json.message ?? (data.json.error ?? 'OK');
                alert(message);
            } else {
                appendDebug('Final raw payload:', data.raw ?? data);
                alert('Received non-JSON response. See debug panel.');
            }
        })
        .catch(err => {
            appendDebug('Fetch error:', err.message);
            alert('Error occurred. See debug panel for details.');
        });
    }


    function onScanFailure(error) {
        // Log scan failures for debugging (but keep console noise low)
        appendDebug('Scan failure (ignored):', error && error.message ? error.message : error);
    }


    </script>
</body>
</html>
