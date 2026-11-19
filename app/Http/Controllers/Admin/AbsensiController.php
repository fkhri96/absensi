<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\PengaturanLokasi;
use App\Models\SesiAbsensi;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function index()
    {
        $sesis = SesiAbsensi::with(['jadwal.mataKuliah.dosen.user', 'jadwal.mataKuliah.prodi', 'absensis'])
            ->latest()
            ->paginate(15);

        $absensis = Absensi::with(['mahasiswa.user', 'mahasiswa.prodi', 'sesiAbsensi.jadwal.mataKuliah'])
            ->latest()
            ->take(20)
            ->get();

        // Ambil pengaturan lokasi aktif (atau buat default jika belum ada)
        $lokasi = PengaturanLokasi::first() ?? new PengaturanLokasi([
            'nama_lokasi'  => 'Kampus Utama',
            'latitude'     => -7.536064,
            'longitude'    => 110.601540,
            'radius_meter' => 100,
            'wajib_gps'    => true,
        ]);

        return view('admin.absensi.index', compact('sesis', 'absensis', 'lokasi'));
    }

    public function simpanLokasi(Request $request)
    {
        $request->validate([
            'nama_lokasi'  => 'required|string|max:100',
            'latitude'     => 'required|numeric|between:-90,90',
            'longitude'    => 'required|numeric|between:-180,180',
            'radius_meter' => 'required|integer|min:10|max:5000',
            'wajib_gps'    => 'nullable|boolean',
        ]);

        $lokasi = PengaturanLokasi::first();
        $data = [
            'nama_lokasi'  => $request->nama_lokasi,
            'latitude'     => $request->latitude,
            'longitude'    => $request->longitude,
            'radius_meter' => $request->radius_meter,
            'wajib_gps'    => $request->boolean('wajib_gps'),
        ];

        if ($lokasi) {
            $lokasi->update($data);
        } else {
            PengaturanLokasi::create($data);
        }

        return back()->with('success', 'Pengaturan lokasi & radius absensi berhasil disimpan!');
    }
}