<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Jadwal;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\SesiAbsensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class AkademikController extends Controller
{
    public function mataKuliah(Request $request)
    {
        $dosen = Dosen::where('user_id', auth()->id())->first();

        $query = MataKuliah::with(['prodi.fakultas', 'jadwals'])
            ->where('dosen_id', $dosen->id ?? 0);

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('nama_mk', 'like', "%{$search}%")
                  ->orWhere('kode_mk', 'like', "%{$search}%");
            });
        }

        $mataKuliahs = $query->orderBy('semester')->orderBy('nama_mk')->get();

        // Hitung jumlah pertemuan & sesi aktif per mata kuliah
        foreach ($mataKuliahs as $mk) {
            $jadwalIds = $mk->jadwals->pluck('id');
            $mk->total_sesi = SesiAbsensi::whereIn('jadwal_id', $jadwalIds)->count();
            $mk->sesi_buka  = SesiAbsensi::whereIn('jadwal_id', $jadwalIds)->where('status', 'buka')->count();
        }

        return view('dosen.matakuliah.index', compact('dosen', 'mataKuliahs'));
    }

    public function detailMataKuliah($id)
    {
        $dosen = Dosen::where('user_id', auth()->id())->first();

        $mataKuliah = MataKuliah::with(['prodi.fakultas', 'jadwals'])
            ->where('dosen_id', $dosen->id ?? 0)
            ->findOrFail($id);

        $jadwalIds = $mataKuliah->jadwals->pluck('id');

        $sesiAbsensis = SesiAbsensi::with('jadwal')
            ->withCount('absensis')
            ->whereIn('jadwal_id', $jadwalIds)
            ->orderByDesc('tanggal')
            ->orderByDesc('pertemuan_ke')
            ->get();

        // Ambil daftar mahasiswa berdasarkan prodi mata kuliah (atau semua mahasiswa jika prodi_id kosong)
        $mahasiswaQuery = Mahasiswa::with('user');
        if ($mataKuliah->prodi_id && Schema::hasColumn('mahasiswas', 'prodi_id')) {
            $mahasiswaQuery->where('prodi_id', $mataKuliah->prodi_id);
        }
        $mahasiswas = $mahasiswaQuery->orderBy('nim')->get();

        // Hitung rekap kehadiran tiap mahasiswa pada mata kuliah ini
        $sesiIds   = $sesiAbsensis->pluck('id');
        $totalSesi = $sesiIds->count();

        foreach ($mahasiswas as $mhs) {
            $records = $totalSesi > 0
                ? \App\Models\Absensi::whereIn('sesi_absensi_id', $sesiIds)
                    ->where('mahasiswa_id', $mhs->id)
                    ->get()
                : collect();

            $mhs->hadir = $records->where('status', 'hadir')->count();
            $mhs->izin  = $records->where('status', 'izin')->count();
            $mhs->sakit = $records->where('status', 'sakit')->count();
            $mhs->alpha = $records->where('status', 'alpha')->count();
            $mhs->persentase = $totalSesi > 0 ? round(($mhs->hadir / $totalSesi) * 100) : 0;
        }

        return view('dosen.matakuliah.show', compact(
            'dosen',
            'mataKuliah',
            'sesiAbsensis',
            'mahasiswas',
            'totalSesi'
        ));
    }

    public function jadwal()
    {
        $dosen     = Dosen::where('user_id', auth()->id())->first();
        $matkulIds = $dosen ? MataKuliah::where('dosen_id', $dosen->id)->pluck('id') : collect();

        $jadwals = Jadwal::with('mataKuliah.prodi')
            ->whereIn('mata_kuliah_id', $matkulIds)
            ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")
            ->orderBy('jam_mulai')
            ->get();

        return view('dosen.jadwal.index', compact('dosen', 'jadwals'));
    }

    public function mahasiswa()
    {
        $dosen      = Dosen::where('user_id', auth()->id())->first();
        $prodiIds   = $dosen ? MataKuliah::where('dosen_id', $dosen->id)->pluck('prodi_id')->filter()->unique() : collect();
        $mahasiswas = Mahasiswa::with(['user', 'prodi'])
            ->when($prodiIds->isNotEmpty(), fn ($q) => $q->whereIn('prodi_id', $prodiIds))
            ->orderBy('nim')
            ->get();

        return view('dosen.mahasiswa.index', compact('dosen', 'mahasiswas'));
    }
}