<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Student;

class StudentGradeController extends Controller
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

        return view('student.grade');
    }
}
