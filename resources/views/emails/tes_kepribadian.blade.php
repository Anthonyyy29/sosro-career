<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        /* CSS sama dengan di atas untuk konsistensi */
        body { font-family: 'Segoe UI', Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; }
        .container { width: 100%; max-width: 650px; margin: 0 auto; background-color: #ffffff; border: 1px solid #e0e0e0; }
        .header { background-color: #d32f2f; color: #ffffff; padding: 25px; text-align: center; }
        .header h1 { margin: 0; font-size: 20px; letter-spacing: 1px; }
        .content { padding: 40px 35px; font-size: 15px; }
        .section-title { font-weight: bold; text-transform: uppercase; display: block; margin-top: 25px; margin-bottom: 10px; color: #d32f2f; }
        ul { padding-left: 20px; margin-top: 10px; }
        li { margin-bottom: 10px; text-align: justify; }
        .footer { background-color: #f4f4f4; padding: 25px 35px; font-size: 12px; color: #666; border-top: 1px solid #eeeeee; line-height: 1.5; }
        a { color: #d32f2f; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>UNDANGAN TES KEPRIBADIAN</h2>
            <p style="margin:5px 0 0;">PT Sinar Sosro Gunung Slamat</p>
        </div>

        <div class="content">
            <p>Yth. <strong>{{ $application->applicant->user->name }}</strong></p>
            
            <p>Kami ingin mengundang Anda untuk melakukan proses psikotes secara online yang dapat diakses melalui laptop pribadi Anda, dengan estimasi waktu pengerjaan 10 hingga 15 menit. Psikotes online dapat dilaksanakan hari <strong>{{ \Carbon\Carbon::parse($data['psikotes_date'])->locale('id')->translatedFormat('l, d F Y') }}</strong>.</p>

            <p>Berikut kami sampaikan beberapa informasi mengenai psikotes online tersebut:</p>

            <ul>
                <li>Silakan buka link <a href="{{ $data['psikotes_link'] }}" target="_blank">{{ $data['psikotes_link'] }}</a> pada laptop pribadi Anda.</li>
                <li>Gunakan kode <strong>{{ $data['psikotes_token'] }}</strong> untuk login, masukkan password (password bebas yang anda buat sendiri) kemudian isi data diri Anda.</li>
                <li>Kode pada point 2 dapat diakses pada pukul <strong>08:00 WIB - 23:59 WIB</strong> (di luar jam tersebut kode tidak dapat diakses).</li>
                <li>Pastikan koneksi internet yang Anda gunakan stabil dan akses kamera serta location sudah diaktifkan.</li>
            </ul>

            <span class="section-title">KETENTUAN PSIKOTEST ONLINE</span>
            <ul>
                <li>Tes dikerjakan dengan <strong>Laptop</strong> dan menggunakan browser <strong>Mozilla Firefox atau Google Chrome</strong> dan tutup semua tab search selain tes ini.</li>
                <li>Pastikan bahwa kamera laptop dan lokasi Anda tetap menyala selama proses psikotes berlangsung.</li>
                <li>Pastikan Anda mengerjakan Tes ini secara individual, tanpa adanya bantuan dari pihak manapun.</li>
                <li>Harap perhatikan dengan seksama instruksi dari setiap soal dari masing-masing bagian tes.</li>
                <li>Tidak ada jawaban benar atau salah. Silakan memilih jawaban secara spontan yang paling menggambarkan diri Anda.</li>
                <li>Apabila terjadi kendala saat mengerjakan tes, silakan refresh browser yang Anda gunakan dan dapat login kembali dengan kode yang sama dan password yang sebelumnya telah dibuat.</li>
                <li>Pastikan Anda menyelesaikan setiap sesi tes dan klik <strong>“Jawab/Selesai”</strong>.</li>
                <li>Segala upaya kecurangan dapat terdeteksi dan dapat mengakibatkan Anda tidak dapat melanjutkan proses di PT Sinar Sosro Gunung Slamat, oleh karena itu percayalah pada kemampuan diri Anda sendiri, karena percaya diri adalah kunci kesuksesan.</li>
            </ul>

            <p style="margin-top: 25px;">Demikian informasi ini kami sampaikan. Atas perhatian dan kerja samanya kami ucapkan terima kasih.</p>
            
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