<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Teacher\GradeStudent; // Menggunakan alias agar mudah
use App\Models\Admin\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentGradeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $student = null;

        // Cek apakah level 4 (siswa) atau level 5 (orang tua)
        if ($user->level == 4) {
            // Relasi dari User ke Student perlu didefinisikan di model User
            $student = $user->student; 
        } elseif ($user->level == 5) {
            $student = Student::find($user->guardian_of_student_id);
        } else {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        if (!$student) {
            // Menampilkan pesan yang lebih ramah jika data siswa tidak terhubung
            return view('student.grade_not_found');
        }

        // Ambil semua data nilai siswa beserta relasinya
        $grades = GradeStudent::with(['course', 'teacher'])
            ->where('student_id', $student->id)
            ->get();

        // Hitung Rata-rata, Tertinggi, Terendah
        $finalScores = $grades->pluck('final_score');
        $average = $finalScores->avg();
        $highestRecord = $grades->sortByDesc('final_score')->first();
        $lowestRecord = $grades->sortBy('final_score')->first();

        // Hitung Peringkat di Kelas
        $rank = '-';
        $totalStudentsInClass = 0;

        if ($student->class_id) {
            // Ambil rata-rata semua siswa di kelas yang sama
            $classAverages = GradeStudent::where('class_id', $student->class_id)
                ->groupBy('student_id')
                ->select('student_id', DB::raw('AVG(final_score) as average_score'))
                ->get()
                ->sortByDesc('average_score');

            $totalStudentsInClass = $classAverages->count();
            
            // Cari posisi/rank siswa saat ini
            $rankings = $classAverages->pluck('student_id')->values();
            $studentRankPosition = $rankings->search($student->id);
            
            if ($studentRankPosition !== false) {
                $rank = $studentRankPosition + 1;
            }
        }

        return view('student.grade', compact(
            'student',
            'grades',
            'average',
            'highestRecord',
            'lowestRecord',
            'rank',
            'totalStudentsInClass'
        ));
    }
}
