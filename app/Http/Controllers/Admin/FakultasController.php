<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fakultas;
use Illuminate\Http\Request;

class FakultasController extends Controller
{
    public function index()
    {
        $fakultasList = Fakultas::withCount('prodis')->latest()->get();
        return view('admin.fakultas.index', compact('fakultasList'));
    }

    public function create()
    {
        return view('admin.fakultas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_fakultas' => 'required|string|max:20|unique:fakultas,kode_fakultas',
            'nama_fakultas' => 'required|string|max:255',
            'dekan'         => 'nullable|string|max:255',
        ]);

        Fakultas::create($request->only(['kode_fakultas', 'nama_fakultas', 'dekan']));

        return redirect()->route('admin.fakultas.index')->with('success', 'Fakultas berhasil ditambahkan.');
    }

    public function edit(Fakultas $fakultas)
    {
        return view('admin.fakultas.edit', compact('fakultas'));
    }

    public function update(Request $request, Fakultas $fakultas)
    {
        $request->validate([
            'kode_fakultas' => 'required|string|max:20|unique:fakultas,kode_fakultas,' . $fakultas->id,
            'nama_fakultas' => 'required|string|max:255',
            'dekan'         => 'nullable|string|max:255',
        ]);

        $fakultas->update($request->only(['kode_fakultas', 'nama_fakultas', 'dekan']));

        return redirect()->route('admin.fakultas.index')->with('success', 'Fakultas berhasil diperbarui.');
    }

    public function destroy(Fakultas $fakultas)
    {
        $fakultas->delete();
        return redirect()->route('admin.fakultas.index')->with('success', 'Fakultas berhasil dihapus.');
    }
}