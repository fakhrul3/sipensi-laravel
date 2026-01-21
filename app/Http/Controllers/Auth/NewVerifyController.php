<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Inkubator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class NewVerifyController extends Controller
{
    public function verify(Request $request, $token, $username, $expired)
    {
        // 1. Cari user berdasarkan token dan username
        $user = User::where([
            'verify_token' => $token,
            'username' => $username
        ])->first();

        if ($user) {
            // 2. Cek apakah link kadaluarsa (24 jam)
            if ($expired > strtotime(now())) {
                
                // --- PERBAIKAN DI SINI ---
                // Update status: 1 artinya Email Valid, tinggal tunggu ACC Admin
                $user->is_verify = 1; 
                
                // Token diacak ulang supaya link yang sama tidak bisa diklik dua kali
                $user->verify_token = Str::random(40); 
                $user->save();
                // -------------------------

                // 3. KIRIM EMAIL KE ADMIN
                $inkubator = Inkubator::where('user_id', $user->id)->first();
                $adminEmail = env('ADMIN_EMAIL', 'diskarpuskesug@gmail.com');

                try {
                    Mail::send('mail.notif_admin_new_user', ['inkubator' => $inkubator], function ($message) use ($adminEmail) {
                        $message->to($adminEmail);
                        $message->subject('Notifikasi SIPENSI: User Baru Menunggu Persetujuan');
                    });
                } catch (\Exception $e) {
                    // Jika email admin gagal kirim, proses tetap lanjut tapi log error
                    report($e);
                }

                return redirect()->route('login')->with('success', "Email berhasil diverifikasi! Pendaftaran Anda sedang ditinjau oleh Admin. Password akan dikirim ke email Anda jika sudah disetujui.");
            } else {
                return redirect()->route('resend.verify', $username)->with('error', "Link verifikasi sudah kadaluarsa, silahkan kirim ulang.");
            }
        }

        return redirect()->route('login')->with('error', "Link verifikasi tidak valid atau sudah pernah digunakan.");
    }
}