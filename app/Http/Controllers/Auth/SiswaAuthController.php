<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiswaAuthController extends Controller
{
    public function redirectToLogin()
    {
        return redirect()->route('siswa.login');
    }
    public function showLoginForm()
    {
        if (Auth::guard('siswa')->check()) {
            return redirect()->route('scan.index');
        }
        return view('siswa.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('nisn', 'password');

        // login manual karena pakai plain text
        $siswa = \App\Models\Siswa::where('nisn', $credentials['nisn'])->first();

        if ($siswa && $siswa->password === $credentials['password']) {
            Auth::guard('siswa')->login($siswa);
            return redirect()->route(route: 'scan.index');
        }

        return back()->withErrors(['login' => 'NISN atau Password salah.']);
    }

    public function logout(Request $request)
    {
        Auth::guard('siswa')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('siswa.login');
    }
}
