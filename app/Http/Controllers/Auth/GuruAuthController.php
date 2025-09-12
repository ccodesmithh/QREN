<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuruAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('guru.login');
    }

    public function login(Request $request)
    {
        
        $credentials = $request->only('idguru', 'password');

        $guru = \App\Models\Guru::where('idguru', $credentials['idguru'])->first();

        if ($guru && $guru->password === $credentials['password']) {
            Auth::guard('guru')->login($guru);
            return redirect()->route('guru.dashboard');
        }

        return back()->withErrors(['login' => 'ID Guru atau Password salah.']);
    }

    public function logout(Request $request)
    {
        Auth::guard('guru')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('guru.login');
    }
}
