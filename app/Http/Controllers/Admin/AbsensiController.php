<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\SesiAbsensi;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function index()
    {
        // Pantau semua sesi QR yang dibuat oleh dosen
        $sesis = SesiAbsensi::with(['jadwal.mataKuliah.dosen.user', 'jadwal.mataKuliah.prodi', 'absensis'])
            ->latest()
            ->paginate(15);

        // Pantau log kehadiran mahasiswa terbaru
        $absensis = Absensi::with(['mahasiswa.user', 'mahasiswa.prodi', 'sesiAbsensi.jadwal.mataKuliah'])
            ->latest()
            ->take(20)
            ->get();

        return view('admin.absensi.index', compact('sesis', 'absensis'));
    }
}