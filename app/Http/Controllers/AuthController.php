<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        // Cari user berdasarkan username
        $user = User::where('username', $data['username'])->first();

        if (!$user) {
            return back()
                ->withErrors(['username' => 'Username atau password salah / belum terverifikasi.'])
                ->onlyInput('username');
        }

        // Cek password
        if (!Hash::check($data['password'], $user->password)) {
            return back()
                ->withErrors(['username' => 'Username atau password salah / belum terverifikasi.'])
                ->onlyInput('username');
        }

        // Cek is_verify jika kolom ada
        $hasIsVerify = Schema::hasColumn('users', 'is_verify');
        if ($hasIsVerify && $user->is_verify != 1) {
            return back()
                ->withErrors(['username' => 'Akun belum terverifikasi.'])
                ->onlyInput('username');
        }

        // Login berhasil
        Auth::login($user, $remember);
        $request->session()->regenerate();
        return redirect()->intended('/dashboard'); // redirect ke dashboard setelah login
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
