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
        $request->validate([
            'siswa_id' => 'required|integer',
            'code' => 'required|string',
            'status' => 'nullable|in:hadir,izin,alpha',
        ]);

        $siswaId = $request->siswa_id;
        $code = $request->code;
        $status = $request->status ?? 'hadir';

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
                'status'     => $status,
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
            'status' => 'required|in:hadir,izin,alpha',
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
            'status' => $request->status,
            'scanned_at' => now(),
        ]);

        return back()->with('success', 'Absensi berhasil ditambahkan untuk ' . $siswa->name);
    }

    public function guruHistory(Request $request)
    {
        $guruId = auth('guru')->id();

        $query = Attendance::with(['siswa.kelas', 'siswa.jurusan', 'qrcode.ajar.kelas', 'qrcode.ajar.jurusan', 'qrcode.ajar.mapel'])
            ->where('guru_id', $guruId);

        // Filters
        if ($request->filled('kelas_id')) {
            $query->whereHas('qrcode.ajar', function($q) use ($request) {
                $q->where('kelas_id', $request->kelas_id);
            });
        }

        if ($request->filled('jurusan_id')) {
            $query->whereHas('qrcode.ajar', function($q) use ($request) {
                $q->where('jurusan_id', $request->jurusan_id);
            });
        }

        if ($request->filled('nama_siswa')) {
            $query->whereHas('siswa', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->nama_siswa . '%');
            });
        }

        $attendances = $query->latest('scanned_at')->get();

        // Group by kelas then by jurusan then by date
        $grouped = $attendances->groupBy(function($att) {
            return $att->qrcode->ajar->kelas->kelas ?? 'Unknown';
        })->map(function($byKelas) {
            return $byKelas->groupBy(function($att) {
                return $att->qrcode->ajar->jurusan->jurusan ?? 'Unknown';
            })->map(function($byJurusan) {
                return $byJurusan->groupBy(function($att) {
                    return $att->scanned_at ? $att->scanned_at->format('Y-m-d') : 'Unknown';
                });
            });
        });

        // Get all kelas and jurusan for filters
        $kelas = \App\Models\Kelas::all();
        $jurusans = \App\Models\Jurusan::all();

        return view('guru.history', compact('grouped', 'kelas', 'jurusans', 'request'));
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
