<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    protected $fillable = ['user_id', 'prodi_id', 'nim', 'angkatan', 'jenis_kelamin', 'no_hp', 'alamat'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }

    public function absensis()
    {
        return $this->hasMany(Absensi::class);
    }
}