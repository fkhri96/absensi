<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showAdminLogin(Request $request)
    {
        $this->resetSesiAktif($request);
        return view('auth.admin-login');
    }

    public function loginAdmin(Request $request)
    {
        return $this->prosesLogin($request, 'admin', 'admin.dashboard');
    }

    public function showDosenLogin(Request $request)
    {
        $this->resetSesiAktif($request);
        return view('auth.dosen-login');
    }

    public function loginDosen(Request $request)
    {
        return $this->prosesLogin($request, 'dosen', 'dosen.dashboard');
    }

    public function showMahasiswaLogin(Request $request)
    {
        $this->resetSesiAktif($request);
        return view('auth.mahasiswa-login');
    }

    public function loginMahasiswa(Request $request)
    {
        return $this->prosesLogin($request, 'mahasiswa', 'mahasiswa.dashboard');
    }

    private function resetSesiAktif(Request $request): void
    {
        if (Auth::check()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }
    }

    private function prosesLogin(Request $request, string $roleWajib, string $ruteTujuan)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            if (Auth::user()->role !== $roleWajib) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Akun ini tidak memiliki akses sebagai ' . ucfirst($roleWajib) . '.',
                ])->onlyInput('email');
            }

            $request->session()->regenerate();
            return redirect()->route($ruteTujuan);
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}