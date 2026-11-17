<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Jadwal;
use App\Models\SesiAbsensi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AbsensiController extends Controller
{
    public function index()
    {
        $dosen = Dosen::where('user_id', auth()->id())->first();

        $sesis = SesiAbsensi::with(['jadwal.mataKuliah', 'absensis'])
            ->whereHas('jadwal', function ($q) use ($dosen) {
                if ($dosen) {
                    $q->where('dosen_id', $dosen->id)
                      ->orWhereHas('mataKuliah', function ($sub) use ($dosen) {
                          $sub->where('dosen_id', $dosen->id);
                      });
                }
            })
            ->latest()
            ->paginate(10);

        return view('dosen.absensi.index', compact('sesis'));
    }

    public function create()
    {
        $dosen = Dosen::where('user_id', auth()->id())->first();

        $jadwals = Jadwal::with('mataKuliah')
            ->where(function ($q) use ($dosen) {
                if ($dosen) {
                    $q->where('dosen_id', $dosen->id)
                      ->orWhereHas('mataKuliah', function ($sub) use ($dosen) {
                          $sub->where('dosen_id', $dosen->id);
                      });
                }
            })
            ->get();

        return view('dosen.absensi.create', compact('jadwals'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jadwal_id'    => 'required|exists:jadwals,id',
            'pertemuan_ke' => 'required|integer|min:1|max:32',
            'materi'       => 'nullable|string|max:255',
            'tanggal'      => 'nullable|date',
        ]);

        // Matikan sesi lain yang masih aktif pada jadwal yang sama
        SesiAbsensi::where('jadwal_id', $request->jadwal_id)
            ->where('status', 'buka')
            ->update(['status' => 'tutup']);

        $sesi = SesiAbsensi::create([
            'jadwal_id'    => $request->jadwal_id,
            'tanggal'      => $request->tanggal ?? now()->toDateString(),
            'pertemuan_ke' => $request->pertemuan_ke,
            'materi'       => $request->filled('materi') ? $request->materi : 'Pertemuan Ke-' . $request->pertemuan_ke,
            'token_qr'     => 'ABSEN-' . strtoupper(Str::random(10)),
            'status'       => 'buka',
        ]);

        return redirect()->route('dosen.absensi.show', $sesi->id)
            ->with('success', 'QR Code berhasil dibuat dan dalam kondisi HIDUP.');
    }

    public function show($id)
    {
        $sesi = SesiAbsensi::with(['jadwal.mataKuliah.dosen.user', 'absensis.mahasiswa.user'])->findOrFail($id);
        return view('dosen.absensi.show', compact('sesi'));
    }

    public function refreshToken($id)
    {
        $sesi = SesiAbsensi::findOrFail($id);

        if ($sesi->status !== 'buka') {
            return response()->json([
                'status'  => 'closed',
                'message' => 'QR Code sedang dimatikan.'
            ], 400);
        }

        // Simpan token sebelumnya ke cache selama 10 detik sebagai toleransi jeda scan jaringan
        if ($sesi->token_qr) {
            cache()->put('qr_prev_' . $sesi->token_qr, $sesi->id, now()->addSeconds(10));
        }

        $tokenBaru = 'ABSEN-' . strtoupper(Str::random(10));

        $sesi->update([
            'token_qr' => $tokenBaru,
        ]);

        return response()->json([
            'status'       => 'ok',
            'token'        => $tokenBaru,
            'jumlah_hadir' => $sesi->absensis()->count(),
        ]);
    }

    public function toggleStatus($id)
    {
        $sesi = SesiAbsensi::findOrFail($id);

        if ($sesi->status === 'buka') {
            $sesi->update(['status' => 'tutup']);
            $pesan = 'QR Code berhasil DIMATIKAN. Mahasiswa tidak dapat melakukan scan.';
        } else {
            SesiAbsensi::where('jadwal_id', $sesi->jadwal_id)
                ->where('id', '!=', $sesi->id)
                ->where('status', 'buka')
                ->update(['status' => 'tutup']);

            $sesi->update([
                'status'   => 'buka',
                'token_qr' => 'ABSEN-' . strtoupper(Str::random(10)),
            ]);
            $pesan = 'QR Code berhasil DIHIDUPKAN kembali. Mahasiswa sudah bisa scan absensi.';
        }

        return back()->with('success', $pesan);
    }

    public function destroy($id)
    {
        $sesi = SesiAbsensi::findOrFail($id);
        $sesi->absensis()->delete();
        $sesi->delete();

        return redirect()->route('dosen.absensi.index')
            ->with('success', 'Sesi absensi berhasil dihapus.');
    }
}