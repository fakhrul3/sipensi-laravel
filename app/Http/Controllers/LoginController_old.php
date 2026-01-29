<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function show()
    {
        return view('auth.login'); // sesuaikan path blade kamu
    }

    public function authenticate(Request $request)
    {
        // kamu pakai input name="username" di blade
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        // opsi A: login pakai kolom `username`
        if (Auth::attempt(['username' => $credentials['username'], 'password' => $credentials['password']], $remember)) {
            $request->session()->regenerate();
            return redirect()->intended('/'); // ganti ke dashboard kamu kalau perlu
        }

        // opsi B (opsional): kalau ternyata kamu simpan email di field yang sama
        if (Auth::attempt(['email' => $credentials['username'], 'password' => $credentials['password']], $remember)) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function forgot()
    {
        return view('auth.forgot-password'); // sesuaikan path blade kamu
    }
}
