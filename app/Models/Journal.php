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
        'jam_start',
        'jam_end',
        'content',
    ];

    protected $casts = [
        'date' => 'date',
        'jam_start' => 'datetime:H:i',
        'jam_end' => 'datetime:H:i',
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
