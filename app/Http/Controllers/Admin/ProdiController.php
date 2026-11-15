<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fakultas;
use App\Models\Prodi;
use Illuminate\Http\Request;

class ProdiController extends Controller
{
    public function index()
    {
        $prodis = Prodi::with('fakultas')->latest()->get();
        return view('admin.prodi.index', compact('prodis'));
    }

    public function create()
    {
        $fakultasList = Fakultas::all();
        return view('admin.prodi.create', compact('fakultasList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fakultas_id' => 'required|exists:fakultas,id',
            'kode_prodi'  => 'required|string|max:20|unique:prodis,kode_prodi',
            'nama_prodi'  => 'required|string|max:255',
            'jenjang'     => 'required|in:D3,D4,S1,S2',
            'kaprodi'     => 'nullable|string|max:255',
        ]);

        Prodi::create($request->only(['fakultas_id', 'kode_prodi', 'nama_prodi', 'jenjang', 'kaprodi']));

        return redirect()->route('admin.prodi.index')->with('success', 'Program Studi berhasil ditambahkan.');
    }

    public function edit(Prodi $prodi)
    {
        $fakultasList = Fakultas::all();
        return view('admin.prodi.edit', compact('prodi', 'fakultasList'));
    }

    public function update(Request $request, Prodi $prodi)
    {
        $request->validate([
            'fakultas_id' => 'required|exists:fakultas,id',
            'kode_prodi'  => 'required|string|max:20|unique:prodis,kode_prodi,' . $prodi->id,
            'nama_prodi'  => 'required|string|max:255',
            'jenjang'     => 'required|in:D3,D4,S1,S2',
            'kaprodi'     => 'nullable|string|max:255',
        ]);

        $prodi->update($request->only(['fakultas_id', 'kode_prodi', 'nama_prodi', 'jenjang', 'kaprodi']));

        return redirect()->route('admin.prodi.index')->with('success', 'Program Studi berhasil diperbarui.');
    }

    public function destroy(Prodi $prodi)
    {
        $prodi->delete();
        return redirect()->route('admin.prodi.index')->with('success', 'Program Studi berhasil dihapus.');
    }
}