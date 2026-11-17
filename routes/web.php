<?php

use App\Http\Controllers\FraudScanController;
use App\Http\Controllers\ScanController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'FraudScanner')->name('home');

Route::get('/scans', [ScanController::class, 'index'])->name('scans.index');
Route::post('/scans', FraudScanController::class)->name('scans.store');
Route::get('/scans/{scan}', [ScanController::class, 'show'])->name('scans.show');
