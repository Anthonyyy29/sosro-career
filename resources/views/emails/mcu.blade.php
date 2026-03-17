<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; }
        .container { width: 100%; max-width: 650px; margin: 0 auto; background-color: #ffffff; border: 1px solid #e0e0e0; }
        .header { background-color: #d32f2f; color: #ffffff; padding: 30px; text-align: center; }
        .content { padding: 40px 30px; }
        .info-box { background-color: #fff8f1; border-left: 5px solid #f57c00; padding: 20px; margin: 25px 0; }
        .footer { background-color: #f4f4f4; padding: 30px; font-size: 12px; color: #666; border-top: 1px solid #ddd; }
        .important { color: #d32f2f; font-weight: bold; background: #ffebee; padding: 2px 5px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin:0; font-size: 22px;">UNDANGAN MEDICAL CHECK UP</h1>
            <p style="margin:5px 0 0;">PT Sinar Sosro Gunung Slamat</p>
        </div>

        <div class="content">
            <p>Selamat Pagi <strong>{{ $application->applicant->user->name }}</strong>,</p>
            
            <p>Melanjutkan proses rekrutmen yang sudah anda laksanakan sebelumnya, melalui pemberitahuan ini kami mengundang Anda untuk dapat mengikuti proses <strong>Medical Check Up</strong> dengan PT Sinar Sosro Gunung Slamat pada:</p>

            <div class="info-box">
                <table width="100%" style="border-collapse: collapse;">
                    <tr><td width="100" style="padding: 5px 0;"><strong>Tanggal</strong></td><td>: {{ \Carbon\Carbon::parse($data['mcu_date'])->locale('id')->translatedFormat('l, d F Y') }}</td></tr>
                    <tr><td style="padding: 5px 0;"><strong>Waktu</strong></td><td>: {{ \Carbon\Carbon::parse($data['mcu_date'])->format('H:i') }} WIB</td></tr>
                    <tr><td style="padding: 5px 0;"><strong>Tempat</strong></td><td>: {{ $data['mcu_location_name'] }}</td></tr>
                    <tr><td style="padding: 5px 0; vertical-align: top;"><strong>Alamat</strong></td><td>: {{ $data['mcu_location_address'] }}</td></tr>
                </table>
            </div>

            <p>Mohon untuk melakukan <span class="important">puasa dari pukul 22.00 WIB H-1 sebelum MCU</span>. Untuk surat pengantar MCU akan kami kirimkan ke pihak RS/Klinik, sehingga anda cukup langsung datang ke bagian MCU dengan membawa <strong>KTP</strong> dan menginformasikan dari <strong>PT Sinar Sosro Gunung Slamat</strong>.</p>

            <p>Demikian informasi ini kami sampaikan. Atas perhatian dan kerja samanya kami ucapkan terima kasih.</p>
            
            <p style="margin-top: 30px;">Regards,<br><strong>HC-OD & Recruitment</strong></p>
        </div>

        <div class="footer">
            <strong>PT.Sinar Sosro Gunung Slamat</strong><br>
            Graha Rekso Lt.8<br>
            Jl. Bulevard Artha Gading No.Kav.A1<br>
            Jakarta Utara, DKI Jakarta 14240<br>
            Email: recruitment.ho@sosro.com
        </div>
    </div>
</body>
</html>