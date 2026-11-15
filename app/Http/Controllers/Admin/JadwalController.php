<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\MataKuliah;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        $query = Jadwal::with(['mataKuliah.prodi', 'mataKuliah.dosen.user']);

        if ($request->filled('hari')) {
            $query->where('hari', $request->hari);
        }

        $jadwals = $query->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu')")
            ->orderBy('jam_mulai')
            ->paginate(10)
            ->withQueryString();

        return view('admin.jadwal.index', compact('jadwals'));
    }

    public function create()
    {
        $mataKuliahs = MataKuliah::with(['prodi', 'dosen.user'])->get();
        return view('admin.jadwal.create', compact('mataKuliahs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'mata_kuliah_id' => 'required|exists:mata_kuliahs,id',
            'hari'           => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_mulai'      => 'required|date_format:H:i',
            'jam_selesai'    => 'required|date_format:H:i|after:jam_mulai',
            'ruang'          => 'required|string|max:100',
            'tahun_akademik' => 'required|string|max:50',
        ]);

        Jadwal::create($request->only([
            'mata_kuliah_id', 'hari', 'jam_mulai', 'jam_selesai', 'ruang', 'tahun_akademik'
        ]));

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal kuliah berhasil ditambahkan.');
    }

    public function edit(Jadwal $jadwal)
    {
        $mataKuliahs = MataKuliah::with(['prodi', 'dosen.user'])->get();
        return view('admin.jadwal.edit', compact('jadwal', 'mataKuliahs'));
    }

    public function update(Request $request, Jadwal $jadwal)
    {
        $request->validate([
            'mata_kuliah_id' => 'required|exists:mata_kuliahs,id',
            'hari'           => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_mulai'      => 'required|date_format:H:i',
            'jam_selesai'    => 'required|date_format:H:i|after:jam_mulai',
            'ruang'          => 'required|string|max:100',
            'tahun_akademik' => 'required|string|max:50',
        ]);

        $jadwal->update($request->only([
            'mata_kuliah_id', 'hari', 'jam_mulai', 'jam_selesai', 'ruang', 'tahun_akademik'
        ]));

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal kuliah berhasil diperbarui.');
    }

    public function destroy(Jadwal $jadwal)
    {
        $jadwal->delete();
        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal kuliah berhasil dihapus.');
    }
}