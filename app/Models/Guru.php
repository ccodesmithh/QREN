<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Guru extends Authenticatable
{
    protected $table = 'gurus'; 
    protected $primaryKey = 'id';

    protected $fillable = [
        'idguru',
        'password',
        'name',
    ];

    public function getAuthPassword()
    {
        return $this->password;
    }
}
