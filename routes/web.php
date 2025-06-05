<?php

use App\Http\Controllers\ParkingController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ParkingController::class, 'index'])->name('welcome');
Route::post('/parking', [ParkingController::class, 'store'])->name('parking.store');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [ParkingController::class, 'dashboard'])->name('dashboard');
    Route::patch('/parking/{id}/exit', [ParkingController::class, 'exit'])->name('parking.exit');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
