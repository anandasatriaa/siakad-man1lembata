<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Admin\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\SchoolClass;
use App\Models\Admin\Student;
use App\Models\Admin\Teacher;
use App\Models\Teacher\GradeStudent; // model untuk grade_students
use Carbon\Carbon;

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
        $classes = SchoolClass::whereHas('schedules', function ($q) use ($teacher) {
            $q->where('teacher_id', $teacher->id);
        })
            ->withCount('students')              // <-- tambahkan ini
            ->with(['schedules' => function ($q) use ($teacher) {
                $q->where('teacher_id', $teacher->id)
                    ->with('course');
            }])
            ->get()
            ->map(function ($class) {
                // kumpulkan unique courses
                $class->courses = $class->schedules->pluck('course')->unique();
                return $class;
            });

        return view('teacher.grade', compact('classes', 'teacher'));
    }

    public function form(Request $request)
    {
        $request->validate([
            'class_id'  => 'required|exists:classes,id',
            'course_id' => 'required|exists:courses,id',
        ]);

        $class   = SchoolClass::findOrFail($request->class_id);
        $course  = Course::findOrFail($request->course_id);
        $students = Student::where('class_id', $request->class_id)
            ->orderBy('full_name')
            ->get();

        // 1) Ambil semua grade yang sudah tersimpan untuk class & course ini
        $grades = GradeStudent::where('class_id', $request->class_id)
            ->where('course_id', $request->course_id)
            ->get();

        // 2) Susun dalam array keyBy student_id
        $existingGrades = $grades->mapWithKeys(function ($g) {
            return [$g->student_id => [
                'assignment'  => $g->assignment_score,
                'mid_exam'    => $g->mid_exam_score,
                'final_exam'  => $g->final_exam_score,
                'final_grade' => $g->final_score,
            ]];
        })->toArray();

        return view('teacher.partials.grade-form', compact(
            'class',
            'course',
            'students',
            'existingGrades'
        ));
    }

    public function store(Request $request)
    {
        // validasi dasar
        $data = $request->validate([
            'class_id'  => 'required|exists:classes,id',
            'course_id' => 'required|exists:courses,id',
            'scores'    => 'required|array',
            'scores.*.assignment'   => 'required|numeric|min:0|max:100',
            'scores.*.mid_exam'     => 'required|numeric|min:0|max:100',
            'scores.*.final_exam'   => 'required|numeric|min:0|max:100',
        ]);

        // ambil model guru
        $teacher = Teacher::where('user_id', Auth::id())->firstOrFail();

        $teacherId = $teacher->id;

        foreach ($data['scores'] as $studentId => $score) {
            // hitung nilai akhir
            $final = round(
                ($score['assignment'] * 0.3)
                    + ($score['mid_exam']   * 0.3)
                    + ($score['final_exam'] * 0.4),
                2
            );

            // apakah tuntas?
            $isPass = $final >= 75;

            // cari atau buat
            GradeStudent::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'class_id'   => $data['class_id'],
                    'course_id'  => $data['course_id'],
                ],
                [
                    'teacher_id'        => $teacherId,
                    'assignment_score'  => $score['assignment'],
                    'mid_exam_score'    => $score['mid_exam'],
                    'final_exam_score'  => $score['final_exam'],
                    'final_score'       => $final,
                    'is_pass'           => $isPass,
                    'updated_at'        => Carbon::now(),
                ]
            );
        }

        return redirect()
            ->route('teacher.grade.form', [
                'class_id'  => $data['class_id'],
                'course_id' => $data['course_id'],
            ])
            ->with('success', 'Nilai berhasil disimpan.');
    }
}
