<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Admin\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Student;

class StudentAnnouncementController extends Controller
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

        // Ambil semua pengumuman yang masih aktif (is_active = 1),
        // diurutkan terbaru dulu (created_at desc).
        $announcements = Announcement::where('is_active', 1)
            ->orderBy('created_at', 'desc')
            ->get();

        // Kirim data ke view
        return view('student.announcement', compact('announcements'));
    }
}
