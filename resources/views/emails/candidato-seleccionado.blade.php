<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>¡Has sido seleccionado/a!</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Helvetica Neue', Arial, sans-serif; background: #F5F3FF; color: #1F2937; }
        .wrapper { max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(91,33,182,0.12); }
        .header { background: linear-gradient(135deg, #7C3AED 0%, #5B21B6 100%); padding: 40px 48px; text-align: center; }
        .logo { font-size: 2.4rem; font-weight: 900; color: #ffffff; letter-spacing: -0.03em; }
        .header-sub { font-size: 0.9rem; color: rgba(255,255,255,0.75); margin-top: 4px; letter-spacing: 0.06em; text-transform: uppercase; }
        .body { padding: 40px 48px; }
        .greeting { font-size: 1.5rem; font-weight: 700; color: #1F2937; margin-bottom: 16px; }
        .greeting span { color: #7C3AED; }
        .text { font-size: 1rem; color: #4B5563; line-height: 1.7; margin-bottom: 16px; }
        .badge { display: inline-block; background: #FFF7ED; border: 1px solid #FDBA74; color: #C2410C; border-radius: 999px; padding: 6px 18px; font-size: 0.82rem; font-weight: 700; margin-bottom: 28px; }
        .divider { border: none; border-top: 1px solid #E5E7EB; margin: 28px 0; }
        .info-box { background: #F5F3FF; border-left: 3px solid #7C3AED; border-radius: 8px; padding: 16px 20px; margin-bottom: 24px; }
        .info-box-titulo { font-size: 1rem; font-weight: 700; color: #4C1D95; margin-bottom: 6px; }
        .info-box p { font-size: 0.88rem; color: #5B21B6; line-height: 1.6; }
        .presentacion-box { background: #FFFBEB; border: 2px solid #F59E0B; border-radius: 12px; padding: 20px 24px; margin-bottom: 24px; }
        .presentacion-titulo { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: #92400E; margin-bottom: 10px; }
        .presentacion-fecha { font-size: 1.4rem; font-weight: 900; color: #78350F; margin-bottom: 4px; }
        .presentacion-hora { font-size: 1rem; font-weight: 600; color: #92400E; }
        .detalle-table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        .detalle-table td { padding: 10px 0; border-bottom: 1px solid #E5E7EB; font-size: 0.9rem; }
        .detalle-table td:first-child { color: #6B7280; font-weight: 600; width: 40%; }
        .detalle-table td:last-child { color: #1F2937; }
        .detalle-table tr:last-child td { border-bottom: none; }
        .footer { background: #F9FAFB; padding: 24px 48px; text-align: center; }
        .footer p { font-size: 0.78rem; color: #9CA3AF; line-height: 1.6; }
        .footer strong { color: #7C3AED; }
    </style>
</head>
<body>
<div class="wrapper">

    <div class="header">
        <div class="logo">VIBEZ</div>
        <div class="header-sub">Bolsa de trabajo</div>
    </div>

    <div class="body">
        <p class="greeting">¡Hola, <span>{{ $candidatura->nombreCompleto() }}</span>!</p>

        <span class="badge">🎉 Has sido seleccionado/a</span>

        <p class="text">
            Nos complace comunicarte que, tras revisar tu candidatura,
            la empresa ha decidido <strong>seleccionarte para el siguiente puesto</strong>:
        </p>

        {{-- Info del puesto --}}
        <div class="info-box">
            <p class="info-box-titulo">{{ $candidatura->oferta->titulo }}</p>
            @if($candidatura->oferta->descripcion)
                <p>{{ $candidatura->oferta->descripcion }}</p>
            @endif
        </div>

        {{-- Datos relevantes del puesto --}}
        @php
            $filas = array_filter([
                'Ubicación'       => $candidatura->oferta->ubicacion ?? null,
                'Salario'         => ($candidatura->oferta->salario_min || $candidatura->oferta->salario_max) ? $candidatura->oferta->salario_formateado : null,
                'Inicio del trabajo' => $candidatura->oferta->fecha_inicio_trabajo
                    ? \Carbon\Carbon::parse($candidatura->oferta->fecha_inicio_trabajo)->locale('es')->isoFormat('D [de] MMMM [de] YYYY')
                    : null,
            ]);
        @endphp
        @if(count($filas))
        <table class="detalle-table">
            @foreach($filas as $etiqueta => $valor)
            <tr>
                <td>{{ $etiqueta }}</td>
                <td>{{ $valor }}</td>
            </tr>
            @endforeach
        </table>
        @endif

        {{-- Caja destacada: día y hora de presentación --}}
        @if($candidatura->oferta->fecha_inicio_trabajo)
        @php
            $fechaInicio  = \Carbon\Carbon::parse($candidatura->oferta->fecha_inicio_trabajo);
            $diaPresentacion = $fechaInicio->copy()->subDay();
        @endphp
        <div class="presentacion-box">
            <p class="presentacion-titulo">📅 Fecha de presentación</p>
            <p class="presentacion-fecha">
                {{ $diaPresentacion->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
            </p>
            <p class="presentacion-hora">
                🕐 A las {{ $fechaInicio->format('H:i') }} h
            </p>
            <p style="font-size:0.82rem;color:#92400E;margin-top:10px;">
                Deberás presentarte <strong>el día anterior al inicio del trabajo</strong>
                a la misma hora en que comienza tu jornada.
            </p>
        </div>
        @endif

        {{-- Botón de invitación al equipo --}}
        @if($urlInvitacion)
        <div style="text-align:center;margin:32px 0;">
            <p style="font-size:0.85rem;color:#4B5563;margin-bottom:16px;">
                Acepta la invitación para unirte oficialmente al equipo de la empresa en VIBEZ:
            </p>
            <a href="{{ $urlInvitacion }}"
               style="display:inline-block;background:linear-gradient(135deg,#7C3AED,#5B21B6);color:#ffffff;text-decoration:none;padding:16px 44px;border-radius:10px;font-size:1rem;font-weight:700;letter-spacing:0.03em;">
                Unirme al equipo →
            </a>
            <p style="font-size:0.75rem;color:#9CA3AF;margin-top:12px;">
                Este enlace es válido durante <strong>5 días</strong> y solo puede usarse una vez.
            </p>
        </div>
        @endif

        <p class="text">
            Si tienes alguna duda sobre el lugar de presentación u otros detalles,
            responde directamente a este correo y te atenderemos lo antes posible.
        </p>

        <hr class="divider">

        <p class="text" style="font-size:0.9rem; color:#6B7280;">
            ¡Enhorabuena y bienvenido/a al equipo! El equipo de VIBEZ te desea mucho éxito en esta nueva etapa.
        </p>
    </div>

    <div class="footer">
        <p>
            Has recibido este correo porque te postulaste a una oferta en <strong>VIBEZ</strong>.<br>
            © {{ date('Y') }} VIBEZ. Todos los derechos reservados.
        </p>
    </div>

</div>
</body>
</html>