<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminStudentController;
use App\Http\Controllers\Admin\AdminTeacherController;
use App\Http\Controllers\Admin\AdminClassController;
use App\Http\Controllers\Admin\AdminCourseController;
use App\Http\Controllers\Admin\AdminAnnouncementController;
use App\Http\Controllers\Admin\AdminGradeController;
use App\Http\Controllers\Admin\AdminScheduleController;
use App\Http\Controllers\Admin\AdminAccountController;

use App\Http\Controllers\Teacher\TeacherDashboardController;
use App\Http\Controllers\Teacher\TeacherMaterialController;
use App\Http\Controllers\Teacher\TeacherClassController;
use App\Http\Controllers\Teacher\TeacherGradeController;

use App\Http\Controllers\Student\StudentDashboardController;
use App\Http\Controllers\Student\StudentScheduleController;
use App\Http\Controllers\Student\StudentAnnouncementController;
use App\Http\Controllers\Student\StudentMaterialController;
use App\Http\Controllers\Student\StudentGradeController;
use App\Http\Controllers\Student\StudentProfileController;

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
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard.index');

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

    Route::get('/account', [AdminAccountController::class, 'index'])->name('admin.account.index');
    Route::post('/account/store', [AdminAccountController::class, 'store'])->name('admin.account.store');
    Route::get('/account/edit/{id}', [AdminAccountController::class, 'edit'])->name('admin.account.edit');
    Route::post('/account/update/{id}', [AdminAccountController::class, 'update'])->name('admin.account.update');
    Route::post('/account/destroy/{id}', [AdminAccountController::class, 'destroy'])->name('admin.account.destroy');
    Route::get('/account/{user}/reset-password', [AdminAccountController::class, 'resetPassword'])->name('admin.account.reset');
});

// Kesiswaan routes
Route::prefix('kesiswaan')->middleware(['auth', 'level:2'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('kesiswaan.dashboard.index');

    // STUDENT (read‐only)
    // Mapping ke method index di AdminStudentController (tampil list saja)
    Route::get('/student', [AdminStudentController::class, 'index'])->name('kesiswaan.student.index');

    // TEACHER (read‐only)
    Route::get('/teacher', [AdminTeacherController::class, 'index'])->name('kesiswaan.teacher.index');

    // CLASS (read‐only)
    Route::get('/class', [AdminClassController::class, 'index'])->name('kesiswaan.class.index');

    // COURSE (read‐only)
    Route::get('/course', [AdminCourseController::class, 'index'])->name('kesiswaan.course.index');

    // ANNOUNCEMENT (read‐only)
    Route::get('/announcement', [AdminAnnouncementController::class, 'index'])->name('kesiswaan.announcement.index');

    // GRADE (read‐only)
    Route::get('/grade', [AdminGradeController::class, 'index'])->name('kesiswaan.grade.index');

    // SCHEDULE (read‐only)
    Route::get('/schedule', [AdminScheduleController::class, 'index'])->name('kesiswaan.schedule.index');
});

// Guru routes
Route::prefix('guru')->middleware(['auth', 'level:3'])->group(function () {
    Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('teacher.dashboard');

    Route::get('/material', [TeacherMaterialController::class, 'index'])->name('teacher.material.index');
    Route::post('/material/store', [TeacherMaterialController::class, 'store'])->name('teacher.material.store');
    Route::post('/material/update/{id}', [TeacherMaterialController::class, 'update'])->name('teacher.material.update');
    Route::post('/material/destroy/{id}', [TeacherMaterialController::class, 'destroy'])->name('teacher.material.destroy');

    Route::get('/class', [TeacherClassController::class, 'index'])->name('teacher.class.index');

    Route::get('/grade', [TeacherGradeController::class, 'index'])->name('teacher.grade.index');
    Route::get('/grade/form', [TeacherGradeController::class, 'form'])->name('teacher.grade.form');
    Route::post('/grade/store', [TeacherGradeController::class, 'store'])->name('teacher.grade.store');
});

// Siswa routes
Route::prefix('siswa')->middleware(['auth', 'level:4'])->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('student.dashboard.index');

    Route::get('/schedule', [StudentScheduleController::class, 'index'])->name('student.schedule.index');

    Route::get('/announcement', [StudentAnnouncementController::class, 'index'])->name('student.announcement.index');

    Route::get('/material', [StudentMaterialController::class, 'index'])->name('student.material.index');

    Route::get('/grade', [StudentGradeController::class, 'index'])->name('student.grade.index');

    Route::get('/profile', [StudentProfileController::class, 'index'])->name('student.profile.index');
    Route::post('/profile/update', [StudentProfileController::class, 'updateProfile'])->name('student.profile.update');
    Route::post('profile/password', [StudentProfileController::class, 'updatePassword'])->name('student.profile.password');
});


Route::prefix('parent')->middleware(['auth', 'level:5'])->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('parent.dashboard.index');

    Route::get('/schedule', [StudentScheduleController::class, 'index'])->name('parent.schedule.index');

    Route::get('/announcement', [StudentAnnouncementController::class, 'index'])->name('parent.announcement.index');

    Route::get('/material', [StudentMaterialController::class, 'index'])->name('parent.material.index');

    Route::get('/grade', [StudentGradeController::class, 'index'])->name('parent.grade.index');

    Route::get('/profile', [StudentProfileController::class, 'index'])->name('parent.profile.index');
    Route::post('/profile/update', [StudentProfileController::class, 'updateProfile'])->name('parent.profile.update');
    Route::post('profile/password', [StudentProfileController::class, 'updatePassword'])->name('parent.profile.password');
});