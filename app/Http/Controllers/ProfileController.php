<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function index()
    {
        $user      = auth()->user();
        $dosen     = Dosen::where('user_id', $user->id)->first();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->first();

        return view('profile.index', compact('user', 'dosen', 'mahasiswa'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name'     => 'required|string|max:150',
            'email'    => ['required', 'email', 'max:150', Rule::unique('users')->ignore($user->id)],
            'no_hp'    => 'nullable|string|max:25',
            'foto'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $userData = [
            'name'  => $validated['name'],
            'email' => $validated['email'],
        ];

        // Proses upload foto profil baru
        if ($request->hasFile('foto')) {
            if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                Storage::disk('public')->delete($user->foto);
            }
            $userData['foto'] = $request->file('foto')->store('foto-profil', 'public');
        }

        // Jika password diisi, perbarui password
        if (!empty($validated['password'])) {
            $userData['password'] = Hash::make($validated['password']);
        }

        $user->update($userData);

        // Simpan No. HP ke tabel dosens atau mahasiswas jika tersedia
        if ($request->filled('no_hp') || $request->has('no_hp')) {
            $dosen = Dosen::where('user_id', $user->id)->first();
            if ($dosen && Schema::hasColumn('dosens', 'no_hp')) {
                $dosen->update(['no_hp' => $request->no_hp]);
            }

            $mahasiswa = Mahasiswa::where('user_id', $user->id)->first();
            if ($mahasiswa && Schema::hasColumn('mahasiswas', 'no_hp')) {
                $mahasiswa->update(['no_hp' => $request->no_hp]);
            }
        }

        return back()->with('success', 'Profil dan foto berhasil diperbarui.');
    }

    public function destroyFoto()
    {
        $user = auth()->user();

        if ($user->foto && Storage::disk('public')->exists($user->foto)) {
            Storage::disk('public')->delete($user->foto);
        }

        $user->update(['foto' => null]);

        return back()->with('success', 'Foto profil berhasil dihapus.');
    }
}