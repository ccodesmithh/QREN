<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GuruAuthController;
use App\Http\Controllers\Auth\SiswaAuthController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\GuruDashboardController;
use App\Http\Controllers\SiswaDashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\ProfileGuruController;
use App\Http\Controllers\AjarController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ProfileSiswaController;
use App\Http\Controllers\ScanController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\NotificationController;

// Public routes
Route::get('/', function () {
    return redirect()->route('siswa.login');
});

// Fallback login route to prevent "Route [login] not defined" error
Route::get('/login', function () {
    return redirect()->route('siswa.login');
})->name('login');

// Guru Authentication Routes
Route::get('/guru/login', [GuruAuthController::class, 'showLoginForm'])->name('guru.login');
Route::post('/guru/login', [GuruAuthController::class, 'login'])->name('guru.login.submit');
Route::get('/guru/logout', [GuruAuthController::class, 'logout'])->name('guru.logout');

// Siswa Authentication Routes
Route::get('/siswa/login', [SiswaAuthController::class, 'showLoginForm'])->name('siswa.login');
Route::post('/siswa/login', [SiswaAuthController::class, 'login'])->name('siswa.login.submit');
Route::get('/siswa/logout', [SiswaAuthController::class, 'logout'])->name('siswa.logout');

// Admin Authentication Routes
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::get('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// Guru Protected Routes
Route::middleware(['auth:guru'])->prefix('guru')->name('guru.')->group(function () {
    Route::get('/dashboard', [GuruDashboardController::class, 'index'])->name('dashboard');
    Route::get('/qrcode', [QrCodeController::class, 'index'])->name('qrcode.index');
    Route::get('/qrcode/create', [QrCodeController::class, 'create'])->name('qrcode.create');
    Route::post('/qrcode', [QrCodeController::class, 'store'])->name('qrcode.store');
    Route::get('/qrcode/{qrcode}', [QrCodeController::class, 'show'])->name('qrcode.show');
    Route::post('/qrcode/update-location', [QrCodeController::class, 'updateLocation'])->name('qrcode.update-location');
    Route::post('/qrcode/generate', [QrCodeController::class, 'generate'])->name('qrcode.generate');
    Route::get('/qrcode/svg', [QrCodeController::class, 'getQrCodeSvg'])->name('qrcode.svg');
    Route::get('/profile', [ProfileGuruController::class, 'edit'])->name('profile');
    Route::patch('/profile', [ProfileGuruController::class, 'update'])->name('profile.update');
    Route::get('/jadwal', [AjarController::class, 'index'])->name('jadwal');
    Route::get('/ajar/create', [AjarController::class, 'create'])->name('ajar');
    Route::post('/ajar', [AjarController::class, 'store'])->name('ajar.store');
    Route::get('/history', [AttendanceController::class, 'guruHistory'])->name('history');
    Route::get('/history/download', [AttendanceController::class, 'downloadHistory'])->name('history.download');
    Route::get('/attendance', [AttendanceController::class, 'guruAttendance'])->name('attendance');
    Route::post('/attendance/manual/{ajarId}', [AttendanceController::class, 'manualAttendance'])->name('attendance.manual');

    // Journal Routes
    Route::resource('journal', JournalController::class);

    // Notification Routes
    Route::get('/notifications/unread', [NotificationController::class, 'unread'])->name('notifications.unread');
    Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
});

// Siswa Protected Routes
Route::middleware(['auth:siswa'])->prefix('siswa')->name('siswa.')->group(function () {
    Route::get('/dashboard', [SiswaDashboardController::class, 'index'])->name('dashboard');
    Route::get('/scan', [ScanController::class, 'index'])->name('scan.index');
    Route::post('/scan', [ScanController::class, 'scan'])->name('scan.submit');
    Route::get('/profile', [ProfileSiswaController::class, 'edit'])->name('profile');
    Route::patch('/profile', [ProfileSiswaController::class, 'update'])->name('profile.update');
    Route::get('/history', [AttendanceController::class, 'siswaHistory'])->name('history');
});

// Admin Protected Routes
Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    // Siswa management
    Route::get('/siswa/create', [AdminController::class, 'createSiswa'])->name('siswa.create');
    Route::post('/siswa', [AdminController::class, 'storeSiswa'])->name('siswa.store');
    Route::get('/siswa/{siswa}/edit', [AdminController::class, 'editSiswa'])->name('siswa.edit');
    Route::patch('/siswa/{siswa}', [AdminController::class, 'updateSiswa'])->name('siswa.update');
    Route::delete('/siswa/{siswa}', [AdminController::class, 'destroySiswa'])->name('siswa.destroy');

    // Guru management
    Route::get('/guru/create', [AdminController::class, 'createGuru'])->name('guru.create');
    Route::post('/guru', [AdminController::class, 'storeGuru'])->name('guru.store');
    Route::get('/guru/{guru}/edit', [AdminController::class, 'editGuru'])->name('guru.edit');
    Route::patch('/guru/{guru}', [AdminController::class, 'updateGuru'])->name('guru.update');
    Route::delete('/guru/{guru}', [AdminController::class, 'destroyGuru'])->name('guru.destroy');

    // Kelas management
    Route::get('/kelas/create', [AdminController::class, 'createKelas'])->name('kelas.create');
    Route::post('/kelas', [AdminController::class, 'storeKelas'])->name('kelas.store');
    Route::get('/kelas/{kelas}/edit', [AdminController::class, 'editKelas'])->name('kelas.edit');
    Route::patch('/kelas/{kelas}', [AdminController::class, 'updateKelas'])->name('kelas.update');
    Route::delete('/kelas/{kelas}', [AdminController::class, 'destroyKelas'])->name('kelas.destroy');

    // Jurusan management
    Route::get('/jurusan/create', [AdminController::class, 'createJurusan'])->name('jurusan.create');
    Route::post('/jurusan', [AdminController::class, 'storeJurusan'])->name('jurusan.store');
    Route::get('/jurusan/{jurusan}/edit', [AdminController::class, 'editJurusan'])->name('jurusan.edit');
    Route::patch('/jurusan/{jurusan}', [AdminController::class, 'updateJurusan'])->name('jurusan.update');
    Route::delete('/jurusan/{jurusan}', [AdminController::class, 'destroyJurusan'])->name('jurusan.destroy');

    // Settings
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
    Route::patch('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');

    // QR Code regeneration
    Route::post('/regenerate-qr-codes', [AdminController::class, 'regenerateQrCodes'])->name('regenerate.qr-codes');
});
