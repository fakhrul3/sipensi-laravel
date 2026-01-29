<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Akun Berhasil</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f7f6; font-family: 'Segoe UI', Arial, sans-serif;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td align="center" style="padding: 40px 0;">
                <table width="600" border="0" cellspacing="0" cellpadding="0" style="background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                    <tr>
                        <td align="center" style="background-color: #2e59d9; padding: 30px 20px;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 22px; font-weight: 700; letter-spacing: 2px;">SIPENSI</h1>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 40px 30px;">
                            <h2 style="color: #333333; margin-top: 0; font-size: 20px;">Selamat, {{ $inkubator->nama_inkubator }}!</h2>
                            <p style="color: #555555; font-size: 15px; line-height: 1.6;">
                                Akun lembaga Anda telah diverifikasi oleh Admin. Sekarang Anda dapat mengakses penuh dashboard SIPENSI.
                            </p>

                            <div style="background-color: #f8f9fc; border: 1px solid #e3e6f0; border-radius: 8px; padding: 20px; margin: 25px 0;">
                                <p style="margin: 0 0 15px 0; font-weight: bold; color: #2e59d9; border-bottom: 1px solid #e3e6f0; padding-bottom: 10px;">DETAIL AKUN LOGIN</p>
                                <table width="100%" style="font-size: 14px; color: #555555;">
                                    <tr>
                                        <td width="100" style="padding: 5px 0;">Username</td>
                                        <td style="padding: 5px 0; font-weight: bold; color: #333333;">: {{ $username }}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 5px 0;">Password</td>
                                        <td style="padding: 5px 0; font-weight: bold; color: #e74a3b;">: {{ $password }}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 5px 0;">Status</td>
                                        <td style="padding: 5px 0;">: <span style="background-color: #1cc88a; color: white; padding: 2px 8px; border-radius: 10px; font-size: 11px;">AKTIF</span></td>
                                    </tr>
                                </table>
                            </div>

                            <p style="color: #858796; font-size: 13px; font-style: italic;">*Demi keamanan, segera ganti password Anda setelah login pertama kali.</p>

                            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top: 30px;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ url('/login') }}" style="background-color: #2e59d9; color: #ffffff; padding: 15px 35px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block;">Login ke Dashboard</a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 0 30px 40px 30px; text-align: center;">
                            <hr style="border: 0; border-top: 1px solid #eeeeee; margin-bottom: 20px;">
                            <p style="color: #b7b9cc; font-size: 12px; margin: 0;">
                                &copy; {{ date('Y') }} SIPENSI - Sistem Informasi Lembaga Inkubator.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>