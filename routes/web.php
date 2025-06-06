<?php

use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\ParkingController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', [ParkingController::class, 'index'])->name('index');
Route::post('/parking', [ParkingController::class, 'store'])->name('parking.store');

Route::middleware(['auth'])->group(function () {
    Route::get('/parking', [ParkingController::class, 'dashboard'])->name('dashboard');
    Route::get('/parking/history', [ParkingController::class, 'history'])->name('parking.history');
    Route::patch('/parking/{id}/exit', [ParkingController::class, 'exit'])->name('parking.exit');

    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::put('password', [PasswordController::class, 'update'])->name('password.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/login', [ParkingController::class, 'showLoginForm'])->name('login');
Route::post('/login', [ParkingController::class, 'login'])->name('login');
Route::middleware('auth')->group(function() {
    Route::post('/logout', function () {
        Auth::logout();
        return redirect()->route('index');
    })->name('logout');
});
