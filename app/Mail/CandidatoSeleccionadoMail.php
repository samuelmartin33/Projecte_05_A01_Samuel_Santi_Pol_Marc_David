<?php

namespace App\Mail;

use App\Models\CandidaturaTrabajo;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CandidatoSeleccionadoMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly CandidaturaTrabajo $candidatura,
        public readonly string $urlInvitacion = ''
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '¡Enhorabuena! Has sido seleccionado/a para el puesto — VIBEZ',
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.candidato-seleccionado');
    }
}