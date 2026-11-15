<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Dosen;
use App\Models\Jadwal;
use App\Models\MataKuliah;
use App\Models\SesiAbsensi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $dosen     = Dosen::where('user_id', auth()->id())->first();
        $matkulIds = $dosen ? $dosen->mataKuliahs()->pluck('id') : collect();
        $jadwalIds = Jadwal::whereIn('mata_kuliah_id', $matkulIds)->pluck('id');

        $hariMap = [
            'Sunday'    => 'Minggu',
            'Monday'    => 'Senin',
            'Tuesday'   => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday'  => 'Kamis',
            'Friday'    => 'Jumat',
            'Saturday'  => 'Sabtu',
        ];
        $hariIni = $hariMap[now()->format('l')] ?? 'Senin';

        $totalMatkul   = $matkulIds->count();
        $totalJadwal   = $jadwalIds->count();
        $sesiAktif     = SesiAbsensi::whereIn('jadwal_id', $jadwalIds)->where('status', 'buka')->count();
        $totalPertemuan = SesiAbsensi::whereIn('jadwal_id', $jadwalIds)->count();

        $jadwalHariIni = Jadwal::with('mataKuliah.prodi')
            ->whereIn('mata_kuliah_id', $matkulIds)
            ->where('hari', $hariIni)
            ->orderBy('jam_mulai')
            ->get();

        $sesiTerbaru = SesiAbsensi::with('jadwal.mataKuliah')
            ->withCount('absensis')
            ->whereIn('jadwal_id', $jadwalIds)
            ->latest()
            ->take(5)
            ->get();

        return view('dosen.dashboard', compact(
            'dosen',
            'hariIni',
            'totalMatkul',
            'totalJadwal',
            'sesiAktif',
            'totalPertemuan',
            'jadwalHariIni',
            'sesiTerbaru'
        ));
    }

    public function notifikasi(Request $request)
    {
        $dosen     = Dosen::where('user_id', auth()->id())->first();
        $matkulIds = $dosen ? $dosen->mataKuliahs()->pluck('id') : collect();
        $jadwalIds = Jadwal::whereIn('mata_kuliah_id', $matkulIds)->pluck('id');

        $hariMap = [
            'Sunday'    => 'Minggu',
            'Monday'    => 'Senin',
            'Tuesday'   => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday'  => 'Kamis',
            'Friday'    => 'Jumat',
            'Saturday'  => 'Sabtu',
        ];
        $hariIni = $hariMap[now()->format('l')] ?? 'Senin';

        $notifikasis = collect();

        // 1. Notifikasi Sesi Absensi yang Masih Terbuka
        $sesiTerbuka = SesiAbsensi::with('jadwal.mataKuliah')
            ->withCount('absensis')
            ->whereIn('jadwal_id', $jadwalIds)
            ->where('status', 'buka')
            ->latest()
            ->get();

        foreach ($sesiTerbuka as $sesi) {
            $namaMk = $sesi->jadwal->mataKuliah->nama_mk ?? 'Mata Kuliah';
            $notifikasis->push((object) [
                'kategori'   => 'sesi',
                'prioritas'  => 'warning',
                'ikon'       => 'bi-broadcast',
                'judul'      => "Sesi Absensi Masih Terbuka: {$namaMk}",
                'pesan'      => "Pertemuan ke-{$sesi->pertemuan_ke} ({$sesi->absensis_count} mahasiswa tercatat hadir). Tutup sesi jika perkuliahan telah berakhir.",
                'waktu'      => $sesi->created_at ? $sesi->created_at->diffForHumans() : 'Sedang berlangsung',
                'timestamp'  => $sesi->created_at ?? now(),
                'aksi_label' => 'Kelola / Tutup Sesi',
                'aksi_url'   => route('dosen.absensi.show', $sesi->id),
            ]);
        }

        // 2. Notifikasi Pengingat Jadwal Mengajar Hari Ini
        $jadwalHariIni = Jadwal::with('mataKuliah')
            ->whereIn('mata_kuliah_id', $matkulIds)
            ->where('hari', $hariIni)
            ->orderBy('jam_mulai')
            ->get();

        foreach ($jadwalHariIni as $jadwal) {
            $namaMk     = $jadwal->mataKuliah->nama_mk ?? 'Mata Kuliah';
            $jamMulai   = Carbon::parse($jadwal->jam_mulai)->format('H:i');
            $jamSelesai = Carbon::parse($jadwal->jam_selesai)->format('H:i');

            $sesiHariIni = SesiAbsensi::where('jadwal_id', $jadwal->id)
                ->whereDate('tanggal', today())
                ->first();

            if ($sesiHariIni) {
                $notifikasis->push((object) [
                    'kategori'   => 'jadwal',
                    'prioritas'  => 'success',
                    'ikon'       => 'bi-calendar-check-fill',
                    'judul'      => "Jadwal Hari Ini Sudah Dibuka: {$namaMk}",
                    'pesan'      => "Pukul {$jamMulai} - {$jamSelesai} di Ruang {$jadwal->ruang}. Sesi pertemuan ke-{$sesiHariIni->pertemuan_ke} sudah dibuat.",
                    'waktu'      => "Hari ini, {$jamMulai}",
                    'timestamp'  => Carbon::today()->setTimeFromTimeString($jadwal->jam_mulai),
                    'aksi_label' => 'Lihat Detail Sesi',
                    'aksi_url'   => route('dosen.absensi.show', $sesiHariIni->id),
                ]);
            } else {
                $notifikasis->push((object) [
                    'kategori'   => 'jadwal',
                    'prioritas'  => 'primary',
                    'ikon'       => 'bi-alarm-fill',
                    'judul'      => "Pengingat Jadwal Mengajar: {$namaMk}",
                    'pesan'      => "Jadwal hari {$hariIni} pukul {$jamMulai} - {$jamSelesai} di Ruang {$jadwal->ruang}. Sesi QR absensi hari ini belum dibuka.",
                    'waktu'      => "Hari ini, {$jamMulai}",
                    'timestamp'  => Carbon::today()->setTimeFromTimeString($jadwal->jam_mulai),
                    'aksi_label' => 'Buka Sesi QR',
                    'aksi_url'   => route('dosen.absensi.create'),
                ]);
            }
        }

        // 3. Notifikasi Aktivitas Presensi Mahasiswa Terbaru
        $sesiIds = SesiAbsensi::whereIn('jadwal_id', $jadwalIds)->pluck('id');
        $absensiTerbaru = Absensi::with(['mahasiswa.user', 'sesiAbsensi.jadwal.mataKuliah'])
            ->whereIn('sesi_absensi_id', $sesiIds)
            ->latest()
            ->take(12)
            ->get();

        foreach ($absensiTerbaru as $absen) {
            $namaMhs = $absen->mahasiswa->user->name ?? 'Mahasiswa';
            $nimMhs  = $absen->mahasiswa->nim ?? '-';
            $namaMk  = $absen->sesiAbsensi->jadwal->mataKuliah->nama_mk ?? 'Mata Kuliah';
            $status  = strtoupper($absen->status ?? 'HADIR');
            $isIzin  = in_array(strtolower($absen->status ?? ''), ['izin', 'sakit']);

            $notifikasis->push((object) [
                'kategori'   => 'mahasiswa',
                'prioritas'  => $isIzin ? 'info' : 'secondary',
                'ikon'       => $isIzin ? 'bi-envelope-exclamation-fill' : 'bi-person-check-fill',
                'judul'      => "{$namaMhs} ({$nimMhs}) • {$status}",
                'pesan'      => "Tercatat {$status} pada mata kuliah {$namaMk} (Pertemuan ke-" . ($absen->sesiAbsensi->pertemuan_ke ?? '-') . ").",
                'waktu'      => $absen->created_at ? $absen->created_at->diffForHumans() : 'Baru saja',
                'timestamp'  => $absen->created_at ?? now(),
                'aksi_label' => 'Cek Kehadiran',
                'aksi_url'   => route('dosen.absensi.show', $absen->sesi_absensi_id),
            ]);
        }

        $ringkasan = [
            'semua'     => $notifikasis->count(),
            'sesi'      => $notifikasis->where('kategori', 'sesi')->count(),
            'jadwal'    => $notifikasis->where('kategori', 'jadwal')->count(),
            'mahasiswa' => $notifikasis->where('kategori', 'mahasiswa')->count(),
        ];

        $filter = $request->query('kategori', 'semua');
        if ($filter !== 'semua') {
            $notifikasis = $notifikasis->where('kategori', $filter)->values();
        }

        return view('dosen.notifikasi.index', compact('notifikasis', 'ringkasan', 'filter', 'hariIni'));
    }
}