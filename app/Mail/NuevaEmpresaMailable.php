<?php

namespace App\Mail;

use App\Models\Empresa;
use App\Models\Usuario;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Notificación al equipo de Vibez cuando una nueva empresa se registra.
 *
 * Este correo se envía automáticamente al email de administración de Vibez
 * cada vez que una empresa completa el formulario de registro y queda en
 * estado "pendiente de revisión". El admin debe acceder al panel y
 * aprobar o rechazar la solicitud.
 */
class NuevaEmpresaMailable extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly Usuario $usuario,
        public readonly Empresa $empresa
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🏢 Nueva solicitud de empresa — ' . $this->empresa->nombre_empresa . ' | VIBEZ',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.nueva-empresa-admin',
        );
    }
}
