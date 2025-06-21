<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Announcement;
use App\Models\Admin\SchoolClass;
use App\Models\Admin\Course;
use App\Models\Admin\Teacher;
use App\Models\Admin\Student;
use App\Models\Admin\Schedule;
use App\Models\Teacher\TeacherMaterial;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // 1. Announcements
        $announcements = Announcement::where('is_active', 1)
                                     ->orderBy('created_at', 'desc')
                                     ->take(5)
                                     ->get();

        // 2. Quick Stats
        $totalStudents    = Student::where('status', 'active')->count();
        $totalTeachers    = Teacher::where('status', 'active')->count();
        $totalClasses     = SchoolClass::count();
        $totalCourses     = Course::count();
        $newMaterials     = TeacherMaterial::whereBetween(
                                'published_at',
                                [Carbon::now()->startOfWeek(), Carbon::now()]
                            )->count();

        // 3. Jadwal Hari Ini
        $todayName        = Carbon::now()->format('l'); // "Monday", dsb.
        $todaySchedules   = Schedule::with(['schoolClass','course','teacher'])
                                    ->where('day', $todayName)
                                    ->orderBy('start_time')
                                    ->get();

        // 4. Statistik Siswa
        $genderDistribution = [
            'M' => Student::where('gender', 'M')->count(),
            'F' => Student::where('gender', 'F')->count(),
        ];
        // siswa per kelas (menggunakan relationship 'students' di model SchoolClass)
        $classesWithCount = SchoolClass::withCount('students')->get();

        // 5. Aktivitas & Registrasi
        $recentMaterials  = TeacherMaterial::with('teacher')
                                          ->orderBy('published_at','desc')
                                          ->take(5)
                                          ->get();
        $newRegistrations = Student::whereBetween('created_at', [Carbon::now()->subDays(7), Carbon::now()])->count()
                             + Teacher::whereBetween('created_at', [Carbon::now()->subDays(7), Carbon::now()])->count();

        // 6. Alerts
        $alerts = [
            'studentsNoAccount'   => Student::whereNull('user_id')->count(),
            'inactiveTeachers'    => Teacher::where('status','inactive')->count(),
            'incompleteSchedules' => Schedule::whereNull('course_id')
                                            ->orWhereNull('teacher_id')
                                            ->count(),
        ];

        return view('admin.dashboard', compact(
            'announcements',
            'totalStudents','totalTeachers','totalClasses','totalCourses','newMaterials',
            'todaySchedules',
            'genderDistribution','classesWithCount',
            'recentMaterials','newRegistrations',
            'alerts'
        ));
    }
}
