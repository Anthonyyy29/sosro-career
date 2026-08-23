<?php

namespace App\Mail;

use App\Models\UserConfirmation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

/*
 * Satu-satunya email di aplikasi ini yang TIDAK dikirim ke pelamar.
 *
 * Tujuannya user (pihak yang meminta lowongan), berisi tautan untuk memilih satu
 * kandidat dari beberapa yang disodorkan admin. Tautannya bertanda tangan dan
 * berbatas waktu, jadi penerimanya tidak perlu punya akun.
 */
class KonfirmasiUserEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $konfirmasi;

    public $tautan;

    public function __construct(UserConfirmation $konfirmasi)
    {
        $this->konfirmasi = $konfirmasi->loadMissing(['lowongan', 'items.application.applicant.user']);

        // Tanda tangan Laravel: tidak bisa ditebak maupun diubah tanpa ketahuan.
        // Masa berlakunya disamakan dengan expires_at kelompok ini.
        $this->tautan = URL::temporarySignedRoute(
            'konfirmasi-user.show',
            $konfirmasi->expires_at,
            ['konfirmasi' => $konfirmasi->id]
        );
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Permintaan Konfirmasi Kandidat - PT Sinar Sosro Gunung Slamat',
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.konfirmasi_user');
    }
}
