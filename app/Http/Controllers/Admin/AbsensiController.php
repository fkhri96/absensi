<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SesiAbsensi;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $sesis = SesiAbsensi::with(['jadwal.mataKuliah.dosen.user'])
            ->withCount('absensis')
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('jadwal.mataKuliah', function ($sub) use ($search) {
                        $sub->where('nama_mk', 'like', "%{$search}%");
                    })->orWhereHas('jadwal.mataKuliah.dosen.user', function ($sub) use ($search) {
                        $sub->where('name', 'like', "%{$search}%");
                    });
                });
            })
            ->when($request->tanggal, function ($query, $tanggal) {
                $query->whereDate('tanggal', $tanggal);
            })
            ->latest()
            ->paginate(10);

        return view('admin.absensi.index', compact('sesis'));
    }
}