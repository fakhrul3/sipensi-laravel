<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            background-color: #f4f7fa;
            font-family: 'Segoe UI', Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 40px 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        .header {
            background: #4f46e5; /* Indigo Modern */
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 24px;
            letter-spacing: 1px;
        }
        .content {
            padding: 40px;
            color: #374151;
            line-height: 1.6;
        }
        .greeting {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
        }
        .data-card {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
        }
        .data-item {
            display: flex;
            margin-bottom: 10px;
            border-bottom: 1px solid #f3f4f6;
            padding-bottom: 8px;
        }
        .data-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }
        .label {
            font-weight: 600;
            color: #6b7280;
            width: 140px;
            font-size: 14px;
        }
        .value {
            color: #111827;
            font-size: 14px;
            flex: 1;
        }
        .status-badge {
            display: inline-block;
            background: #fef3c7;
            color: #92400e;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-top: 10px;
        }
        .footer {
            text-align: center;
            padding: 30px;
            background: #f9fafb;
            color: #9ca3af;
            font-size: 13px;
        }
        .btn {
            display: block;
            background: #22466C;
            color: #ffffff !important;
            text-align: center;
            padding: 14px 20px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin-top: 30px;
            box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2);
        }
        .btn:hover {
            background: #22466C;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>SIPENSI</h1>
        </div>
        <div class="content">
            <div class="greeting">Halo Admin,</div>
            <p>Sistem mendeteksi adanya pendaftaran <strong>Lembaga Inkubator baru</strong> yang memerlukan peninjauan Anda.</p>
            
            <div class="data-card">
                <div class="data-item">
                    <span class="label">Nama Lembaga</span>
                    <span class="value">{{ $inkubator->nama_inkubator }}</span>
                </div>
                <div class="data-item">
                    <span class="label">Induk Lembaga</span>
                    <span class="value">{{ $inkubator->induk_inkubator }}</span>
                </div>
                <div class="data-item">
                    <span class="label">Email</span>
                    <span class="value">{{ $inkubator->email }}</span>
                </div>
                <div class="data-item">
                    <span class="label">Username</span>
                    <span class="value"><strong>{{ $username ?? '-' }}</strong></span>
                </div>
                <div class="status-badge">Menunggu Verifikasi</div>
            </div>

            <p>Silakan klik tombol di bawah ini untuk masuk ke dashboard dan melakukan verifikasi data.</p>
            
            <a href="{{ url('/login') }}" class="btn">Masuk ke Dashboard</a>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} SIPENSI - Sistem Informasi Penilaian Inkubator.<br>
            Email ini dikirim secara otomatis oleh sistem.
        </div>
    </div>
</body>
</html>