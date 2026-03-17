<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AcceptedEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $application;
    public $data;
    public $type;

    public function __construct($application, $data, $type)
    {
        $this->application = $application;
        $this->data = $data;
        $this->type = $type;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Informasi Hari Pertama Karyawan Baru PT Sinar Sosro Gunung Slamat',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: match ($this->type) {
                'HO' => 'emails.accepted_ho',
                'KPW' => 'emails.accepted_kpw',
                'KPB' => 'emails.accepted_kpb',
            },
        );
    }
}