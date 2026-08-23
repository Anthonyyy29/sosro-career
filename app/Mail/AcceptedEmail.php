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

    public function __construct($application, $data = [])
    {
        $this->application = $application;
        $this->data = $data;
        // Menentukan template mana yang dipakai (HO / KPW / KPB). Dulu dikirim
        // sebagai argumen ketiga, sekarang ikut menumpang di dalam $data.
        $this->type = $data['office_type'] ?? null;
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