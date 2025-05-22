<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\AdminStudentController;

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

    Route::get('/student', [AdminStudentController::class, 'index'])->name('admin.student.index');
    Route::post('/student/store', [AdminStudentController::class, 'store'])->name('admin.student.store');
    Route::get('/student/edit/{id}', [AdminStudentController::class, 'edit'])->name('admin.student.edit');
    Route::post('/student/update/{id}', [AdminStudentController::class, 'update'])->name('admin.student.update');
    Route::post('/student/destroy/{id}', [AdminStudentController::class, 'destroy'])->name('admin.student.destroy');

    Route::get('/teacher', function () {
        return view('admin.teacher');
    })->name('admin.teacher.index');

    Route::get('/class', function () {
        return view('admin.class');
    })->name('admin.class.index');

    Route::get('/course', function () {
        return view('admin.course');
    })->name('admin.course.index');
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