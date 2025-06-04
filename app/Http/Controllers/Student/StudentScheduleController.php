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

        // 2. Cari data student berdasarkan user_id
        $student = Student::with('class')->where('user_id', $user->id)->first();

        if (! $student) {
            // Jika student tidak ditemukan (misal akun ini bukan siswa)
            return redirect()->route('home')->with('error', 'Anda bukan siswa atau data siswa tidak ditemukan.');
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
