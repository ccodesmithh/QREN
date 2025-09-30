<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use App\Models\Journal;

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

        $recentJournals = Journal::where('guru_id', $guru->id)
            ->with('ajar.mapel', 'ajar.kelas', 'ajar.jurusan')
            ->latest('date')
            ->take(5)
            ->get();

        return view('guru.dashboard', compact('guru', 'ajars', 'recentAttendances', 'recentJournals'));
    }
}
