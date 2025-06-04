<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Admin\Announcement;
use Illuminate\Http\Request;

class StudentAnnouncementController extends Controller
{
    public function index()
    {
        // Ambil semua pengumuman yang masih aktif (is_active = 1),
        // diurutkan terbaru dulu (created_at desc).
        $announcements = Announcement::where('is_active', 1)
            ->orderBy('created_at', 'desc')
            ->get();

        // Kirim data ke view
        return view('student.announcement', compact('announcements'));
    }
}
