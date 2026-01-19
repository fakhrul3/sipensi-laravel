<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail; 
use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\User;
use App\Models\Inkubator;
use App\Mail\NotifDaftarAdmin;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        // Ambil data provinsi diurutkan abjad
        $provinsi = Provinsi::orderBy('name', 'asc')->get();
        
        // Data jenis lembaga (sesuaikan dengan kebutuhan form)
        $jenis_lembaga = [
            1 => 'Pemerintah Pusat',
            2 => 'Pemerintah Daerah',
            3 => 'Lembaga Pendidikan',
            4 => 'Badan Usaha',
        ];

        return view('auth.register', compact('provinsi', 'jenis_lembaga'));
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
            'is_verify' => 1, // Hanya user yang sudah di-ACC admin bisa login
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'username' => 'Akun tidak ditemukan, password salah, atau belum diverifikasi admin.'
        ])->onlyInput('username');
    }

    public function register(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'nama_inkubator'  => 'required|string|max:250',
            'email'           => 'required|email|unique:inkubator,email',
            'username'        => 'required|string|unique:users,username',
            'password'        => 'required|string|min:6|confirmed',
            'provinsi_id'     => 'required',
            'kabupaten_id'    => 'required',
            'path_legalitas'  => 'nullable|mimes:pdf|max:2048',
        ]);

        try {
            DB::beginTransaction();

            // 2. Simpan ke tabel users
            $user = User::create([
                'username'  => $request->username,
                'password'  => Hash::make($request->password),
                'is_admin'  => 0,
                'is_verify' => 0, // Status default: Menunggu ACC
            ]);

            // 3. Handle Upload File
            $fileName = null;
            if ($request->hasFile('path_legalitas')) {
                $fileName = time() . '_' . $request->file('path_legalitas')->getClientOriginalName();
                $request->file('path_legalitas')->storeAs('public/legalitas', $fileName);
            }

            // 4. Simpan ke tabel inkubator
            // ✅ FIX: kode_provinsi diisi manual dari provinsi_id agar tidak null
            $inkubator = Inkubator::create([
                'user_id'         => $user->id,
                'nama_inkubator'  => $request->nama_inkubator,
                'induk_inkubator' => $request->induk_inkubator,
                'no_kontak'       => $request->no_kontak,
                'email'           => $request->email,
                'alamat_kantor'   => $request->alamat_kantor,
                'provinsi_id'     => $request->provinsi_id,
                'kode_provinsi'   => $request->provinsi_id, // Duplikat nilai agar terisi
                'kabupaten_id'    => $request->kabupaten_id,
                'jenis_inkubator' => $request->jenis_inkubator,
                'path_legalitas'  => $fileName,
            ]);

            // 5. KIRIM EMAIL NOTIFIKASI KE ADMIN
            $adminEmail = env('ADMIN_EMAIL', 'diskarpuskesug@gmail.com');
            Mail::to($adminEmail)->send(new NotifDaftarAdmin($inkubator));

            DB::commit();

            return redirect()->route('login')->with('success', 'Pendaftaran berhasil! Silahkan tunggu verifikasi admin.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Gagal mendaftar: ' . $e->getMessage()])->withInput();
        }
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
}