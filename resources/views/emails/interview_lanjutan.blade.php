<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; }
        .container { width: 100%; max-width: 650px; margin: 0 auto; background-color: #ffffff; border: 1px solid #e0e0e0; }
        .header { background-color: #d32f2f; color: #ffffff; padding: 30px; text-align: center; }
        .content { padding: 40px 30px; }
        .info-box { background-color: #f8f9fa; border-left: 5px solid #d32f2f; padding: 20px; margin: 25px 0; }
        .footer { background-color: #f4f4f4; padding: 30px; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin:0;">UNDANGAN INTERVIEW LANJUTAN</h1>
            <p style="margin:5px 0 0;">PT Sinar Sosro Gunung Slamat</p>
        </div>

        <div class="content">
            <p>Selamat Pagi <strong>{{ $application->applicant->user->name }}</strong>,</p>
            
            <p>Melanjutkan proses rekrutmen yang sudah anda laksanakan sebelumnya, melalui pemberitahuan ini kami mengundang Anda untuk dapat mengikuti proses <strong>INTERVIEW LANJUTAN</strong> dengan <strong>PT Sinar Sosro Gunung Slamat</strong> pada:</p>

            <div class="info-box">
                <strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($data['interview_date'])->locale('id')->translatedFormat('l, d F Y') }}<br>
                <strong>Waktu:</strong> {{ \Carbon\Carbon::parse($data['interview_date'])->format('H:i') }} WIB<br>
                <strong>Link:</strong> <a href="{{ $data['interview_link'] }}" target="_blank" style="color: #d32f2f; font-weight: bold;">{{ $data['interview_link'] }}</a>
            </div>

            <p>Adapun proses interview dilaksanakan secara virtual, untuk <strong>ID Meeting</strong> akan kami informasikan sebelum pelaksanaan interview melalui <strong>Whatsapp</strong>.</p>
            
            <p><strong>Ketentuan:</strong> Pada saat proses interview, baik pria/wanita wajib mengenakan pakaian rapi dan sopan.</p>

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