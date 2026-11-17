<?php

use App\Http\Controllers\FraudScanController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'FraudScanner')->name('home');
Route::post('/scans', FraudScanController::class)->name('scan/{id}');
