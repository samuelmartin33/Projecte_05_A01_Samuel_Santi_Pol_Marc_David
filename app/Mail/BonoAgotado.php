<?php

namespace App\Mail;

use App\Models\BonoCompra;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BonoAgotado extends Mailable
{
    use Queueable, SerializesModels;

    public BonoCompra $bono;

    /**
     * Create a new message instance.
     */
    public function __construct(BonoCompra $bono)
    {
        $this->bono = $bono;
        $this->bono->loadMissing(['usuario', 'tipo', 'evento']);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🍹 Tu bono se ha agotado — VIBEZ',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.bono-agotado',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
