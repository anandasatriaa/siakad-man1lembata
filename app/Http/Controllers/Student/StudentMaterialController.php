<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Admin\Student;
use App\Models\Teacher\TeacherMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentMaterialController extends Controller
{
    public function index()
    {
        // 1. Ambil user yang sedang login
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

        $classId = $student->class_id;

        // 3. Tarik semua materi yang berhubungan dengan class_id siswa
        //    Jika ingin juga menyertakan materi berdasarkan course_id yang masuk jadwal,
        //    Anda bisa menambahkan logika join dengan tabel schedules untuk memfilter course_id.
        //    Tapi di sini kita akan menampilkan TeacherMaterial yang class_id = student->class_id
        $materials = TeacherMaterial::with(['teachers', 'course'])
            ->where('class_id', $classId)
            ->orderBy('published_at', 'desc')
            ->get();

        return view('student.material', compact('materials'));
    }
}
