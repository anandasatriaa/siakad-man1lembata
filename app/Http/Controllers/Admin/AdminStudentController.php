<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Student;
use App\Models\Admin\Classroom;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class AdminStudentController extends Controller
{
    public function index()
    {
        $students = Student::all(); // Pastikan model Student sudah ada
        return view('admin.student', compact('students'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nis' => 'required|string|max:20|unique:students,nis',
            'full_name' => 'required|string|max:100',
            'gender' => 'required|in:M,F',
            'birth_place' => 'required|string|max:100',
            'birth_date' => 'required|date',
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:100',
            'religion' => 'required|string|max:20',
            'enrollment_year' => 'required|integer|min:2000|max:2099',
            'status' => 'required|in:Aktif,Tidak Aktif',
            'guardian_name' => 'required|string|max:100',
            'guardian_phone' => 'required|string|max:20',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        try {
            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('uploads/students', 'public');
            }

            Student::create([
                'nis' => $request->nis,
                'full_name' => $request->full_name,
                'gender' => $request->gender,
                'birth_place' => $request->birth_place,
                'birth_date' => $request->birth_date,
                'address' => $request->address,
                'phone' => $request->phone,
                'email' => $request->email,
                'religion' => $request->religion,
                'enrollment_year' => $request->enrollment_year,
                'status' => $request->status == 'Aktif' ? 'active' : 'inactive',
                'guardian_name' => $request->guardian_name,
                'guardian_phone' => $request->guardian_phone,
                'photo' => $photoPath,
            ]);

            return redirect()->route('admin.student.index')->with('success', 'Data siswa berhasil disimpan.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $request->validate([
            'full_name' => 'required|string|max:100',
            'gender' => 'required|in:M,F',
            'birth_place' => 'required|string|max:100',
            'birth_date' => 'required|date',
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:100',
            'religion' => 'required|string|max:20',
            'enrollment_year' => 'required|integer|min:2000|max:2099',
            'status' => 'required|in:active,inactive',
            'guardian_name' => 'required|string|max:100',
            'guardian_phone' => 'required|string|max:20',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        try {
            $data = $request->except('photo', 'status'); // exclude 'photo' and 'status' from mass assignment

            // Handle status (convert ke internal value)
            $data['status'] = $request->status;

            // Handle photo upload
            if ($request->hasFile('photo')) {
                // Hapus foto lama jika ada
                if ($student->photo && Storage::disk('public')->exists($student->photo)) {
                    Storage::disk('public')->delete($student->photo);
                }

                // Simpan foto baru
                $data['photo'] = $request->file('photo')->store('uploads/students', 'public');
            } else {
                // Jika tidak upload foto baru, pertahankan yang lama
                $data['photo'] = $student->photo;
            }

            // Update data
            $student->update($data);

            return redirect()->route('admin.student.index')->with('success', 'Data siswa berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat memperbarui data.');
        }
    }

    public function destroy($id)
    {
        // Gunakan transaction agar atomic
        DB::beginTransaction();

        try {
            $student = Student::with('user')->findOrFail($id);

            // 1) Hapus foto siswa jika ada
            if ($student->photo && Storage::disk('public')->exists($student->photo)) {
                Storage::disk('public')->delete($student->photo);
            }

            // 2) Hapus akun user terkait (jika ada)
            if ($student->user) {
                $student->user->delete();
            }

            // 3) Hapus record siswa
            $student->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data siswa dan akun user berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus: ' . $e->getMessage()
            ], 500);
        }
    }
}
