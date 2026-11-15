<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfilController extends Controller
{
    public function index()
    {
        $user  = auth()->user();
        $dosen = Dosen::with('fakultas')->where('user_id',$user->id)->first();

        return view('dosen.profil.index', compact('user', 'dosen'));
    }

    public function update(Request $request)
    {
        $user  = auth()->user();
        $dosen = Dosen::where('user_id',$user->id)->first();

        $validated =$request->validate([
            'name'               => 'required|string|max:150',
            'email'              => ['required', 'email', 'max:150', Rule::unique('users')->ignore($user->id)],
            'nidn'               => 'nullable|string|max:30',
            'nip'                => 'nullable|string|max:30',
            'jabatan_fungsional' => 'nullable|string|max:100',
            'no_hp'              => 'nullable|string|max:25',
            'foto'               => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'password'           => 'nullable|string|min:6|confirmed',
        ]);

        $userData = [
            'name'  => $validated['name'],
            'email' => $validated['email'],
        ];

        if ($request->hasFile('foto')) {
            if (Schema::hasColumn('users', 'foto')) {
                if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                    Storage::disk('public')->delete($user->foto);
                }
                $userData['foto'] =$request->file('foto')->store('foto-profil', 'public');
            }
        }

        if (!empty($validated['password'])) {
            $userData['password'] = Hash::make($validated['password']);
        }

        $user->update($userData);

        if ($dosen) {$dosenData = array_filter([
                'nidn'               => $validated['nidn'] ?? $dosen->nidn,
                'nip'                => $validated['nip'] ?? $dosen->nip,
                'jabatan_fungsional' => $validated['jabatan_fungsional'] ?? $dosen->jabatan_fungsional,
                'no_hp'              => $validated['no_hp'] ?? $dosen->no_hp,
            ], fn ($v) => !is_null($v));

            $dosen->update($dosenData);
        }

        return back()->with('success', 'Profil dosen &