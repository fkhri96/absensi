<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Jadwal;
use App\Models\SesiAbsensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AbsensiController extends Controller
{
    public function index()
    {
        $dosen = Dosen::where('user_id', auth()->id())->first();
        $matkulIds = $dosen ? $dosen->mataKuliahs()->pluck('id') : collect();
        $jadwalIds = Jadwal::whereIn('mata_kuliah_id', $matkulIds)->pluck('id');

        $sesis = SesiAbsensi::with('jadwal.mataKuliah')
            ->withCount('absensis')
            ->whereIn('jadwal_id', $jadwalIds)
            ->latest()
            ->paginate(10);

        return view('dosen.absensi.index', compact('sesis'));
    }

    public function create()
    {
        $dosen = Dosen::where('user_id', auth()->id())->first();
        $matkulIds = $dosen ? $dosen->mataKuliahs()->pluck('id') : collect();

        $jadwals = Jadwal::with('mataKuliah')
            ->whereIn('mata_kuliah_id', $matkulIds)
            ->get();

        return view('dosen.absensi.create', compact('jadwals'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jadwal_id'    => 'required|exists:jadwals,id',
            'pertemuan_ke' => 'required|integer|min:1|max:32',
            'tanggal'      => 'required|date',
            'materi'       => 'nullable|string|max:500',
        ]);

        $data = [
            'jadwal_id'    => $validated['jadwal_id'],
            'pertemuan_ke' => $validated['pertemuan_ke'],
            'tanggal'      => $validated['tanggal'],
            'status'       => 'buka',
        ];

        if (Schema::hasColumn('sesi_absensis', 'materi')) {
            $data['materi'] = $validated['materi'] ?? null;
        }
        if (Schema::hasColumn('sesi_absensis', 'kode_qr')) {
            $data['kode_qr'] = strtoupper(Str::random(10));
        }
        if (Schema::hasColumn('sesi_absensis', 'token')) {
            $data['token'] = strtoupper(Str::random(10));
        }

        $sesi = SesiAbsensi::create($data);

        return redirect()
            ->route('dosen.absensi.show', $sesi->id)
            ->with('success', 'Sesi absensi berhasil dibuka. Silakan tampilkan QR Code kepada mahasiswa.');
    }

    public function show($id)
    {
        $sesi = SesiAbsensi::with(['jadwal.mataKuliah', 'absensis.mahasiswa.user'])
            ->findOrFail($id);

        return view('dosen.absensi.show', compact('sesi'));
    }

    public function update(Request $request, $id)
    {
        $sesi = SesiAbsensi::findOrFail($id);
        $sesi->update([
            'status' => $request->input('status', 'tutup'),
        ]);

        return redirect()
            ->route('dosen.absensi.show', $sesi->id)
            ->with('success', 'Sesi absensi telah ditutup.');
    }

    public function destroy($id)
    {
        $sesi = SesiAbsensi::findOrFail($id);
        $sesi->delete();

        return redirect()
            ->route('dosen.absensi.index')
            ->with('success', 'Sesi absensi berhasil dihapus.');
    }
}