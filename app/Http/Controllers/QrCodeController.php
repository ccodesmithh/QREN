<?php

namespace App\Http\Controllers;

use App\Models\QrCode;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class QrCodeController extends Controller
{
    public function index()
    {
        $qr = QrCode::latest()->first();

        return view('guru.qrcode', compact('qr'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'guru_id' => 'required|exists:gurus,id',
        ]);

        $code = 'QREN-' . strtoupper(Str::random(8)); // contoh kode unik

        // cek apakah guru sudah punya QR
        $qr = QrCode::where('guru_id', $request->guru_id)->first();

        if ($qr) {
            // update kode lama
            $qr->update(['code' => $code]);
        } else {
            // buat baru
            $qr = QrCode::create([
                'guru_id' => $request->guru_id,
                'code'    => $code,
            ]);
        }

        return redirect()
            ->route('guru.qrcode.index')
            ->with('success', 'QR Code berhasil dibuat/diperbarui!')
            ->with('qr', $qr);
    }
}
