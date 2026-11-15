<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Prodi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MahasiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = Mahasiswa::with(['user', 'prodi']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nim', 'like', "%{$search}%")
                ->orWhereHas('user', fn($q) => $q->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"));
        }

        $mahasiswas = $query->latest()->paginate(10)->withQueryString();
        return view('admin.mahasiswa.index', compact('mahasiswas'));
    }

    public function create()
    {
        $prodis = Prodi::with('fakultas')->get();
        return view('admin.mahasiswa.create', compact('prodis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email',
            'password'      => 'required|min:6',
            'nim'           => 'required|string|unique:mahasiswas,nim',
            'prodi_id'      => 'nullable|exists:prodis,id',
            'angkatan'      => 'required|digits:4|integer',
            'jenis_kelamin' => 'required|in:L,P',
            'no_hp'         => 'nullable|string|max:20',
            'alamat'        => 'nullable|string',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => 'mahasiswa',
            ]);

            Mahasiswa::create([
                'user_id'       => $user->id,
                'prodi_id'      => $request->prodi_id,
                'nim'           => $request->nim,
                'angkatan'      => $request->angkatan,
                'jenis_kelamin' => $request->jenis_kelamin,
                'no_hp'         => $request->no_hp,
                'alamat'        => $request->alamat,
            ]);
        });

        return redirect()->route('admin.mahasiswa.index')->with('success', 'Data mahasiswa berhasil ditambahkan.');
    }

    public function show(Mahasiswa $mahasiswa)
    {
        $mahasiswa->load(['user', 'prodi.fakultas', 'absensis.sesiAbsensi.jadwal.mataKuliah']);
        return view('admin.mahasiswa.show', compact('mahasiswa'));
    }

    public function edit(Mahasiswa $mahasiswa)
    {
        $mahasiswa->load('user');
        $prodis = Prodi::with('fakultas')->get();
        return view('admin.mahasiswa.edit', compact('mahasiswa', 'prodis'));
    }

    public function update(Request $request, Mahasiswa $mahasiswa)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email,' . $mahasiswa->user_id,
            'password'      => 'nullable|min:6',
            'nim'           => 'required|string|unique:mahasiswas,nim,' . $mahasiswa->id,
            'prodi_id'      => 'nullable|exists:prodis,id',
            'angkatan'      => 'required|digits:4|integer',
            'jenis_kelamin' => 'required|in:L,P',
            'no_hp'         => 'nullable|string|max:20',
            'alamat'        => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $mahasiswa) {
            $userData = [
                'name'  => $request->name,
                'email' => $request->email,
            ];
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }
            $mahasiswa->user->update($userData);

            $mahasiswa->update([
                'prodi_id'      => $request->prodi_id,
                'nim'           => $request->nim,
                'angkatan'      => $request->angkatan,
                'jenis_kelamin' => $request->jenis_kelamin,
                'no_hp'         => $request->no_hp,
                'alamat'        => $request->alamat,
            ]);
        });

        return redirect()->route('admin.mahasiswa.index')->with('success', 'Data mahasiswa berhasil diperbarui.');
    }

    public function destroy(Mahasiswa $mahasiswa)
    {
        $mahasiswa->user->delete(); // Otomatis menghapus data mahasiswa karena cascade
        return redirect()->route('admin.mahasiswa.index')->with('success', 'Data mahasiswa berhasil dihapus.');
    }
}