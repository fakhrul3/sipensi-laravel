<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotifDaftarAdmin extends Mailable
{
    use Queueable, SerializesModels;

    // Variabel ini bakal bisa diakses langsung di file view (Blade)
    public $inkubator;
    public $username;

    public function __construct($inkubator)
    {
        $this->inkubator = $inkubator;

        // Trik biar nggak error: kalau relasi user nggak ada, ambil dari request input
        // Jadi lu nggak perlu repot benerin Model relasi kalau emang lagi buru-buru
        $this->username = $inkubator->user->username ?? request('username') ?? '-';
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pendaftaran Lembaga Inkubator Baru - SIPENSI',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.notif_admin',
            // Kita kirim data tambahan ke view lewat with
            with: [
                'username' => $this->username,
            ],
        );
    }
}