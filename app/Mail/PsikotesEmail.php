<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PsikotesEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $application;
    public $data; // Kita simpan data input di sini

    public function __construct($application, $data)
    {
        $this->application = $application;
        $this->data = $data;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Undangan Psikotes - PT Sinar Sosro Gunung Slamat',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.psikotes',
        );
    }
}