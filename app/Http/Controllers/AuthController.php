<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Hash, DB, Mail, RateLimiter};
use Illuminate\Support\Str;
use App\Models\{Provinsi, Kabupaten, User, Inkubator};
use App\Mail\NotifDaftarAdmin; 
use Carbon\Carbon;

class AuthController extends Controller
{
    public function showLogin() {
        return view('auth.login');
    }

    public function showRegister() {
        $provinsi = Provinsi::orderBy('name', 'asc')->get();
        $jenis_lembaga = [
            1 => 'Pemerintah Pusat',
            2 => 'Pemerintah Daerah',
            3 => 'Lembaga Pendidikan',
            4 => 'Badan Usaha',
        ];
        return view('auth.register', compact('provinsi', 'jenis_lembaga'));
    }

    public function showResendPage($username) {
        return view('auth.verify_resend', compact('username'));
    }

    /**
     * FUNGSI VERIFIKASI: Mengaktifkan akun setelah klik link di email
     */
    public function verifyEmail($username, $token)
    {
        // 1. Cari user berdasarkan username dan token
        $user = User::where('username', $username)->where('verify_token', $token)->first();

        if (!$user) {
            return redirect()->route('login')->withErrors(['error' => 'Link verifikasi tidak valid atau sudah kadaluarsa.']);
        }

        try {
            DB::beginTransaction();

            // 2. Update status user jadi verified (is_verify = 1)
            $user->update([
                'is_verify' => 1,
                'email_verified_at' => now(),
                'verify_token' => null,
            ]);

            // 3. Trigger Kirim Notif ke Admin
            if ($user->inkubator) {
                // Mengambil email admin dari .env (ADMIN_EMAIL) atau fallback ke email default
                $adminEmail = env('ADMIN_EMAIL', 'diskarpuskesug@gmail.com');
                Mail::to($adminEmail)->send(new NotifDaftarAdmin($user->inkubator));
            }

            DB::commit();
            return redirect()->route('login')->with('success', 'Email berhasil diverifikasi! Admin telah diberitahu untuk aktivasi akun Anda.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('login')->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function register(Request $request)
    {
        $request->validate([
            'nama_inkubator'  => 'required|string|max:250',
            'email'           => 'required|email|unique:inkubator,email',
            'username'        => 'required|string|unique:users,username',
            'provinsi_id'     => 'required',
            'kabupaten_id'    => 'required',
            'path_legalitas'  => 'nullable|mimes:pdf|max:2048',
            'jenis_inkubator' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $verify_token = Str::random(40);
            $expired = strtotime(Carbon::now()->addHours(24));

            // 1. Simpan User
            $user = User::create([
                'username'     => $request->username,
                'password'     => Hash::make(Str::random(12)), 
                'is_admin'     => 0,
                'is_verify'    => 0,
                'verify_token' => $verify_token,
            ]);

            // 2. Handle Upload File Legalitas
            $fileName = null;
            if ($request->hasFile('path_legalitas')) {
                $fileName = time() . '_' . $request->file('path_legalitas')->getClientOriginalName();
                $request->file('path_legalitas')->storeAs('public/legalitas', $fileName);
            }

            // 3. Simpan Data Inkubator
            Inkubator::create([
                'user_id'         => $user->id,
                'nama_inkubator'  => $request->nama_inkubator,
                'induk_inkubator' => $request->induk_inkubator,
                'no_kontak'       => $request->no_kontak,
                'email'           => $request->email,
                'alamat_kantor'   => $request->alamat_kantor,
                'kode_provinsi'   => $request->provinsi_id, // Ini yang sudah ada
                'provinsi_id'     => $request->provinsi_id, // ✅ TAMBAHKAN INI agar kolom provinsi_id terisi
                'kabupaten_id'    => $request->kabupaten_id,
                'jenis_inkubator' => $request->jenis_inkubator,
                'path_legalitas'  => $fileName,
            ]);

            // 4. Kirim Email Verifikasi ke User
            Mail::send('mail.verify_email_registrasi', [
                'token'    => $verify_token, 
                'username' => $request->username, 
                'name'     => $request->nama_inkubator, 
                'expired'  => $expired
            ], function ($message) use ($request) {
                $message->to($request->email)->subject('Verifikasi Email Akun SIPENSI');
            });

            DB::commit();
            return redirect()->route('resend.verify', $request->username)
                             ->with('success', 'Registrasi sukses! Silakan cek email kamu.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Gagal registrasi: ' . $e->getMessage()])->withInput();
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);
    
        $user = User::where('username', $request->username)->first();
    
        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'username' => 'Username atau password salah'
            ])->onlyInput('username');
        }
    
        /**
         * 🔒 RULE AKSES
         * - Admin      → is_admin = 1
         * - Inkubator  → is_verify = 2
         */
        if ($user->is_admin != 1 && $user->is_verify != 2) {
            return back()->withErrors([
                'username' => 'Akun belum memiliki hak akses'
            ])->onlyInput('username');
        }
    
        Auth::login($user);
        $request->session()->regenerate();
    
        // 🔁 REDIRECT
        if ($user->is_admin == 1) {
            // ADMIN MASUK PORTAL ADMIN
            return redirect()->route('dashboard');
        }
    
        if ($user->is_verify == 2) {
            // INKUBATOR: BELUM ADA DASHBOARD
            return redirect()->route('login')->with(
                'success',
                'Login berhasil. Dashboard inkubator sedang dalam pengembangan.'
            );
        }
    
        return redirect('/login');
    }
    

    public function resendEmail($username)
    {
        $user = User::where('username', $username)->first();
        if (!$user || $user->is_verify == 1) return back();

        $key = 'resend-email:'.$username;
        if (RateLimiter::tooManyAttempts($key, 3)) {
            return back()->withErrors(['error' => 'Terlalu banyak mencoba. Tunggu beberapa saat lagi.']);
        }

        Mail::send('mail.verify_email_registrasi', [
            'token' => $user->verify_token, 
            'username' => $user->username, 
            'name' => $user->inkubator->nama_inkubator ?? 'User', 
            'expired' => strtotime(Carbon::now()->addHours(24))
        ], function ($message) use ($user) {
            $message->to($user->inkubator->email)->subject('Kirim Ulang Verifikasi SIPENSI');
        });

        RateLimiter::hit($key, 300);
        return back()->with('success', 'Email verifikasi telah dikirim ulang!');
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    public function getKabupaten($provinsi_id) {
        return response()->json(Kabupaten::where('provinsi_id', $provinsi_id)->get(['id', 'name']));
    }
}