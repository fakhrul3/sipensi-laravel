@extends('layouts.admin')

@section('title', 'Detail Lembaga Inkubator')
@section('page-title', 'Detail Lembaga Inkubator')

@section('breadcrumb')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('lembaga-inkubator.index') }}">Data Inkubator</a></li>
    <li class="breadcrumb-item active" aria-current="page">Detail</li>
</ol>
@endsection

@push('styles')
<style>
    .admin-detail-container {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0px 10px 20px rgba(200, 208, 216, 0.3);
        padding: 30px;
    }

    .detail-section {
        margin-bottom: 30px;
    }

    .detail-section-title {
        font-size: 18px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #e9ecef;
    }

    .detail-row {
        display: flex;
        margin-bottom: 15px;
        padding: 10px 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .detail-label {
        font-weight: 600;
        color: #495057;
        width: 200px;
        flex-shrink: 0;
    }

    .detail-value {
        color: #212529;
        flex: 1;
    }

    .badge-status {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
        white-space: nowrap;
    }

    .badge-status-verified {
        background-color: #28a745;
        color: #fff;
    }

    .badge-status-pending {
        background-color: #ffc107;
        color: #000;
    }

    .btn-back {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        text-decoration: none;
        display: inline-block;
    }

    .btn-back:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        color: #fff;
    }
</style>
@endpush

@section('content')
<div class="admin-detail-container">
    <div style="margin-bottom: 20px;">
        <a href="{{ route('lembaga-inkubator.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar
        </a>
    </div>

    @if($inkubator)
        <!-- Informasi Umum -->
        <div class="detail-section">
            <h5 class="detail-section-title">Informasi Umum</h5>
            <div class="detail-row">
                <div class="detail-label">Nama Lembaga Inkubator</div>
                <div class="detail-value">{{ $inkubator->nama_inkubator ?? '-' }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">No Tanda Daftar</div>
                <div class="detail-value">{{ $inkubator->no_tanda_daftar ?? '-' }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Jenis Lembaga Inkubator</div>
                <div class="detail-value">{{ $jenisMap[$inkubator->jenis_inkubator ?? 0] ?? '-' }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Induk Lembaga Inkubator</div>
                <div class="detail-value">{{ $inkubator->induk_inkubator ?? '-' }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Nama Pimpinan</div>
                <div class="detail-value">{{ $inkubator->nama_pimpinan ?? '-' }}</div>
            </div>
        </div>

        <!-- Kontak -->
        <div class="detail-section">
            <h5 class="detail-section-title">Kontak</h5>
            <div class="detail-row">
                <div class="detail-label">No Kontak</div>
                <div class="detail-value">{{ $inkubator->no_kontak ?? '-' }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Email</div>
                <div class="detail-value">{{ $inkubator->email ?? '-' }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Alamat Kantor</div>
                <div class="detail-value">{{ $inkubator->alamat_kantor ?? '-' }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Provinsi</div>
                <div class="detail-value">{{ $inkubator->nama_provinsi ?? '-' }}</div>
            </div>
        </div>

        <!-- Status -->
        <div class="detail-section">
            <h5 class="detail-section-title">Status</h5>
            <div class="detail-row">
                <div class="detail-label">Status Verifikasi</div>
                <div class="detail-value">
                    @if($inkubator->is_verify == 1 || $inkubator->is_verify == 2)
                        <span class="badge-status badge-status-verified">Terverifikasi</span>
                    @else
                        <span class="badge-status badge-status-pending">Verifikasi Email</span>
                    @endif
                </div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Status Legal Dokumen</div>
                <div class="detail-value">
                    @php
                        $hasLegalitas = !empty($inkubator->path_legalitas) || !empty($inkubator->no_tanda_daftar);
                    @endphp
                    @if($hasLegalitas)
                        <span class="badge-status badge-status-verified">Lengkap</span>
                    @else
                        <span class="badge-status badge-status-pending">Belum Lengkap</span>
                    @endif
                </div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Peringkat</div>
                <div class="detail-value">{{ $inkubator->pemeringkatan_rank ?? '-' }}</div>
            </div>
        </div>

        <!-- Informasi Akun -->
        <div class="detail-section">
            <h5 class="detail-section-title">Informasi Akun</h5>
            <div class="detail-row">
                <div class="detail-label">Username</div>
                <div class="detail-value">{{ $inkubator->username ?? '-' }}</div>
            </div>
        </div>
    @else
        <div class="alert alert-danger">
            Data inkubator tidak ditemukan.
        </div>
    @endif
</div>
@endsection
