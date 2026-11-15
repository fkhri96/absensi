<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\MataKuliah;
use App\Models\Prodi;
use Illuminate\Http\Request;

class MataKuliahController extends Controller
{
    public function index(Request $request)
    {
        $query = MataKuliah::with(['prodi', 'dosen.user']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('kode_mk', 'like', "%{$search}%")
                ->orWhere('nama_mk', 'like', "%{$search}%");
        }

        $mataKuliahs = $query->latest()->paginate(10)->withQueryString();
        return view('admin.matakuliah.index', compact('mataKuliahs'));
    }

    public function create()
    {
        $prodis = Prodi::all();
        $dosens = Dosen::with('user')->get();
        return view('admin.matakuliah.create', compact('prodis', 'dosens'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'prodi_id' => 'required|exists:prodis,id',
            'dosen_id' => 'required|exists:dosens,id',
            'kode_mk'  => 'required|string|max:20|unique:mata_kuliahs,kode_mk',
            'nama_mk'  => 'required|string|max:255',
            'sks'      => 'required|integer|min:1|max:6',
            'semester' => 'required|integer|min:1|max:8',
        ]);

        MataKuliah::create($request->only([
            'prodi_id', 'dosen_id', 'kode_mk', 'nama_mk', 'sks', 'semester'
        ]));

        return redirect()->route('admin.matakuliah.index')->with('success', 'Mata kuliah berhasil ditambahkan.');
    }

    public function edit(MataKuliah $matakuliah)
    {
        $prodis = Prodi::all();
        $dosens = Dosen::with('user')->get();
        return view('admin.matakuliah.edit', compact('matakuliah', 'prodis', 'dosens'));
    }

    public function update(Request $request, MataKuliah $matakuliah)
    {
        $request->validate([
            'prodi_id' => 'required|exists:prodis,id',
            'dosen_id' => 'required|exists:dosens,id',
            'kode_mk'  => 'required|string|max:20|unique:mata_kuliahs,kode_mk,' . $matakuliah->id,
            'nama_mk'  => 'required|string|max:255',
            'sks'      => 'required|integer|min:1|max:6',
            'semester' => 'required|integer|min:1|max:8',
        ]);

        $matakuliah->update($request->only([
            'prodi_id', 'dosen_id', 'kode_mk', 'nama_mk', 'sks', 'semester'
        ]));

        return redirect()->route('admin.matakuliah.index')->with('success', 'Mata kuliah berhasil diperbarui.');
    }

    public function destroy(MataKuliah $matakuliah)
    {
        $matakuliah->delete();
        return redirect()->route('admin.matakuliah.index')->with('success', 'Mata kuliah berhasil dihapus.');
    }
}