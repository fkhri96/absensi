<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
    protected $fillable = ['prodi_id', 'dosen_id', 'kode_mk', 'nama_mk', 'sks', 'semester'];

    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class);
    }

    public function jadwals()
    {
        return $this->hasMany(Jadwal::class);
    }
}