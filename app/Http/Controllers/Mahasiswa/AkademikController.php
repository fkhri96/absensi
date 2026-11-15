<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\MataKuliah;
use App\Models\Mahasiswa;

class AkademikController extends Controller
{
    public function mataKuliah()
    {
        $user = auth()->user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->first();
        
        // Ambil daftar mata kuliah berdasarkan prodi mahasiswa
        $mataKuliahs = MataKuliah::with(['dosen.user', 'prodi'])
            ->when($mahasiswa && $mahasiswa->prodi_id, function($query) use ($mahasiswa) {
                return $query->where('prodi_id', $mahasiswa->prodi_id);
            })
            ->get();

        return view('mahasiswa.matakuliah.index', compact('mataKuliahs'));
    }

    public function jadwal()
    {
        $user = auth()->user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->first();
        
        $matkulIds = MataKuliah::when($mahasiswa && $mahasiswa->prodi_id, function($query) use ($mahasiswa) {
                return $query->where('prodi_id', $mahasiswa->prodi_id);
            })->pluck('id');
        
        $jadwals = Jadwal::with(['mataKuliah.dosen.user'])
            ->whereIn('mata_kuliah_id', $matkulIds)
            ->orderBy('hari')
            ->orderBy('jam_mulai')
            ->get();

        return view('mahasiswa.jadwal.index', compact('jadwals'));
    }
}