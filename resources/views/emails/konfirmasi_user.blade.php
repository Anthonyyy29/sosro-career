<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Permintaan Konfirmasi Kandidat</title>
</head>
<body style="margin:0;padding:0;background-color:#f4f4f5;font-family:Arial,Helvetica,sans-serif;color:#27272a;">
    <div style="max-width:600px;margin:24px auto;background:#ffffff;border-radius:12px;overflow:hidden;border:1px solid #e4e4e7;">

        <div style="background:#B11116;padding:20px 28px;">
            <h1 style="margin:0;font-size:18px;color:#ffffff;">PT Sinar Sosro Gunung Slamat</h1>
        </div>

        <div style="padding:28px;">
            <h2 style="margin:0 0 16px;font-size:20px;">Permintaan Konfirmasi Kandidat</h2>

            <p style="margin:0 0 16px;line-height:1.6;">
                Tim rekrutmen telah menyeleksi
                <strong>{{ $konfirmasi->items->count() }} kandidat</strong>
                untuk posisi <strong>{{ $konfirmasi->lowongan->judul_lowongan ?? '-' }}</strong>.
            </p>

            <p style="margin:0 0 24px;line-height:1.6;">
                Mohon kesediaan Bapak/Ibu untuk meninjau catatan hasil interview masing-masing
                kandidat, lalu memilih <strong>satu</strong> kandidat melalui tautan di bawah ini.
            </p>

            <div style="text-align:center;margin:0 0 24px;">
                <a href="{{ $tautan }}"
                   style="display:inline-block;background:#B11116;color:#ffffff;text-decoration:none;padding:14px 28px;border-radius:8px;font-weight:bold;">
                    Lihat &amp; Pilih Kandidat
                </a>
            </div>

            <p style="margin:0 0 8px;font-size:13px;color:#71717a;line-height:1.6;">
                Tautan ini berlaku sampai
                <strong>{{ $konfirmasi->expires_at->translatedFormat('d F Y') }}</strong>
                dan tidak memerlukan akun untuk membukanya.
            </p>

            <p style="margin:0;font-size:13px;color:#71717a;line-height:1.6;">
                Jika tombol di atas tidak berfungsi, salin alamat berikut ke peramban Anda:<br>
                <span style="word-break:break-all;">{{ $tautan }}</span>
            </p>
        </div>

        <div style="background:#fafafa;padding:16px 28px;border-top:1px solid #e4e4e7;">
            <p style="margin:0;font-size:12px;color:#a1a1aa;">
                Email ini dikirim otomatis oleh sistem rekrutmen. Mohon tidak membalas email ini.
            </p>
        </div>
    </div>
</body>
</html>
