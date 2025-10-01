<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\QrCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class AttendanceController extends Controller
{
    public function index()
    {
        return view('scan.index');
    }

    public function scan(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        $siswaId = auth('siswa')->id();
        $code = $request->code;
        $studentLat = $request->lat;
        $studentLng = $request->lng;

        // Cari QR code di database
        $qr = QrCode::where('code', $code)->first();

        if (!$qr) {
            return response()->json(['message' => 'QR Code tidak valid'], 400);
        }

        // Check if teacher location is set
        if (!$qr->teacher_lat || !$qr->teacher_lng) {
            return response()->json(['message' => 'Lokasi guru belum diatur untuk QR ini'], 400);
        }

        // Calculate distance using Haversine formula
        $distance = $this->calculateDistance($studentLat, $studentLng, $qr->teacher_lat, $qr->teacher_lng);

        // Determine status: if distance > 50m, alpha, else hadir
        $status = $distance > 50 ? 'alpha' : 'hadir';

        try {
            $attendance = Attendance::create([
                'siswa_id'   => $siswaId,
                'guru_id'    => $qr->guru_id,
                'qrcode_id'  => $qr->id,
                'status'     => $status,
                'distance'   => $distance,
                'scanned_at' => now(),
            ]);

            $ajar = $qr->ajar;
            $message = $status === 'hadir' ? 'Absensi berhasil dicatat' : 'Radius terlalu jauh, absensi dicatat sebagai alpha';
            return response()->json([
                'message' => $message,
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

    private function calculateDistance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6371000; // in meters

        $latDelta = deg2rad($lat2 - $lat1);
        $lngDelta = deg2rad($lng2 - $lng1);

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($lngDelta / 2) * sin($lngDelta / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    public function siswaHistory()
    {
        $attendances = Attendance::with(['siswa', 'guru', 'qrcode.ajar'])->where('siswa_id', auth('siswa')->id())->latest()->get();

        // Fetch journals for the attendances
        $ajarIds = $attendances->pluck('qrcode.ajar.id')->unique();
        $dates = $attendances->pluck('scanned_at')->filter()->map->toDateString()->unique();
        $journals = \App\Models\Journal::whereIn('ajar_id', $ajarIds)->whereIn('date', $dates)->get()->keyBy(function($j) {
            return $j->ajar_id . '-' . $j->date->toDateString();
        });

        return view('siswa.history', compact('attendances', 'journals'));
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

        $attendance = Attendance::create([
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

        if ($request->filled('start_date')) {
            $query->whereDate('scanned_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('scanned_at', '<=', $request->end_date);
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

        // Fetch journals for the guru on the dates of attendances
        $dates = $attendances->pluck('scanned_at')->filter()->map->toDateString()->unique();
        $journals = \App\Models\Journal::where('guru_id', $guruId)->whereIn('date', $dates)->get()->keyBy(function($j) {
            return $j->ajar_id . '-' . $j->date->toDateString();
        });

        // Get all kelas and jurusan for filters
        $kelas = \App\Models\Kelas::all();
        $jurusans = \App\Models\Jurusan::all();

        return view('guru.history', compact('grouped', 'kelas', 'jurusans', 'request', 'journals'));
    }

    public function exportAttendance(Request $request)
    {
        $guruId = auth('guru')->id();

        $filters = $request->only(['kelas_id', 'jurusan_id', 'nama_siswa', 'start_date', 'end_date', 'selected_dates']);

        return Excel::download(new \App\Exports\AttendanceExport($filters, $guruId), 'attendance_export.xlsx');
    }

    public function downloadHistory(Request $request)
    {
        $guruId = auth('guru')->id();

        $filters = $request->only(['kelas_id', 'jurusan_id', 'nama_siswa', 'start_date', 'end_date', 'selected_dates']);

        return Excel::download(new \App\Exports\AttendanceExport($filters, $guruId), 'attendance_history.xlsx');
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

    public function guruAttendance()
    {
        $guruId = auth('guru')->id();

        $ajar = \App\Models\Ajar::with('mapel', 'kelas', 'jurusan')->where('guru_id', $guruId)->first();

        if (!$ajar) {
            abort(404, 'Tidak ada jadwal mengajar ditemukan untuk guru ini.');
        }

        $attendances = Attendance::with(['siswa', 'qrcode.ajar'])
            ->where('guru_id', $guruId)
            ->latest()
            ->get();

        return view('guru.attendance', compact('ajar', 'attendances'));
    }
}
