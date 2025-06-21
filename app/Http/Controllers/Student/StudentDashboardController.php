<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Announcement;
use App\Models\Admin\Schedule;
use App\Models\Teacher\TeacherMaterial;
use App\Models\Admin\Student;
use App\Models\User;
use Carbon\Carbon;

class StudentDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Cek apakah level 4 (siswa) atau level 5 (orang tua)
        if ($user->level == 4) {
            $student = $user->student;
        } elseif ($user->level == 5) {
            $student = Student::find($user->guardian_of_student_id);
        } else {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        if (!$student) {
            abort(404, 'Data siswa tidak ditemukan.');
        }

        // 1. Announcements Sekolah
        $announcements = Announcement::where('is_active', 1)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // 2. Jadwal Hari Ini
        $dayName = Carbon::now()->format('l');
        $todaySchedules = Schedule::with(['course', 'teacher', 'schoolClass'])->where('class_id', $student->class_id)
            ->where('day', $dayName)
            ->orderBy('start_time')
            ->get();

        // 3. Materi Terbaru
        $materials = TeacherMaterial::with('course')->where('class_id', $student->class_id)
            ->orderBy('published_at', 'desc')
            ->take(5)
            ->get();

        // 4. Profil Siswa
        $profile = $student;

        return view('student.dashboard', compact(
            'announcements',
            'todaySchedules',
            'materials',
            'profile'
        ));
    }
}
