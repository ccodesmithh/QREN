<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;

class SiswaDashboardController extends Controller
{
    public function index()
    {
        $siswa = Auth::guard('siswa')->user();

        // Calculate attendance stats
        $totalHadir = Attendance::where('siswa_id', $siswa->id)->where('status', 'Hadir')->count();
        $totalIzin = Attendance::where('siswa_id', $siswa->id)->where('status', 'Izin')->count();
        $totalAlpha = Attendance::where('siswa_id', $siswa->id)->where('status', 'Alpha')->count();

        return view('siswa.dashboard', compact('siswa', 'totalHadir', 'totalIzin', 'totalAlpha'));
    }

    public function scan()
    {
        $siswa = Auth::guard('siswa')->user();
        return view('scan.index', compact('siswa'));
    }
}
