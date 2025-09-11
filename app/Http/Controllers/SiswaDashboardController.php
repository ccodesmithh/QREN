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
}
