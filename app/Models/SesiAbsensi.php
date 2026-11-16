<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SesiAbsensi extends Model
{
    use HasFactory;

    protected $table = 'sesi_absensis';

    protected $fillable = [
        'jadwal_id',
        'tanggal',
        'pertemuan_ke',
        'materi',
        'token_qr',
        'status',
    ];

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }

    public function absensis()
    {
        return $this->hasMany(Absensi::class, 'sesi_absensi_id');
    }
}