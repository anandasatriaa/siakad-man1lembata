<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->has('remember'))) {
            $request->session()->regenerate();

            // Cek level jika ingin diarahkan berdasarkan level
            $user = Auth::user();

            switch ($user->level) {
                case '1':
                    return redirect()->intended('/admin/dashboard');
                case '2':
                    return redirect()->intended('/kesiswaan/dashboard');
                case '3':
                    return redirect()->intended('/guru/dashboard');
                case '4':
                    return redirect()->intended('/siswa/dashboard');
                default:
                    Auth::logout();
                    abort(403, 'Akses tidak dikenali.');
            }

            // default
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput();
    }
}
