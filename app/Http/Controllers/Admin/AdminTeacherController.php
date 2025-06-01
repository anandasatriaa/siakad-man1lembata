<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Admin\Teacher;

class AdminTeacherController extends Controller
{
    public function index()
    {
        $teachers = Teacher::all();
        return view('admin.teacher', compact('teachers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nip' => 'required|string|unique:teachers,nip|max:20',
            'full_name' => 'required|string|max:100',
            'gender' => 'required|in:F,M',
            'birth_place' => 'required|string|max:100',
            'birth_date' => 'required|date',
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:100',
            'religion' => 'required|string|max:20',
            'enrollment_year' => 'required|integer|min:2000|max:2099',
            'status' => 'required|in:active,inactive',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('uploads/teachers', 'public');
        }

        Teacher::create($data);

        return redirect()->route('admin.teacher.index')->with('success', 'Data guru berhasil disimpan.');
    }

    public function edit($id)
    {
        $teacher = Teacher::findOrFail($id);
        return response()->json($teacher);
    }

    public function update(Request $request, $id)
    {
        $teacher = Teacher::findOrFail($id);
        $data = $request->validate([
            'nip' => 'required|string|max:20|unique:teachers,nip,' . $teacher->id,
            'full_name' => 'required|string|max:100',
            'gender' => 'required|in:F,M',
            'birth_place' => 'required|string|max:100',
            'birth_date' => 'required|date',
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:100',
            'religion' => 'required|string|max:20',
            'enrollment_year' => 'required|integer|min:2000|max:2099',
            'status' => 'required|in:active,inactive',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($teacher->photo) {
                Storage::disk('public')->delete($teacher->photo);
            }
            $data['photo'] = $request->file('photo')->store('uploads/teachers', 'public');
        }

        $teacher->update($data);
        return redirect()->route('admin.teacher.index')->with('success', 'Data guru berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $teacher = Teacher::findOrFail($id);

        try {
            // Hapus foto jika ada
            if ($teacher->photo && Storage::disk('public')->exists($teacher->photo)) {
                Storage::disk('public')->delete($teacher->photo);
            }

            // Hapus data siswa
            $teacher->delete();

            return response()->json(['success' => true, 'message' => 'Data guru berhasil dihapus.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menghapus data.']);
        }
    }
}
