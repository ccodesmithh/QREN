<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        // Get existing data
        $guru = DB::table('gurus')->first();
        $siswa = DB::table('siswas')->first();
        $ajar = DB::table('ajars')->first();

        // Create QR code
        $qrcodeId = DB::table('qrcodes')->insertGetId([
            'guru_id' => $guru->id,
            'ajar_id' => $ajar->id,
            'code' => 'TEST123',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create attendance records for different dates
        $dates = [
            now()->subDays(1),
            now()->subDays(2),
            now(),
        ];

        foreach ($dates as $date) {
            DB::table('attendances')->insert([
                'siswa_id' => $siswa->id,
                'guru_id' => $guru->id,
                'qrcode_id' => $qrcodeId,
                'status' => 'hadir',
                'scanned_at' => $date,
                'created_at' => $date,
                'updated_at' => $date,
            ]);
        }
    }
}
