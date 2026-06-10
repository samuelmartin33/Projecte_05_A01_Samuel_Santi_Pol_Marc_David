<?php

namespace App\Mail;

use App\Models\BonoCompra;
use App\Models\Usuario;
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

/**
 * Mailable BonoComprado — Email con el QR del bono al comprarlo.
 *
 * El QR se envía de dos formas para máxima compatibilidad:
 *  - Embebido en base64 en el cuerpo (funciona en webmail, Apple Mail)
 *  - Adjunto como PNG descargable (fallback para Gmail móvil, Outlook)
 */
class BonoComprado extends Mailable
{
    use Queueable, SerializesModels;

    // PNG binario del QR generado en el constructor y reutilizado en content() y attachments()
    private ?string $qrPngData = null;

    public function __construct(
        public BonoCompra $bono,
        public Usuario $usuario,
    ) {
        $this->bono->loadMissing(['tipo', 'evento']);

        // Generar el QR una sola vez para usarlo tanto embebido como adjunto
        try {
            $result = Builder::create()
                ->writer(new PngWriter())
                ->data($this->bono->codigo_qr)
                ->encoding(new Encoding('UTF-8'))
                ->errorCorrectionLevel(ErrorCorrectionLevel::High)
                ->size(240)
                ->margin(10)
                ->build();

            $this->qrPngData = $result->getString();
        } catch (\Throwable) {
            $this->qrPngData = null;
        }
    }

    public function envelope(): Envelope
    {
        $bebidas = $this->bono->tipo?->cantidad_bebidas ?? '?';
        return new Envelope(
            subject: '🍹 Tu bono de ' . $bebidas . ' bebidas — VIBEZ',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.bono-comprado',
            with: ['qrImageData' => $this->qrPngData],
        );
    }

    public function attachments(): array
    {
        if (!$this->qrPngData) {
            return [];
        }

        // Adjuntar el QR como PNG para clientes de email que bloquean data: URIs
        $codigoCorto = substr($this->bono->codigo_qr, 0, 8);
        return [
            Attachment::fromData(fn () => $this->qrPngData, 'qr-bono-' . $codigoCorto . '.png')
                ->withMime('image/png'),
        ];
    }
}
