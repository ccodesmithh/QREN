<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Guru;
use App\Models\Siswa;

class AuthController extends Controller
{
    // Login Guru
    public function showGuruLogin()
    {
        return view('auth.login-guru');
    }

    public function loginGuru(Request $request)
    {
        $request->validate([
            'idguru' => 'required',
            'password' => 'required',
        ]);

        $guru = Guru::where('idguru', $request->idguru)->first();

        if ($guru && $guru->password === $request->password) {
            Session::put('user', $guru);
            Session::put('role', 'guru');
            return redirect()->route('dashboard')->with('success', 'Login Guru berhasil');
        }

        return back()->with('error', 'ID Guru atau password salah');
    }

    // Login Siswa
    public function showSiswaLogin()
    {
        return view('auth.login-siswa');
    }

    public function loginSiswa(Request $request)
    {
        $request->validate([
            'nisn' => 'required',
            'password' => 'required',
        ]);

        $siswa = Siswa::where('nisn', $request->nisn)->first();

        if ($siswa && $siswa->password === $request->password) {
            Session::put('user', $siswa);
            Session::put('role', 'siswa');
            return redirect()->route('dashboard')->with('success', 'Login Siswa berhasil');
        }

        return back()->with('error', 'NISN atau password salah');
    }

    // Logout
    public function logout()
    {
        Session::flush();
        return redirect()->route('login.guru')->with('success', 'Berhasil logout');
    }
}
