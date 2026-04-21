<?php

namespace App\Mail;

use App\Models\Usuario;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BienvenidaMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public readonly Usuario $usuario) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '¡Bienvenido a VIBEZ! Tu cuenta ha sido aprobada',
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.bienvenida');
    }
}
