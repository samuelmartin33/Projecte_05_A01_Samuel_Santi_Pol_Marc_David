<?php

namespace App\Mail;

use App\Models\Usuario;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RechazoMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public readonly Usuario $usuario) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Información sobre tu solicitud de registro en VIBEZ',
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.rechazo');
    }
}
