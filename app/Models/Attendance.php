<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Ajar;
use App\Models\QrCode;

class Attendance extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'siswa_id',
        'guru_id',
        'qrcode_id',
        'status',
        'scanned_at',
        'distance',
    ];

    protected $casts = [
        'scanned_at' => 'datetime',
    ];

    // Relasi ke User (siswa)
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    // Relasi ke User (guru)
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }

    // Relasi ke QR Code
    public function qrcode()
    {
        return $this->belongsTo(QrCode::class, 'qrcode_id');
    }

    public function ajar()
    {
        return $this->hasOneThrough(
            Ajar::class,
            QrCode::class,
            'id', // Foreign key on qrcodes table...
            'id', // Foreign key on ajars table...
            'qrcode_id', // Local key on attendances table...
            'ajar_id' // Local key on qrcodes table...
        );
    }
}
