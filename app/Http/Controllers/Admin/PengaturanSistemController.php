<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class PengaturanSistemController extends Controller
{
    private function getSettings(): array
    {
        $defaults = [
            'nama_aplikasi'       => 'Absensi Kampus',
            'nama_kampus'         => 'Universitas Boyolali',
            'tahun_akademik'      => '2026/2027',
            'semester'            => 'Ganjil',
            'toleransi_terlambat' => 15,
            'minimal_kehadiran'   => 75,
            'alamat_kampus'       => '',
        ];

        $merged = $defaults;

        if (Storage::disk('local')->exists('pengaturan_sistem.json')) {
            $saved = json_decode(Storage::disk('local')->get('pengaturan_sistem.json'), true);
            if (is_array($saved)) {
                $merged = array_merge($merged, $saved);
            }
        }

        if (Schema::hasTable('pengaturan_sistems')) {
            $row = DB::table('pengaturan_sistems')->first();
            if ($row) {
                $merged = array_merge($merged, array_filter((array) $row, fn ($v) => !is_null($v)));
            }
        }

        return $merged;
    }

    public function index()
    {
        $setting = (object) $this->getSettings();

        return view('admin.pengaturan-sistem.index', compact('setting'));
    }

    public function store(Request $request)
    {
        return $this->update($request);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'nama_aplikasi'       => 'required|string|max:100',
            'nama_kampus'         => 'required|string|max:150',
            'tahun_akademik'      => 'required|string|max:20',
            'semester'            => 'required|in:Ganjil,Genap',
            'toleransi_terlambat' => 'required|integer|min:0|max:120',
            'minimal_kehadiran'   => 'required|integer|min:0|max:100',
            'alamat_kampus'       => 'nullable|string|max:255',
        ]);

        if (Schema::hasTable('pengaturan_sistems')) {
            $columns = Schema::getColumnListing('pengaturan_sistems');
            $dbData  = array_intersect_key($validated, array_flip($columns));

            if (!empty($dbData)) {
                $existing = DB::table('pengaturan_sistems')->first();
                if ($existing) {
                    if (in_array('updated_at', $columns)) {
                        $dbData['updated_at'] = now();
                    }
                    DB::table('pengaturan_sistems')->where('id', $existing->id)->update($dbData);
                } else {
                    if (in_array('created_at', $columns)) {
                        $dbData['created_at'] = now();
                    }
                    if (in_array('updated_at', $columns)) {
                        $dbData['updated_at'] = now();
                    }
                    DB::table('pengaturan_sistems')->insert($dbData);
                }
            }
        }

        Storage::disk('local')->put('pengaturan_sistem.json', json_encode($validated, JSON_PRETTY_PRINT));

        return back()->with('success', 'Pengaturan sistem akademik berhasil disimpan.');
    }
}