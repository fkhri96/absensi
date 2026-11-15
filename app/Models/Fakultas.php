<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fakultas extends Model
{
    protected $table = 'fakultas';
    protected $fillable = ['kode_fakultas', 'nama_fakultas', 'dekan'];

    public function prodis()
    {
        return $this->hasMany(Prodi::class, 'fakultas_id');
    }

    public function dosens()
    {
        return $this->hasMany(Dosen::class, 'fakultas_id');
    }
}