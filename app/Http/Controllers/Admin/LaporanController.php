<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\MataKuliah;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $mataKuliahs = MataKuliah::with('dosen.user')->orderBy('nama_mk')->get();

        $query = Absensi::with([
            'mahasiswa.user',
            'sesiAbsensi.jadwal.mataKuliah.dosen.user',
        ])
        ->when($request->mata_kuliah_id, function ($q, $mkId) {
            $q->whereHas('sesiAbsensi.jadwal', function ($sub) use ($mkId) {
                $sub->where('mata_kuliah_id', $mkId);
            });
        })
        ->when($request->tanggal_mulai, function ($q, $mulai) {
            $q->whereHas('sesiAbsensi', function ($sub) use ($mulai) {
                $sub->whereDate('tanggal', '>=', $mulai);
            });
        })
        ->when($request->tanggal_selesai, function ($q, $selesai) {
            $q->whereHas('sesiAbsensi', function ($sub) use ($selesai) {
                $sub->whereDate('tanggal', '<=', $selesai);
            });
        })
        ->when($request->status, function ($q, $status) {
            $q->where('status', $status);
        });

        // Ringkasan statistik sesuai filter yang dipilih
        $ringkasan = [
            'total' => (clone $query)->count(),
            'hadir' => (clone $query)->where('status', 'hadir')->count(),
            'izin'  => (clone $query)->where('status', 'izin')->count(),
            'sakit' => (clone $query)->where('status', 'sakit')->count(),
            'alpha' => (clone $query)->where('status', 'alpha')->count(),
        ];

        $laporans = $query->latest()->paginate(15);

        return view('admin.laporan.index', compact('laporans', 'mataKuliahs', 'ringkasan'));
    }
}