<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Jadwal;
use App\Models\MataKuliah;
use App\Models\SesiAbsensi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AbsensiController extends Controller
{
    public function index()
    {
        $dosen     = \App\Models\Dosen::where('user_id', auth()->id())->first();
        $matkulIds = $dosen ? $dosen->mataKuliahs()->pluck('id') : collect();
        $jadwalIds = \App\Models\Jadwal::whereIn('mata_kuliah_id', $matkulIds)->pluck('id');

        $sesis = \App\Models\SesiAbsensi::with(['jadwal.mataKuliah'])
            ->withCount('absensis')
            ->whereIn('jadwal_id', $jadwalIds)
            ->latest()
            ->paginate(15);

        return view('dosen.absensi.index', compact('sesis'));
    }
    public function create(Request $request)
    {
        $dosen     = Dosen::where('user_id', auth()->id())->first();
        $matkulIds = $dosen ? $dosen->mataKuliahs()->pluck('id') : collect();
        $jadwals   = Jadwal::with('mataKuliah')->whereIn('mata_kuliah_id', $matkulIds)->get();

        return view('dosen.absensi.create', compact('jadwals'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jadwal_id'    => 'required|exists:jadwals,id',
            'pertemuan_ke' => 'required|integer|min:1|max:16',
            'materi'       => 'nullable|string|max:255',
            'durasi_menit' => 'required|integer|min:5|max:180',
        ]);

        // Buat token unik untuk QR Code
        $tokenQr = Str::uuid()->toString() . '-' . time();

        // Hitung waktu kedaluwarsa berdasarkan durasi yang diatur dosen
        $berakhirPada = now()->addMinutes($validated['durasi_menit']);

        $sesi = SesiAbsensi::create([
            'jadwal_id'     => $validated['jadwal_id'],
            'pertemuan_ke'  => $validated['pertemuan_ke'],
            'materi'        => $validated['materi'] ?? 'Perkuliahan Reguler',
            'token_qr'      => $tokenQr,
            'status'        => 'buka',
            'tanggal'       => today(),
            'jam_mulai'     => now(),
            'jam_selesai'   => $berakhirPada,
        ]);

        return redirect()->route('dosen.absensi.show', $sesi->id)
            ->with('success', 'Sesi QR absensi berhasil dibuka dan dapat diatur!');
    }

    public function show($id)
    {
        $sesi = SesiAbsensi::with(['jadwal.mataKuliah.dosen.user', 'absensis.mahasiswa.user'])
            ->findOrFail($id);

        return view('dosen.absensi.show', compact('sesi'));
    }

    public function updateStatus(Request $request, $id)
    {
        $sesi = SesiAbsensi::findOrFail($id);
        $sesi->update(['status' => $request->status]); // 'buka' atau 'tutup'

        return back()->with('success', 'Status sesi absensi berhasil diperbarui.');
    }
    public function destroy($id)
    {
        $dosen     = \App\Models\Dosen::where('user_id', auth()->id())->first();
        $matkulIds = $dosen ? $dosen->mataKuliahs()->pluck('id') : collect();
        $jadwalIds = \App\Models\Jadwal::whereIn('mata_kuliah_id', $matkulIds)->pluck('id');

        $sesi = \App\Models\SesiAbsensi::whereIn('jadwal_id', $jadwalIds)->findOrFail($id);
        
        // Hapus relasi absensi mahasiswa terlebih dahulu jika ada
        $sesi->absensis()->delete();
        $sesi->delete();

        return redirect()->route('dosen.absensi.index')
            ->with('success', 'Sesi QR absensi berhasil dihapus.');
    }
}