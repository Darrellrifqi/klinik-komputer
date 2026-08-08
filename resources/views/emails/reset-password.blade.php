<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Klinik Komputer</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f1f5f9; color: #334155;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #f1f5f9; padding: 40px 10px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" max-width="560px" cellspacing="0" cellpadding="0" style="max-width: 560px; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.05); border: 1px solid #e2e8f0;">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background-color: #1e293b; padding: 28px; text-align: center;">
                            <h1 style="color: #ffffff; font-size: 1.4rem; font-weight: 800; margin: 0; letter-spacing: 0.03em;">KLINIK KOMPUTER</h1>
                            <p style="color: #94a3b8; font-size: 0.8rem; margin: 4px 0 0 0; text-transform: uppercase; letter-spacing: 0.08em;">Layanan Perbaikan & Maintenance Perangkat</p>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding: 32px 28px;">
                            <h2 style="font-size: 1.2rem; font-weight: 700; color: #0f172a; margin-top: 0; margin-bottom: 12px;">Halo, {{ $user->name }}!</h2>
                            <p style="font-size: 0.92rem; line-height: 1.6; color: #475569; margin-bottom: 20px;">
                                Kami menerima permintaan untuk mereset password akun Klinik Komputer Anda. Silakan klik tombol di bawah ini untuk membuat password baru:
                            </p>

                            <!-- CTA Button -->
                            <table role="presentation" cellspacing="0" cellpadding="0" style="margin: 28px 0;">
                                <tr>
                                    <td align="center" style="border-radius: 8px; background-color: #2563eb;">
                                        <a href="{{ $resetUrl }}" target="_blank" style="font-size: 0.92rem; font-weight: 700; color: #ffffff; text-decoration: none; display: inline-block; padding: 12px 28px; border-radius: 8px; border: 1px solid #2563eb;">
                                            Reset Password Akun
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="font-size: 0.85rem; line-height: 1.5; color: #64748b; margin-bottom: 24px;">
                                Link reset password ini hanya berlaku selama <strong>60 menit</strong>. Jika Anda tidak merasa melakukan permintaan ini, Anda dapat mengabaikan email ini dan password Anda akan tetap aman.
                            </p>

                            <hr style="border: none; border-top: 1px solid #e2e8f0; margin: 24px 0;">

                            <p style="font-size: 0.78rem; color: #94a3b8; line-height: 1.5; margin: 0;">
                                Jika tombol di atas tidak dapat diklik, salin dan tempel link berikut ke browser Anda:<br>
                                <a href="{{ $resetUrl }}" style="color: #2563eb; word-break: break-all;">{{ $resetUrl }}</a>
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8fafc; padding: 20px 28px; text-align: center; border-top: 1px solid #e2e8f0;">
                            <p style="font-size: 0.78rem; color: #94a3b8; margin: 0;">
                                &copy; {{ date('Y') }} Klinik Komputer. Hak Cipta Dilindungi.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
