<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Course;

class AdminCourseController extends Controller
{
    public function index()
    {
        $courses = Course::all()->groupBy('grade');
        return view('admin.course', compact('courses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'grade' => 'required|in:X,XI,XII',
            'code' => 'nullable|string|max:20',
        ]);

        // Cek jika code sudah ada
        if ($request->code && Course::where('code', $request->code)->exists()) {
            return redirect()->back()->with('error', 'Kode mata pelajaran sudah digunakan.');
        }

        Course::create($request->only('name', 'grade', 'code'));

        return redirect()->back()->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'grade' => 'required|in:X,XI,XII',
            'code' => 'nullable|string|max:20',
        ]);

        $course = Course::findOrFail($id);

        if ($request->code && Course::where('code', $request->code)->where('id', '!=', $id)->exists()) {
            return redirect()->back()->with('error', 'Kode mata pelajaran sudah digunakan oleh mata pelajaran lain.');
        }

        $course->update($request->only('name', 'grade', 'code'));

        return redirect()->back()->with('success', 'Mata pelajaran berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Course::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Mata pelajaran berhasil dihapus.');
    }
}
