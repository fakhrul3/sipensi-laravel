<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Exports\AdminExport;
use App\Exports\LembagaInkubatorExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Milon\Barcode\Facades\DNS2DFacade as DNS2D;

class RoleUserController extends Controller
{
    /**
     * Menampilkan daftar Admin
     */
    public function adminIndex(Request $request)
    {
        try {
            $users = cache()->remember('admin_users_list', 10, function () {
                try {
                    return DB::table('users')
                        ->select('id', 'username', 'password', 'is_admin', 'is_verify', 'created_at', 'updated_at')
                        ->where('is_admin', 1)
                        ->orderBy('id', 'asc')
                        ->get();
                } catch (\Exception $e) {
                    \Log::error('Admin Users List Error: ' . $e->getMessage());
                    return collect();
                }
            });

            \Log::info('Total users retrieved: ' . $users->count());
            return view('role-user.admin.index', compact('users'));
        } catch (\Exception $e) {
            \Log::error('RoleUser Admin Index Error: ' . $e->getMessage());
            return view('role-user.admin.index', ['users' => collect()]);
        }
    }

    /**
     * Store - Create new admin user
     */
    public function adminStore(Request $request)
    {
        try {
            \Log::info('Admin Store Request: ', $request->all());

            $validated = $request->validate([
                'username' => 'required|string|unique:users,username',
                'password' => 'required|string|min:6',
            ]);

            $schema = DB::getSchemaBuilder();
            $hasIsAdmin = $schema->hasColumn('users', 'is_admin');
            $hasIsVerify = $schema->hasColumn('users', 'is_verify');

            $userData = [
                'username' => $validated['username'],
                'password' => Hash::make($validated['password']),
            ];

            if ($hasIsAdmin) $userData['is_admin'] = 1;
            if ($hasIsVerify) $userData['is_verify'] = 1;

            \Log::info('User Data to Create: ', $userData);

            $userId = DB::table('users')->insertGetId($userData);

            \Log::info('Admin created with ID: ' . $userId);

            cache()->forget('admin_users_list');

            return response()->json([
                'success' => true,
                'message' => 'Admin berhasil ditambahkan',
                'id' => $userId
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation Error: ', $e->errors());
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Admin Store Error: ' . $e->getMessage());
            \Log::error('Stack Trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan admin: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show - Get single admin user
     */
    public function adminShow($id)
    {
        try {
            $user = User::findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $user->id,
                    'username' => $user->username,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Update - Update admin user
     */
    public function adminUpdate(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            $validated = $request->validate([
                'username' => 'required|string|unique:users,username,' . $id,
                'password' => 'nullable|string|min:6',
            ]);

            $userData = [
                'username' => $validated['username'],
            ];

            if (!empty($validated['password'])) {
                $userData['password'] = Hash::make($validated['password']);
            }

            \Log::info('User Data to Update (ID: ' . $id . '): ', $userData);

            $updated = DB::table('users')->where('id', $id)->update($userData);

            \Log::info('Admin updated (ID: ' . $id . '), rows affected: ' . $updated);

            cache()->forget('admin_users_list');

            return response()->json([
                'success' => true,
                'message' => 'Admin berhasil diupdate',
                'updated' => $updated
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Admin Update Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate admin: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Destroy - Delete admin user
     */
    public function adminDestroy($id)
    {
        try {
            $user = User::findOrFail($id);

            if ($user->id == auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menghapus akun yang sedang digunakan'
                ], 400);
            }

            \Log::info('Deleting admin ID: ' . $id);

            $deleted = DB::table('users')->where('id', $id)->delete();

            \Log::info('Admin deleted (ID: ' . $id . '), rows affected: ' . $deleted);

            cache()->forget('admin_users_list');

            return response()->json([
                'success' => true,
                'message' => 'Admin berhasil dihapus',
                'deleted' => $deleted
            ]);
        } catch (\Exception $e) {
            \Log::error('Admin Destroy Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus admin: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menampilkan daftar User Inkubator (Role User)
     * Route: role-user/inkubator -> name role-user.inkubator.index
     */
    public function inkubatorIndex(Request $request)
    {
        try {
            $users = cache()->remember('inkubator_users_list', 10, function () {
                try {
                    return DB::table('users')
                    ->leftJoin('inkubator', 'inkubator.user_id', '=', 'users.id')
                    ->select(
                        'users.id',
                        'users.username',
                        'users.password',
                        'users.is_admin',
                        'users.is_verify',
                        'users.created_at',
                        'users.updated_at',

                        // tambahan dari tabel inkubator
                        'inkubator.no_tanda_daftar',
                        'inkubator.nama_inkubator',
                        'inkubator.email as inkubator_email'
                    )
                    ->where('users.is_admin', 0)
                    ->orderBy('users.id', 'asc')
                    ->get();
                } catch (\Exception $e) {
                    \Log::error('Inkubator Users List Error: ' . $e->getMessage());
                    return collect();
                }
            });

            \Log::info('Total inkubator users retrieved: ' . $users->count());
            return view('role-user.inkubator.index', compact('users'));
        } catch (\Exception $e) {
            \Log::error('RoleUser Inkubator Index Error: ' . $e->getMessage());
            return view('role-user.inkubator.index', ['users' => collect()]);
        }
    }

    /**
     * Store - Create new inkubator user
     */
    public function inkubatorStore(Request $request)
    {
        try {
            \Log::info('Inkubator Store Request: ', $request->all());

            $validated = $request->validate([
                'username' => 'required|string|unique:users,username',
                'password' => 'required|string|min:6',
            ]);

            $schema = DB::getSchemaBuilder();
            $hasIsAdmin = $schema->hasColumn('users', 'is_admin');
            $hasIsVerify = $schema->hasColumn('users', 'is_verify');

            $userData = [
                'username' => $validated['username'],
                'password' => Hash::make($validated['password']),
            ];

            if ($hasIsAdmin) $userData['is_admin'] = 0;
            if ($hasIsVerify) $userData['is_verify'] = 1;

            $userId = DB::table('users')->insertGetId($userData);

            cache()->forget('inkubator_users_list');

            return response()->json([
                'success' => true,
                'message' => 'User inkubator berhasil ditambahkan',
                'id' => $userId
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Inkubator Validation Error: ', $e->errors());
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Inkubator Store Error: ' . $e->getMessage());
            \Log::error('Stack Trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan user inkubator: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show - Get single inkubator user
     */
    public function inkubatorShow($id)
    {
        try {
            $user = User::findOrFail($id);

            if ((int)($user->is_admin ?? 0) === 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'User bukan inkubator'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $user->id,
                    'username' => $user->username,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Update - Update inkubator user
     */
    public function inkubatorUpdate(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            if ((int)($user->is_admin ?? 0) === 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'User bukan inkubator'
                ], 404);
            }

            $validated = $request->validate([
                'username' => 'required|string|unique:users,username,' . $id,
                'password' => 'nullable|string|min:6',
            ]);

            $userData = [
                'username' => $validated['username'],
            ];

            if (!empty($validated['password'])) {
                $userData['password'] = Hash::make($validated['password']);
            }

            $updated = DB::table('users')->where('id', $id)->update($userData);

            cache()->forget('inkubator_users_list');

            return response()->json([
                'success' => true,
                'message' => 'User inkubator berhasil diupdate',
                'updated' => $updated
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Inkubator Update Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate user inkubator: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Destroy - Delete inkubator user
     */
    public function inkubatorDestroy($id)
    {
        try {
            $user = User::findOrFail($id);

            if ((int)($user->is_admin ?? 0) === 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'User bukan inkubator'
                ], 404);
            }

            if ($user->id == auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menghapus akun yang sedang digunakan'
                ], 400);
            }

            $deleted = DB::table('users')->where('id', $id)->delete();

            cache()->forget('inkubator_users_list');

            return response()->json([
                'success' => true,
                'message' => 'User inkubator berhasil dihapus',
                'deleted' => $deleted
            ]);
        } catch (\Exception $e) {
            \Log::error('Inkubator Destroy Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus user inkubator: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export Inkubator users data to CSV/XLSX
     */
    public function inkubatorExport(Request $request, $format = 'csv')
    {
        try {
            $users = DB::table('users')
                ->select('id', 'username', 'password', 'is_admin', 'is_verify', 'created_at', 'updated_at')
                ->where('is_admin', 0)
                ->orderBy('id', 'asc')
                ->get();

            $filename = 'inkubator_users_' . date('Y-m-d_His');

            if ($format === 'xlsx') {
                return Excel::download(new AdminExport($users), $filename . '.xlsx');
            }

            return Excel::download(new AdminExport($users), $filename . '.csv', \Maatwebsite\Excel\Excel::CSV);
        } catch (\Exception $e) {
            \Log::error('Inkubator Export Error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Gagal mengekspor data: ' . $e->getMessage()]);
        }
    }

    /**
     * Menampilkan daftar Lembaga Inkubator (Role User)
     */
    public function lembagaInkubatorIndex(Request $request)
    {
        try {
            $inkubators = cache()->remember('lembaga_inkubator_list', 10, function () {
                try {
                    $inkubators = DB::table('inkubator')
                        ->join('users', function ($join) {
                            $join->on('users.id', '=', 'inkubator.user_id')
                                ->where('users.is_admin', 0);
                        })
                        ->select(
                            'inkubator.id',
                            'inkubator.no_tanda_daftar',
                            'inkubator.jenis_inkubator',
                            'inkubator.nama_inkubator',
                            'inkubator.induk_inkubator',
                            'inkubator.nama_pimpinan',
                            'inkubator.no_kontak',
                            'inkubator.email',
                            'inkubator.alamat_kantor',
                            'inkubator.path_kantor',
                            'inkubator.path_ruang_usaha',
                            'inkubator.path_ruang_rapat',
                            'inkubator.path_ruang_pelatihan',
                            'inkubator.path_ruang_komunikasi',
                            'inkubator.path_legalitas',
                            'inkubator.path_spesialisasi_inkubasi',
                            'inkubator.path_model_inkubasi',
                            'inkubator.path_rencana_strategis',
                            'inkubator.pemeringkatan_rank',
                            'users.is_verify'
                        )
                        ->orderBy('inkubator.id', 'asc')
                        ->get();

                    if ($inkubators->isNotEmpty()) {
                        $pemeringkatanMap = DB::table('pemeringkatan')
                            ->select('inkubator_id', 'grade', 'status')
                            ->whereIn('inkubator_id', $inkubators->pluck('id'))
                            ->orderBy('tanggal_sk', 'desc')
                            ->orderBy('id', 'desc')
                            ->get()
                            ->groupBy('inkubator_id')
                            ->map(function ($items) {
                                return $items->first();
                            });

                        return $inkubators->map(function ($inkubator) use ($pemeringkatanMap) {
                            $pemeringkatan = $pemeringkatanMap->get($inkubator->id);
                            $inkubator->peringkat = $pemeringkatan->grade ?? null;
                            $inkubator->pemeringkatan_status = $pemeringkatan->status ?? null;
                            return $inkubator;
                        });
                    }

                    return $inkubators;
                } catch (\Exception $e) {
                    \Log::error('Lembaga Inkubator List Error: ' . $e->getMessage());
                    \Log::error('Stack Trace: ' . $e->getTraceAsString());
                    return collect();
                }
            });

            \Log::info('Total inkubators retrieved: ' . $inkubators->count());
            return view('role-user.lembaga-inkubator.index', compact('inkubators'));
        } catch (\Exception $e) {
            \Log::error('RoleUser Lembaga Inkubator Index Error: ' . $e->getMessage());
            return view('role-user.lembaga-inkubator.index', ['inkubators' => collect()]);
        }
    }

    /**
     * Approve verifikasi inkubator
     */
    public function approveInkubator($id)
    {
        try {
            $inkubator = DB::table('inkubator')->where('id', $id)->first();
            if (!$inkubator) {
                return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
            }
    
            $user = User::find($inkubator->user_id);
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'User terkait tidak ditemukan'], 404);
            }
    
            // Generate password HANYA sekali
            if ($user->is_verify != 2) {
                $plainPassword = Str::random(8);
                $user->password = Hash::make($plainPassword);
            } else {
                $plainPassword = null;
            }
    
            $user->is_verify = 2;
            $user->save();
    
            // Kirim email hanya jika password baru dibuat
            if (!empty($inkubator->email) && $plainPassword) {
                Mail::send('emails.inkubator_approved', [
                    'inkubator' => $inkubator,
                    'username'  => $user->username,
                    'password'  => $plainPassword
                ], function ($message) use ($inkubator) {
                    $message->to($inkubator->email)
                            ->subject('✔ Akun SIPENSI Aktif');
                });
            }
    
            cache()->forget('lembaga_inkubator_list');
    
            return response()->json([
                'success' => true,
                'message' => 'Inkubator berhasil di-ACC'
            ]);
        } catch (\Exception $e) {
            \Log::error('Approve Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server'
            ], 500);
        }
    }
    
    /**
     * Download sertifikat inkubator (PDF)
     */
    public function downloadSertifikat($id)
    {
        try {
            \Log::info('Download Sertifikat Request - ID: ' . $id);

            $inkubator = DB::table('inkubator')
                ->join('users', 'users.id', '=', 'inkubator.user_id')
                ->select('inkubator.*', 'users.is_verify')
                ->where('inkubator.id', $id)
                ->first();

            if (!$inkubator) {
                \Log::warning('Inkubator not found - ID: ' . $id);
                return redirect()->back()->with('error', 'Data inkubator tidak ditemukan');
            }

            $isVerified = ($inkubator->is_verify == 1 || $inkubator->is_verify == 2);
            $isLegalComplete = $this->checkLegalComplete($inkubator);

            \Log::info('Verification Status - Verified: ' . ($isVerified ? 'Yes' : 'No') . ', Legal Complete: ' . ($isLegalComplete ? 'Yes' : 'No'));

            if (!$isVerified || !$isLegalComplete) {
                return redirect()->back()->with('error', 'Sertifikat hanya dapat diunduh jika status terverifikasi dan dokumen legal lengkap');
            }

            $fileName = 'Sertifikat_SIPENSI-' . str_replace(' ', '_', $inkubator->nama_inkubator ?? 'Inkubator') . '.pdf';

            $data = [
                'inkubator' => $inkubator,
                'no_tanda_daftar' => $inkubator->no_tanda_daftar ?? '-',
                'nama_inkubator' => $inkubator->nama_inkubator ?? '-',
                'alamat' => $inkubator->alamat_kantor ?? '-',
                'tanggal' => $inkubator->created_at ?? now(),
                'nama_penandatangan' => 'Irwansyah Putra, S.STP., M.Si.',
                'nip' => '19800814 200003 1001',
            ];

            try {
                $qrCodeUrl = route('inkubators.detail', $inkubator->id);
                $qrCodeBase64 = DNS2D::getBarcodePNG($qrCodeUrl, 'QRCODE');
                $data['qr_code_base64'] = $qrCodeBase64;
            } catch (\Exception $e) {
                \Log::warning('QR Code generation failed: ' . $e->getMessage());
                $data['qr_code_base64'] = '';
            }

            try {
                $html = view('role-user.lembaga-inkubator.sertifikat', $data)->render();
                \Log::info('View rendered OK. HTML length: ' . strlen($html));
            } catch (\Throwable $e) {
                \Log::error('View render error: ' . $e->getMessage());
                \Log::error($e->getTraceAsString());
                return redirect()->back()->with('error', 'Template sertifikat error: ' . $e->getMessage());
            }

            $pdf = Pdf::loadView('role-user.lembaga-inkubator.sertifikat', $data)
                ->setPaper('a4', 'landscape')
                ->setOptions([
                    'isRemoteEnabled' => true,
                    'enable-local-file-access' => true,
                    'chroot' => public_path(),
                    'isHtml5ParserEnabled' => true,
                    'defaultFont' => 'Arial',
                    'isPhpEnabled' => true,
                ]);

            return $pdf->download($fileName);
        } catch (\Throwable $e) {
            \Log::error('Download Sertifikat Error: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            return redirect()->back()->with('error', 'Gagal mengunduh sertifikat: ' . $e->getMessage());
        }
    }

    /**
     * Check apakah semua dokumen legal sudah lengkap
     */
    private function checkLegalComplete($inkubator)
    {
        $requiredPaths = [
            'path_kantor',
            'path_ruang_usaha',
            'path_ruang_rapat',
            'path_ruang_pelatihan',
            'path_ruang_komunikasi',
            'path_legalitas',
            'path_spesialisasi_inkubasi',
            'path_model_inkubasi',
            'path_rencana_strategis'
        ];

        foreach ($requiredPaths as $path) {
            $value = $inkubator->$path ?? null;

            if (empty($value)) {
                \Log::info("Legal check failed - Missing: {$path}");
                return false;
            }

            if (is_string($value) && strpos($value, '[') === 0) {
                $paths = json_decode($value, true);
                if (!is_array($paths) || empty($paths)) {
                    \Log::info("Legal check failed - Empty array: {$path}");
                    return false;
                }
            }
        }

        \Log::info("Legal check passed - All documents complete");
        return true;
    }

    /**
     * Show detail lembaga inkubator
     */
    public function lembagaInkubatorShow($id)
    {
        try {
            $inkubator = DB::table('inkubator')
                ->join('users', 'users.id', '=', 'inkubator.user_id')
                ->leftJoin('provinsi', 'provinsi.kode_provinsi', '=', 'inkubator.kode_provinsi')
                ->select(
                    'inkubator.*',
                    'users.username',
                    'users.is_verify',
                    'provinsi.name as nama_provinsi'
                )
                ->where('inkubator.id', $id)
                ->first();

            if (!$inkubator) {
                return redirect()->route('lembaga-inkubator.index')->with('error', 'Data inkubator tidak ditemukan');
            }

            $jenisMap = [
                1 => 'Pemerintah Pusat',
                2 => 'Pemerintah Daerah',
                3 => 'Lembaga Pendidikan',
                4 => 'Badan Usaha',
                5 => 'Masyarakat'
            ];

            return view('role-user.lembaga-inkubator.show', compact('inkubator', 'jenisMap'));
        } catch (\Exception $e) {
            \Log::error('Lembaga Inkubator Show Error: ' . $e->getMessage());
            return redirect()->route('lembaga-inkubator.index')->with('error', 'Gagal memuat data inkubator');
        }
    }

    /**
     * Delete lembaga inkubator
     */
    public function lembagaInkubatorDestroy($id)
    {
        try {
            $inkubator = DB::table('inkubator')->where('id', $id)->first();

            if (!$inkubator) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data inkubator tidak ditemukan'
                ], 404);
            }

            $deleted = DB::table('inkubator')->where('id', $id)->delete();

            if ($deleted) {
                cache()->forget('lembaga_inkubator_list');
                return response()->json([
                    'success' => true,
                    'message' => 'Data inkubator berhasil dihapus'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data inkubator'
            ], 500);
        } catch (\Exception $e) {
            \Log::error('Lembaga Inkubator Destroy Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data inkubator: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper untuk mendapatkan path file
     */
    private function getFilePath($path)
    {
        $cleanPath = ltrim(str_replace('public/', '', $path), '/');

        $possiblePaths = [
            public_path('file_legalitas/' . basename($cleanPath)),
            public_path('storage/file_legalitas/' . basename($cleanPath)),
            storage_path('app/public/file_legalitas/' . basename($cleanPath)),
            storage_path('app/file_legalitas/' . basename($cleanPath)),
            public_path($cleanPath),
            storage_path('app/public/' . basename($cleanPath)),
            storage_path('app/' . basename($cleanPath)),
        ];

        foreach ($possiblePaths as $possiblePath) {
            if (file_exists($possiblePath)) {
                \Log::info('File found at: ' . $possiblePath);
                return $possiblePath;
            }
        }

        \Log::warning('File not found for path: ' . $path);
        return $possiblePaths[0];
    }

    /**
     * Export Admin data to CSV/XLSX
     */
    public function adminExport(Request $request, $format = 'csv')
    {
        try {
            $users = DB::table('users')
                ->select('id', 'username', 'password', 'is_admin', 'is_verify', 'created_at', 'updated_at')
                ->orderBy('id', 'asc')
                ->get();

            $filename = 'admin_' . date('Y-m-d_His');

            if ($format === 'xlsx') {
                return Excel::download(new AdminExport($users), $filename . '.xlsx');
            }

            return Excel::download(new AdminExport($users), $filename . '.csv', \Maatwebsite\Excel\Excel::CSV);
        } catch (\Exception $e) {
            \Log::error('Admin Export Error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Gagal mengekspor data: ' . $e->getMessage()]);
        }
    }

    /**
     * Export Lembaga Inkubator data to CSV/XLSX
     */
    public function lembagaInkubatorExport(Request $request, $format = 'csv')
    {
        try {
            $inkubators = DB::table('inkubator')
                ->join('provinsi', 'inkubator.provinsi_id', '=', 'provinsi.kode_provinsi')
                ->leftJoin('pemeringkatan as p', function ($join) {
                    $join->on('p.id', '=', DB::raw('(
                        SELECT p2.id
                        FROM pemeringkatan p2
                        WHERE p2.inkubator_id = inkubator.id
                        ORDER BY p2.tanggal_sk DESC, p2.id DESC
                        LIMIT 1
                    )'));
                })
                ->leftJoin('users as u', 'inkubator.user_id', '=', 'u.id')
                ->select(
                    'inkubator.id as Nomor',
                    'inkubator.user_id as Account',
                    'inkubator.no_tanda_daftar as Tanda_Daftar',
                    'inkubator.jenis_inkubator as Jenis_Lembaga_Inkubator',
                    'inkubator.nama_inkubator as Nama_Lembaga_Inkubator',
                    'inkubator.induk_inkubator as Lembaga_Induk_Inkubator',
                    'inkubator.nama_pimpinan as Nama_Ketua_Lembaga_Inkubator',
                    'inkubator.no_kontak as No_Kontak',
                    'inkubator.email as Email',
                    'p.grade as Peringkat',
                    'inkubator.alamat_kantor as Alamat_Kantor',
                    'inkubator.created_at as Tanggal_Daftar',
                    'inkubator.updated_at as Tanggal_Update',
                    'provinsi.name as Provinsi',
                    'p.tanggal_sk as Tanggal_SK_Terbit',
                    'u.username',
                    'u.password',
                    'u.is_verify'
                )
                ->whereNotNull('inkubator.no_tanda_daftar')
                ->orderBy('inkubator.id', 'asc')
                ->get();

            $filename = 'lembaga_inkubator_' . date('Y-m-d_His');

            if ($format === 'xlsx') {
                return Excel::download(new LembagaInkubatorExport($inkubators), $filename . '.xlsx');
            }

            return Excel::download(new LembagaInkubatorExport($inkubators), $filename . '.csv', \Maatwebsite\Excel\Excel::CSV);
        } catch (\Exception $e) {
            \Log::error('Lembaga Inkubator Export Error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Gagal mengekspor data']);
        }
    }

    /**
     * Export to CSV
     */
    private function exportCSV($data, $type, $columnHeaders)
    {
        $filename = $type . '_' . date('Y-m-d_His') . '.csv';

        $responseHeaders = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function () use ($data, $columnHeaders, $type) {
            $file = fopen('php://output', 'w');

            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, $columnHeaders);

            $index = 1;
            foreach ($data as $row) {
                if ($type === 'admin') {
                    fputcsv($file, [
                        $index++,
                        $row->username ?? '',
                        '***********',
                        $row->is_admin ?? 0,
                        $row->is_verify ?? 0,
                        $row->created_at ?? '',
                        $row->updated_at ?? ''
                    ]);
                } else {
                    fputcsv($file, [
                        $row->Nomor ?? $index++,
                        $row->Account ?? '',
                        $row->Tanda_Daftar ?? '',
                        $row->Jenis_Lembaga_Inkubator ?? '',
                        $row->Nama_Lembaga_Inkubator ?? '',
                        $row->Lembaga_Induk_Inkubator ?? '',
                        $row->Nama_Ketua_Lembaga_Inkubator ?? '',
                        $row->No_Kontak ?? '',
                        $row->Email ?? '',
                        $row->Peringkat ?? '',
                        $row->Alamat_Kantor ?? '',
                        $row->Tanggal_Daftar ? date('d/m/Y', strtotime($row->Tanggal_Daftar)) : '',
                        $row->Tanggal_Update ? date('d/m/Y', strtotime($row->Tanggal_Update)) : '',
                        $row->Provinsi ?? '',
                        $row->Tanggal_SK_Terbit ? date('d/m/Y', strtotime($row->Tanggal_SK_Terbit)) : '',
                        $row->username ?? '',
                        ($row->is_verify == 1 || $row->is_verify == 2) ? 'Terverifikasi' : 'Belum Terverifikasi'
                    ]);
                }
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $responseHeaders);
    }

    /**
     * Export to XLSX (using XML SpreadsheetML format)
     */
    private function exportXLSX($data, $type, $columnHeaders)
    {
        $filename = $type . '_' . date('Y-m-d_His') . '.xlsx';

        $xml = '<?xml version="1.0"?>' . "\n";
        $xml .= '<?mso-application progid="Excel.Sheet"?>' . "\n";
        $xml .= '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"' . "\n";
        $xml .= ' xmlns:o="urn:schemas-microsoft-com:office:office"' . "\n";
        $xml .= ' xmlns:x="urn:schemas-microsoft-com:office:excel"' . "\n";
        $xml .= ' xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"' . "\n";
        $xml .= ' xmlns:html="http://www.w3.org/TR/REC-html40">' . "\n";
        $xml .= '<Worksheet ss:Name="Sheet1">' . "\n";
        $xml .= '<Table>' . "\n";

        $xml .= '<Row>' . "\n";
        foreach ($columnHeaders as $header) {
            $xml .= '<Cell><Data ss:Type="String">' . htmlspecialchars($header, ENT_XML1, 'UTF-8') . '</Data></Cell>' . "\n";
        }
        $xml .= '</Row>' . "\n";

        $index = 1;
        foreach ($data as $row) {
            $xml .= '<Row>' . "\n";

            if ($type === 'admin') {
                $rowData = [
                    $index++,
                    $row->username ?? '',
                    '***********',
                    $row->is_admin ?? 0,
                    $row->is_verify ?? 0,
                    $row->created_at ?? '',
                    $row->updated_at ?? ''
                ];
            } else {
                $rowData = [
                    $row->Nomor ?? $index++,
                    $row->Account ?? '',
                    $row->Tanda_Daftar ?? '',
                    $row->Jenis_Lembaga_Inkubator ?? '',
                    $row->Nama_Lembaga_Inkubator ?? '',
                    $row->Lembaga_Induk_Inkubator ?? '',
                    $row->Nama_Ketua_Lembaga_Inkubator ?? '',
                    $row->No_Kontak ?? '',
                    $row->Email ?? '',
                    $row->Peringkat ?? '',
                    $row->Alamat_Kantor ?? '',
                    $row->Tanggal_Daftar ? date('d/m/Y', strtotime($row->Tanggal_Daftar)) : '',
                    $row->Tanggal_Update ? date('d/m/Y', strtotime($row->Tanggal_Update)) : '',
                    $row->Provinsi ?? '',
                    $row->Tanggal_SK_Terbit ? date('d/m/Y', strtotime($row->Tanggal_SK_Terbit)) : '',
                    $row->username ?? '',
                    ($row->is_verify == 1 || $row->is_verify == 2) ? 'Terverifikasi' : 'Belum Terverifikasi'
                ];
            }

            foreach ($rowData as $cellValue) {
                if (is_numeric($cellValue) && !is_string($cellValue)) {
                    $xml .= '<Cell><Data ss:Type="Number">' . htmlspecialchars($cellValue, ENT_XML1, 'UTF-8') . '</Data></Cell>' . "\n";
                } else {
                    $xml .= '<Cell><Data ss:Type="String">' . htmlspecialchars((string)$cellValue, ENT_XML1, 'UTF-8') . '</Data></Cell>' . "\n";
                }
            }

            $xml .= '</Row>' . "\n";
        }

        $xml .= '</Table>' . "\n";
        $xml .= '</Worksheet>' . "\n";
        $xml .= '</Workbook>';

        return response($xml, 200, [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'max-age=0'
        ]);
    }
}
