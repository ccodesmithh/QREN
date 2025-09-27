<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GuruAuthController;
use App\Http\Controllers\Auth\SiswaAuthController;
use App\Http\Controllers\GuruDashboardController;
use App\Http\Controllers\SiswaDashboardController;
use App\Http\Controllers\QRCodeController;
use App\Http\Controllers\ScanController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ProfileSiswaController;
use App\Http\Controllers\ProfileGuruController;
use App\Http\Controllers\AjarController;
use App\Models\Siswa;


Route::get('/login/siswa', [SiswaAuthController::class, 'showLoginForm'])->name('siswa.login');
Route::get('/', [SiswaAuthController::class, 'redirectToLogin']);

// Siswa jadi default
// Route::get('/login/siswa', [SiswaAuthController::class, 'showLoginForm'])->name('siswa.login');
Route::post('/login/siswa', [SiswaAuthController::class, 'login'])->name('siswa.login.submit');
Route::get('/logout/siswa', [SiswaAuthController::class, 'logout'])->name('siswa.logout');

// Guru
Route::get('/login/guru', [GuruAuthController::class, 'showLoginForm'])->name('guru.login');
Route::post('/login/guru', [GuruAuthController::class, 'login'])->name('guru.login.submit');
Route::get('/logout/guru', [GuruAuthController::class, 'logout'])->name('guru.logout');

// // Dummy dashboard
// Route::get('/dashboard/siswa', fn() => 'Halo Siswa!')->name('siswa.dashboard')->middleware('auth:siswa');
// Route::get('/dashboard/guru', fn() => 'Halo Guru!')->name('guru.dashboard')->middleware('auth:guru');

Route::middleware(['auth:siswa'])->group(function () {
    Route::get('/siswa/dashboard', [SiswaDashboardController::class, 'index'])->name('siswa.dashboard');
    Route::get('/scan', [AttendanceController::class, 'index'])->name('scan.index');
    Route::post('/scan/submit', [AttendanceController::class, 'scan'])->name('scan.submit');
    Route::get('/dashboard', function() {
        $siswa = Siswa::all();
        return view('dashboard', compact('siswa'));
    })->name('siswa.dashboard');
    Route::get('/siswa/history', [AttendanceController::class, 'history'])->name('siswa.history');
    Route::get('/siswa/profile', [ProfileSiswaController::class, 'index'])->name('siswa.profile');
});

Route::middleware(['auth:guru'])->group(function () {
    Route::get('/guru/dashboard', [GuruDashboardController::class, 'index'])->name('guru.dashboard');
    Route::get('/guru/qrcode', [QRCodeController::class, 'index'])->name('guru.qrcode.index');
    Route::post('/guru/qrcode/generate', [QRCodeController::class, 'generate'])->name('guru.qrcode.generate');
    Route::get('guru/profile', [ProfileGuruController::class, 'index'])->name('guru.profile');
    Route::get('guru/jadwal', [AjarController::class, 'jadwal'])->name('guru.jadwal');
    Route::get('guru/ajar', [AjarController::class, 'index'])->name('guru.ajar');
    Route::post('guru/ajar/store', [AjarController::class, 'store'])->name('guru.ajar.store');
    Route::get('/guru/attendance/{ajar}', [AttendanceController::class, 'attendanceByAjar'])->name('guru.attendance');
    Route::post('/guru/attendance/{ajar}/manual', [AttendanceController::class, 'manualAttendance'])->name('guru.attendance.manual');
    Route::get('/guru/history', [AttendanceController::class, 'guruHistory'])->name('guru.history');
    Route::get('/guru/history/download', [AttendanceController::class, 'exportAttendance'])->name('guru.history.download');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AdminAuthController;

// Admin
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::get('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

Route::middleware(['auth:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::post('/admin/settings', [AdminController::class, 'update'])->name('admin.settings.update');

    // Siswa CRUD
    Route::get('/admin/siswa/create', [AdminController::class, 'createSiswa'])->name('admin.siswa.create');
    Route::post('/admin/siswa', [AdminController::class, 'storeSiswa'])->name('admin.siswa.store');
    Route::get('/admin/siswa/{siswa}/edit', [AdminController::class, 'editSiswa'])->name('admin.siswa.edit');
    Route::put('/admin/siswa/{siswa}', [AdminController::class, 'updateSiswa'])->name('admin.siswa.update');
    Route::delete('/admin/siswa/{siswa}', [AdminController::class, 'destroySiswa'])->name('admin.siswa.destroy');

    // Guru CRUD
    Route::get('/admin/guru/create', [AdminController::class, 'createGuru'])->name('admin.guru.create');
    Route::post('/admin/guru', [AdminController::class, 'storeGuru'])->name('admin.guru.store');
    Route::get('/admin/guru/{guru}/edit', [AdminController::class, 'editGuru'])->name('admin.guru.edit');
    Route::put('/admin/guru/{guru}', [AdminController::class, 'updateGuru'])->name('admin.guru.update');
    Route::delete('/admin/guru/{guru}', [AdminController::class, 'destroyGuru'])->name('admin.guru.destroy');

    // Kelas CRUD
    Route::get('/admin/kelas/create', [AdminController::class, 'createKelas'])->name('admin.kelas.create');
    Route::post('/admin/kelas', [AdminController::class, 'storeKelas'])->name('admin.kelas.store');
    Route::get('/admin/kelas/{kelas}/edit', [AdminController::class, 'editKelas'])->name('admin.kelas.edit');
    Route::put('/admin/kelas/{kelas}', [AdminController::class, 'updateKelas'])->name('admin.kelas.update');
    Route::delete('/admin/kelas/{kelas}', [AdminController::class, 'destroyKelas'])->name('admin.kelas.destroy');

    // Jurusan CRUD
    Route::get('/admin/jurusan/create', [AdminController::class, 'createJurusan'])->name('admin.jurusan.create');
    Route::post('/admin/jurusan', [AdminController::class, 'storeJurusan'])->name('admin.jurusan.store');
    Route::get('/admin/jurusan/{jurusan}/edit', [AdminController::class, 'editJurusan'])->name('admin.jurusan.edit');
    Route::put('/admin/jurusan/{jurusan}', [AdminController::class, 'updateJurusan'])->name('admin.jurusan.update');
    Route::delete('/admin/jurusan/{jurusan}', [AdminController::class, 'destroyJurusan'])->name('admin.jurusan.destroy');
});

require __DIR__.'/auth.php';
