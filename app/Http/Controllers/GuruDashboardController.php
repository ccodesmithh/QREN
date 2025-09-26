<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class GuruDashboardController extends Controller
{
    public function index()
    {
        $guru = Auth::guard('guru')->user();
        /** @var \App\Models\Guru $guru */
        $ajars = $guru->ajars()->with('mapel', 'kelas', 'jurusan')->get();
        return view('guru.dashboard', compact('guru', 'ajars'));
    }
}
