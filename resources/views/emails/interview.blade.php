<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; }
        .container { width: 100%; max-width: 650px; margin: 0 auto; background-color: #ffffff; border: 1px solid #e0e0e0; }
        .header { background-color: #d32f2f; color: #ffffff; padding: 25px; text-align: center; }
        .content { padding: 40px 35px; font-size: 15px; }
        .info-table { width: 100%; margin: 20px 0; border-collapse: collapse; }
        .info-table td { padding: 5px 0; vertical-align: top; }
        .label { width: 130px; font-weight: bold; }
        .separator { width: 20px; text-align: center; }
        .footer { background-color: #f4f4f4; padding: 25px 35px; font-size: 12px; color: #666; border-top: 1px solid #eeeeee; }
        a { color: #d32f2f; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2 style="margin:0; letter-spacing: 1px;">UNDANGAN INTERVIEW</h2>
            <p style="margin:5px 0 0;">PT Sinar Sosro Gunung Slamat</p>
        </div>

        <div class="content">
            <p>Yth. <strong>{{ $application->applicant->user->name }}</strong></p>
            
            <p>Melanjutkan proses rekrutmen yang sudah dilaksanakan sebelumnya, melalui email ini kami mengundang Anda untuk dapat mengikuti proses interview dengan <strong>PT Sinar Sosro Gunung Slamat</strong> pada:</p>

            <table class="info-table">
                <tr>
                    <td class="label">Hari / Tanggal</td>
                    <td class="separator">:</td>
                    <td>{{ \Carbon\Carbon::parse($data['interview_date'])->locale('id')->translatedFormat('l, d F Y') }}</td>
                </tr>
                <tr>
                    <td class="label">Waktu</td>
                    <td class="separator">:</td>
                    <td>{{ \Carbon\Carbon::parse($data['interview_date'])->format('H:i') }} WIB</td>
                </tr>
            </table>

            <p>Adapun proses interview dilaksanakan secara virtual melalui Zoom/Gmeet. Pada saat proses interview, baik pria/wanita mengenakan pakaian rapi, dan sopan.</p>

            <table class="info-table">
                <tr>
                    <td class="label">Link Interview</td>
                    <td class="separator">:</td>
                    <td><a href="{{ $data['interview_link'] }}" target="_blank">{{ $data['interview_link'] }}</a></td>
                </tr>
            </table>

            <p>Demikian informasi ini kami sampaikan. Atas perhatian dan kerja samanya kami ucapkan terima kasih.</p>
            
            <p style="margin-top: 30px; line-height: 1.4;">
                Regards,<br>
                <strong>HC - OD & Recruitment</strong>
            </p>
        </div>

        <div class="footer">
            <strong>PT.Sinar Sosro Gunung Slamat</strong><br>
            Graha Rekso Lt. 8 - 10,<br>
            Jl. Bulevard Artha Gading No.Kav.A1<br>
            Jakarta Utara, DKI Jakarta 14240<br>
            Email: recruitment.ho@sosro.com
        </div>
    </div>
</body>
</html>