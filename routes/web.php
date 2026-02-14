<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MeterAirController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MeterAirExportController;
use App\Http\Controllers\MeterAirImportController;

Route::get('/', function () {

    if (!auth()->check()) {
        return redirect()->route('login');
    }

    return auth()->user()->role === 'admin'
        ? redirect()->route('air.index')
        : redirect()->route('warga.index');
});


Route::get('/login', [AuthController::class,'showLogin'])->name('login');
Route::post('/login', [AuthController::class,'login']);
Route::post('/logout', [AuthController::class,'logout'])
    ->middleware('auth')
    ->name('logout');


// Route::middleware(['auth','role:admin'])->group(function () {
//     Route::get('/admin/dashboard', fn() => 'Dashboard Admin');
// });

// Route::middleware(['auth','role:warga'])->prefix('warga')->group(function () {
//     Route::get('/dashboard', fn() => 'Dashboard Warga');
// });
// Route::get('/users/create', [UserController::class,'create'])->name('users.create');
// Route::post('/users', [UserController::class,'store'])->name('users.store');

Route::middleware(['auth','role:admin'])->group(function () {
    // User Warga
    Route::get('/users', [UserController::class,'index'])->name('users.index');
    Route::get('/users/create', [UserController::class,'create'])->name('users.create');
    Route::post('/users', [UserController::class,'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class,'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class,'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class,'destroy'])->name('users.destroy');
    Route::post('/users/{user}/reset-password',
    [UserController::class,'resetPassword'])
    ->name('users.reset');
    Route::get('/users/live-search', [UserController::class, 'liveSearch'])
    ->name('users.liveSearch');

    // Meter Air
    Route::get('/air/input', [MeterAirController::class,'create'])
        ->name('air.create');

    Route::post('/air/input', [MeterAirController::class,'store'])
        ->name('air.store');

    Route::get('/air', [MeterAirController::class,'index'])
        ->name('air.index');
    Route::get('/air/{meter}/edit', [MeterAirController::class,'edit'])->name('air.edit');
    Route::put('/air/{meter}', [MeterAirController::class,'update'])->name('air.update');
    Route::delete('/air/{meter}', [MeterAirController::class,'destroy'])->name('air.destroy');

    Route::get('/air/export/excel', [MeterAirExportController::class, 'excel'])
        ->name('air.export.excel');
    Route::get('/air/export-nota-bulk', [MeterAirExportController::class, 'notaBulk']
        )->name('air.export.nota.bulk');

    Route::get('/air/prev-stand', [MeterAirController::class, 'getPrevStand']);
    Route::post('/air/{id}/lunas', [MeterAirController::class, 'setLunas'])
    ->name('air.lunas');
    Route::post('/meter-air/import', [MeterAirImportController::class, 'import'])
    ->name('meter.import');
    Route::get('/meter/import', [MeterAirImportController::class, 'form'])
    ->name('meter.import.form');
});

Route::middleware(['auth','role:warga'])->group(function () {

    Route::get('/warga/tagihan', [MeterAirController::class,'indexWarga'])
        ->name('warga.index');
});
