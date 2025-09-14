<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ajar;

class AjarController extends Controller
{
    public function index()
    {
        return view('guru.ajar');
    }

    public function store(Request $request)
    {
        $guruId = auth()->guard('guru')->id();
        
        $request->validate([
            'mapel_id' => 'required|exists:mapels,id',
            'kelas_id' => 'required|exists:kelas,id',
            'jurusan_id' => 'required|exists:jurusans,id',
            'jam_awal' => 'required|integer|between:1,10',
            'jam_akhir' => 'required|integer|between:1,10|gt:jam_awal',
        ]);

        try {
            Ajar::create([
                'mapel_id' => $request->mapel_id,
                'kelas_id' => $request->kelas_id,
                'jurusan_id' => $request->jurusan_id,
                'jam_awal' => $request->jam_awal,
                'jam_akhir' => $request->jam_akhir,
                'guru_id' => $guruId
            ]);

            return redirect()->route('guru.ajar')->with('success', 'Data ajar berhasil disimpan!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data ajar. Silakan coba lagi.']);
        }
    }
    public function jadwal()
    {
        $guruId = auth()->guard('guru')->id();
        $jadwals = Ajar::where('guru_id', $guruId)->with(['Mapel', 'Kelas', 'Jurusan'])->get();
        return view('guru.jadwal', compact('jadwals'));
    }
    
}
