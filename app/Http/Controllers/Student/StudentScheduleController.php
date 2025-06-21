<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Admin\Schedule;
use App\Models\Admin\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentScheduleController extends Controller
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

        // 3. Ambil class_id milik siswa
        $classId = $student->class_id;

        // 4. Tarik semua jadwal untuk kelas tersebut, lengkap dengan data course & teacher
        $schedules = Schedule::with(['course', 'teacher'])
            ->where('class_id', $classId)
            ->orderByRaw("
                FIELD(day, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'
                ), start_time
            ")
            ->get();

        // 5. Kirim ke view
        return view('student.schedule', compact('schedules'));
    }
}
