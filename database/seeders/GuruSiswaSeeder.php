<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GuruSiswaSeeder extends Seeder
{
    public function run(): void
    {
        // Buat 1 akun guru default
        DB::table('gurus')->insert([
            'idguru' => 'G001',
            'name' => 'Guru Satu',
            'password' => 'password123', // plain text dulu biar gampang
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Buat 1 akun siswa default
        DB::table('siswas')->insert([
            'nisn' => '1234567890',
            'name' => 'Siswa Satu',
            'password' => 'password123', // ntar di-hash di model
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
