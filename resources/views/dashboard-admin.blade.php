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
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            padding: 35px 25px;
            border: 1px solid rgba(226, 232, 240, 0.6);
            box-shadow: 0px 4px 15px rgba(200, 208, 216, 0.15);
            border-radius: 16px;
            transition: all 0.3s ease;
        }

        .icon-card:hover {
            transform: translateY(-2px);
            box-shadow: 0px 8px 25px rgba(200, 208, 216, 0.25);
        }

        .icon-card.icon-card-3 {
            display: block;
            padding: 0px;
        }

        .icon-card.icon-card-3 .card-content {
            display: flex;
            padding: 25px;
            padding-bottom: 0;
        }

        @media only screen and (min-width: 1200px) and (max-width: 1399px) {
            .icon-card h6 {
                font-size: 17px;
            }
        }

        @media only screen and (min-width: 1200px) and (max-width: 1399px) {
            .icon-card h3 {
                font-size: 32px;
            }
        }

        .icon-card .icon {
            font-size: 52px;
            color: #6c7ae0;
            margin-right: 25px;
            opacity: 0.85;
        }

        .icon-card h6 {
            font-size: 16px;
            color: #64748b;
            margin: 0;
            font-weight: 500;
            letter-spacing: 0.3px;
        }

        .icon-card h3 {
            font-size: 36px;
            color: #334155;
            margin: 8px 0 0 0;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .icon-card h5 {
            font-size: 30px;
            color: #334155;
            margin: 8px 0 0 0;
            font-weight: 700;
            letter-spacing: -0.3px;
        }

        .card {
            border: 1px solid rgba(226, 232, 240, 0.6);
            border-radius: 16px;
            box-shadow: 0px 4px 15px rgba(200, 208, 216, 0.15);
            background: linear-gradient(135deg, #ffffff 0%, #fafbfc 100%);
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0px 8px 25px rgba(200, 208, 216, 0.2);
        }

        .card-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            border-bottom: 1px solid rgba(226, 232, 240, 0.6);
            padding: 25px;
            border-radius: 16px 16px 0 0;
        }

        .card-title {
            font-size: 20px;
            font-weight: 700;
            color: #334155;
            margin: 0;
            letter-spacing: -0.2px;
        }

        .card-body {
            padding: 25px;
        }

        .table {
            margin: 0;
        }

        .table td {
            padding: 16px;
            vertical-align: middle;
            font-size: 16px;
            color: #475569;
            border-bottom: 1px solid rgba(226, 232, 240, 0.5);
        }

        .table td.text-end {
            font-weight: 600;
            color: #334155;
            font-size: 17px;
        }

        /* Soft background colors untuk icon cards */
        .icon-card[style*="background-color: #D6F4FA"] {
            background: linear-gradient(135deg, #e0f7fa 0%, #b2ebf2 100%) !important;
            border-color: rgba(178, 235, 242, 0.4);
        }

        .icon-card[style*="background-color:#F5FBE0"] {
            background: linear-gradient(135deg, #f1f8e9 0%, #dcedc8 100%) !important;
            border-color: rgba(220, 237, 200, 0.4);
        }

        .icon-card[style*="background-color:#FFE7BA"] {
            background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%) !important;
            border-color: rgba(255, 224, 178, 0.4);
        }

        /* Loading spinner styling */
        .spinner-border {
            color: #6c7ae0;
        }

        .text-muted {
            color: #94a3b8 !important;
            font-size: 15px;
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
