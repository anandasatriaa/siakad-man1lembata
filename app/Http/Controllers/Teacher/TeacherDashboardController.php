<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Schedule;
use App\Models\Admin\Teacher;
use Illuminate\Support\Facades\Log;

class TeacherDashboardController extends Controller
{
    public function index()
    {
        // 1) Ambil user_id dari Auth
        $userId = Auth::id();
        Log::info("TeacherDashboardController@index → Authenticated user_id: {$userId}");

        // 2) Cari data Teacher berdasarkan kolom user_id
        $teacher = Teacher::where('user_id', $userId)->first();

        if (! $teacher) {
            // Kalau tidak ditemukan, kita log error dan bisa abort atau redirect
            Log::error("TeacherDashboardController@index → Tidak ditemukan record teacher untuk user_id: {$userId}");
            abort(403, 'Data guru tidak ditemukan untuk akun ini.');
        }

        // 3) Kalau ditemukan, kita dapatkan teacher_id
        $teacherId = $teacher->id;
        Log::info("TeacherDashboardController@index → Found teacher_id: {$teacherId} for user_id: {$userId}");

        // 4) Sekarang gunakan $teacherId untuk ambil jadwal
        $schedules = Schedule::with(['class', 'course'])
            ->where('teacher_id', $teacherId)
            ->orderByRaw("FIELD(day, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")
            ->orderBy('start_time')
            ->get()
            ->groupBy('day');

        return view('teacher.dashboard', compact('schedules'));
    }
}
