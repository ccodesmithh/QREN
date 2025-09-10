<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\ScanController;
use Illuminate\Support\Facades\Route;

use function Laravel\Prompts\form;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/generate-qr/{guruId}', [QrCodeController::class, 'generate']);

// ini buat testing doang
Route::get('/scan/manuals', function(){
    return view('scan');
})->name('scan.form');


Route::get('/scan', [AttendanceController::class, 'index'])->name('scan.index');
// Route::post('/scan', [AttendanceController::class, 'store'])->name('scan.store');

Route::post('/scan', [AttendanceController::class, 'scan'])->name('scan.submit');

Route::get('/guru/qrcode', [QRCodeController::class, 'index'])->name('guru.qrcode.index');
Route::post('/guru/qrcode/generate', [QRCodeController::class, 'generate'])->name('guru.qrcode.generate');
