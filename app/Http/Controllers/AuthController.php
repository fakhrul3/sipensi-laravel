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

        // Kalau mau paksa hanya akun yg sudah verify:
        // (kalau kolom is_verify ada)
        $credentials = [
            'username' => $data['username'],
            'password' => $data['password'],
        ];

        // Optional: kalau tabel users ada kolom is_verify
        // Uncomment baris ini kalau emang dipakai buat login gate
        $credentials['is_verify'] = 1;

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->intended('/'); // ubah kalau mau ke dashboard admin
        }

        return back()
            ->withErrors(['username' => 'Username atau password salah / belum terverifikasi.'])
            ->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
