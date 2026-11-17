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

    public function scanLangsung($token)
    {
        $tokenBersih = trim(basename(parse_url($token, PHP_URL_PATH) ?? $token));
        return $this->prosesPresensi($tokenBersih);
    }

    public function store(Request $request)
    {
        $request->validate([
            'token_qr' => 'required|string',
        ]);

        $input = trim($request->token_qr);

        // Ekstrak kode token murni baik dari URL apa pun maupun teks langsung
        $path  = parse_url($input, PHP_URL_PATH) ?? $input;
        $token = trim(basename($path));

        return $this->prosesPresensi($token);
    }

    private function prosesPresensi(string $token)
    {
        $user = auth()->user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->first();

        if (!$mahasiswa) {
            return redirect()->route('mahasiswa.absensi.index')
                ->with('error', 'Data profil mahasiswa tidak ditemukan.');
        }

        // Bersihkan karakter tak terlihat jika ada
        $tokenClean = preg_replace('/[^A-Za-z0-9\-]/', '', $token);

        // 1. Cari sesi berdasarkan token aktif saat ini
        $sesi = SesiAbsensi::with('jadwal.mataKuliah')
            ->where('token_qr', $tokenClean)
            ->first();

        // 2. Toleransi jeda 10 detik jika token baru saja berganti tepat saat mahasiswa melakukan scan
        if (!$sesi && cache()->has('qr_prev_' . $tokenClean)) {
            $sesiId = cache()->get('qr_prev_' . $tokenClean);
            $sesi = SesiAbsensi::with('jadwal.mataKuliah')->find($sesiId);
        }

        if (!$sesi) {
            return redirect()->route('mahasiswa.absensi.index')
                ->with('error', 'QR Code tidak dikenali atau sudah kedaluwarsa. Silakan arahkan kamera ke QR terbaru di layar dosen.');
        }

        if ($sesi->status !== 'buka') {
            return redirect()->route('mahasiswa.absensi.index')
                ->with('error', 'QR Code sedang DIMATIKAN oleh dosen. Presensi tidak dapat dilakukan.');
        }

        $prodiMatkul = $sesi->jadwal->mataKuliah->prodi_id ?? null;
        if ($mahasiswa->prodi_id && $prodiMatkul && $mahasiswa->prodi_id != $prodiMatkul) {
            return redirect()->route('mahasiswa.absensi.index')
                ->with('error', 'QR Code ini bukan untuk Program Studi Anda.');
        }

        $sudahAbsen = Absensi::where('sesi_absensi_id', $sesi->id)
            ->where('mahasiswa_id', $mahasiswa->id)
            ->exists();

        if ($sudahAbsen) {
            return redirect()->route('mahasiswa.riwayat.index')
                ->with('warning', 'Anda sudah tercatat hadir pada pertemuan ke-' . $sesi->pertemuan_ke . ' ini.');
        }

        Absensi::create([
            'sesi_absensi_id' => $sesi->id,
            'mahasiswa_id'    => $mahasiswa->id,
            'status'          => 'hadir',
        ]);

        return redirect()->route('mahasiswa.riwayat.index')
            ->with('success', 'Presensi berhasil! Kehadiran Anda pada mata kuliah ' . ($sesi->jadwal->mataKuliah->nama_mk ?? '') . ' telah tercatat.');
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