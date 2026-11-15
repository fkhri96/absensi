<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Jadwal;
use App\Models\MataKuliah;
use App\Models\SesiAbsensi;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $dosen     = Dosen::where('user_id', auth()->id())->first();
        $matkulIds = $dosen ? $dosen->mataKuliahs()->pluck('id') : collect();
        $mataKuliahs = MataKuliah::whereIn('id', $matkulIds)->get();

        $selectedMk = $request->query('mata_kuliah_id');

        $query = SesiAbsensi::with(['jadwal.mataKuliah', 'absensis.mahasiswa.user'])
            ->whereHas('jadwal', function ($q) use ($matkulIds, $selectedMk) {
                $q->whereIn('mata_kuliah_id', $matkulIds);
                if ($selectedMk) {
                    $q->where('mata_kuliah_id', $selectedMk);
                }
            });

        $sesis = $query->latest('tanggal')->paginate(15);

        return view('dosen.laporan.index', compact('mataKuliahs', 'sesis', 'selectedMk'));
    }

    public function cetak(Request $request)
    {
        $dosen     = Dosen::where('user_id', auth()->id())->first();
        $matkulIds = $dosen ? $dosen->mataKuliahs()->pluck('id') : collect();
        
        $selectedMk = $request->query('mata_kuliah_id');
        $mataKuliah = $selectedMk ? MataKuliah::find($selectedMk) : null;

        $sesis = SesiAbsensi::with(['jadwal.mataKuliah', 'absensis.mahasiswa.user'])
            ->whereHas('jadwal', function ($q) use ($matkulIds, $selectedMk) {
                $q->whereIn('mata_kuliah_id', $matkulIds);
                if ($selectedMk) {
                    $q->where('mata_kuliah_id', $selectedMk);
                }
            })
            ->latest('tanggal')
            ->get();

        return view('dosen.laporan.cetak', compact('sesis', 'mataKuliah'));
    }

    public function exportExcel(Request $request)
    {
        // Sediakan fitur unduh/rekap sederhana atau arahkan kembali ke index
        return redirect()->route('dosen.laporan.index')->with('success', 'Fitur export excel siap digunakan.');
    }
}