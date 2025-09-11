<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

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
}
