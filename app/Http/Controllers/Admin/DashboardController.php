<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Dosen;
use App\Models\Fakultas;
use App\Models\Jadwal;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\Prodi;
use App\Models\SesiAbsensi;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_mahasiswa' => Schema::hasTable('mahasiswas') ? Mahasiswa::count() : 0,
            'total_dosen'     => Schema::hasTable('dosens') ? Dosen::count() : 0,
            'total_matkul'    => Schema::hasTable('mata_kuliahs') ? MataKuliah::count() : 0,
            'total_fakultas'  => Schema::hasTable('fakultas') ? Fakultas::count() : 0,
            'total_prodi'     => Schema::hasTable('prodis') ? Prodi::count() : 0,
            'total_jadwal'    => Schema::hasTable('jadwals') ? Jadwal::count() : 0,
            'sesi_aktif'      => Schema::hasTable('sesi_absensis') ? SesiAbsensi::where('status', 'buka')->count() : 0,
            'hadir_hari_ini'  => Schema::hasTable('absensis') ? Absensi::whereDate('created_at', today())->where('status', 'hadir')->count() : 0,
        ];

        $aktivitasTerbaru = Schema::hasTable('absensis')
            ? Absensi::with(['mahasiswa.user', 'sesiAbsensi.jadwal.mataKuliah'])->latest()->take(8)->get()
            : collect();

        return view('admin.dashboard', compact('stats', 'aktivitasTerbaru'));
    }
}