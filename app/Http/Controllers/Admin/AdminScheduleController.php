<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\SchoolClass;
use App\Models\Admin\Course;
use App\Models\Admin\Teacher;
use Illuminate\Support\Facades\DB;


class AdminScheduleController extends Controller
{
    public function index()
    {
        $classes  = SchoolClass::with([
            'schedules.course',
            'schedules.teacher'
        ])->get();

        $courses  = Course::all();
        $teachers = Teacher::where('status', 'active')->get();
        $days     = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        return view('admin.schedule', compact('classes', 'courses', 'teachers', 'days'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_id'     => 'required|exists:classes,id',
            'days'         => 'required|array',
            'course_ids'   => 'required|array',
            'teacher_ids'  => 'required|array',
            'start_times'  => 'required|array',
            'end_times'    => 'required|array',
        ]);

        $classId     = $request->input('class_id');
        $days        = $request->input('days');
        $courseIds   = $request->input('course_ids');
        $teacherIds  = $request->input('teacher_ids');
        $startTimes  = $request->input('start_times');
        $endTimes    = $request->input('end_times');

        DB::beginTransaction();
        try {
            foreach ($days as $i => $day) {
                $courseId  = $courseIds[$i] === 'istirahat' ? null : $courseIds[$i];
                $teacherId = $courseIds[$i] === 'istirahat' ? null : $teacherIds[$i];

                DB::table('schedules')->insert([
                    'class_id'   => $classId,
                    'day'        => $day,
                    'course_id'  => $courseId,
                    'teacher_id' => $teacherId,
                    'start_time' => $startTimes[$i],
                    'end_time'   => $endTimes[$i],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Jadwal berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan jadwal: ' . $e->getMessage());
        }
    }
}
