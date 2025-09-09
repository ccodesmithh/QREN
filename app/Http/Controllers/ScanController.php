<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QrCode;
use App\Models\Attendance;

class ScanController extends Controller
{
    public function index() {
        return view('scan.index');
    }
    public function store(Request $request)
    {
        $code = $request->input('code');
        $siswaId = 1; // sementara hardcode siswa ID = 1 (nanti bisa pakai Auth)

        $qr = QrCode::where('code', $code)->first();

        if (!$qr) {
            return response()->json(['success' => false, 'message' => 'QR Code tidak valid']);
        }

        // simpan kehadiran
        Attendance::create([
            'guru_id' => $qr->guru_id,
            'siswa_id' => $siswaId,
            'qr_code_id' => $qr->id,
        ]);

        return response()->json(['success' => true, 'message' => 'Kehadiran tercatat!']);
    }
}
