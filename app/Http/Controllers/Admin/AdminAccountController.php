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
        // Ambil semua guru yang belum punya akun
        $teachers = Teacher::whereNull('user_id')
            ->orderBy('full_name')
            ->get();

        // Ambil semua siswa yang belum punya akun
        $students = Student::whereNull('user_id')
            ->orderBy('full_name')
            ->get();

        // Ambil semua user yang sudah ada di tabel users
        // Kita hanya mengambil kolom yang akan ditampilkan
        $users = \App\Models\User::select('id', 'name', 'email', 'level', 'created_at')
            ->orderBy('level') // misal tampilkan guru (level 3) dulu, lalu siswa (level 4)
            ->get();

        return view('admin.account', compact('teachers', 'students', 'users'));
    }

    public function store(Request $request)
    {
        try {
            // 1. Validasi input
            $data = $request->validate([
                'accounts'             => 'required|array',
                'accounts.*.type'      => 'required|in:teacher,student',
                'accounts.*.person_id' => 'required|integer',
                'accounts.*.email'     => 'required|email|unique:users,email',
                'accounts.*.password'  => 'required',
            ]);

            Log::debug('Akun diterima:', $data['accounts']);

            // 2. Loop setiap baris untuk membuat user
            foreach ($data['accounts'] as $row) {
                $user = \App\Models\User::create([
                    'name'     => ($row['type'] === 'teacher')
                        ? Teacher::find($row['person_id'])->full_name
                        : Student::find($row['person_id'])->full_name,
                    'email'    => $row['email'],
                    'password' => bcrypt($row['password']),
                    'level'    => ($row['type'] === 'teacher') ? '3' : '4',
                ]);

                // 3. Update kolom user_id pada guru/siswa
                if ($row['type'] === 'teacher') {
                    Teacher::where('id', $row['person_id'])
                        ->update(['user_id' => $user->id]);
                } else {
                    Student::where('id', $row['person_id'])
                        ->update(['user_id' => $user->id]);
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
