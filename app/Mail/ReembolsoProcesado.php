<?php

namespace App\Mail;

use App\Models\Pedido;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Correo enviado al usuario cuando su reembolso ha sido procesado correctamente.
 * Informa del importe devuelto, el evento y el número de entradas canceladas.
 */
class ReembolsoProcesado extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Pedido $pedido)
    {
        $this->pedido->loadMissing(['entradas.evento', 'usuario']);
    }

    public function envelope(): Envelope
    {
        $titulo = $this->pedido->entradas->first()?->evento?->titulo ?? 'tu evento';

        return new Envelope(
            subject: '💸 Reembolso procesado: ' . $titulo . ' — VIBEZ',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reembolso-procesado',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
