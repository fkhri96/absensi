<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $table = 'absensis';

    protected $fillable = [
        'sesi_absensi_id',
        'mahasiswa_id',
        'status',
        'metode',
        'keterangan',
        'dicatat_oleh',
    ];

    // Dipakai oleh DashboardController: with('sesiAbsensi.jadwal.mataKuliah')
    public function sesiAbsensi()
    {
        return $this->belongsTo(SesiAbsensi::class, 'sesi_absensi_id');
    }

    // Alias supaya kode lama yang memakai ->sesi() tetap jalan
    public function sesi()
    {
        return $this->sesiAbsensi();
    }

    // Dipakai oleh DashboardController: with('mahasiswa.user')
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id');
    }

    public function dicatatOleh()
    {
        return $this->belongsTo(User::class, 'dicatat_oleh');
    }
}