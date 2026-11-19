<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengaturanLokasi;
use Illuminate\Http\Request;

class PengaturanQrController extends Controller
{
    public function index()
    {
        $setting = PengaturanLokasi::first();
        return view('admin.pengaturan-qr.index', compact('setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'durasi_qr'        => 'required|integer|min:10|max:7200',
            'refresh_otomatis' => 'nullable|boolean',
            'wajib_lokasi'     => 'nullable|boolean',
            'latitude'         => 'nullable|numeric|between:-90,90',
            'longitude'        => 'nullable|numeric|between:-180,180',
            'radius_km'        => 'required|numeric|min:1|max:20',
        ]);

        if ($request->boolean('wajib_lokasi') && (!$request->filled('latitude') || !$request->filled('longitude'))) {
            return back()->withErrors([
                'latitude' => 'Klik tombol "Gunakan Lokasi Terkini" terlebih dahulu sebelum mengaktifkan validasi lokasi.'
            ]);
        }

        // Konversi KM ke Meter sebelum disimpan ke database (contoh: 1 KM -> 1000 Meter, 1.2 KM -> 1200 Meter)
        $radiusMeter = (int) round((float) $request->radius_km * 1000);

        $data = [
            'nama_lokasi'  => 'Kampus Utama',
            'latitude'     => $request->latitude ?? 0,
            'longitude'    => $request->longitude ?? 0,
            'radius_meter' => $radiusMeter,
            'wajib_gps'    => $request->boolean('wajib_lokasi'),
        ];

        $lokasi = PengaturanLokasi::first();
        if ($lokasi) {
            $lokasi->update($data);
        } else {
            PengaturanLokasi::create($data);
        }

        return back()->with('success', 'Pengaturan QR Code & Area Kampus (' . $request->radius_km . ' KM) berhasil disimpan!');
    }
}