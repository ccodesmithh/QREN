<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\jurusan;
use App\Models\Attendance;
use App\Models\QrCode;
use App\Models\Mapel;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function manage(Request $request)
    {
        $settings = [
            'radius' => Setting::getValue('radius', '50'),
            'geolocation_timeout' => Setting::getValue('geolocation_timeout', '10000'),
            'max_age' => Setting::getValue('max_age', '0'),
            'enable_high_accuracy' => Setting::getValue('enable_high_accuracy', 'true'),
            'scan_cooldown' => Setting::getValue('scan_cooldown', '10'),
        ];

        $siswaQuery = Siswa::with('kelas', 'jurusan');

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $siswaQuery->where(function($q) use ($search) {
                $q->where('nisn', 'like', '%' . $search . '%')
                  ->orWhere('name', 'like', '%' . $search . '%')
                  ->orWhereHas('kelas', function($kq) use ($search) {
                      $kq->where('kelas', 'like', '%' . $search . '%');
                  })
                  ->orWhereHas('jurusan', function($jq) use ($search) {
                      $jq->where('jurusan', 'like', '%' . $search . '%');
                  });
            });
        }

        if ($request->has('kelas_id') && $request->kelas_id) {
            $siswaQuery->where('kelas_id', $request->kelas_id);
        }

        if ($request->has('jurusan_id') && $request->jurusan_id) {
            $siswaQuery->where('jurusan_id', $request->jurusan_id);
        }

        $siswas = $siswaQuery->get();
        $gurus = Guru::all();
        $kelas = Kelas::all();
        $jurusans = jurusan::all();
        $attendances = Attendance::with('siswa', 'ajar')->get();
        $mapels = Mapel::all();

        return view('admin.manage', compact('settings', 'siswas', 'gurus', 'kelas', 'jurusans', 'attendances', 'request', 'mapels'));
    }

    public function index(Request $request)
    {
        $settings = [
            'radius' => Setting::getValue('radius', '50'),
            'geolocation_timeout' => Setting::getValue('geolocation_timeout', '10000'),
            'max_age' => Setting::getValue('max_age', '0'),
            'enable_high_accuracy' => Setting::getValue('enable_high_accuracy', 'true'),
            'scan_cooldown' => Setting::getValue('scan_cooldown', '10'),
        ];

        $siswaQuery = Siswa::with('kelas', 'jurusan');

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $siswaQuery->where(function($q) use ($search) {
                $q->where('nisn', 'like', '%' . $search . '%')
                  ->orWhere('name', 'like', '%' . $search . '%')
                  ->orWhereHas('kelas', function($kq) use ($search) {
                      $kq->where('kelas', 'like', '%' . $search . '%');
                  })
                  ->orWhereHas('jurusan', function($jq) use ($search) {
                      $jq->where('jurusan', 'like', '%' . $search . '%');
                  });
            });
        }

        if ($request->has('kelas_id') && $request->kelas_id) {
            $siswaQuery->where('kelas_id', $request->kelas_id);
        }

        if ($request->has('jurusan_id') && $request->jurusan_id) {
            $siswaQuery->where('jurusan_id', $request->jurusan_id);
        }

        $siswas = $siswaQuery->get();
        $gurus = Guru::all();
        $kelas = Kelas::all();
        $jurusans = jurusan::all();
        $attendances = Attendance::with('siswa', 'ajar')->get();

        return view('admin.dashboard', compact('settings', 'siswas', 'gurus', 'kelas', 'jurusans', 'attendances', 'request'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'radius' => 'required|numeric|min:0',
            'geolocation_timeout' => 'required|numeric|min:0',
            'max_age' => 'required|numeric|min:0',
            'enable_high_accuracy' => 'required|in:true,false',
            'scan_cooldown' => 'required|numeric|min:0',
        ]);

        Setting::setValue('radius', $request->radius);
        Setting::setValue('geolocation_timeout', $request->geolocation_timeout);
        Setting::setValue('max_age', $request->max_age);
        Setting::setValue('enable_high_accuracy', $request->enable_high_accuracy);
        Setting::setValue('scan_cooldown', $request->scan_cooldown);

        return redirect()->route('admin.dashboard')->with('success', 'Settings updated successfully.');
    }

    // Siswa CRUD
    public function createSiswa()
    {
        $kelas = Kelas::all();
        $jurusans = jurusan::all();
        return view('admin.siswa.create', compact('kelas', 'jurusans'));
    }

    public function storeSiswa(Request $request)
    {
        $request->validate([
            'nisn' => 'required|unique:siswas',
            'name' => 'required',
            'password' => 'required',
            'kelas_id' => 'required|exists:kelas,id',
            'jurusan_id' => 'required|exists:jurusans,id',
        ]);

        Siswa::create($request->all());
        return redirect()->route('admin.dashboard')->with('success', 'Siswa created successfully.');
    }

    public function editSiswa(Siswa $siswa)
    {
        $kelas = Kelas::all();
        $jurusans = jurusan::all();
        return view('admin.siswa.edit', compact('siswa', 'kelas', 'jurusans'));
    }

    public function updateSiswa(Request $request, Siswa $siswa)
    {
        $request->validate([
            'nisn' => 'required|unique:siswas,nisn,' . $siswa->id,
            'name' => 'required',
            'password' => 'nullable',
            'kelas_id' => 'required|exists:kelas,id',
            'jurusan_id' => 'required|exists:jurusans,id',
        ]);

        $siswa->update($request->only(['nisn', 'name', 'kelas_id', 'jurusan_id']) + ($request->password ? ['password' => $request->password] : []));
        return redirect()->route('admin.dashboard')->with('success', 'Siswa updated successfully.');
    }

    public function destroySiswa(Siswa $siswa)
    {
        $siswa->delete();
        return redirect()->route('admin.dashboard')->with('success', 'Siswa deleted successfully.');
    }

    // Guru CRUD
    public function createGuru()
    {
        return view('admin.guru.create');
    }

    public function storeGuru(Request $request)
    {
        $request->validate([
            'idguru' => 'required|unique:gurus',
            'name' => 'required',
            'password' => 'required',
        ]);

        Guru::create($request->all());
        return redirect()->route('admin.dashboard')->with('success', 'Guru created successfully.');
    }

    public function editGuru(Guru $guru)
    {
        return view('admin.guru.edit', compact('guru'));
    }

    public function updateGuru(Request $request, Guru $guru)
    {
        $request->validate([
            'idguru' => 'required|unique:gurus,idguru,' . $guru->id,
            'name' => 'required',
            'password' => 'nullable',
        ]);

        $guru->update($request->only(['idguru', 'name']) + ($request->password ? ['password' => $request->password] : []));
        return redirect()->route('admin.dashboard')->with('success', 'Guru updated successfully.');
    }

    public function destroyGuru(Guru $guru)
    {
        $guru->delete();
        return redirect()->route('admin.dashboard')->with('success', 'Guru deleted successfully.');
    }

    // Kelas CRUD
    public function createKelas()
    {
        return view('admin.kelas.create');
    }

    public function storeKelas(Request $request)
    {
        $request->validate([
            'kelas' => 'required|unique:kelas',
        ]);

        Kelas::create($request->all());
        return redirect()->route('admin.dashboard')->with('success', 'Kelas created successfully.');
    }

    public function editKelas(Kelas $kelas)
    {
        return view('admin.kelas.edit', compact('kelas'));
    }

    public function updateKelas(Request $request, Kelas $kelas)
    {
        $request->validate([
            'kelas' => 'required|unique:kelas,kelas,' . $kelas->id,
        ]);

        $kelas->update($request->all());
        return redirect()->route('admin.dashboard')->with('success', 'Kelas updated successfully.');
    }

    public function destroyKelas(Kelas $kelas)
    {
        $kelas->delete();
        return redirect()->route('admin.dashboard')->with('success', 'Kelas deleted successfully.');
    }

    // Jurusan CRUD
    public function createJurusan()
    {
        return view('admin.jurusan.create');
    }

    public function storeJurusan(Request $request)
    {
        $request->validate([
            'jurusan' => 'required|unique:jurusans',
        ]);

        jurusan::create($request->all());
        return redirect()->route('admin.dashboard')->with('success', 'Jurusan created successfully.');
    }

    public function editJurusan(jurusan $jurusan)
    {
        return view('admin.jurusan.edit', compact('jurusan'));
    }

    public function updateJurusan(Request $request, jurusan $jurusan)
    {
        $request->validate([
            'jurusan' => 'required|unique:jurusans,jurusan,' . $jurusan->id,
        ]);

        $jurusan->update($request->all());
        return redirect()->route('admin.dashboard')->with('success', 'Jurusan updated successfully.');
    }

    public function destroyJurusan(jurusan $jurusan)
    {
        $jurusan->delete();
        return redirect()->route('admin.dashboard')->with('success', 'Jurusan deleted successfully.');
    }

    public function createMapel()
    {
        return view('admin.mapel.create');
    }

    public function storeMapel(Request $request)
    {
        $request->validate([
            'nama_mapel' => 'required|unique:mapels',
        ]);

        Mapel::create($request->all());
        return redirect()->route('admin.mapel.index')->with('success', 'Mata Pelajaran created successfully.');
    }

    public function editMapel(Mapel $mapel)
    {
        return view('admin.mapel.edit', compact('mapel'));
    }

    public function updateMapel(Request $request, Mapel $mapel)
    {
        $request->validate([
            'nama_mapel' => 'required|unique:mapels,nama_mapel,' . $mapel->id,
        ]);

        $mapel->update($request->all());
        return redirect()->route('admin.mapel.index')->with('success', 'Mata Pelajaran updated successfully.');
    }

    public function destroyMapel(Mapel $mapel)
    {
        $mapel->delete();
        return redirect()->route('admin.mapel.index')->with('success', 'Mata Pelajaran deleted successfully.');
    }

    // Removed regenerateQrCodes method as part of cleanup
    // public function regenerateQrCodes()
    // {
    //     // Get all QR codes and regenerate them, keeping existing location data
    //     $qrCodes = QrCode::all();
    //     $regeneratedCount = 0;

    //     foreach ($qrCodes as $qr) {
    //         $newCode = 'QREN-' . strtoupper(Str::random(8));
    //         // Update only the code, keep existing location data
    //         $qr->update([
    //             'code' => $newCode,
    //         ]);
    //         $regeneratedCount++;
    //     }

    //     return response()->json([
    //         'success' => true,
    //         'message' => "Successfully regenerated {$regeneratedCount} QR codes",
    //         'count' => $regeneratedCount
    //     ]);
    // }
}
