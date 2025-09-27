<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;

class GuruDashboardController extends Controller
{
    public function index()
    {
        $guru = Auth::guard('guru')->user();
        /** @var \App\Models\Guru $guru */
        $ajars = $guru->ajars()->with('mapel', 'kelas', 'jurusan')->get();
        $recentAttendances = Attendance::whereHas('qrcode', function($q) use ($guru) {
            $q->where('guru_id', $guru->id);
        })->with('siswa', 'qrcode.ajar.mapel')->latest('scanned_at')->take(10)->get();
        return view('guru.dashboard', compact('guru', 'ajars', 'recentAttendances'));
    }
}
