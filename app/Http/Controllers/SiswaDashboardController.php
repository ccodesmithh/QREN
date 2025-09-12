<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class SiswaDashboardController extends Controller
{
    public function index()
    {
        $siswa = Auth::guard('siswa')->user();
        return view('dashboard.siswa', compact('siswa'));
    }
    public function scan()
    {
        $siswa = Auth::guard('siswa')->user();
        return view('scan.index', compact('siswa'));
    }
}
