<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SesiAbsensi extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Relasi ke tabel jadwals
    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class, 'jadwal_id');
    }

    // Relasi ke tabel absensis (untuk menghitung jumlah hadir)
    public function absensis()
    {
        return $this->hasMany(Absensi::class, 'sesi_absensi_id');
    }
}