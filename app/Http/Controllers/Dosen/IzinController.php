<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Dosen;
use App\Models\Perizinan;
use App\Models\SesiAbsensi;
use Illuminate\Http\Request;

class IzinController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $dosen = Dosen::where('user_id', $user->id)->first();

        $perizinans = $dosen
            ? Perizinan::with(['mahasiswa.user', 'jadwal.mataKuliah'])
                ->where('dosen_id', $dosen->id)
                ->latest()
                ->paginate(15)
            : collect();

        return view('dosen.izin.index', compact('perizinans'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:disetujui,ditolak',
        ]);

        $perizinan = Perizinan::findOrFail($id);
        $perizinan->update(['status' => $request->status]);

        // Jika disetujui, otomatis catat kehadiran mahasiswa sebagai 'izin' atau 'sakit' pada sesi tanggal tersebut
        if ($request->status === 'disetujui' && $perizinan->jadwal_id) {
            $sesi = SesiAbsensi::where('jadwal_id', $perizinan->jadwal_id)
                ->whereDate('created_at', $perizinan->tanggal)
                ->latest()
                ->first();

            if ($sesi) {
                Absensi::updateOrCreate(
                    [
                        'sesi_absensi_id' => $sesi->id,
                        'mahasiswa_id'    => $perizinan->mahasiswa_id,
                    ],
                    [
                        'status' => $perizinan->jenis, // 'izin' atau 'sakit'
                    ]
                );
            }
        }

        return back()->with('success', 'Status surat izin mahasiswa berhasil diperbarui menjadi ' . strtoupper($request->status) . '.');
    }
}