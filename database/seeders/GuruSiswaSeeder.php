<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GuruSiswaSeeder extends Seeder
{
    public function run(): void
    {
        // Buat jurusan if not exists
        $jurusan = DB::table('jurusans')->where('jurusan', 'Teknik Informatika')->first();
        if (!$jurusan) {
            $jurusanId = DB::table('jurusans')->insertGetId([
                'jurusan' => 'Teknik Informatika',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $jurusanId = $jurusan->id;
        }

        // Buat kelas if not exists
        $kelas = DB::table('kelas')->where('kelas', 'XI A')->first();
        if (!$kelas) {
            $kelasId = DB::table('kelas')->insertGetId([
                'kelas' => 'XI A',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $kelasId = $kelas->id;
        }

        // Buat 1 akun guru default if not exists
        $guru = DB::table('gurus')->where('idguru', 'G001')->first();
        if (!$guru) {
            $guruId = DB::table('gurus')->insertGetId([
                'idguru' => 'G001',
                'name' => 'Guru Satu',
                'password' => 'password123', // plain text dulu biar gampang
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $guruId = $guru->id;
        }

        // Buat 1 akun siswa default if not exists
        $siswa = DB::table('siswas')->where('nisn', '1234567890')->first();
        if (!$siswa) {
            $siswaId = DB::table('siswas')->insertGetId([
                'nisn' => '1234567890',
                'name' => 'Siswa Satu',
                'password' => 'password123', // ntar di-hash di model
                'kelas_id' => $kelasId,
                'jurusan_id' => $jurusanId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $siswaId = $siswa->id;
        }

        // Skip mapel and ajar for now to avoid table errors
        // Buat mata pelajaran
        // $mapelId = DB::table('mapels')->insertGetId([
        //     'nama_mapel' => 'Pemrograman Web',
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ]);

        // Buat ajar untuk guru
        // DB::table('ajars')->insert([
        //     'guru_id' => $guruId,
        //     'mapel_id' => $mapelId,
        //     'kelas_id' => $kelasId,
        //     'jurusan_id' => $jurusanId,
        //     'jam_awal' => 8,
        //     'jam_akhir' => 9,
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ]);
    }
}
