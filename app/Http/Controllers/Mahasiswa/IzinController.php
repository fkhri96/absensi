<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\Perizinan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class IzinController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->first();

        $izins = $mahasiswa
            ? Perizinan::with(['jadwal.mataKuliah', 'dosen.user'])
                ->where('mahasiswa_id', $mahasiswa->id)
                ->latest()
                ->paginate(10)
            : collect();

        $matkulIds = MataKuliah::when($mahasiswa && $mahasiswa->prodi_id, function ($q) use ($mahasiswa) {
            return $q->where('prodi_id', $mahasiswa->prodi_id);
        })->pluck('id');

        $jadwals = Jadwal::with(['mataKuliah.dosen.user', 'dosen.user'])
            ->whereIn('mata_kuliah_id', $matkulIds)
            ->orderBy('hari')
            ->orderBy('jam_mulai')
            ->get();

        return view('mahasiswa.izin.index', compact('izins', 'jadwals'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jadwal_id'  => 'required|exists:jadwals,id',
            'jenis'      => 'required|in:izin,sakit',
            'tanggal'    => 'required|date',
            'keterangan' => 'required|string|max:500',
            'bukti_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $user = auth()->user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->first();

        if (!$mahasiswa) {
            return back()->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        $jadwal = Jadwal::with('mataKuliah')->findOrFail($validated['jadwal_id']);
        $dosenId = $jadwal->dosen_id ?? $jadwal->mataKuliah->dosen_id ?? null;

        $path = null;
        if ($request->hasFile('bukti_file')) {
            $path = $request->file('bukti_file')->store('bukti_izin', 'public');
        }

        Perizinan::create([
            'mahasiswa_id' => $mahasiswa->id,
            'jadwal_id'    => $jadwal->id,
            'dosen_id'     => $dosenId,
            'jenis'        => $validated['jenis'],
            'tanggal'      => $validated['tanggal'],
            'keterangan'   => $validated['keterangan'],
            'bukti'        => $path,
            'status'       => 'pending',
        ]);

        return redirect()->route('mahasiswa.izin.index')
            ->with('success', 'Surat izin/sakit berhasil dikirim ke dosen pengampu mata kuliah tersebut.');
    }

    public function destroy($id)
    {
        $user = auth()->user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->first();

        if (!$mahasiswa) {
            return back()->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        $perizinan = Perizinan::where('id', $id)
            ->where('mahasiswa_id', $mahasiswa->id)
            ->firstOrFail();

        if ($perizinan->status !== 'pending') {
            return back()->with('error', 'Pengajuan tidak dapat dibatalkan karena sudah diproses oleh dosen.');
        }

        // Hapus file lampiran dari storage jika ada
        if ($perizinan->bukti && Storage::disk('public')->exists($perizinan->bukti)) {
            Storage::disk('public')->delete($perizinan->bukti);
        }

        $perizinan->delete();

        return redirect()->route('mahasiswa.izin.index')
            ->with('success', 'Pengajuan surat izin/sakit berhasil dibatalkan. Silakan ajukan ulang jika ada perbaikan.');
    }
}