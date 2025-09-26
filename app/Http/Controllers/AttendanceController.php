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

            $ajar = $qr->ajar;
            return response()->json([
                'message' => 'Absensi berhasil dicatat',
                'ajar' => [
                    'guru_name' => $ajar->guru->name ?? 'N/A',
                    'mapel_name' => $ajar->mapel->nama_mapel ?? 'N/A',
                    'kelas_name' => $ajar->kelas->nama_kelas ?? 'N/A',
                    'jurusan_name' => $ajar->jurusan->nama_jurusan ?? 'N/A',
                    'jam_awal' => $ajar->jam_awal,
                    'jam_akhir' => $ajar->jam_akhir,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menyimpan absensi',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function history()
    {
        $attendances = Attendance::with(['siswa', 'guru', 'qrcode.ajar'])->latest()->get();

        return view('siswa.history', compact('attendances'));
    }

    public function attendanceByAjar($ajarId)
    {
        $ajar = \App\Models\Ajar::with('mapel', 'kelas', 'jurusan')->findOrFail($ajarId);

        // Ensure the guru owns this ajar
        if ($ajar->guru_id != auth('guru')->id()) {
            abort(403);
        }

        $qrcode = $ajar->qrcode;
        if (!$qrcode) {
            $attendances = collect(); // Empty if no QR generated
        } else {
            $attendances = Attendance::where('qrcode_id', $qrcode->id)->with('siswa')->get();
        }

        return view('guru.attendance', compact('ajar', 'attendances'));
    }

    public function manualAttendance(Request $request, $ajarId)
    {
        $request->validate([
            'nisn' => 'required|string',
        ]);

        $ajar = \App\Models\Ajar::findOrFail($ajarId);

        // Ensure the guru owns this ajar
        if ($ajar->guru_id != auth('guru')->id()) {
            abort(403);
        }

        $siswa = \App\Models\Siswa::where('nisn', $request->nisn)->first();
        if (!$siswa) {
            return back()->withErrors(['nisn' => 'Siswa dengan NISN tersebut tidak ditemukan.']);
        }

        $qrcode = $ajar->qrcode;
        if (!$qrcode) {
            return back()->withErrors(['error' => 'QR Code belum dibuat untuk ajar ini.']);
        }

        // Check if already attended
        $existing = Attendance::where('qrcode_id', $qrcode->id)->where('siswa_id', $siswa->id)->first();
        if ($existing) {
            return back()->withErrors(['nisn' => 'Siswa ini sudah melakukan absensi.']);
        }

        Attendance::create([
            'siswa_id' => $siswa->id,
            'guru_id' => $ajar->guru_id,
            'qrcode_id' => $qrcode->id,
            'status' => 'hadir',
            'scanned_at' => now(),
        ]);

        return back()->with('success', 'Absensi berhasil ditambahkan untuk ' . $siswa->name);
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
