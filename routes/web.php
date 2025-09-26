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
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
