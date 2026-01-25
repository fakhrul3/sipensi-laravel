<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Inkubator;
use App\Models\Tenant;
use App\Models\Pendanaan;

class DashboardController extends Controller
{
    /**
     * Format angka menjadi format Rupiah Indonesia
     */
    public function convertRupiah($angka)
    {
        if (empty($angka) || $angka == 0) {
            return '0';
        }
        
        $rupiah = number_format($angka, 0, ',', '.');
        return $rupiah;
    }

    public function index()
    {
        try {
            // Hanya load statistik cards (cached, cepat)
            // Tabel top 10 akan di-load via AJAX setelah page ready
            
            // 1. Jumlah Lembaga Inkubator (COUNT) - Cached
            $totalInkubator = cache()->remember('dashboard_total_inkubator', 300, function () {
                try {
                    return Inkubator::join('users', function($join) {
                        $join->on('users.id', '=', 'inkubator.user_id')
                             ->where('users.is_admin', 0)
                             ->where('users.is_verify', 2);
                    })->count();
                } catch (\Exception $e) {
                    \Log::error('Count Inkubator Error: ' . $e->getMessage());
                    return 0;
                }
            });

            // 2. Jumlah Tenant (COUNT) - Cached
            $totalTenant = cache()->remember('dashboard_total_tenant', 300, function () {
                try {
                    return Tenant::count();
                } catch (\Exception $e) {
                    return 0;
                }
            });

            // 3. Total Pendanaan Saat Ini (SUM) - Cached
            $totalPendanaan = cache()->remember('dashboard_total_pendanaan', 300, function () {
                try {
                    $schema = DB::getSchemaBuilder();
                    
                    if ($schema->hasTable('pendanaan')) {
                        $result = DB::table('pendanaan')
                            ->select(DB::raw('COALESCE(SUM(nilai), 0) as total'))
                            ->first();
                        return $result->total ?? 0;
                    }
                    
                    $columns = $schema->getColumnListing('tenant');
                    if (in_array('pendanaan', $columns)) {
                        $result = DB::table('tenant')
                            ->select(DB::raw('COALESCE(SUM(pendanaan), 0) as total'))
                            ->first();
                        return $result->total ?? 0;
                    } elseif (in_array('nilai', $columns)) {
                        $result = DB::table('tenant')
                            ->select(DB::raw('COALESCE(SUM(nilai), 0) as total'))
                            ->first();
                        return $result->total ?? 0;
                    }
                    
                    return 0;
                } catch (\Exception $e) {
                    return 0;
                }
            });

            // Breadcrumb
            $breadcumb = [
                ['label' => 'Home', 'url' => route('dashboard')],
                ['label' => 'Dashboard', 'url' => null]
            ];

            // Jangan load inkubatortopten dan tenanttopten di sini, load via AJAX
            return view('dashboard-admin', compact(
                'totalInkubator',
                'totalTenant',
                'totalPendanaan',
                'breadcumb'
            ));
        } catch (\Exception $e) {
            \Log::error('Dashboard Error: ' . $e->getMessage());
            return view('dashboard-admin', [
                'totalInkubator' => 0,
                'totalTenant' => 0,
                'totalPendanaan' => 0,
                'breadcumb' => [
                    ['label' => 'Home', 'url' => route('dashboard')],
                    ['label' => 'Dashboard', 'url' => null]
                ]
            ]);
        }
    }

    /**
     * AJAX endpoint untuk load top 10 inkubator
     */
    public function getTopInkubator(Request $request)
    {
        try {
            $inkubatortopten = cache()->remember('dashboard_inkubatortopten', 300, function () {
                try {
                    return Inkubator::join('users', function($join) {
                        $join->on('users.id', '=', 'inkubator.user_id')
                            ->where('users.is_admin', 0)
                            ->where('users.is_verify', 2);
                    })
                    ->withCount(['tenant'])
                    ->orderBy('tenant_count', 'desc')
                    ->limit(10)
                    ->get()
                    ->map(function($item) {
                        return [
                            'id' => $item->id,
                            'nama_inkubator' => $item->nama_inkubator,
                            'tenant_count' => $item->tenant_count ?? 0
                        ];
                    });
                } catch (\Exception $e) {
                    \Log::error('Top Inkubator Error: ' . $e->getMessage());
                    return collect();
                }
            });

            return response()->json([
                'success' => true,
                'data' => $inkubatortopten->values()
            ]);
        } catch (\Exception $e) {
            \Log::error('Get Top Inkubator Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Gagal memuat data'
            ], 500);
        }
    }

    /**
     * AJAX endpoint untuk load top 10 tenant
     */
    public function getTopTenant(Request $request)
    {
        try {
            $tenanttopten = cache()->remember('dashboard_tenanttopten', 300, function () {
                try {
                    $schema = DB::getSchemaBuilder();
                    
                    if ($schema->hasTable('pendanaan')) {
                        try {
                            return Tenant::withSum('pendanaan', 'nilai')
                                ->join('pendanaan', 'pendanaan.tenant_id', '=', 'tenant.id')
                                ->orderBy('pendanaan_sum_nilai', 'desc')
                                ->limit(10)
                                ->groupBy('tenant.id')
                                ->get()
                                ->map(function($item) {
                                    return [
                                        'id' => $item->id,
                                        'nama_usaha' => $item->nama_usaha ?? null,
                                        'nama_tenant' => $item->nama_tenant ?? null,
                                        'pendanaan_sum_nilai' => $item->pendanaan_sum_nilai ?? 0
                                    ];
                                });
                        } catch (\Exception $e) {
                            \Log::error('Top Tenant Pendanaan Error: ' . $e->getMessage());
                            return collect();
                        }
                    }
                    
                    return collect();
                } catch (\Exception $e) {
                    \Log::error('Top Tenant Pendanaan Error: ' . $e->getMessage());
                    return collect();
                }
            });

            return response()->json([
                'success' => true,
                'data' => $tenanttopten->values()
            ]);
        } catch (\Exception $e) {
            \Log::error('Get Top Tenant Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Gagal memuat data'
            ], 500);
        }
    }
}
