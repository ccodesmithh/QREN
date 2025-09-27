<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QrCode extends Model
{
    use HasFactory;

    protected $table = 'qrcodes';

    protected $fillable = [
        'code',
        'guru_id',
        'ajar_id',
        'teacher_lat',
        'teacher_lng',
    ];

    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    public function ajar()
    {
        return $this->belongsTo(Ajar::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'qrcode_id');
    }
}
