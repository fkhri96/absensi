<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Jadwal;
use App\Models\MataKuliah;
use App\Models\SesiAbsensi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $dosen = Schema::hasTable('dosens') ? Dosen::with('fakultas')->where('user_id', $user->id)->first() : null;

        $mataKuliahs = collect();
        $jadwals = collect();
        $sesis = collect();

        if ($dosen && Schema::hasTable('mata_kuliahs')) {
            $mataKuliahs = MataKuliah::with('prodi')->where('dosen_id', $dosen->id)->get();
            $mkIds = $mataKuliahs->pluck('id');

            if (Schema::hasTable('jadwals')) {
                $jadwals = Jadwal::with('mataKuliah')
                    ->whereIn('mata_kuliah_id', $mkIds)
                    ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu')")
                    ->orderBy('jam_mulai')
                    ->get();
            }

            if (Schema::hasTable('sesi_absensis')) {
                $jadwalIds = $jadwals->pluck('id');
                $sesis = SesiAbsensi::with(['jadwal.mataKuliah'])
                    ->withCount('absensis')
                    ->whereIn('jadwal_id', $jadwalIds)
                    ->latest()
                    ->take(6)
                    ->get();
            }
        }

        $stats = [
            'total_matkul' => $mataKuliahs->count(),
            'total_jadwal' => $jadwals->count(),
            'sesi_aktif'   => $sesis->where('status', 'buka')->count(),
            'total_sesi'   => $sesis->count(),
        ];

        return view('dosen.dashboard', compact('dosen', 'stats', 'mataKuliahs', 'jadwals', 'sesis'));
    }
}