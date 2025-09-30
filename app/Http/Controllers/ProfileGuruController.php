<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileGuruController extends Controller
{
    public function edit()
    {
        $guru = auth()->guard('guru')->user();
        return view('guru.profile', compact('guru'));
    }
}
