<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Admin\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\SchoolClass;
use App\Models\Admin\Student;
use App\Models\Admin\Teacher;

class TeacherGradeController extends Controller
{
    public function index()
    {
        // Ambil ID guru yang sedang login
        $userId = Auth::id();
        $teacher = Teacher::where('user_id', $userId)->first();

        if (!$teacher) {
            abort(404, 'Data guru tidak ditemukan');
        }

        // Ambil kelas yang diajar guru beserta mata pelajarannya
        $classes = SchoolClass::whereHas('schedules', function ($query) use ($teacher) {
            $query->where('teacher_id', $teacher->id);
        })
            ->with(['schedules' => function ($query) use ($teacher) {
                $query->where('teacher_id', $teacher->id)
                    ->with('course');
            }])
            ->get()
            ->map(function ($class) {
                $class->courses = $class->schedules->pluck('course')->unique();
                return $class;
            });

        return view('teacher.grade', compact('classes', 'teacher'));
    }

    public function form(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'course_id' => 'required|exists:courses,id'
        ]);

        $class = SchoolClass::findOrFail($request->class_id);
        $course = Course::findOrFail($request->course_id);
        $students = Student::where('class_id', $request->class_id)
            ->orderBy('full_name')
            ->get();

        // Ambil nilai yang sudah ada (jika sudah pernah diinput)
        // Asumsi ada tabel 'grades' dengan struktur:
        // student_id, course_id, assignment, mid_exam, final_exam, etc.
        $existingGrades = []; // Implementasi sebenarnya tergantung struktur tabel nilai

        return view('teacher.partials.grade-form', compact('class', 'course', 'students', 'existingGrades'));
    }

}
