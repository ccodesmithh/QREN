<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $table = 'kelas';
    protected $primaryKey = 'id'; 


    public function siswas()
    {
        return $this->hasMany(Siswa::class, 'kelas_id');
    }
}
