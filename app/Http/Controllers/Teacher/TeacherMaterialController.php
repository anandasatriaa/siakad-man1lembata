<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Admin\SchoolClass;
use App\Models\Admin\Course;
use App\Models\Teacher\TeacherMaterial;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class TeacherMaterialController extends Controller
{
    /**
     * Tampilkan daftar materi
     */
    public function index()
    {
        // Ambil semua materi milik guru yang login, beserta data kelas & course untuk dropdown
        $teacherId = Auth::id();
        $materials = TeacherMaterial::with(['classroom', 'course'])
            ->where('teacher_id', $teacherId)
            ->orderBy('created_at', 'desc')
            ->get();

        $classes = SchoolClass::orderBy('name')->get();
        $courses = Course::orderBy('name')->get();

        return view('teacher.material', compact('materials', 'classes', 'courses'));
    }

    /**
     * Simpan materi baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'class_id'    => 'nullable|exists:classes,id',
            'course_id'   => 'nullable|exists:courses,id',
            'description' => 'nullable|string',
            'file'        => 'required|file|mimes:pdf,doc,docx,ppt,pptx,zip|max:10240',
        ]);

        $file      = $request->file('file');
        $fileName  = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
        $path      = $file->storeAs('teacher_materials', $fileName, 'public');
        $fileType  = $file->extension();

        TeacherMaterial::create([
            'teacher_id'   => Auth::id(),
            'class_id'     => $request->input('class_id'),
            'course_id'    => $request->input('course_id'),
            'title'        => $request->input('title'),
            'description'  => $request->input('description'),
            'file_path'    => $path,
            'file_type'    => $fileType,
            'published_at' => now(),
        ]);

        return redirect()->route('teacher.material.index')
            ->with('success', 'Materi berhasil ditambahkan.');
    }

    /**
     * Update materi (judul, deskripsi, class/course, bisa optional ganti file)
     */
    public function update(Request $request, $id)
    {
        $material = TeacherMaterial::where('teacher_id', Auth::id())
            ->findOrFail($id);

        $request->validate([
            'title'       => 'required|string|max:255',
            'class_id'    => 'nullable|exists:classes,id',
            'course_id'   => 'nullable|exists:courses,id',
            'description' => 'nullable|string',
            'file'        => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,zip|max:10240',
        ]);

        // Jika ada file baru, delete file lama & simpan file baru
        if ($request->hasFile('file')) {
            // Hapus file lama
            Storage::disk('public')->delete($material->file_path);

            // Simpan file baru
            $file      = $request->file('file');
            $fileName  = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $path      = $file->storeAs('teacher_materials', $fileName, 'public');
            $fileType  = $file->extension();

            $material->file_path = $path;
            $material->file_type = $fileType;
        }

        // Update field lain
        $material->title       = $request->input('title');
        $material->class_id    = $request->input('class_id');
        $material->course_id   = $request->input('course_id');
        $material->description = $request->input('description');
        // published_at tetap menggunakan created_at, atau bisa di‐set ulang:
        // $material->published_at = now();
        $material->save();

        return redirect()->route('teacher.material.index')
            ->with('success', 'Materi berhasil diupdate.');
    }

    /**
     * Hapus materi dan file-nya
     */
    public function destroy($id)
    {
        $material = TeacherMaterial::where('teacher_id', Auth::id())
            ->findOrFail($id);

        // Hapus file dari storage
        Storage::disk('public')->delete($material->file_path);

        // Hapus record di database
        $material->delete();

        return redirect()->route('teacher.material.index')
            ->with('success', 'Materi berhasil dihapus.');
    }
}
