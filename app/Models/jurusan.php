<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class jurusan extends Model
{
    protected $table = 'jurusans';
    protected $primaryKey = 'id';
    protected $fillable = ['jurusan'];

    public function siswas()
    {
        return $this->hasMany(Siswa::class, 'jurusan_id');
    }
}
