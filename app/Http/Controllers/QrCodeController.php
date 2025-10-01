<?php

namespace App\Http\Controllers;

use App\Models\QrCode;
use App\Models\Ajar;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Events\GeolocationUpdateNotification;

class QrCodeController extends Controller
{
    public function index()
    {
        /** @var \App\Models\Guru $guru */
        $guru = auth()->guard('guru')->user();
        $ajars = $guru->ajars()->with('kelas', 'jurusan', 'mapel')->get();

        return view('guru.qrcode', compact('ajars'));
    }

    public function create()
    {
        /** @var \App\Models\Guru $guru */
        $guru = auth()->guard('guru')->user();
        $ajars = $guru->ajars()->with('kelas', 'jurusan', 'mapel')->get();

        return view('guru.qrcode.create', compact('ajars'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ajar_id' => 'required|exists:ajars,id',
            'teacher_lat' => 'required|numeric',
            'teacher_lng' => 'required|numeric',
        ]);

        /** @var \App\Models\Guru $guru */
        $guru = auth()->guard('guru')->user();

        $ajar = Ajar::find($request->ajar_id);
        if ($ajar->guru_id != $guru->id) {
            abort(403, 'Unauthorized');
        }

        $code = 'QREN-' . strtoupper(Str::random(8));

        $qr = QrCode::create([
            'guru_id' => $guru->id,
            'ajar_id' => $request->ajar_id,
            'code' => $code,
            'teacher_lat' => $request->teacher_lat,
            'teacher_lng' => $request->teacher_lng,
        ]);

        return redirect()->route('guru.qrcode.show', $qr)->with('success', 'QR Code created successfully!');
    }

    public function show(QrCode $qrcode)
    {
        /** @var \App\Models\Guru $guru */
        $guru = auth()->guard('guru')->user();

        if ($qrcode->guru_id != $guru->id) {
            abort(403, 'Unauthorized');
        }

        return view('guru.qrcode.show', compact('qrcode'));
    }

    public function generate(Request $request)
    {
        /** @var \App\Models\Guru $guru */
        $guru = auth()->guard('guru')->user();
        $request->validate([
            'ajar_id' => 'required|exists:ajars,id',
            'teacher_lat' => 'required|numeric',
            'teacher_lng' => 'required|numeric',
        ]);

        $ajar = Ajar::find($request->ajar_id);
        if ($ajar->guru_id != $guru->id) {
            abort(403, 'Unauthorized');
        }

        $code = 'QREN-' . strtoupper(Str::random(8)); // contoh kode unik

        // cek apakah sudah ada QR untuk ajar ini
        $qr = QrCode::where('ajar_id', $request->ajar_id)->first();

        if ($qr) {
            // update kode lama
            $qr->update([
                'code' => $code,
                'teacher_lat' => $request->teacher_lat,
                'teacher_lng' => $request->teacher_lng,
            ]);
        } else {
            // buat baru
            $qr = QrCode::create([
                'guru_id' => $guru->id,
                'ajar_id' => $request->ajar_id,
                'code'    => $code,
                'teacher_lat' => $request->teacher_lat,
                'teacher_lng' => $request->teacher_lng,
            ]);
        }

        return redirect()
            ->route('guru.qrcode.index')
            ->with('success', 'QR Code berhasil dibuat/diperbarui!')
            ->with('qr', $qr);
    }

    public function updateLocation(Request $request)
    {
        /** @var \App\Models\Guru $guru */
        $guru = auth()->guard('guru')->user();
        $request->validate([
            'ajar_id' => 'required|exists:ajars,id',
            'teacher_lat' => 'required|numeric',
            'teacher_lng' => 'required|numeric',
        ]);

        $ajar = Ajar::find($request->ajar_id);
        if ($ajar->guru_id != $guru->id) {
            \Log::warning('Geolocation update: Unauthorized access', [
                'guru_id' => $guru->id,
                'ajar_id' => $request->ajar_id,
                'ajar_guru_id' => $ajar->guru_id,
            ]);
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $qr = QrCode::where('ajar_id', $request->ajar_id)->first();
        if (!$qr) {
            \Log::warning('Geolocation update: QR Code not found', [
                'ajar_id' => $request->ajar_id,
            ]);
            return response()->json(['error' => 'QR Code not found'], 404);
        }

        $old_lat = $qr->teacher_lat;
        $old_lng = $qr->teacher_lng;

        $newCode = 'QREN-' . strtoupper(Str::random(8));

        $qr->update([
            'code' => $newCode,
            'teacher_lat' => $request->teacher_lat,
            'teacher_lng' => $request->teacher_lng,
        ]);

        \Log::info('Geolocation updated', [
            'qr_id' => $qr->id,
            'ajar_id' => $request->ajar_id,
            'guru_id' => $guru->id,
            'old_lat' => $old_lat,
            'old_lng' => $old_lng,
            'new_lat' => $request->teacher_lat,
            'new_lng' => $request->teacher_lng,
        ]);

        // Fire the real-time geolocation update notification event
        event(new GeolocationUpdateNotification($qr));

        return response()->json(['success' => true]);
    }

    public function getQrCodeSvg(Request $request)
    {
        /** @var \App\Models\Guru $guru */
        $guru = auth()->guard('guru')->user();
        $request->validate([
            'ajar_id' => 'required|exists:ajars,id',
        ]);

        $ajar = Ajar::find($request->ajar_id);
        if ($ajar->guru_id != $guru->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $qr = QrCode::where('ajar_id', $request->ajar_id)->first();
        if (!$qr) {
            return response()->json(['error' => 'QR Code not found'], 404);
        }

        $qrSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(150)->generate($qr->code);
        $qrFullSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(400)->generate($qr->code);

        return response()->json([
            'success' => true,
            'qr_svg' => $qrSvg,
            'qr_full_svg' => $qrFullSvg,
            'code' => $qr->code,
        ]);
    }
}
