<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GuruSiswaSeeder extends Seeder
{
    public function run(): void
    {
        // Buat jurusan
        $jurusanId = DB::table('jurusans')->insertGetId([
            'jurusan' => 'Rekayasa Perangkat Lunak',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Buat kelas
        $kelasId = DB::table('kelas')->insertGetId([
            'kelas' => 'XI A',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Buat 1 akun guru default
        $guruId = DB::table('gurus')->insertGetId([
            'idguru' => 'G001',
            'name' => 'Guru Satu',
            'password' => 'password123', // plain text dulu biar gampang
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Buat 1 akun siswa default
        $siswaId = DB::table('siswas')->insertGetId([
            'nisn' => '1234567890',
            'name' => 'Siswa Satu',
            'password' => 'password123', // ntar di-hash di model
            'kelas_id' => $kelasId,
            'jurusan_id' => $jurusanId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Buat mata pelajaran
        $mapelId = DB::table('mapels')->insertGetId([
            'nama_mapel' => 'Pemrograman Web',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Buat ajar untuk guru
        DB::table('ajars')->insert([
            'guru_id' => $guruId,
            'mapel_id' => $mapelId,
            'kelas_id' => $kelasId,
            'jurusan_id' => $jurusanId,
            'jam_awal' => 8,
            'jam_akhir' => 9,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
