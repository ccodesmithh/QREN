<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Scan QR - QREN</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="height:100vh;">
    <div class="card shadow-lg p-4" style="width: 400px;">
        <h4 class="mb-3 text-center">Simulasi Scan QR</h4>
        
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('scan.submit') }}">
            @csrf
            <div class="mb-3">
                <label for="siswa_id" class="form-label">ID Siswa</label>
                <input type="number" class="form-control" id="siswa_id" name="siswa_id" required>
            </div>

            <div class="mb-3">
                <label for="code" class="form-label">Kode QR</label>
                <input type="text" class="form-control" id="code" name="code" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Kirim</button>
        </form>
    </div>
</body>
</html>
