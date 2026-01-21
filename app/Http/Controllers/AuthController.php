<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail; 
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\User;
use App\Models\Inkubator;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        $provinsi = Provinsi::orderBy('name', 'asc')->get();
        $jenis_lembaga = [
            1 => 'Pemerintah Pusat',
            2 => 'Pemerintah Daerah',
            3 => 'Lembaga Pendidikan',
            4 => 'Badan Usaha',
        ];
        return view('auth.register', compact('provinsi', 'jenis_lembaga'));
    }

    public function showResendPage($username)
    {
        return view('auth.verify_resend', compact('username'));
    }

    /**
     * Fitur Kirim Ulang Email Verifikasi dengan Pembatasan (Rate Limiting)
     */
    public function resendEmail($username)
    {
        $key = 'resend-email:' . $username . '|' . request()->ip();

        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors([
                'error' => "Terlalu banyak permintaan. Silakan coba lagi dalam $seconds detik."
            ]);
        }

        $user = User::where('username', $username)->first();

        if (!$user || !$user->inkubator) {
            return back()->withErrors(['error' => 'Data pengguna tidak ditemukan.']);
        }

        if ($user->is_verify == 1) {
            return redirect()->route('login')->with('success', 'Akun Anda sudah aktif.');
        }

        try {
            RateLimiter::hit($key, 300);

            $expired = strtotime(Carbon::now()->addHours(24));

            Mail::send('mail.verify_email_registrasi', [
                'token'    => $user->verify_token, 
                'username' => $user->username, 
                'name'     => $user->inkubator->nama_inkubator, 
                'expired'  => $expired
            ], function ($message) use ($user) {
                $message->to($user->inkubator->email);
                $message->subject('Kirim Ulang: Verifikasi Email Akun SIPENSI');
            });

            return back()->with('success', 'Email verifikasi telah dikirim ulang ke ' . $user->inkubator->email);
            
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal mengirim email: ' . $e->getMessage()]);
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = [
            'username'  => $request->username,
            'password'  => $request->password,
            'is_verify' => 1, 
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'username' => 'Akun tidak ditemukan, password salah, atau belum diverifikasi admin.'
        ])->onlyInput('username');
    }

    /**
     * Alur Registrasi Baru: Tanpa Input Password
     */
    public function register(Request $request)
    {
        // Validasi dihapus bagian password & password_confirmation
        $request->validate([
            'nama_inkubator'  => 'required|string|max:250',
            'email'           => 'required|email|unique:inkubator,email',
            'username'        => 'required|string|unique:users,username',
            'provinsi_id'     => 'required',
            'kabupaten_id'    => 'required',
            'path_legalitas'  => 'nullable|mimes:pdf|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $verify_token = $this->generateRandomString(40);
            $expired = strtotime(Carbon::now()->addHours(24));

            // Generate password acak sementara (hanya untuk pengisi database)
            // Password asli akan di-generate ulang & dikirim saat Admin ACC
            $temp_password = Str::random(12);

            $user = User::create([
                'username'     => $request->username,
                'password'     => Hash::make($temp_password),
                'is_admin'     => 0,
                'is_verify'    => 0,
                'verify_token' => $verify_token,
            ]);

            $fileName = null;
            if ($request->hasFile('path_legalitas')) {
                $fileName = time() . '_' . $request->file('path_legalitas')->getClientOriginalName();
                $request->file('path_legalitas')->storeAs('public/legalitas', $fileName);
            }

            $inkubator = Inkubator::create([
                'user_id'         => $user->id,
                'nama_inkubator'  => $request->nama_inkubator,
                'induk_inkubator' => $request->induk_inkubator,
                'no_kontak'       => $request->no_kontak,
                'email'           => $request->email,
                'alamat_kantor'   => $request->alamat_kantor,
                'provinsi_id'     => $request->provinsi_id,
                'kode_provinsi'   => $request->provinsi_id, 
                'kabupaten_id'    => $request->kabupaten_id,
                'jenis_inkubator' => $request->jenis_inkubator,
                'path_legalitas'  => $fileName,
            ]);

            // Kirim email verifikasi
            Mail::send('mail.verify_email_registrasi', [
                'token'    => $verify_token, 
                'username' => $request->username, 
                'name'     => $request->nama_inkubator, 
                'expired'  => $expired
            ], function ($message) use ($request) {
                $message->to($request->email);
                $message->subject('Verifikasi Email Akun SIPENSI');
            });

            DB::commit();

            return redirect()->route('resend.verify', $request->username)
                             ->with('success', 'Registrasi berhasil! Silahkan cek email kamu untuk verifikasi.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Gagal mendaftar: ' . $e->getMessage()])->withInput();
        }
    }

    public function generateRandomString($length = 10)
    {
        return Str::random($length);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    public function getKabupaten($provinsi_id)
    {
        $kabupaten = Kabupaten::where('provinsi_id', $provinsi_id)
            ->select('id', 'name')
            ->orderBy('name', 'asc')
            ->get();
        return response()->json($kabupaten);
    }

    public function showForgotPassword()
{
    return view('auth.forgot-password'); 
}
}