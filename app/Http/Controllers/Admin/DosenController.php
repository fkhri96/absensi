<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Fakultas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DosenController extends Controller
{
    public function index(Request $request)
    {
        $query = Dosen::with(['user', 'fakultas']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nidn', 'like', "%{$search}%")
                ->orWhereHas('user', fn($q) => $q->where('name', 'like', "%{$search}%"));
        }

        $dosens = $query->latest()->paginate(10)->withQueryString();
        return view('admin.dosen.index', compact('dosens'));
    }

    public function create()
    {
        $fakultasList = Fakultas::all();
        return view('admin.dosen.create', compact('fakultasList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'               => 'required|string|max:255',
            'email'              => 'required|email|unique:users,email',
            'password'           => 'required|min:6',
            'nidn'               => 'required|string|unique:dosens,nidn',
            'nip'                => 'nullable|string|max:30',
            'fakultas_id'        => 'nullable|exists:fakultas,id',
            'jabatan_fungsional' => 'nullable|string|max:100',
            'no_hp'              => 'nullable|string|max:20',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => 'dosen',
            ]);

            Dosen::create([
                'user_id'            => $user->id,
                'fakultas_id'        => $request->fakultas_id,
                'nidn'               => $request->nidn,
                'nip'                => $request->nip,
                'jabatan_fungsional' => $request->jabatan_fungsional,
                'no_hp'              => $request->no_hp,
            ]);
        });

        return redirect()->route('admin.dosen.index')->with('success', 'Data dosen berhasil ditambahkan.');
    }

    public function edit(Dosen $dosen)
    {
        $dosen->load('user');
        $fakultasList = Fakultas::all();
        return view('admin.dosen.edit', compact('dosen', 'fakultasList'));
    }

    public function update(Request $request, Dosen $dosen)
    {
        $request->validate([
            'name'               => 'required|string|max:255',
            'email'              => 'required|email|unique:users,email,' . $dosen->user_id,
            'password'           => 'nullable|min:6',
            'nidn'               => 'required|string|unique:dosens,nidn,' . $dosen->id,
            'nip'                => 'nullable|string|max:30',
            'fakultas_id'        => 'nullable|exists:fakultas,id',
            'jabatan_fungsional' => 'nullable|string|max:100',
            'no_hp'              => 'nullable|string|max:20',
        ]);

        DB::transaction(function () use ($request, $dosen) {
            $userData = [
                'name'  => $request->name,
                'email' => $request->email,
            ];
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }
            $dosen->user->update($userData);

            $dosen->update([
                'fakultas_id'        => $request->fakultas_id,
                'nidn'               => $request->nidn,
                'nip'                => $request->nip,
                'jabatan_fungsional' => $request->jabatan_fungsional,
                'no_hp'              => $request->no_hp,
            ]);
        });

        return redirect()->route('admin.dosen.index')->with('success', 'Data dosen berhasil diperbarui.');
    }

    public function destroy(Dosen $dosen)
    {
        $dosen->user->delete();
        return redirect()->route('admin.dosen.index')->with('success', 'Data dosen berhasil dihapus.');
    }
}