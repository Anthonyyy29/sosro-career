<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; }
        .container { width: 100%; max-width: 650px; margin: 0 auto; background-color: #ffffff; border: 1px solid #e0e0e0; }
        .header { background-color: #d32f2f; color: #ffffff; padding: 30px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; letter-spacing: 1px; }
        .content { padding: 40px 30px; }
        .greeting { font-size: 18px; font-weight: bold; color: #d32f2f; margin-bottom: 20px; }
        .info-box { background-color: #f8f9fa; border-left: 5px solid #d32f2f; padding: 20px; margin: 25px 0; }
        .info-table td { padding: 5px 0; vertical-align: top; }
        .label { font-weight: bold; width: 120px; color: #555; }
        .section-title { font-weight: bold; text-decoration: underline; color: #222; margin-top: 25px; display: block; text-transform: uppercase; }
        ul { padding-left: 20px; margin-top: 10px; }
        li { margin-bottom: 8px; }
        .highlight { color: #d32f2f; font-weight: bold; }
        .footer { background-color: #f4f4f4; padding: 30px; font-size: 12px; color: #666; line-height: 1.8; }
        .address { border-top: 1px solid #ddd; margin-top: 20px; padding-top: 20px; font-style: normal; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>UNDANGAN PSIKOTES ONLINE</h1>
            <p style="margin-top: 5px; opacity: 0.9;">PT. Sinar Sosro Gunung Slamat</p>
        </div>

        <div class="content">
            <p class="greeting">Selamat Pagi {{ $application->applicant->user->name }},</p>
            
            <p>Dengan ini kami dari <strong>PT. Sinar Sosro Gunung Slamat</strong> mengundang Saudara/i untuk mengikuti Seleksi Psikotes secara online yang dapat diakses melalui laptop/gadget pribadi anda, dengan estimasi waktu pengerjaan 2-3 jam.</p>

            <div class="info-box">
                <table class="info-table" width="100%">
                    <tr>
                        <td class="label">Pengerjaan</td>
                        <td>: <strong>{{ \Carbon\Carbon::parse($data['psikotes_date'])->locale('id')->translatedFormat('l, d F Y') }} s/d {{ \Carbon\Carbon::parse($data['psikotes_end_date'])->locale('id')->translatedFormat('l, d F Y') }}</strong></td>
                    </tr>
                    <tr>
                        <td class="label">Link Psikotes</td>
                        <td>: <a href="{{ $data['psikotes_link'] }}" target="_blank" style="color: #d32f2f; font-weight: bold;">{{ $data['psikotes_link'] }}</a></td>
                    </tr>
                    <tr>
                        <td class="label">KODE TES</td>
                        <td>: <span class="highlight">{{ $data['psikotes_token'] ?? 'Lihat di Riwayat Lamaran' }}</span></td>
                    </tr>
                </table>
            </div>

            <span class="section-title">Informasi Psikotes Online:</span>
            <ul>
                <li>Silakan buka link Psikotes terlampir pada laptop pribadi.</li>
                <li>Gunakan <strong>KODE TES</strong> untuk login, masukkan password (bebas yang anda buat sendiri) kemudian isi data diri Anda.</li>
                <li>KODE TES dapat diakses pada pukul <strong>08:00 WIB - 17:00 WIB</strong> (di luar jam tersebut kode tidak dapat diakses).</li>
                <li>Pastikan koneksi internet stabil dan akses <strong>kamera serta lokasi</strong> sudah diaktifkan.</li>
            </ul>

            <span class="section-title">Ketentuan Psikotes Online:</span>
            <ul>
                <li>Wajib menggunakan Laptop dengan browser <strong>Mozilla Firefox atau Google Chrome</strong>. Tutup semua tab lain.</li>
                <li>Kamera laptop dan lokasi Anda <strong>harus tetap menyala</strong> selama proses berlangsung.</li>
                <li>Mengerjakan secara individual tanpa bantuan pihak manapun.</li>
                <li>Tes terdiri dari inteligensi dan kepribadian. Perhatikan instruksi di setiap sesi.</li>
                <li>Untuk tes kepribadian, pilih jawaban yang paling menggambarkan diri Anda (spontan).</li>
                <li>Jika terkendala, silakan <strong>refresh browser</strong> dan login kembali dengan kode & password yang sama.</li>
                <li>Pastikan klik <strong>Jawab/Selesai</strong> di setiap sesi.</li>
                <li><strong class="highlight">Kecurangan akan terdeteksi sistem</strong> dan dapat mengakibatkan diskualifikasi. Percayalah pada kemampuan diri Anda.</li>
            </ul>

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