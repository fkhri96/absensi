<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Mahasiswa;
use App\Models\SesiAbsensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class AbsensiController extends Controller
{
    public function index()
    {
        $mahasiswa = Mahasiswa::where('user_id', auth()->id())->first();

        $riwayatAbsensi = $mahasiswa
            ? Absensi::with(['sesiAbsensi.jadwal.mataKuliah.dosen.user'])
                ->where('mahasiswa_id', $mahasiswa->id)
                ->latest()
                ->paginate(10)
            : collect();

        return view('mahasiswa.absensi.index', compact('riwayatAbsensi'));
    }

    public function scan()
    {
        return view('mahasiswa.absensi.scan');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_qr' => 'required|string',
        ]);

        $mahasiswa = Mahasiswa::where('user_id', auth()->id())->first();
        if (!$mahasiswa) {
            return back()->with('error', 'Data profil mahasiswa tidak ditemukan. Hubungi Admin.');
        }

        $kode = trim($request->kode_qr);

        $sesi = SesiAbsensi::where('status', 'buka')
            ->where(function ($query) use ($kode) {
                $query->where('id', $kode);
                if (Schema::hasColumn('sesi_absensis', 'kode_qr')) {
                    $query->orWhere('kode_qr', $kode);
                }
                if (Schema::hasColumn('sesi_absensis', 'token')) {
                    $query->orWhere('token', $kode);
                }
            })
            ->first();

        if (!$sesi) {
            return back()->with('error', 'Kode QR tidak valid atau sesi absensi sudah ditutup.');
        }

        $sudahAbsen = Absensi::where('sesi_absensi_id', $sesi->id)
            ->where('mahasiswa_id', $mahasiswa->id)
            ->exists();

        if ($sudahAbsen) {
            return redirect()
                ->route('mahasiswa.absensi.index')
                ->with('success', 'Kamu sudah tercatat hadir pada pertemuan ini.');
        }

        Absensi::create([
            'sesi_absensi_id' => $sesi->id,
            'mahasiswa_id'    => $mahasiswa->id,
            'status'          => 'hadir',
        ]);

        return redirect()
            ->route('mahasiswa.absensi.index')
            ->with('success', 'Presensi berhasil dicatat!');
    }
}