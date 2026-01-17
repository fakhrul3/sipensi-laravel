<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;
use App\Models\User;
use App\Exports\AdminExport;
use App\Exports\LembagaInkubatorExport;
use Maatwebsite\Excel\Facades\Excel;

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
     */
    public function lembagaInkubatorIndex(Request $request)
    {
        try {
            // Query sesuai dengan SQL yang diberikan user
            $inkubators = cache()->remember('lembaga_inkubator_list', 10, function () {
                try {
                    return DB::table('inkubator')
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
                            'inkubator.path_legalitas',
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
