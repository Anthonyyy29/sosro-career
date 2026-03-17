<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; }
        .container { width: 100%; max-width: 650px; margin: 0 auto; background-color: #ffffff; border: 1px solid #e0e0e0; }
        .header { background-color: #2e7d32; color: #ffffff; padding: 30px; text-align: center; }
        .content { padding: 40px 30px; }
        .info-box { background-color: #f1f8e9; border-left: 5px solid #2e7d32; padding: 20px; margin: 20px 0; }
        .footer { background-color: #f4f4f4; padding: 30px; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin:0; font-size: 22px;">SELAMAT BERGABUNG!</h1>
            <p style="margin:5px 0 0;">PT Sinar Sosro Gunung Slamat</p>
        </div>
        <div class="content">
            <p>Selamat Pagi <strong>{{ $application->applicant->user->name }}</strong>,</p>
            <p>Dengan ini kami mengucapkan Selamat Bergabung di PT Sinar Sosro Gunung Slamat untuk posisi:</p>
            
            <div class="info-box">
                <strong>Jabatan:</strong> {{ $application->lowongan->judul_lowongan }}<br>
                <strong>Penempatan Kerja:</strong> {{ $data['work_location'] }}<br>
                <strong>Tanggal Mulai:</strong> {{ \Carbon\Carbon::parse($data['join_date'])->locale('id')->translatedFormat('l, d F Y') }}
            </div>

            <strong>Jam Kerja & Ketentuan:</strong>
            <ul>
                <li>Senin – Jumat: Pukul 08.00 – 16.00</li>
                <li>Sabtu: Pukul 08.00 – 13.00</li>
                <li>Senin – Kamis & Sabtu: Kemeja bebas, celana kain/jeans, sepatu tertutup</li>
                <li>Jumat: Kemeja batik, celana kain/jeans, sepatu tertutup</li>
            </ul>

            <p>Pada hari pertama, silakan hadir sebelum pukul 08.00 di <strong>{{ $data['office_address'] }}</strong> dengan mengenakan <strong>kemeja batik</strong>. Silakan lapor ke satpam untuk hari pertama kerja dan menemui Tim Recruitment.</p>
            
            <p>Atas perhatiannya kami ucapkan terima kasih.</p>
            <p>Regards,<br><strong>HC-OD & Recruitment</strong></p>
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