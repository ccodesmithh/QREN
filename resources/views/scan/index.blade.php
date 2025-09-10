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
    </style>
</head>
<body>
    <h2>Scan QR Code</h2>
    <div id="reader"></div>
    <div class="result">
        <p>Hasil Scan: <span id="resultText">-</span></p>
        <br>
        <p>&copy; {{ date('Y') }} Pasific Studios All Rights Reserved</p>
        <p>Programmed by Yudha Prasetiya</p>
        <!-- Debug -->
        <p>Debug</p>
        <pre id="debug">Debug log initialized...
</pre>
        
    </div>

<script>
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

    const html5QrCode = new Html5Qrcode("reader");
    html5QrCode.start(
        { facingMode: "environment" }, 
        { fps: 10, qrbox: 250 },
        onScanSuccess,
        onScanFailure
    ).catch(err => {
        appendDebug('Failed to start scanner:', err);
    });
</script>

</body>
</html>
