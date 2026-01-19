<?php

namespace App\Http\Controllers;

use App\Models\ManajemenGambar;
use App\Models\KontakKami;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;


class KontakController extends Controller
{
    // ========== PUBLIC METHODS ==========
    
    public function index()
    {
        $kontakBg = ManajemenGambar::select('path_gambar')
            ->where('option_gambar', 'kontak_2')
            ->where('is_show', 1)
            ->first();

        return view('kontak.kontak', compact('kontakBg'));
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

    // ========== ADMIN METHODS ==========

    /**
     * Menampilkan daftar Kontak Kami (Admin)
     */
    public function adminIndex(Request $request)
    {
        try {
            $query = DB::table('kontak_kami')
                ->select('id', 'nama_lengkap', 'email', 'pesan', 'created_at', 'updated_at')
                ->orderBy('created_at', 'desc');

            // Search functionality
            if ($request->filled('search')) {
                $searchTerm = '%' . $request->search . '%';
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('nama_lengkap', 'like', $searchTerm)
                      ->orWhere('email', 'like', $searchTerm)
                      ->orWhere('pesan', 'like', $searchTerm);
                });
            }

            $kontaks = $query->get();

            return view('admin.kontak.index', compact('kontaks'));
        } catch (\Exception $e) {
            \Log::error('Kontak Kami Admin Index Error: ' . $e->getMessage());
            return view('admin.kontak.index', ['kontaks' => collect()])->with('error', 'Gagal memuat data kontak: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan detail kontak
     */
    public function adminShow($id)
    {
        try {
            $kontak = DB::table('kontak_kami')->where('id', $id)->first();

            if (!$kontak) {
                return response()->json(['success' => false, 'message' => 'Kontak tidak ditemukan'], 404);
            }

            return response()->json(['success' => true, 'data' => $kontak]);
        } catch (\Exception $e) {
            \Log::error('Kontak Kami Admin Show Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data kontak'], 500);
        }
    }

    /**
     * Hapus kontak
     */
    public function destroy($id)
    {
        try {
            $kontak = DB::table('kontak_kami')->where('id', $id)->first();

            if (!$kontak) {
                return response()->json(['success' => false, 'message' => 'Kontak tidak ditemukan'], 404);
            }

            DB::table('kontak_kami')->where('id', $id)->delete();

            return response()->json([
                'success' => true,
                'message' => 'Kontak berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            \Log::error('Kontak Kami Destroy Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus kontak: ' . $e->getMessage()
            ], 500);
        }
    }
}
