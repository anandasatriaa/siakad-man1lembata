<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Announcement;

class TeacherAnnouncementController extends Controller
{
    public function index()
    {
        // Ambil semua pengumuman yang masih aktif (is_active = 1),
        // diurutkan terbaru dulu (created_at desc).
        $announcements = Announcement::where('is_active', 1)
            ->orderBy('created_at', 'desc')
            ->get();

        // Kirim data ke view
        return view('teacher.announcement', compact('announcements'));
    }
}
