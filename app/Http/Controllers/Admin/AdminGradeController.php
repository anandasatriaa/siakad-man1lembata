<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Grade;
use App\Models\Admin\Student;
use App\Models\Admin\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class AdminGradeController extends Controller
{
    public function index(Request $request)
    {
        // 1) Ambil filter dari request lebih dahulu
        $classId   = $request->input('class_id');
        $studentId = $request->input('student_id');
        $semester  = $request->input('semester'); // bisa 1 / 2

        // 2) Data filter
        $classes  = SchoolClass::all();
        $students = $classId
            ? Student::where('class_id', $classId)->get()
            : Student::whereNotNull('class_id')->get();

        $gradeRecords = collect();
        $selectedStudent = null;

        // inisialisasi default
        $average    = 0;
        $highest    = 0;
        $lowest     = 0;
        $rank       = null;
        $allInClass = collect();  // atau array()

        if ($studentId) {
            // 3) Ambil semua grade untuk siswa dan kelas tertentu
            $gradeRecords = Grade::with(['course', 'teacher'])
                ->where('student_id', $studentId)
                ->when($classId, fn($q) => $q->where('class_id', $classId))
                // ->where('semester',$semester) // jika ada kolom semester
                ->get();

            $selectedStudent = Student::find($studentId);

            // 4) Hitung ringkasan: rata‑rata, peringkat, tertinggi, terendah
            $finalScores = $gradeRecords->pluck('final_score');
            $average   = $finalScores->avg();
            $highest   = $finalScores->max();
            $lowest    = $finalScores->min();

            // kumpulkan semua student_id di kelas
            $allInClass = Grade::where('class_id', $selectedStudent->class_id)
                ->distinct()
                ->pluck('student_id')
                ->toArray();

            // hitung rata-rata per student
            $averages = Grade::whereIn('student_id', $allInClass)
                ->groupBy('student_id')
                ->selectRaw('student_id, AVG(final_score) as avg_score')
                ->orderByDesc('avg_score')
                ->get();

            // bangun array [student_id => avg_score]
            $rankings = $averages->pluck('avg_score', 'student_id')->toArray();
            arsort($rankings);

            // cara A: cek isset sebelum pakai
            if (isset($rankings[$studentId])) {
                // ubah menjadi [student_id, …] tersort, lalu flip ke posisi
                $ranks = array_flip(array_keys($rankings));
                $rank  = $ranks[$studentId] + 1;
            } else {
                $rank = null;  // atau 0, atau '-' sesuai kebutuhan
            }
        }

        $data = [
            'classes'      => $classes,
            'students'     => $students,
            'gradeRecords' => $gradeRecords,
            'selectedStudent' => $selectedStudent,
            'semester'     => $semester,
            'classId'      => $classId,
        ];

        // kalau sudah pilih siswa, tambahkan ringkasan
        if ($studentId) {
            $data = array_merge($data, [
                'average'    => $average,
                'highest'    => $highest,
                'lowest'     => $lowest,
                'rank'       => $rank,
                'allInClass' => $allInClass,
            ]);
        }

        if ($classId && !$studentId) {
            $studentsInClass = Student::where('class_id', $classId)->get();

            $studentIds = $studentsInClass->pluck('id');

            $gradeRecords = Grade::with(['course', 'teacher', 'student'])
                ->whereIn('student_id', $studentIds)
                ->get();

            $data['studentsInClass'] = $studentsInClass;
            $data['gradeRecords'] = $gradeRecords;
        }


        return view('admin.grade', $data);
    }

    public function getStudentsByClass($classId)
    {
        $students = Student::where('class_id', $classId)->get();

        return response()->json($students);
    }
}
