<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mapel extends Model
{
    protected $table = 'mapels';

    protected $fillable = [
        'nama_mapel',
    ];

    public function ajars()
    {
        return $this->hasMany(Ajar::class);
    }
}
