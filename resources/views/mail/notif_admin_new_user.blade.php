<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; }
        .container { padding: 20px; border: 1px solid #eee; border-radius: 10px; max-width: 600px; }
        .header { border-bottom: 2px solid #2563eb; padding-bottom: 10px; margin-bottom: 20px; }
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table td { padding: 8px 0; border-bottom: 1px solid #f4f4f4; }
        .label { font-weight: bold; width: 150px; }
        .footer { margin-top: 30px; font-size: 0.8rem; color: #777; }
        .btn { background: #2563eb; color: white !important; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Notifikasi Pendaftaran Baru</h2>
        </div>
        <p>Halo Admin SIPENSI,</p>
        <p>Ada lembaga inkubator baru yang telah berhasil memverifikasi email dan menunggu persetujuan Anda:</p>

        <table class="data-table">
            <tr>
                <td class="label">Nama Lembaga</td>
                <td>: {{ $inkubator->nama_inkubator }}</td>
            </tr>
            <tr>
                <td class="label">Username</td>
                <td>: {{ $inkubator->user->username }}</td>
            </tr>
            <tr>
                <td class="label">Email</td>
                <td>: {{ $inkubator->email }}</td>
            </tr>
            <tr>
                <td class="label">No. Kontak</td>
                <td>: {{ $inkubator->no_kontak }}</td>
            </tr>
            <tr>
                <td class="label">Waktu Verif</td>
                <td>: {{ now()->format('d M Y H:i') }} WIB</td>
            </tr>
        </table>

        <p>Silakan login ke Dashboard Admin untuk memeriksa dokumen legalitas dan menyetujui akun ini.</p>
        
        <a href="{{ url('/admin/login') }}" class="btn">Buka Dashboard Admin</a>

        <div class="footer">
            <p>Email ini dikirim otomatis oleh Sistem Informasi Inkubator Bisnis (SIPENSI).</p>
        </div>
    </div>
</body>
</html>