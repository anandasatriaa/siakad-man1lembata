<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Teacher;
use App\Models\Admin\Student;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class AdminAccountController extends Controller
{
    public function index()
    {
        $allTeachers = Teacher::with('user')->orderBy('full_name')->get();
        $allStudents = Student::with('user')->orderBy('full_name')->get();

        $users = \App\Models\User::select('id', 'name', 'email', 'level', 'created_at')
            ->orderBy('level')
            ->get();

        return view('admin.account', compact('allTeachers', 'allStudents', 'users'));
    }

    public function store(Request $request)
    {
        try {
            // 1. Validasi input
            $data = $request->validate([
                'accounts'             => 'required|array',
                'accounts.*.type'      => 'required|in:teacher,student,guardian',
                'accounts.*.person_id' => 'required|integer',
                'accounts.*.email'     => 'required|email|unique:users,email',
                'accounts.*.password'  => 'required',
            ]);

            Log::debug('Akun diterima:', $data['accounts']);

            // 2. Loop setiap baris untuk membuat user
            foreach ($data['accounts'] as $row) {
                $type     = $row['type'];
                $personId = $row['person_id'];
                $email    = $row['email'];
                $password = bcrypt($row['password']);

                $relation = [];
                $guardianOfStudentId = null;

                switch ($type) {
                    case 'teacher':
                        $model    = Teacher::findOrFail($personId);
                        $name     = $model->full_name;
                        $level    = 3;
                        $relation = ['teacher_id' => $personId];
                        break;

                    case 'student':
                        $model    = Student::findOrFail($personId);
                        $name     = $model->full_name;
                        $level    = 4;
                        $relation = ['student_id' => $personId];
                        break;

                    case 'guardian':
                        $model    = Student::findOrFail($personId);
                        $name     = $model->guardian_name;
                        $level    = 5;
                        $guardianOfStudentId = $model->id;
                        break;

                    default:
                        continue 2;
                }

                $user = \App\Models\User::create([
                    'name'     => $name,
                    'email'    => $email,
                    'password' => $password,
                    'level'    => $level,
                    'guardian_of_student_id'  => $guardianOfStudentId,
                ]);

                if (isset($relation['teacher_id'])) {
                    Teacher::where('id', $relation['teacher_id'])->update(['user_id' => $user->id]);
                }
                if (isset($relation['student_id'])) {
                    Student::where('id', $relation['student_id'])->update(['user_id' => $user->id]);
                }
            }

            // 4. Jika semua sukses, redirect dengan pesan success
            return redirect()
                ->route('admin.account.index')
                ->with('success', 'Akun berhasil ditambahkan.');
        } catch (\Throwable $e) {
            // Kalau validasi gagal atau terjadi exception apa pun, redirect back dengan pesan error
            return redirect()
                ->route('admin.account.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        // Ambil user berdasarkan ID
        $user = User::findOrFail($id);

        return view('admin.account_edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        // 1. Validasi input: pastikan email valid dan unik (kecuali untuk user yang sama)
        $validated = $request->validate([
            'email' => 'required|email|unique:users,email,' . $id,
        ]);

        // 2. Jika validasi berhasil, simpan perubahan
        $user = User::findOrFail($id);
        $user->email = $validated['email'];
        $user->save();

        // 3. Redirect lagi ke index dengan pesan sukses
        return redirect()
            ->route('admin.account.index')
            ->with('success', 'Email untuk akun ' . $user->name . ' berhasil di‐update.');
    }

    public function resetPassword(\App\Models\User $user)
    {
        // Contoh sederhana: langsung set password default "password123"
        // atau Anda bisa tampilkan formulir modal, generate token reset, dsb.

        try {
            $user->update([
                'password' => bcrypt('lembata123')
            ]);

            return redirect()
                ->route('admin.account.index')
                ->with('success', "Password untuk akun {$user->email} berhasil di‐reset menjadi 'lembata123'.");
        } catch (\Throwable $e) {
            return redirect()
                ->route('admin.account.index')
                ->with('error', 'Gagal mereset password: ' . $e->getMessage());
        }
    }
}
