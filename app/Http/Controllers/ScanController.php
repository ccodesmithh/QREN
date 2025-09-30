<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\QrCode;
use App\Models\Attendance;
use App\Models\Setting;

class ScanController extends Controller
{
    public function index() {
        return view('scan.index');
    }

    public function scan(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        $code = $request->input('code');
        $studentLat = $request->input('lat');
        $studentLng = $request->input('lng');

        $siswaId = Auth::guard('siswa')->id();
        if (!$siswaId) {
            return response()->json(['success' => false, 'message' => 'Siswa tidak terautentikasi']);
        }

        $qr = QrCode::where('code', $code)->first();

        if (!$qr) {
            return response()->json(['success' => false, 'message' => 'QR Code tidak valid']);
        }

        if (!$qr->teacher_lat || !$qr->teacher_lng) {
            return response()->json(['success' => false, 'message' => 'Lokasi guru tidak tersedia']);
        }

        $distance = $this->haversineDistance($studentLat, $studentLng, $qr->teacher_lat, $qr->teacher_lng);

        $radius = (float) Setting::getValue('radius', 50.0);

        $status = $distance <= $radius ? 'hadir' : 'alpha';

        Attendance::create([
            'guru_id' => $qr->guru_id,
            'siswa_id' => $siswaId,
            'qrcode_id' => $qr->id,
            'status' => $status,
            'scanned_at' => now(),
            'distance' => round($distance, 2),
        ]);

        $message = $status === 'hadir' ? 'Kehadiran tercatat!' : 'Jarak terlalu jauh, kehadiran tidak tercatat sebagai hadir.';

        return response()->json([
            'success' => true,
            'message' => $message,
            'distance' => round($distance, 2),
        ]);
    }

    private function haversineDistance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6371000; // Radius bumi dalam meter

        $latDelta = deg2rad($lat2 - $lat1);
        $lngDelta = deg2rad($lng2 - $lng1);

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($lngDelta / 2) * sin($lngDelta / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}
