<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RejectedEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $application;
    public $data;

    public $reason;

    public function __construct($application, $data = [])
    {
        $this->application = $application;
        $this->data = $data;
        // Diambil dari $data supaya bentuknya seragam dengan kelas email lain.
        // Template emails.rejected tetap memakai $reason seperti sebelumnya.
        $this->reason = $data['rejection_reason'] ?? null;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Konfirmasi Seleksi PT Sinar Sosro Gunung Slamat',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.rejected',
            with: [
                'reason' => $this->reason,
            ],
        );
    }
}