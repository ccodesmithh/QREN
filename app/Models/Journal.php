<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    use HasFactory;

    protected $fillable = [
        'guru_id',
        'ajar_id',
        'date',
        'content',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function ajar()
    {
        return $this->belongsTo(Ajar::class);
    }
}
