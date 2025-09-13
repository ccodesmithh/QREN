<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileSiswaController extends Controller
{
    public function index()
    {
        $siswa = Auth::guard('siswa')->user();
        return view('siswa.profile', compact('siswa'));
    }

    
}
