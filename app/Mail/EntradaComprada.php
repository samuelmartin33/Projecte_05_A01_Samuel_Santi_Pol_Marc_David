<?php

namespace App\Mail;

use App\Models\Pedido;
use Barryvdh\DomPDF\Facade\Pdf;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EntradaComprada extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Pedido $pedido)
    {
        // Aseguramos que las relaciones necesarias estén cargadas
        $this->pedido->loadMissing(['entradas.evento', 'usuario']);
    }

    public function envelope(): Envelope
    {
        $titulo = $this->pedido->entradas->first()?->evento?->titulo ?? 'tu evento';

        return new Envelope(
            subject: '🎟️ Tus entradas para ' . $titulo . ' — VIBEZ',
        );
    }

    public function content(): Content
    {
        // Generamos el QR de cada entrada como PNG con endroid/qr-code (usa GD, sin Imagick).
        $qrImages = [];
        foreach ($this->pedido->entradas as $entrada) {
            try {
                $result = Builder::create()
                    ->writer(new PngWriter())
                    ->data($entrada->codigo_qr)
                    ->encoding(new Encoding('UTF-8'))
                    ->errorCorrectionLevel(ErrorCorrectionLevel::High)
                    ->size(220)
                    ->margin(8)
                    ->build();

                $qrImages[$entrada->id] = $result->getString();
            } catch (\Throwable) {
                $qrImages[$entrada->id] = null;
            }
        }

        return new Content(
            view: 'emails.entrada-comprada',
            with: ['qrImages' => $qrImages],
        );
    }

    public function attachments(): array
    {
        $pdf = Pdf::loadView('emails.factura-compra-pdf', ['pedido' => $this->pedido])
            ->setPaper('A4', 'portrait');

        $filename = 'factura-vibez-' . str_pad($this->pedido->id, 6, '0', STR_PAD_LEFT) . '.pdf';

        return [
            Attachment::fromData(fn () => $pdf->output(), $filename)
                ->withMime('application/pdf'),
        ];
    }
}
