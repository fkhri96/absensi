<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\MataKuliah;
use App\Models\Jadwal;
use App\Models\SesiAbsensi;

// Controller Admin (10 Modul)
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\MahasiswaController as AdminMahasiswaController;
use App\Http\Controllers\Admin\DosenController as AdminDosenController;
use App\Http\Controllers\Admin\MataKuliahController as AdminMataKuliahController;
use App\Http\Controllers\Admin\FakultasController as AdminFakultasController;
use App\Http\Controllers\Admin\ProdiController as AdminProdiController;
use App\Http\Controllers\Admin\JadwalController as AdminJadwalController;
use App\Http\Controllers\Admin\AbsensiController as AdminAbsensiController;
use App\Http\Controllers\Admin\LaporanController as AdminLaporanController;
use App\Http\Controllers\Admin\PengaturanQrController as AdminPengaturanQrController;
use App\Http\Controllers\Admin\PengaturanSistemController as AdminPengaturanSistemController;

// Controller Dosen & Mahasiswa
use App\Http\Controllers\Dosen\DashboardController as DosenDashboardController;
use App\Http\Controllers\Dosen\AkademikController as DosenAkademikController;
use App\Http\Controllers\Dosen\AbsensiController as DosenAbsensiController;
use App\Http\Controllers\Dosen\LaporanController as DosenLaporanController;
use App\Http\Controllers\Mahasiswa\DashboardController as MahasiswaDashboardController;
use App\Http\Controllers\Mahasiswa\AbsensiController as MahasiswaAbsensiController;
/*
|--------------------------------------------------------------------------
| Halaman Utama & Autentikasi Multi-Role
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    $stats = [
        'mahasiswa' => Schema::hasTable('mahasiswas') ? Mahasiswa::count() : 0,
        'dosen'     => Schema::hasTable('dosens') ? Dosen::count() : 0,
        'matkul'    => Schema::hasTable('mata_kuliahs') ? MataKuliah::count() : 0,
        'sesi_buka' => Schema::hasTable('sesi_absensis') ? SesiAbsensi::where('status', 'buka')->count() : 0,
    ];

    $jadwalHariIni = collect();
    if (Schema::hasTable('jadwals') && Schema::hasTable('mata_kuliahs')) {
        $hariMap = [
            'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
        ];
        $hariIni = $hariMap[now()->format('l')] ?? 'Senin';

        $jadwalHariIni = Jadwal::with(['mataKuliah.dosen.user', 'mataKuliah.prodi'])
            ->where('hari', $hariIni)
            ->orderBy('jam_mulai')
            ->take(5)
            ->get();
    }

    return view('welcome', compact('stats', 'jadwalHariIni'));
})->name('home');

Route::get('/login', function () {
    return redirect()->route('home');
})->name('login');

Route::middleware('guest')->group(function () {
    // Login Admin
    Route::get('/admin/login', [AuthController::class, 'showAdminLogin'])->name('admin.login');
    Route::post('/admin/login', [AuthController::class, 'loginAdmin'])->name('admin.login.process');

    // Login Dosen
    Route::get('/dosen/login', [AuthController::class, 'showDosenLogin'])->name('dosen.login');
    Route::post('/dosen/login', [AuthController::class, 'loginDosen'])->name('dosen.login.process');

    // Login Mahasiswa
    Route::get('/mahasiswa/login', [AuthController::class, 'showMahasiswaLogin'])->name('mahasiswa.login');
    Route::post('/mahasiswa/login', [AuthController::class, 'loginMahasiswa'])->name('mahasiswa.login.process');
});

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

/*
|--------------------------------------------------------------------------
| Profil & Upload Foto (Admin, Dosen, Mahasiswa)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/profil', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profil/foto', [ProfileController::class, 'destroyFoto'])->name('profile.foto.destroy');

    // Alias URL per role (jika diakses dari menu sidebar masing-masing role)
    Route::get('/admin/profil', [ProfileController::class, 'index'])->name('admin.profile.index');
    Route::get('/dosen/profil', [ProfileController::class, 'index'])->name('dosen.profile.index');
    Route::get('/mahasiswa/profil', [ProfileController::class, 'index'])->name('mahasiswa.profile.index');
});

/*
|--------------------------------------------------------------------------
| Group Route ADMIN (10 Modul Lengkap)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    // 1. Dashboard Admin
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // 2. Manajemen Mahasiswa
    Route::resource('mahasiswa', AdminMahasiswaController::class);

    // 3. Manajemen Dosen
    Route::resource('dosen', AdminDosenController::class)->except(['show']);

    // 4. Manajemen Mata Kuliah
    Route::resource('matakuliah', AdminMataKuliahController::class)->except(['show']);

    // 5. Manajemen Fakultas dan Prodi
    Route::resource('fakultas', AdminFakultasController::class)
        ->parameters(['fakultas' => 'fakultas'])
        ->except(['show']);
    Route::resource('prodi', AdminProdiController::class)->except(['show']);

    // 6. Manajemen Jadwal Kuliah
    Route::resource('jadwal', AdminJadwalController::class)->except(['show']);

    // 7. Manajemen Absensi
    Route::resource('absensi', AdminAbsensiController::class);
    Route::patch('absensi/{absensi}/toggle-status', [AdminAbsensiController::class, 'toggleStatus'])
        ->name('absensi.toggle-status');
    Route::patch('absensi/record/{record}/status', [AdminAbsensiController::class, 'updateStatusMahasiswa'])
        ->name('absensi.update-mahasiswa');

    // 8. Laporan Absensi
    Route::get('/laporan', [AdminLaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/cetak', [AdminLaporanController::class, 'cetak'])->name('laporan.cetak');

    // 9. Pengaturan QR Code
    Route::get('/pengaturan-qr', [AdminPengaturanQrController::class, 'index'])->name('qr.index');
    Route::put('/pengaturan-qr', [AdminPengaturanQrController::class, 'update'])->name('qr.update');

    // 10. Pengaturan Sistem
    Route::get('/pengaturan-sistem', [AdminPengaturanSistemController::class, 'index'])->name('sistem.index');
    Route::put('/pengaturan-sistem', [AdminPengaturanSistemController::class, 'update'])->name('sistem.update');
    Route::put('/pengaturan-sistem/password', [AdminPengaturanSistemController::class, 'updatePassword'])->name('sistem.password');
});

/*
|--------------------------------------------------------------------------
| Group Route DOSEN
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Group Route DOSEN (10 Modul Lengkap)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:dosen'])->prefix('dosen')->name('dosen.')->group(function () {
    Route::get('/dashboard', [DosenDashboardController::class, 'index'])->name('dashboard');
    Route::get('/matakuliah', [DosenAkademikController::class, 'mataKuliah'])->name('matakuliah.index');
    Route::get('/matakuliah/{id}', [DosenAkademikController::class, 'detailMataKuliah'])->name('matakuliah.show');
    Route::get('/jadwal', [DosenAkademikController::class, 'jadwal'])->name('jadwal.index');

    Route::resource('absensi', DosenAbsensiController::class);
    Route::patch('absensi/{id}/update-status', [DosenAbsensiController::class, 'updateStatus'])->name('absensi.update-status');
    Route::post('/absensi/{sesi}/koreksi', [DosenAbsensiController::class, 'koreksiPresensi'])->name('absensi.koreksi');

    Route::get('/mahasiswa', [DosenAkademikController::class, 'mahasiswa'])->name('mahasiswa.index');
    Route::get('/laporan', [DosenLaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/cetak', [DosenLaporanController::class, 'cetak'])->name('laporan.cetak');
    Route::get('/laporan/excel', [DosenLaporanController::class, 'exportExcel'])->name('laporan.excel');
    Route::get('/notifikasi', [DosenDashboardController::class, 'notifikasi'])->name('notifikasi.index');
});

Route::middleware(['auth', 'role:mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
    Route::get('/dashboard', function () {
        return view('mahasiswa.dashboard');
    })->name('dashboard');

    Route::get('absensi', [MahasiswaAbsensiController::class, 'index'])->name('absensi.index');
    Route::post('absensi', [MahasiswaAbsensiController::class, 'store'])->name('absensi.store');
});