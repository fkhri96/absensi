<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Mahasiswa;
use App\Models\SesiAbsensi;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function index()
    {
        return view('mahasiswa.absensi.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'token_qr' => 'required|string',
        ]);

        $user = auth()->user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->first();
        
        if (!$mahasiswa) {
            return back()->with('error', 'Data profil mahasiswa tidak ditemukan.');
        }

        $sesi = SesiAbsensi::where('token_qr', $validated['token_qr'])
            ->where('status', 'buka')
            ->first();

        if (!$sesi) {
            return back()->with('error', 'QR Code tidak valid, sudah kedaluwarsa, atau sesi ditutup oleh dosen.');
        }

        $sudahAbsen = Absensi::where('sesi_absensi_id', $sesi->id)
            ->where('mahasiswa_id', $mahasiswa->id)
            ->exists();

        if ($sudahAbsen) {
            return back()->with('warning', 'Anda sudah tercatat hadir pada sesi perkuliahan ini.');
        }

        Absensi::create([
            'sesi_absensi_id' => $sesi->id,
            'mahasiswa_id'    => $mahasiswa->id,
            'status'          => 'hadir',
        ]);

        return redirect()->route('mahasiswa.absensi.index')
            ->with('success', 'Presensi berhasil! Kehadiran Anda telah tercatat.');
    }

    public function riwayat()
    {
        $user = auth()->user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->first();
        
        $absensis = $mahasiswa ? Absensi::with(['sesiAbsensi.jadwal.mataKuliah'])
            ->where('mahasiswa_id', $mahasiswa->id)
            ->latest()
            ->paginate(15) : collect();

        return view('mahasiswa.riwayat.index', compact('absensis'));
    }

    public function statistik()
    {
        $user = auth()->user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->first();
        $absensis = $mahasiswa ? Absensi::where('mahasiswa_id', $mahasiswa->id)->get() : collect();

        $hadir = $absensis->where('status', 'hadir')->count();
        $izin  = $absensis->where('status', 'izin')->count();
        $sakit = $absensis->where('status', 'sakit')->count();
        $alpa  = $absensis->where('status', 'alpa')->count();

        return view('mahasiswa.statistik.index', compact('hadir', 'izin', 'sakit', 'alpa'));
    }

    public function cetakPdf()
    {
        $user = auth()->user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->first();
        $absensis = $mahasiswa ? Absensi::with(['sesiAbsensi.jadwal.mataKuliah'])
            ->where('mahasiswa_id', $mahasiswa->id)
            ->get() : collect();

        return view('mahasiswa.laporan.cetak', compact('mahasiswa', 'absensis'));
    }
}