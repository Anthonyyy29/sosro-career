<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; }
        .container { width: 100%; max-width: 650px; margin: 0 auto; background-color: #ffffff; border: 1px solid #e0e0e0; }
        .header { background-color: #374151; color: #ffffff; padding: 30px; text-align: center; }
        .content { padding: 40px 30px; }
        .footer { background-color: #f4f4f4; padding: 30px; font-size: 12px; color: #666; border-top: 1px solid #ddd; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin:0; font-size: 20px;">PEMBERITAHUAN HASIL SELEKSI</h1>
            <p style="margin:5px 0 0;">PT Sinar Sosro Gunung Slamat</p>
        </div>

        <div class="content">
            <p>Yth. <strong>{{ $application->applicant->user->name }}</strong>,</p>
            
            <p>Terima kasih atas keikutsertaan Anda dalam proses seleksi calon karyawan di <strong>PT Sinar Sosro Gunung Slamat</strong> yang telah dilaksanakan beberapa waktu yang lalu.</p>

            <p>Melalui email ini kami ingin menyampaikan bahwa berdasarkan review hasil profil yang Anda miliki belum sesuai dengan kriteria yang kami butuhkan saat ini sehingga kami belum dapat melanjutkan ke proses selanjutnya.</p>
            
            <p>Namun demikian, data dan profile Anda akan kami simpan terlebih dahulu. Kami akan menghubungi kembali apabila di kemudian hari terdapat posisi yang lebih sesuai dengan kualifikasi yang Anda miliki.</p>

            <p>Kami mohon maaf apabila terdapat perilaku atau perkataan kami yang kurang berkenan.</p>

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