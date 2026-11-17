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

// ======================================================
// CONTROLLER ADMIN (10 MODUL)
// ======================================================
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

// ======================================================
// CONTROLLER DOSEN
// ======================================================
use App\Http\Controllers\Dosen\DashboardController as DosenDashboardController;
use App\Http\Controllers\Dosen\AkademikController as DosenAkademikController;
use App\Http\Controllers\Dosen\AbsensiController as DosenAbsensiController;
use App\Http\Controllers\Dosen\LaporanController as DosenLaporanController;
use App\Http\Controllers\Dosen\IzinController as DosenIzinController;

// ======================================================
// CONTROLLER MAHASISWA
// ======================================================
use App\Http\Controllers\Mahasiswa\DashboardController as MahasiswaDashboardController;
use App\Http\Controllers\Mahasiswa\AbsensiController as MahasiswaAbsensiController;
use App\Http\Controllers\Mahasiswa\AkademikController as MahasiswaAkademikController;
use App\Http\Controllers\Mahasiswa\IzinController as MahasiswaIzinController;


// ======================================================
// HALAMAN UTAMA & AUTENTIKASI MULTI-ROLE
// ======================================================
Route::get('/', function () {
    $stats = [
        'mahasiswa' => Schema::hasTable('mahasiswas') ? Mahasiswa::count() : 0,
        'dosen'     => Schema::hasTable('dosens') ? Dosen::count() : 0,
        'matkul'    => Schema::hasTable('mata_kuliahs') ? MataKuliah::count() : 0,
        'sesi_buka' => Schema::hasTable('sesi_absensis')
            ? SesiAbsensi::where('status', 'buka')->count()
            : 0,
    ];

    $jadwalHariIni = collect();

    if (Schema::hasTable('jadwals') && Schema::hasTable('mata_kuliahs')) {
        $hariMap = [
            'Sunday'    => 'Minggu',
            'Monday'    => 'Senin',
            'Tuesday'   => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday'  => 'Kamis',
            'Friday'    => 'Jumat',
            'Saturday'  => 'Sabtu',
        ];

        $hariIni = $hariMap[now()->format('l')] ?? 'Senin';

        $jadwalHariIni = Jadwal::with([
                'mataKuliah.dosen.user',
                'mataKuliah.prodi',
                'dosen.user'
            ])
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


// ======================================================
// LOGIN ADMIN, DOSEN, MAHASISWA
// ======================================================
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


// ======================================================
// LOGOUT
// ======================================================
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');


// ======================================================
// PROFIL & UPLOAD FOTO (ADMIN, DOSEN, MAHASISWA)
// ======================================================
Route::middleware(['auth'])->group(function () {
    Route::get('/profil', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profil/foto', [ProfileController::class, 'destroyFoto'])->name('profile.foto.destroy');

    // Alias URL per role
    Route::get('/admin/profil', [ProfileController::class, 'index'])->name('admin.profile.index');
    Route::get('/dosen/profil', [ProfileController::class, 'index'])->name('dosen.profile.index');
    Route::get('/mahasiswa/profil', [ProfileController::class, 'index'])->name('mahasiswa.profile.index');
});


// ======================================================
// GROUP ROUTE ADMIN (10 MODUL)
// ======================================================
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

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


// ======================================================
// GROUP ROUTE DOSEN
// ======================================================
Route::middleware(['auth', 'role:dosen'])
    ->prefix('dosen')
    ->name('dosen.')
    ->group(function () {

        // 1. Dashboard
        Route::get('/dashboard', [DosenDashboardController::class, 'index'])->name('dashboard');

        // 2. Mata Kuliah
        Route::get('/matakuliah', [DosenAkademikController::class, 'mataKuliah'])->name('matakuliah.index');
        Route::get('/matakuliah/{id}', [DosenAkademikController::class, 'detailMataKuliah'])->name('matakuliah.show');

        // 3. Jadwal Mengajar
        Route::get('/jadwal', [DosenAkademikController::class, 'jadwal'])->name('jadwal.index');

        // 4. Absensi & Kontrol Hidup/Mati QR
        Route::resource('absensi', DosenAbsensiController::class);
        Route::patch('absensi/{id}/toggle-status', [DosenAbsensiController::class, 'toggleStatus'])->name('absensi.toggle');
        Route::patch('absensi/{id}/update-status', [DosenAbsensiController::class, 'toggleStatus'])->name('absensi.update-status');
        Route::post('/absensi/{id}/refresh-token', [DosenAbsensiController::class, 'refreshToken'])->name('absensi.refresh_token');
        Route::post('/absensi/{sesi}/koreksi', [DosenAbsensiController::class, 'koreksiPresensi'])->name('absensi.koreksi');

        // 5. Surat Izin Mahasiswa
        Route::get('/izin', [DosenIzinController::class, 'index'])->name('izin.index');
        Route::patch('/izin/{id}', [DosenIzinController::class, 'updateStatus'])->name('izin.update');

        // 6. Daftar Mahasiswa
        Route::get('/mahasiswa', [DosenAkademikController::class, 'mahasiswa'])->name('mahasiswa.index');

        // 7. Laporan
        Route::get('/laporan', [DosenLaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/cetak', [DosenLaporanController::class, 'cetak'])->name('laporan.cetak');
        Route::get('/laporan/excel', [DosenLaporanController::class, 'exportExcel'])->name('laporan.excel');

        // 8. Notifikasi
        Route::get('/notifikasi', [DosenDashboardController::class, 'notifikasi'])->name('notifikasi.index');
    });


// ======================================================
// GROUP ROUTE MAHASISWA
// ======================================================
Route::middleware(['auth', 'role:mahasiswa'])
    ->prefix('mahasiswa')
    ->name('mahasiswa.')
    ->group(function () {

        // 1. Dashboard
        Route::get('/dashboard', [MahasiswaDashboardController::class, 'index'])->name('dashboard');

        // 2. Profil
        Route::get('/profil', [ProfileController::class, 'index'])->name('profil.index');
        Route::put('/profil', [ProfileController::class, 'update'])->name('profil.update');

        // 3. Mata Kuliah
        Route::get('/matakuliah', [MahasiswaAkademikController::class, 'mataKuliah'])->name('matakuliah.index');

        // 4. Jadwal Kuliah
        Route::get('/jadwal', [MahasiswaAkademikController::class, 'jadwal'])->name('jadwal.index');

        // 5. Absensi & Scan Otomatis QR
        Route::get('/absensi', [MahasiswaAbsensiController::class, 'index'])->name('absensi.index');
        Route::post('/absensi', [MahasiswaAbsensiController::class, 'store'])->name('absensi.store');
        Route::get('/absensi/scan-langsung/{token}', [MahasiswaAbsensiController::class, 'scanLangsung'])->name('absensi.scan_langsung');

        // 6. Riwayat Absensi
        Route::get('/riwayat', [MahasiswaAbsensiController::class, 'riwayat'])->name('riwayat.index');

        // 7. Statistik Kehadiran
        Route::get('/statistik', [MahasiswaAbsensiController::class, 'statistik'])->name('statistik.index');

        // 8. Pengajuan Izin, Sakit & Cancel Izin
        Route::resource('izin', MahasiswaIzinController::class)
            ->only(['index', 'store', 'destroy'])
            ->parameters(['izin' => 'id']);

        // 9. Cetak Laporan
        Route::get('/laporan/cetak', [MahasiswaAbsensiController::class, 'cetakPdf'])->name('laporan.cetak');

        // 10. Notifikasi
        Route::get('/notifikasi', [MahasiswaDashboardController::class, 'notifikasi'])->name('notifikasi.index');
    });