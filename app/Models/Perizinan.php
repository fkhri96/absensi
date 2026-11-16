<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perizinan extends Model
{
    use HasFactory;

    protected $table = 'perizinans';

    protected $fillable = [
        'mahasiswa_id',
        'jadwal_id',
        'dosen_id',
        'jenis',
        'tanggal',
        'keterangan',
        'bukti',
        'status',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class);
    }
}