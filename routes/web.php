<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\AdminStudentController;
use App\Http\Controllers\Admin\AdminTeacherController;
use App\Http\Controllers\Admin\AdminClassController;
use App\Http\Controllers\Admin\AdminCourseController;
use App\Http\Controllers\Admin\AdminAnnouncementController;
use App\Http\Controllers\Admin\AdminGradeController;
use App\Http\Controllers\Admin\AdminScheduleController;

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

    Route::get('/teacher', [AdminTeacherController::class, 'index'])->name('admin.teacher.index');
    Route::post('/teacher/store', [AdminTeacherController::class, 'store'])->name('admin.teacher.store');
    Route::get('/teacher/edit/{id}', [AdminTeacherController::class, 'edit'])->name('admin.teacher.edit');
    Route::post('/teacher/update/{id}', [AdminTeacherController::class, 'update'])->name('admin.teacher.update');
    Route::post('/teacher/destroy/{id}', [AdminTeacherController::class, 'destroy'])->name('admin.teacher.destroy');

    Route::get('/class', [AdminClassController::class, 'index'])->name('admin.class.index');
    Route::post('/class/store', [AdminClassController::class, 'store'])->name('admin.class.store');
    Route::get('/class/edit/{id}', [AdminClassController::class, 'edit'])->name('admin.class.edit');
    Route::post('/class/update/{id}', [AdminClassController::class, 'update'])->name('admin.class.update');

    Route::get('/course', [AdminCourseController::class, 'index'])->name('admin.course.index');
    Route::post('/course/store', [AdminCourseController::class, 'store'])->name('admin.course.store');
    Route::get('/course/edit/{id}', [AdminCourseController::class, 'edit'])->name('admin.course.edit');
    Route::post('/course/update/{id}', [AdminCourseController::class, 'update'])->name('admin.course.update');
    Route::post('/course/destroy/{id}', [AdminCourseController::class, 'destroy'])->name('admin.course.destroy');

    Route::get('/announcement', [AdminAnnouncementController::class, 'index'])->name('admin.announcement.index');
    Route::post('/announcement/store', [AdminAnnouncementController::class, 'store'])->name('admin.announcement.store');
    Route::get('/announcement/edit/{id}', [AdminAnnouncementController::class, 'edit'])->name('admin.announcement.edit');
    Route::post('/announcement/update/{id}', [AdminAnnouncementController::class, 'update'])->name('admin.announcement.update');
    Route::post('/announcement/destroy/{id}', [AdminAnnouncementController::class, 'destroy'])->name('admin.announcement.destroy');

    Route::get('/grade', [AdminGradeController::class, 'index'])->name('admin.grade.index');

    Route::get('/schedule', [AdminScheduleController::class, 'index'])->name('admin.schedule.index');
    Route::post('/schedule/store', [AdminScheduleController::class, 'store'])->name('admin.schedule.store');
    Route::get('/schedule/edit/{id}', [AdminScheduleController::class, 'edit'])->name('admin.schedule.edit');
    Route::post('/schedule/update/{id}', [AdminScheduleController::class, 'update'])->name('admin.schedule.update');
    Route::post('/schedule/destroy/{id}', [AdminScheduleController::class, 'destroy'])->name('admin.schedule.destroy');
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
