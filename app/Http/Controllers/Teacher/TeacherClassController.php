<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Schedule;
use App\Models\Admin\SchoolClass;
use App\Models\Admin\Teacher;
use App\Models\Admin\Student;

class TeacherClassController extends Controller
{
    public function index()
    {
        // Ambil ID guru yang sedang login
        $userId = Auth::id();
        $teacher = Teacher::where('user_id', $userId)->first();

        if (!$teacher) {
            abort(404, 'Data guru tidak ditemukan');
        }

        // Ambil semua kelas yang diajar guru ini
        $schedules = Schedule::with(['class', 'course'])
            ->where('teacher_id', $teacher->id)
            ->get()
            ->groupBy('class_id');

        $classesData = [];

        foreach ($schedules as $classId => $classSchedules) {
            $class = SchoolClass::find($classId);
            $courses = $classSchedules->pluck('course')->unique();
            $students = Student::where('class_id', $classId)->get();

            $classesData[] = [
                'class' => $class,
                'courses' => $courses,
                'students' => $students
            ];
        }

        return view('teacher.class', compact('classesData'));
    }
}
