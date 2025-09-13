<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Authenticatable
{
    protected $table = 'siswas'; // tabel database
    protected $primaryKey = 'id'; // bisa ubah kalau pakai id khusus

    protected $fillable = [
        'nisn',
        'password',
        'name',
    ];

    //  override biar password kagak di-hash otomatis
    public function getAuthPassword()
    {
        return $this->password;
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }
    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'jurusan_id');
    }
}
