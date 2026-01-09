<?php

namespace App\Http\Controllers;

use App\Models\KontakKami;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class KontakController extends Controller
{
    public function index()
    {
        return view('kontak.kontak'); // sesuaikan kalau view lu di folder lain
    }

    public function store(Request $request)
    {
        // 1) validasi input + wajib captcha
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:100'],
            'message' => ['required', 'string'],
            'g-recaptcha-response' => ['required'],
        ], [
            'g-recaptcha-response.required' => 'Tolong centang captcha dulu.',
        ]);

        // 2) verifikasi captcha ke Google (server-side)
        $secret = env('RECAPTCHA_SECRET_KEY');

        if (!$secret) {
            return back()
                ->withErrors(['captcha' => 'RECAPTCHA_SECRET_KEY belum diisi di .env'])
                ->withInput();
        }

        $verify = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret'   => $secret,
            'response' => $request->input('g-recaptcha-response'),
            'remoteip' => $request->ip(),
        ]);

        $verifyBody = $verify->json();

        if (!($verifyBody['success'] ?? false)) {
            return back()
                ->withErrors(['captcha' => 'Captcha gagal. Coba ulangi.'])
                ->withInput();
        }

        // 3) insert ke tabel kontak_kami
        KontakKami::create([
            'nama_lengkap' => $request->name,
            'email'        => $request->email,
            'pesan'        => $request->message,
        ]);

        return back()->with('success', 'Pesan berhasil dikirim. Terima kasih!');
    }
}
