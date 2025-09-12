<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class GuruDashboardController extends Controller
{
    public function index()
    {
        $guru = Auth::guard('guru')->user();
        return view('guru.dashboard', compact('guru'));
    }
}
