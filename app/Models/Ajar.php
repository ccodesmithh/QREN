<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ajar extends Model
{
    use HasFactory;
    protected $table = 'ajars';

    protected $fillable = [
        'guru_id',
        'kelas_id',
        'mapel_id',
        'jurusan_id',
        'jam_awal',
        'jam_akhir',
    ];

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function mapel()
    {
        return $this->belongsTo(Mapel::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class);
    }
}
