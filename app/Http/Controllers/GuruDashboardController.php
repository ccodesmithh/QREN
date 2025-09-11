<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class GuruDashboardController extends Controller
{
    public function index()
    {
        $guru = Auth::guard('guru')->user();
        return view('dashboard.guru', compact('guru'));
    }
}
