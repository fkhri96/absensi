<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Perizinan;
use Illuminate\Http\Request;

class IzinController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->first();
        
        $izins = $mahasiswa ? Perizinan::where('mahasiswa_id', $mahasiswa->id)->latest()->paginate(10) : collect();

        return view('mahasiswa.izin.index', compact('izins'));
    }

    public function create()
    {
        return view('mahasiswa.izin.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis'       => 'required|in:izin,sakit',
            'tanggal'     => 'required|date',
            'keterangan'  => 'required|string|max:500',
            'bukti_file'  => 'nullable|image|max:2048',
        ]);

        $user = auth()->user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->first();

        if (!$mahasiswa) {
            return back()->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        $path = null;
        if ($request->hasFile('bukti_file')) {
            $path = $request->file('bukti_file')->store('bukti_izin', 'public');
        }

        Perizinan::create([
            'mahasiswa_id' => $mahasiswa->id,
            'jenis'        => $validated['jenis'],
            'tanggal'      => $validated['tanggal'],
            'keterangan'   => $validated['keterangan'],
            'bukti'        => $path,
            'status'       => 'pending',
        ]);

        return redirect()->route('mahasiswa.izin.index')
            ->with('success', 'Pengajuan izin atau sakit berhasil dikirim dan menunggu persetujuan.');
    }
}