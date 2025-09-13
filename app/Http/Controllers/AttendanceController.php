<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\QrCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AttendanceController extends Controller
{
    public function index()
    {
        return view('scan.index');
    }

    public function scan(Request $request)
    {
        $siswaId = $request->siswa_id;
        $code = $request->code;

        // Cari QR code di database
        $qr = QrCode::where('code', $code)->first();

        if (!$qr) {
            return response()->json(['message' => 'QR Code tidak valid'], 400);
        }

        try {
            Attendance::create([
                'siswa_id'   => $siswaId,
                'guru_id'    => $qr->guru_id,
                'qrcode_id'  => $qr->id,
                'status'     => 'hadir',
                'scanned_at' => now(),
            ]);

            return response()->json(['message' => 'Absensi berhasil dicatat']);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menyimpan absensi',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function history()
    {
        $attendances = Attendance::with(['siswa', 'guru', 'qrcode'])->latest()->get();

        // Debug: cek isi data
        foreach ($attendances as $a) {
            Log::info('Attendance debug', [
                'attendance_id' => $a->id,
                'guru_id'       => $a->guru_id,
                'guru_relation' => $a->guru, // ini seharusnya tampil object guru
            ]);
        }

        return view('siswa.history', compact('attendances'));
    }

    // public function store(Request $request)
    // {
    //     $data = $request->input('qr_result');

    //     try {
    //         Attendance::create([
    //             'siswa_id'   => $request->siswa_id ?? null,
    //             'guru_id'    => $request->guru_id ?? null,
    //             'qrcode_id'  => null,
    //             'status'     => 'hadir',
    //             'scanned_at' => now(),
    //         ]);

    //         return response()->json(['status' => 'success']);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status' => 'failed',
    //             'error'  => $e->getMessage()
    //         ], 500);
    //     }
    // }
}
