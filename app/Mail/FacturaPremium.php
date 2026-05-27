<?php

namespace App\Mail;

use App\Models\Usuario;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FacturaPremium extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param Usuario $usuario        Propietario de la cuenta que acaba de activar Premium.
     * @param float   $importe        Importe cobrado (normalmente 5.00).
     * @param string  $sessionId      ID de la sesión de Stripe (sirve como número de referencia).
     * @param Carbon  $fechaPago      Fecha y hora del pago confirmado.
     */
    public function __construct(
        public Usuario $usuario,
        public float   $importe,
        public string  $sessionId,
        public Carbon  $fechaPago,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '⭐ ¡Bienvenido a VIBEZ Premium! Tu factura está lista',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.premium-comprado',
        );
    }

    public function attachments(): array
    {
        $pdf = Pdf::loadView('emails.factura-premium-pdf', [
            'usuario'   => $this->usuario,
            'importe'   => $this->importe,
            'sessionId' => $this->sessionId,
            'fechaPago' => $this->fechaPago,
        ])->setPaper('A4', 'portrait');

        // Número de factura derivado de los primeros 8 caracteres del session_id de Stripe.
        $ref      = strtoupper(substr($this->sessionId, -8));
        $filename = 'factura-premium-vibez-' . $ref . '.pdf';

        return [
            Attachment::fromData(fn () => $pdf->output(), $filename)
                ->withMime('application/pdf'),
        ];
    }
}
