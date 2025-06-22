<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Schedule;
use App\Models\Admin\Teacher;
use Illuminate\Support\Facades\Log;
use App\Models\Admin\Announcement;
use App\Models\Admin\Course;
use App\Models\Admin\SchoolClass;
use App\Models\Admin\Student;
use Carbon\Carbon;
use App\Models\Teacher\TeacherMaterial;

class TeacherDashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $teacher = Teacher::where('user_id', $userId)->firstOrFail();

        // 1) Pengumuman aktif
        $announcements = Announcement::where('is_active',1)
                                     ->latest()
                                     ->take(5)
                                     ->get();

        // 2) Jadwal hari ini
        $today = Carbon::now()->translatedFormat('l');
        $schedules = Schedule::with(['schoolClass','course'])
            ->where('teacher_id', $teacher->id)
            ->where('day', $today)
            ->orderBy('start_time')
            ->get();

        // 3) Ringkasan
        $classesTaught = SchoolClass::whereIn('id',
            Schedule::where('teacher_id',$teacher->id)
                    ->pluck('class_id')->unique()
        )->count();
        $studentsCount = Student::whereIn('class_id',
            Schedule::where('teacher_id',$teacher->id)
                    ->pluck('class_id')->unique()
        )->count();

        // 4) Materi terbaru
        $materials = TeacherMaterial::where('teacher_id', $teacher->id)
                                    ->latest('published_at')
                                    ->take(5)
                                    ->get();

        return view('teacher.dashboard', compact(
            'announcements','schedules',
            'classesTaught','studentsCount','materials'
        ));
    }
}
