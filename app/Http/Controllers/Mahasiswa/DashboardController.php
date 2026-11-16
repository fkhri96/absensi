<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Mahasiswa;
use App\Models\SesiAbsensi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $mahasiswa = Schema::hasTable('mahasiswas')
            ? Mahasiswa::with('prodi.fakultas')->where('user_id', $user->id)->first()
            : null;

        $sesiAktif = Schema::hasTable('sesi_absensis')
            ? SesiAbsensi::with(['jadwal.mataKuliah.dosen.user'])->where('status', 'buka')->latest()->get()
            : collect();

        $riwayatAbsensi = collect();
        if ($mahasiswa && Schema::hasTable('absensis')) {
            $riwayatAbsensi = Absensi::with(['sesiAbsensi.jadwal.mataKuliah'])
                ->where('mahasiswa_id', $mahasiswa->id)
                ->latest()
                ->get();
        }

        $totalPertemuan = $riwayatAbsensi->count();
        $totalHadir     = $riwayatAbsensi->where('status', 'hadir')->count();
        $totalIzinSakit = $riwayatAbsensi->whereIn('status', ['izin', 'sakit'])->count();
        $persentase     = $totalPertemuan > 0 ? round(($totalHadir / $totalPertemuan) * 100) : 100;

        $stats = [
            'total_pertemuan' => $totalPertemuan,
            'total_hadir'     => $totalHadir,
            'izin_sakit'      => $totalIzinSakit,
            'persentase'      => $persentase,
        ];

        return view('mahasiswa.dashboard', compact('mahasiswa', 'stats', 'sesiAktif', 'riwayatAbsensi'));
    }

    public function notifikasi()
    {
        return view('mahasiswa.notifikasi.index');
    }
}