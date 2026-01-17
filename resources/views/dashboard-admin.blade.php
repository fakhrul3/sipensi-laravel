@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('breadcrumb')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
</ol>
@endsection

@push('styles')
    <style>
        /* ======= icon-card ======== */
        .icon-card {
            display: flex;
            align-items: center;
            background: #fff;
            padding: 30px 20px;
            border: 1px solid #e2e8f0;
            box-shadow: 0px 10px 20px rgba(200, 208, 216, 0.3);
            border-radius: 10px;
        }

        .icon-card.icon-card-3 {
            display: block;
            padding: 0px;
        }

        .icon-card.icon-card-3 .card-content {
            display: flex;
            padding: 20px;
            padding-bottom: 0;
        }

        @media only screen and (min-width: 1200px) and (max-width: 1399px) {
            .icon-card h6 {
                font-size: 15px;
            }
        }

        @media only screen and (min-width: 1200px) and (max-width: 1399px) {
            .icon-card h3 {
                font-size: 20px;
            }
        }

        .icon-card .icon {
            font-size: 40px;
            color: #007bff;
            margin-right: 20px;
        }

        .icon-card h6 {
            font-size: 14px;
            color: #6c757d;
            margin: 0;
            font-weight: 500;
        }

        .icon-card h3 {
            font-size: 28px;
            color: #2c3e50;
            margin: 0;
            font-weight: 700;
        }

        .icon-card h5 {
            font-size: 24px;
            color: #2c3e50;
            margin: 0;
            font-weight: 700;
        }

        .card {
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            box-shadow: 0px 10px 20px rgba(200, 208, 216, 0.3);
            background: #fff;
        }

        .card-header {
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            padding: 20px;
        }

        .card-title {
            font-size: 16px;
            font-weight: 700;
            color: #2c3e50;
            margin: 0;
        }

        .card-body {
            padding: 20px;
        }

        .table {
            margin: 0;
        }

        .table td {
            padding: 12px;
            vertical-align: middle;
            font-size: 14px;
            color: #495057;
        }

        .table td.text-end {
            font-weight: 600;
            color: #2c3e50;
        }
    </style>
@endpush

@section('content')
    <!-- CONTAINER -->
    <div class="container-fluid">

        <!-- Content Page -->
        <div class="row">
            <div class="col-xl-4 col-lg-4 col-sm-6">
                <div class="icon-card mb-30" style="background-color: #D6F4FA;">
                    <div class="icon purple">
                        <i class="lni lni-cart-full"></i>
                    </div>
                    <div class="content">
                        <h6 class="mb-10">Jumlah Lembaga Inkubator</h6>
                        <h3 class="text-bold mb-10">{{ number_format($totalInkubator ?? 0, 0, ',', '.') }}</h3>
                    </div>
                </div>
                <!-- End Icon Cart -->
            </div>
            <div class="col-xl-4 col-lg-4 col-sm-6">
                <div class="icon-card mb-30" style="background-color:#F5FBE0;">
                    <div class="icon purple">
                        <i class="lni lni-cart-full"></i>
                    </div>
                    <div class="content">
                        <h6 class="mb-10">Jumlah Tenant</h6>
                        <h3 class="text-bold mb-10">{{ number_format($totalTenant ?? 0, 0, ',', '.') }}</h3>
                    </div>
                </div>
                <!-- End Icon Cart -->
            </div>
            <div class="col-xl-4 col-lg-4 col-sm-6">
                <div class="icon-card mb-30" style="background-color:#FFE7BA;">
                    <div class="icon purple">
                        <i class="lni lni-cart-full"></i>
                    </div>
                    <div class="content">
                        <h6 class="mb-10">Total Pendanaan Saat Ini</h6>
                        @inject('convert', 'App\Http\Controllers\DashboardController')
                        <h5 class="text-bold mb-10">Rp <span
                                style="font-family:Roboto;font-size:27px;">{{ $convert->convertRupiah($totalPendanaan ?? 0) }}</span>
                        </h5>
                    </div>
                </div>
                <!-- End Icon Cart -->
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-sm-6 col-lg-6 col-md-6">
                <!-- Klasifikasi Usaha -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title fw-bold">10 Lembaga Inkubator dengan Tenant Terbanyak</h5>
                    </div>
                    <div class="card-body">
                        <div id="inkubatorTableLoading" class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2 text-muted">Memuat data...</p>
                        </div>
                        <table class="table" id="inkubatorTable" style="display: none;">
                            <tbody id="inkubatorTableBody">
                                <!-- Data akan di-load via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-6 col-md-6">
                <!-- Klasifikasi Usaha -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title fw-bold">10 Tenant Pendanaan Terbanyak</h5>
                    </div>
                    <div class="card-body">
                        <div id="tenantTableLoading" class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2 text-muted">Memuat data...</p>
                        </div>
                        <table class="table" id="tenantTable" style="display: none;">
                            <tbody id="tenantTableBody">
                                <!-- Data akan di-load via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Col -->
    </div>

    <!-- Content Page END -->
    </div>
    <!-- CONTAINER END -->
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(document).ready(function() {
    // Load tabel top 10 inkubator via AJAX setelah page ready
    function loadTopInkubator() {
        $.ajax({
            url: '{{ route("dashboard.top-inkubator") }}',
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success && response.data.length > 0) {
                    let html = '';
                    response.data.forEach(function(item) {
                        html += '<tr>';
                        html += '<td class="border border-top-0 border-end-0 border-start-0 border-bottom" width="600px">';
                        html += (item.nama_inkubator || '-');
                        html += '</td>';
                        html += '<td class="border border-top-0 border-end-0 border-start-0 border-bottom text-end">';
                        html += (item.tenant_count || 0);
                        html += '</td>';
                        html += '</tr>';
                    });
                    $('#inkubatorTableBody').html(html);
                    $('#inkubatorTableLoading').hide();
                    $('#inkubatorTable').show();
                } else {
                    $('#inkubatorTableBody').html('<tr><td colspan="2" class="text-center">Tidak ada data</td></tr>');
                    $('#inkubatorTableLoading').hide();
                    $('#inkubatorTable').show();
                }
            },
            error: function() {
                $('#inkubatorTableBody').html('<tr><td colspan="2" class="text-center text-danger">Gagal memuat data</td></tr>');
                $('#inkubatorTableLoading').hide();
                $('#inkubatorTable').show();
            }
        });
    }

    // Load tabel top 10 tenant via AJAX setelah page ready
    function loadTopTenant() {
        $.ajax({
            url: '{{ route("dashboard.top-tenant") }}',
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success && response.data.length > 0) {
                    let html = '';
                    response.data.forEach(function(item) {
                        const nama = item.nama_usaha || item.nama_tenant || '-';
                        const pendanaan = formatRupiah(item.pendanaan_sum_nilai || 0);
                        
                        html += '<tr>';
                        html += '<td class="border border-top-0 border-end-0 border-start-0 border-bottom" width="600px">';
                        html += nama;
                        html += '</td>';
                        html += '<td class="border border-top-0 border-end-0 border-start-0 border-bottom text-end">';
                        html += pendanaan;
                        html += '</td>';
                        html += '</tr>';
                    });
                    $('#tenantTableBody').html(html);
                    $('#tenantTableLoading').hide();
                    $('#tenantTable').show();
                } else {
                    $('#tenantTableBody').html('<tr><td colspan="2" class="text-center">Tidak ada data</td></tr>');
                    $('#tenantTableLoading').hide();
                    $('#tenantTable').show();
                }
            },
            error: function() {
                $('#tenantTableBody').html('<tr><td colspan="2" class="text-center text-danger">Gagal memuat data</td></tr>');
                $('#tenantTableLoading').hide();
                $('#tenantTable').show();
            }
        });
    }

    // Format rupiah
    function formatRupiah(angka) {
        if (!angka || angka == 0) {
            return '0';
        }
        return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    // Load data setelah page ready (delay sedikit untuk smooth UX)
    setTimeout(function() {
        loadTopInkubator();
        loadTopTenant();
    }, 300);
});
</script>
@endpush
