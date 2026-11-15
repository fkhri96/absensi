<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengaturanQrController extends Controller
{
    public function index()
    {
        $setting = DB::table('pengaturan_qrs')->first();

        if (!$setting) {
            $id = DB::table('pengaturan_qrs')->insertGetId([
                'durasi_detik'     => 60,
                'refresh_otomatis' => true,
                'validasi_lokasi'  => false,
                'latitude'         => null,
                'longitude'        => null,
                'radius_meter'     => 100,
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);

            $setting = DB::table('pengaturan_qrs')->find($id);
        }

        return view('admin.pengaturan-qr.index', compact('setting'));
    }

    public function store(Request $request)
    {
        return $this->update($request);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'durasi_detik' => 'required|integer|min:5|max:3600',
            'latitude'     => 'nullable|string|max:50',
            'longitude'    => 'nullable|string|max:50',
            'radius_meter' => 'required|integer|min:10|max:5000',
        ]);

        $data = [
            'durasi_detik'     => (int) $validated['durasi_detik'],
            'refresh_otomatis' => $request->has('refresh_otomatis'),
            'validasi_lokasi'  => $request->has('validasi_lokasi'),
            'latitude'         => $validated['latitude'] ?? null,
            'longitude'        => $validated['longitude'] ?? null,
            'radius_meter'     => (int) $validated['radius_meter'],
            'updated_at'       => now(),
        ];

        $existing = DB::table('pengaturan_qrs')->first();

        if ($existing) {
            DB::table('pengaturan_qrs')->where('id', $existing->id)->update($data);
        } else {
            $data['created_at'] = now();
            DB::table('pengaturan_qrs')->insert($data);
        }

        return back()->with('success', 'Pengaturan QR Code berhasil disimpan ke database.');
    }
}