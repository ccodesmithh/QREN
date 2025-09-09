<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scan QR Code</title>
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <style>
        body { font-family: sans-serif; text-align: center; padding: 20px; }
        #reader { width: 300px; margin: auto; }
        .result { margin-top: 15px; font-size: 1.2rem; font-weight: bold; }
    </style>
</head>
<body>
    <h2>Scan QR Code</h2>
    <div id="reader"></div>
    <div class="result">
        <p>Hasil Scan: <span id="resultText">-</span></p>
    </div>

<script>
    function onScanSuccess(decodedText, decodedResult) {
        document.getElementById('resultText').innerText = decodedText;

        fetch("{{ route('scan.submit') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                siswa_id: 1, // untuk tes
                code: decodedText
            })
        })
        .then(res => res.json())
        .then(data => {
            alert(data.message);
        })
        .catch(err => console.error(err));
    }


    function onScanFailure(error) {
        // error bisa diabaikan
    }

    const html5QrCode = new Html5Qrcode("reader");
    html5QrCode.start(
        { facingMode: "environment" }, 
        { fps: 10, qrbox: 250 },
        onScanSuccess,
        onScanFailure
    );
</script>

</body>
</html>
