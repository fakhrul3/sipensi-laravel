<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Akun SIPENSI</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f7fa; font-family: 'Segoe UI', Helvetica, Arial, sans-serif;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td align="center" style="padding: 40px 0;">
                <table border="0" cellpadding="0" cellspacing="0" width="600" style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                    <tr>
                        <td align="center" style="padding: 30px 40px; background-color: #1e3a8a;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 24px; letter-spacing: 1px;">SIPENSI</h1>
                            <p style="color: #bfdbfe; margin: 5px 0 0 0; font-size: 13px;">Sistem Informasi Inkubator Bisnis</p>
                        </td>
                    </tr>
                    
                    <tr>
                        <td style="padding: 40px;">
                            <h2 style="color: #1f2937; margin: 0 0 20px 0; font-size: 20px;">Halo, {{ $name }}!</h2>
                            <p style="color: #4b5563; line-height: 1.6; margin-bottom: 25px;">
                                Terima kasih telah melakukan registrasi di aplikasi <strong>SIPENSI</strong>. Langkah terakhir untuk mengaktifkan akun Anda adalah dengan melakukan verifikasi alamat email.
                            </p>
                            
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td align="center" style="padding: 20px 0;">
                                        <a href="{{ route('user.verify', ['token' => $token, 'username' => $username, 'expired' => $expired]) }}" 
                                           style="background-color: #2563eb; color: #ffffff; padding: 14px 30px; text-decoration: none; border-radius: 6px; font-weight: bold; display: inline-block; font-size: 16px;">
                                            Konfirmasi Alamat Email
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="color: #4b5563; line-height: 1.6; margin-top: 25px;">
                                Tautan verifikasi ini hanya berlaku selama <strong>24 jam</strong>. Jika Anda tidak merasa melakukan pendaftaran ini, harap abaikan email ini.
                            </p>
                            
                            <hr style="border: none; border-top: 1px solid #e5e7eb; margin: 30px 0;">
                            
                            <p style="color: #9ca3af; font-size: 12px; text-align: center; margin: 0;">
                                Jika tombol di atas tidak berfungsi, salin dan tempel tautan berikut di browser Anda:<br>
                                <span style="color: #2563eb; word-break: break-all;">{{ route('user.verify', ['token' => $token, 'username' => $username, 'expired' => $expired]) }}</span>
                            </p>
                        </td>
                    </tr>
                    
                    <tr>
                        <td style="padding: 20px 40px; background-color: #f9fafb; text-align: center;">
                            <p style="color: #6b7280; font-size: 12px; margin: 0;">
                                &copy; {{ date('Y') }} Kementerian Usaha Mikro, Kecil, dan Menengah.<br>
                                Republik Indonesia.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>