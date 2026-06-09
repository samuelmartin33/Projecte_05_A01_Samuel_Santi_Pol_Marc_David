<?php

namespace App\Mail;

use App\Models\PedidoProveedor;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * FacturaProveedor — Email de confirmación de reposición de stock.
 *
 * Se envía al camarero tras confirmar el pago con Stripe.
 * Adjunta un PDF con los detalles de la reposición.
 */
class FacturaProveedor extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param PedidoProveedor $pedido   El pedido de reposición ya pagado.
     * @param mixed           $camarero El Organizador con la relación usuario cargada.
     */
    public function __construct(public PedidoProveedor $pedido, public $camarero)
    {
        // Asegura que las relaciones necesarias están disponibles para el email y el PDF
        $this->pedido->loadMissing(['producto.evento']);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '📦 Reposición confirmada — VIBEZ',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.factura-proveedor',
        );
    }

    public function attachments(): array
    {
        $pdf = Pdf::loadView('emails.factura-proveedor-pdf', [
            'pedido'   => $this->pedido,
            'camarero' => $this->camarero,
        ])->setPaper('A4', 'portrait');

        $nombre = 'factura-reposicion-' . str_pad($this->pedido->id, 6, '0', STR_PAD_LEFT) . '.pdf';

        return [
            Attachment::fromData(fn () => $pdf->output(), $nombre)
                ->withMime('application/pdf'),
        ];
    }
}
