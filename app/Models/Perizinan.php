<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perizinan extends Model
{
    use HasFactory;

    protected $table = 'perizinans'; // Sesuaikan dengan nama tabel di database jika ada

    protected $fillable = [
        'mahasiswa_id',
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
}