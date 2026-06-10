<?php

namespace App\Mail;

use App\Models\PedidoProveedor;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Mailable PedidoPagado — Confirmación de pago enviada al camarero.
 * Se dispara cuando el camarero paga un pedido de reposición de stock.
 */
class PedidoPagado extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public PedidoProveedor $pedido) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirmación de pago — Pedido #' . $this->pedido->id . ' · VIBEZ',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.pedido-pagado',
        );
    }
}
