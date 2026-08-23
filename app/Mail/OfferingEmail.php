<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OfferingEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $application;

    public $data;

    // $data tidak dipakai template email ini, tapi tetap diterima supaya bentuk
    // pemanggilan kesembilan kelas email seragam: new XEmail($application, $data).
    public function __construct($application, $data = [])
    {
        $this->application = $application;
        $this->data = $data;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Informasi Tahap Offering Letter - PT Sinar Sosro Gunung Slamat',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.offering',
        );
    }
}