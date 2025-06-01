<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Teacher;
use App\Models\Admin\Student;
use App\Models\Admin\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminClassController extends Controller
{
    public function index()
    {
        $teachers = Teacher::where('status', 'active')->get();
        $students = Student::where('status', 'active')->get();

        $classes = SchoolClass::with(['teacher', 'students'])->get()->groupBy('category');

        return view('admin.class', compact('teachers', 'students', 'classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'teacher_id' => 'required|exists:teachers,id',
            'students' => 'required|array',
            'students.*' => 'exists:students,id',
        ]);

        // Ambil guru
        $teacher = Teacher::findOrFail($request->teacher_id);
        if ($teacher->class_id !== null) {
            return redirect()->back()->with('warning', 'Guru tersebut sudah menjadi wali di kelas lain. Silakan pindahkan melalui menu edit.');
        }

        // Cek apakah ada siswa yang sudah punya kelas
        $studentsWithClass = Student::whereIn('id', $request->students)
            ->whereNotNull('class_id')
            ->get();

        if ($studentsWithClass->isNotEmpty()) {
            $studentNames = $studentsWithClass->pluck('full_name')->implode(', ');
            return redirect()->back()->with('warning', 'Siswa berikut sudah ada di kelas lain: ' . $studentNames . '. Silakan pindahkan melalui menu edit.');
        }

        DB::beginTransaction();
        try {
            // Simpan data kelas
            $class = SchoolClass::create([
                'name' => $request->name,
                'category' => $request->category,
            ]);

            // Update wali kelas
            $teacher->class_id = $class->id;
            $teacher->save();

            // Update semua siswa
            Student::whereIn('id', $request->students)->update(['class_id' => $class->id]);

            DB::commit();

            return redirect()->back()->with('success', 'Kelas berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $class = SchoolClass::with('students', 'teacher')->findOrFail($id);
        $teachers = Teacher::where('status', 'active')->get();
        $students = Student::where('status', 'active')->get();

        return view('admin.class_edit', compact('class', 'teachers', 'students'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'students' => 'required|array',
            'students.*' => 'exists:students,id',
        ]);

        DB::beginTransaction();
        try {
            $class = SchoolClass::findOrFail($id);

            // Reset guru lama (jika ada)
            Teacher::where('class_id', $class->id)->update(['class_id' => null]);

            // Set guru baru
            $newTeacher = Teacher::findOrFail($request->teacher_id);
            $newTeacher->class_id = $class->id;
            $newTeacher->save();

            // Reset semua siswa lama di kelas ini
            Student::where('class_id', $class->id)->update(['class_id' => null]);

            // Set siswa baru
            Student::whereIn('id', $request->students)->update(['class_id' => $class->id]);

            DB::commit();
            return redirect()->route('admin.class.index')->with('success', 'Data kelas berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
