<?php

namespace App\Http\Controllers;

use App\Models\QrCode;
use App\Models\Ajar;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class QrCodeController extends Controller
{
    public function index()
    {
        /** @var \App\Models\Guru $guru */
        $guru = auth()->guard('guru')->user();
        $ajars = $guru->ajars()->with('kelas', 'jurusan', 'mapel')->get();

        return view('guru.qrcode', compact('ajars'));
    }

    public function generate(Request $request)
    {
        /** @var \App\Models\Guru $guru */
        $guru = auth()->guard('guru')->user();
        $request->validate([
            'ajar_id' => 'required|exists:ajars,id',
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
            $qr->update(['code' => $code]);
        } else {
            // buat baru
            $qr = QrCode::create([
                'guru_id' => $guru->id,
                'ajar_id' => $request->ajar_id,
                'code'    => $code,
            ]);
        }

        return redirect()
            ->route('guru.qrcode.index')
            ->with('success', 'QR Code berhasil dibuat/diperbarui!')
            ->with('qr', $qr);
    }
}
