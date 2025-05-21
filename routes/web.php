<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;

// Login
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Logout
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');

// Admin routes
Route::prefix('admin')->middleware(['auth', 'level:1'])->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});

// Kesiswaan routes
Route::prefix('kesiswaan')->middleware(['auth', 'level:2'])->group(function () {
    Route::get('/dashboard', function () {
        return 'Dashboard Kesiswaan';
    })->name('kesiswaan.dashboard');
});

// Guru routes
Route::prefix('guru')->middleware(['auth', 'level:3'])->group(function () {
    Route::get('/dashboard', function () {
        return 'Dashboard Guru';
    })->name('guru.dashboard');
});

// Siswa routes
Route::prefix('siswa')->middleware(['auth', 'level:4'])->group(function () {
    Route::get('/dashboard', function () {
        return 'Dashboard Siswa';
    })->name('siswa.dashboard');
});