<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Response as ResponseFacade;
use App\Models\User;
use App\Exports\AdminExport;
use App\Exports\LembagaInkubatorExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class RoleUserController extends Controller
{
    /**
     * Menampilkan daftar Admin
     */
    public function adminIndex(Request $request)
    {
        try {
            // Tampilkan SEMUA data dari table users
            $users = cache()->remember('admin_users_list', 10, function () {
                try {
                    // Ambil semua users tanpa filter
                    return DB::table('users')
                        ->select('id', 'username', 'password', 'is_admin', 'is_verify', 'created_at', 'updated_at')
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

            // Cek kolom is_admin
            $schema = DB::getSchemaBuilder();
            $hasIsAdmin = $schema->hasColumn('users', 'is_admin');
            $hasIsVerify = $schema->hasColumn('users', 'is_verify');

            $userData = [
                'username' => $validated['username'],
                'password' => Hash::make($validated['password']),
            ];

            if ($hasIsAdmin) {
                $userData['is_admin'] = 1;
            }
            if ($hasIsVerify) {
                $userData['is_verify'] = 1;
            }

            \Log::info('User Data to Create: ', $userData);

            // Gunakan DB::table untuk memastikan data tersimpan
            $userId = DB::table('users')->insertGetId($userData);
            
            \Log::info('Admin created with ID: ' . $userId);

            // Clear cache
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

            // Update password jika diisi
            if (!empty($validated['password'])) {
                $userData['password'] = Hash::make($validated['password']);
            }

            \Log::info('User Data to Update (ID: ' . $id . '): ', $userData);

            // Gunakan DB::table untuk memastikan update ter-eksekusi
            $updated = DB::table('users')->where('id', $id)->update($userData);
            
            \Log::info('Admin updated (ID: ' . $id . '), rows affected: ' . $updated);

            // Clear cache
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
            
            // Jangan hapus user yang sedang login
            if ($user->id == auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menghapus akun yang sedang digunakan'
                ], 400);
            }

            \Log::info('Deleting admin ID: ' . $id);

            // Gunakan DB::table untuk memastikan delete ter-eksekusi
            $deleted = DB::table('users')->where('id', $id)->delete();
            
            \Log::info('Admin deleted (ID: ' . $id . '), rows affected: ' . $deleted);

            // Clear cache
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
     * Menampilkan daftar Lembaga Inkubator (Role User)
     * Query disesuaikan dengan web asli: join users dengan filter is_admin=0 dan is_verify=2
     */
    public function lembagaInkubatorIndex(Request $request)
    {
        try {
            // Query sesuai dengan web asli (HomeController.php)
            // Join users dengan filter is_admin=0 (tampilkan semua, termasuk yang belum terverifikasi)
            $inkubators = cache()->remember('lembaga_inkubator_list', 10, function () {
                try {
                    // Query sesuai dengan HomeController.php web asli
                    // Hapus filter is_verify=2 untuk menampilkan semua inkubator
                    $inkubators = DB::table('inkubator')
                        ->join('users', function($join) {
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

                    // Ambil data pemeringkatan terbaru untuk setiap inkubator
                    if ($inkubators->isNotEmpty()) {
                        $pemeringkatanMap = DB::table('pemeringkatan')
                            ->select('inkubator_id', 'grade', 'status')
                            ->whereIn('inkubator_id', $inkubators->pluck('id'))
                            ->orderBy('tanggal_sk', 'desc')
                            ->orderBy('id', 'desc')
                            ->get()
                            ->groupBy('inkubator_id')
                            ->map(function($items) {
                                return $items->first(); // Ambil yang terbaru
                            });

                        // Gabungkan data pemeringkatan ke inkubator
                        return $inkubators->map(function($inkubator) use ($pemeringkatanMap) {
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
                return response()->json([
                    'success' => false,
                    'message' => 'Data inkubator tidak ditemukan'
                ], 404);
            }

            // Update is_verify di tabel users menjadi 2 (terverifikasi)
            $user = DB::table('users')->where('id', $inkubator->user_id)->first();
            if ($user) {
                DB::table('users')
                    ->where('id', $inkubator->user_id)
                    ->update(['is_verify' => 2]);
            }

            // Clear cache
            cache()->forget('lembaga_inkubator_list');

            return response()->json([
                'success' => true,
                'message' => 'Inkubator berhasil diverifikasi'
            ]);
        } catch (\Exception $e) {
            \Log::error('Approve Inkubator Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memverifikasi inkubator: ' . $e->getMessage()
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

            // Cek apakah sudah terverifikasi dan legal dokumen lengkap
            $isVerified = ($inkubator->is_verify == 1 || $inkubator->is_verify == 2);
            $isLegalComplete = $this->checkLegalComplete($inkubator);

            \Log::info('Verification Status - Verified: ' . ($isVerified ? 'Yes' : 'No') . ', Legal Complete: ' . ($isLegalComplete ? 'Yes' : 'No'));

            if (!$isVerified || !$isLegalComplete) {
                \Log::warning('Download blocked - Verified: ' . ($isVerified ? 'Yes' : 'No') . ', Legal Complete: ' . ($isLegalComplete ? 'Yes' : 'No'));
                return redirect()->back()->with('error', 'Sertifikat hanya dapat diunduh jika status terverifikasi dan dokumen legal lengkap');
            }

            // Generate nama file
            $fileName = 'Sertifikat_SIPENSI-' . str_replace(' ', '_', $inkubator->nama_inkubator ?? 'Inkubator') . '.pdf';
            
            \Log::info('Generating PDF - File: ' . $fileName);
            
            // Generate PDF menggunakan dompdf
            \Log::info('Loading view for PDF generation');
            
            // Test render view terlebih dahulu untuk memastikan tidak ada error
            try {
                $html = view('role-user.lembaga-inkubator.sertifikat', compact('inkubator'))->render();
                \Log::info('View rendered successfully, HTML length: ' . strlen($html));
            } catch (\Exception $viewError) {
                \Log::error('View render error: ' . $viewError->getMessage());
                \Log::error('View Stack Trace: ' . $viewError->getTraceAsString());
                return redirect()->back()->with('error', 'Gagal memuat template sertifikat: ' . $viewError->getMessage());
            }
            
            // Generate PDF
            try {
                $pdf = Pdf::loadView('role-user.lembaga-inkubator.sertifikat', compact('inkubator'));
                $pdf->setPaper('a4', 'landscape');
                
                // Konfigurasi dompdf untuk handle gambar dengan benar
                $pdf->setOption('enable-local-file-access', true);
                $pdf->setOption('isHtml5ParserEnabled', true);
                $pdf->setOption('isRemoteEnabled', true);
                $pdf->setOption('chroot', public_path());
                $pdf->setOption('defaultFont', 'Arial');
                $pdf->setOption('isPhpEnabled', true);
                $pdf->setOption('debugKeepTemp', false);
                
                \Log::info('PDF generated successfully, returning download response');
                
                // Gunakan method download() untuk memastikan response PDF yang benar
                return $pdf->download($fileName);
            } catch (\Exception $pdfError) {
                \Log::error('PDF Generation Error: ' . $pdfError->getMessage());
                \Log::error('PDF Stack Trace: ' . $pdfError->getTraceAsString());
                
                // Cek apakah error terkait dengan gambar
                if (strpos($pdfError->getMessage(), 'image') !== false || strpos($pdfError->getMessage(), 'Image') !== false) {
                    return redirect()->back()->with('error', 'Gagal menghasilkan PDF: File gambar tidak ditemukan atau tidak dapat dibaca. Pastikan file gambar background dan TTE ada di folder public/img/sertifikat/ atau public/assets/images/');
                }
                
                return redirect()->back()->with('error', 'Gagal menghasilkan PDF: ' . $pdfError->getMessage());
            }
        } catch (\Exception $e) {
            \Log::error('Download Sertifikat Error: ' . $e->getMessage());
            \Log::error('Stack Trace: ' . $e->getTraceAsString());
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
            
            // Cek jika null atau empty
            if (empty($value)) {
                \Log::info("Legal check failed - Missing: {$path}");
                return false;
            }

            // Jika JSON array, cek apakah ada isi
            if (strpos($value, '[') === 0) {
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

            // Mapping jenis inkubator
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

            // Hapus data inkubator
            $deleted = DB::table('inkubator')->where('id', $id)->delete();
            
            if ($deleted) {
                // Clear cache
                cache()->forget('lembaga_inkubator_list');
                
                return response()->json([
                    'success' => true,
                    'message' => 'Data inkubator berhasil dihapus'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus data inkubator'
                ], 500);
            }
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
        // Normalisasi path - hapus 'public/' jika ada
        $cleanPath = ltrim(str_replace('public/', '', $path), '/');
        
        // Cek berbagai kemungkinan lokasi file
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
        // Jika tidak ditemukan, return path pertama sebagai fallback
        return $possiblePaths[0];
    }

    /**
     * Export Admin data to CSV/XLSX
     */
    public function adminExport(Request $request, $format = 'csv')
    {
        try {
            // Ambil semua data admin
            $users = DB::table('users')
                ->select('id', 'username', 'password', 'is_admin', 'is_verify', 'created_at', 'updated_at')
                ->orderBy('id', 'asc')
                ->get();

            $filename = 'admin_' . date('Y-m-d_His');

            if ($format === 'xlsx') {
                return Excel::download(new AdminExport($users), $filename . '.xlsx');
            } else {
                return Excel::download(new AdminExport($users), $filename . '.csv', \Maatwebsite\Excel\Excel::CSV);
            }
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
            // Query sesuai dengan SQL yang diberikan user
            $inkubators = DB::table('inkubator')
                ->join('provinsi', 'inkubator.provinsi_id', '=', 'provinsi.kode_provinsi')
                ->leftJoin('pemeringkatan as p', function($join) {
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
            } else {
                return Excel::download(new LembagaInkubatorExport($inkubators), $filename . '.csv', \Maatwebsite\Excel\Excel::CSV);
            }
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

        $callback = function() use ($data, $columnHeaders, $type) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Write column headers
            fputcsv($file, $columnHeaders);

            // Write data
            $index = 1;
            foreach ($data as $row) {
                if ($type === 'admin') {
                    fputcsv($file, [
                        $index++,
                        $row->username ?? '',
                        '***********', // Password masked
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
        
        // Create XML SpreadsheetML content
        $xml = '<?xml version="1.0"?>' . "\n";
        $xml .= '<?mso-application progid="Excel.Sheet"?>' . "\n";
        $xml .= '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"' . "\n";
        $xml .= ' xmlns:o="urn:schemas-microsoft-com:office:office"' . "\n";
        $xml .= ' xmlns:x="urn:schemas-microsoft-com:office:excel"' . "\n";
        $xml .= ' xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"' . "\n";
        $xml .= ' xmlns:html="http://www.w3.org/TR/REC-html40">' . "\n";
        $xml .= '<Worksheet ss:Name="Sheet1">' . "\n";
        $xml .= '<Table>' . "\n";
        
        // Write headers
        $xml .= '<Row>' . "\n";
        foreach ($columnHeaders as $header) {
            $xml .= '<Cell><Data ss:Type="String">' . htmlspecialchars($header, ENT_XML1, 'UTF-8') . '</Data></Cell>' . "\n";
        }
        $xml .= '</Row>' . "\n";
        
        // Write data
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
                    $xml .= '<Cell><Data ss:Type="String">' . htmlspecialchars($cellValue, ENT_XML1, 'UTF-8') . '</Data></Cell>' . "\n";
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
