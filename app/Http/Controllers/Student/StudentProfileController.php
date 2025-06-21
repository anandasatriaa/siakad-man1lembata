<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Admin\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class StudentProfileController extends Controller
{
    public function index()
    {
        // 1. Ambil data user yang sedang login
        $user = Auth::user();

        // 2. Ambil data student berdasarkan user_id
        $student = Student::where('user_id', $user->id)->first();

        if (! $student) {
            return redirect()->route('home')
                ->with('error', 'Data siswa tidak ditemukan.');
        }

        // Kirim ke view
        return view('student.profile', compact('user', 'student'));
    }

    /**
     * Proses pembaruan data profil (tanpa password)
     */
    public function updateProfile(Request $request)
    {
        $user       = Auth::user();
        $student    = Student::where('user_id', $user->id)->first();

        if (! $student) {
            return redirect()->route('student.profile.index')
                ->with('error', 'Data siswa tidak ditemukan.');
        }

        // 1. Validasi input
        $validated = $request->validate([
            // User: name dan email
            'name'      => ['required', 'string', 'max:255'],
            'email'     => [
                'required',
                'email',
                'max:255',
                // unik di tabel users kecuali untuk id user saat ini
                Rule::unique('users', 'email')->ignore($user->id),
            ],

            // Student: full_name, nis, gender, birth_place, birth_date, address, phone, religion, guardian_name, guardian_phone
            'full_name'       => ['required', 'string', 'max:255'],
            'nis'             => ['required', 'string', 'max:50'],
            'gender'          => ['required', Rule::in(['M', 'F'])],
            'birth_place'     => ['nullable', 'string', 'max:100'],
            'birth_date'      => ['nullable', 'date'],
            'address'         => ['nullable', 'string', 'max:500'],
            'phone'           => ['nullable', 'string', 'max:20'],
            'religion'        => ['nullable', 'string', 'max:50'],
            'guardian_name'   => ['nullable', 'string', 'max:255'],
            'guardian_phone'  => ['nullable', 'string', 'max:20'],

            // Foto profil opt-ion­al: `photo`
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        // 2. Update data di tabel users
        $user->name  = $validated['name'];
        $user->email = $validated['email'];
        $user->save();

        // 3. Jika ada upload foto baru, proses simpan ke storage (public/photos) dan hapus file lama bila ada
        if ($request->hasFile('photo')) {
            // Hapus file lama jika ada
            if ($student->photo) {
                Storage::disk('public')->delete($student->photo);
            }
            // Simpan file baru: simpan di public/photos/ dengan nama unik
            $path = $request->file('photo')->store('photos', 'public');
            $student->photo = $path;
        }

        // 4. Update data student
        $student->full_name       = $validated['full_name'];
        $student->nis             = $validated['nis'];
        $student->gender          = $validated['gender'];
        $student->birth_place     = $validated['birth_place'] ?? null;
        $student->birth_date      = $validated['birth_date'] ?? null;
        $student->address         = $validated['address'] ?? null;
        $student->phone           = $validated['phone'] ?? null;
        $student->religion        = $validated['religion'] ?? null;
        $student->guardian_name   = $validated['guardian_name'] ?? null;
        $student->guardian_phone  = $validated['guardian_phone'] ?? null;
        $student->save();

        return redirect()->route('student.profile.index')
            ->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Proses ganti password
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        // 1. Validasi input password
        $validated = $request->validate([
            'current_password'      => ['required', 'string'],
            'new_password'          => ['required', 'string', 'min:8', 'confirmed'],
            // `confirmed` berarti ada input `new_password_confirmation`
        ]);

        // 2. Cek apakah password saat ini benar
        if (! Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Password sekarang tidak sesuai.']);
        }

        // 3. Update password baru
        $user->password = Hash::make($validated['new_password']);
        $user->save();

        return redirect()->route('student.profile.index')
            ->with('success_password', 'Kata sandi berhasil diubah.');
    }
}
