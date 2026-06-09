<?php

namespace App\Mail;

use App\Models\PlaylistEventoCancion;
use App\Models\Usuario;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Mailable CancionComprada — Email de confirmación al añadir una canción a la playlist.
 *
 * Se envía después de:
 *  - Confirmar el pago Stripe (canciones de pago)
 *  - Añadir directamente (canciones gratuitas)
 */
class CancionComprada extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public PlaylistEventoCancion $registro,
        public Usuario $usuario,
    ) {
        // Cargamos las relaciones para tenerlas disponibles en la vista del email
        $this->registro->loadMissing(['cancion', 'evento']);
    }

    public function envelope(): Envelope
    {
        $titulo = $this->registro->cancion?->titulo ?? 'tu canción';

        return new Envelope(
            subject: '🎵 "' . $titulo . '" añadida a tu playlist — VIBEZ',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.cancion-comprada',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
