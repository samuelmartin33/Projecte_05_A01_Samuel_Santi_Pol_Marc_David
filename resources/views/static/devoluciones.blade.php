@extends('layouts.app')
@section('titulo', 'Política de devoluciones — VIBEZ')
@section('contenido')

<div style="max-width:780px;margin:80px auto;padding:0 32px;">
  <div class="mono" style="font-size:11px;color:var(--magenta);margin-bottom:16px;">Legal</div>
  <h1 class="display" style="font-size:clamp(48px,7vw,100px);margin:0 0 48px;line-height:0.88;">
    Devolu<em style="color:var(--magenta);font-style:italic;">ciones</em>.
  </h1>

  <div style="font-family:'Archivo Narrow',sans-serif;font-size:16px;color:var(--ink-dim);line-height:1.75;">
    <p class="mono" style="font-size:10px;color:var(--ink-dim);margin-bottom:32px;">Última actualización: {{ date('d/m/Y') }}</p>

    @foreach([
      ['Evento cancelado', 'Si el organizador cancela el evento, recibirás el reembolso íntegro del precio de la entrada en el método de pago original en un plazo de 5-10 días hábiles. VIBEZ te notificará por email.'],
      ['Evento aplazado', 'Si el evento se pospone, tu entrada seguirá siendo válida para la nueva fecha. Si no puedes asistir, dispones de 14 días desde el anuncio del cambio para solicitar el reembolso.'],
      ['Desistimiento (compra online)', 'Conforme al Real Decreto Legislativo 1/2007, tienes derecho a desistir de la compra en un plazo de 14 días naturales desde la adquisición, salvo que el evento sea anterior a ese plazo.'],
      ['Entradas ya usadas', 'No se realizan reembolsos de entradas que ya hayan sido escaneadas y validadas en el acceso al evento.'],
      ['Cómo solicitar un reembolso', 'Escríbenos a reembolsos@vibez.es con tu número de pedido y motivo. Procesamos las solicitudes en un máximo de 48 horas laborables.'],
      ['Gastos de gestión', 'En casos de reembolso por cancelación o aplazamiento, no se cobran gastos de gestión. En caso de desistimiento voluntario del usuario, puede aplicarse una tarifa de gestión del 5% sobre el importe de la entrada.'],
    ] as [$titulo, $texto])
    <div style="margin-bottom:24px;padding:20px;border:1px solid var(--line);">
      <div class="mono" style="font-size:10px;color:var(--magenta);margin-bottom:8px;text-transform:uppercase;letter-spacing:0.1em;">{{ $titulo }}</div>
      <p style="margin:0;font-size:14px;">{{ $texto }}</p>
    </div>
    @endforeach

    <div style="margin-top:32px;padding:24px;background:rgba(168,85,247,0.08);border:1px solid rgba(168,85,247,0.3);">
      <div class="mono" style="font-size:10px;color:var(--magenta);margin-bottom:8px;">CONTACTO DIRECTO</div>
      <p style="margin:0;">Para cualquier consulta sobre reembolsos: <a href="mailto:reembolsos@vibez.es" style="color:var(--magenta);">reembolsos@vibez.es</a></p>
    </div>
  </div>

  <a href="{{ route('home') }}" style="display:inline-flex;align-items:center;gap:8px;color:var(--magenta);font-family:'Archivo Narrow',sans-serif;font-size:13px;text-transform:uppercase;letter-spacing:0.1em;text-decoration:none;border-bottom:1px solid var(--magenta);padding-bottom:2px;margin-top:40px;">
    ← Volver al inicio
  </a>
</div>

@endsection
