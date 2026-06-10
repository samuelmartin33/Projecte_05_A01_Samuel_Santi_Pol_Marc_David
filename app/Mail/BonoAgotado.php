<?php

namespace App\Mail;

use App\Models\BonoCompra;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Mailable BonoAgotado — Notifica al cliente que su bono de bebidas se ha agotado.
 */
class BonoAgotado extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public BonoCompra $bono)
    {
        $this->bono->loadMissing(['usuario', 'tipo', 'evento']);
    }

    public function envelope(): Envelope
    {
        $bebidas = $this->bono->tipo?->cantidad_bebidas ?? '?';
        return new Envelope(
            subject: '🍹 Tu bono de ' . $bebidas . ' bebidas se ha agotado — VIBEZ',
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.bono-agotado');
    }

    public function attachments(): array
    {
        return [];
    }
}
