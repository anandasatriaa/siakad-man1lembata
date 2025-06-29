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

    public function edit(SchoolClass $id)
    {
        $classes  = SchoolClass::all();       // untuk dropdown (kelas lain jika perlu)
        $courses  = Course::all();
        $teachers = Teacher::where('status', 'active')->get();
        $days     = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];

        // ambil semua jadwal milik kelas ini
        $schedules = $id->schedules()->orderBy('day')->orderBy('start_time')->get();

        return view('admin.schedule.index', compact(
            'class','classes','courses','teachers','days','schedules'
        ));
    }

    public function update(Request $request, SchoolClass $id)
    {
        $request->validate([
            'days'        => 'required|array',
            'course_ids'  => 'required|array',
            'teacher_ids' => 'required|array',
            'start_times' => 'required|array',
            'end_times'   => 'required|array',
        ]);

        DB::beginTransaction();
        try {
            // Hapus semua jadwal lama
            $id->schedules()->delete();

            // Masukkan ulang berdasarkan input
            foreach ($request->days as $i => $day) {
                $id->schedules()->create([
                    'class_id'   => $id->id,
                    'day'        => $day,
                    'course_id'  => $request->course_ids[$i]  === 'istirahat' ? null : $request->course_ids[$i],
                    'teacher_id' => $request->course_ids[$i]  === 'istirahat' ? null : $request->teacher_ids[$i],
                    'start_time' => $request->start_times[$i],
                    'end_time'   => $request->end_times[$i],
                ]);
            }

            DB::commit();
            return redirect()->route('admin.schedule.index')
                             ->with('success','Jadwal berhasil di‑update.');
        } catch(\Exception $e) {
            DB::rollBack();
            return back()->withError('Gagal update jadwal: '.$e->getMessage());
        }
    }
}
