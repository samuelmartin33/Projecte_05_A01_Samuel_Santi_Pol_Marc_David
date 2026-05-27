@extends('layouts.app')

@section('titulo', 'VIBEZ Premium — Cupones exclusivos')

@push('estilos')
<link rel="stylesheet" href="{{ asset('css/vibez-home.css') }}">
@endpush

@section('content')

{{-- Nav reutilizado del home --}}
@include('partials.home.nav')

{{-- ── Hero ── --}}
<div style="background:rgba(7,6,12,0.95);border-bottom:1px solid var(--line);padding:2rem 2rem 1.5rem;">
    <div style="max-width:900px;margin:0 auto;">
        <div class="mono" style="font-size:9px;color:var(--magenta);margin-bottom:0.5rem;display:flex;align-items:center;gap:8px;">
            <span style="width:18px;height:1px;background:var(--magenta);display:inline-block;"></span>
            MEMBRESÍA
        </div>
        <h1 class="display" style="font-size:clamp(2rem,5vw,3.5rem);color:var(--ink);margin:0;line-height:1.1;">
            VIBEZ <span style="background:linear-gradient(90deg,#7c3aed,#a855f7);-webkit-background-clip:text;-webkit-text-fill-color:transparent;">Premium</span>
        </h1>
    </div>
</div>

{{-- ── Cuerpo ── --}}
<div style="background:radial-gradient(circle,rgba(124,58,237,0.08) 1.5px,transparent 1.5px),linear-gradient(160deg,#0d0820 0%,#130228 45%,#0d0820 100%);background-size:28px 28px,100% 100%;min-height:calc(100vh - 160px);padding:3rem 2rem;">
<div style="max-width:900px;margin:0 auto;display:grid;grid-template-columns:1fr 380px;gap:2.5rem;align-items:start;">

    {{-- ── Columna izquierda: beneficios ── --}}
    <div>

        {{-- Banner de incentivo cuando el usuario viene desde la sección de cupones --}}
        @if(session('desde_cupones'))
        <div style="background:linear-gradient(135deg,rgba(124,58,237,0.18),rgba(168,85,247,0.1));border:1.5px solid rgba(168,85,247,0.45);padding:1rem 1.25rem;border-radius:12px;margin-bottom:1.5rem;display:flex;align-items:flex-start;gap:14px;">
            <div style="font-size:24px;line-height:1;flex-shrink:0;">🎟️</div>
            <div>
                <div style="font-family:'Archivo Narrow',sans-serif;font-size:14px;font-weight:700;color:#e9d5ff;margin-bottom:4px;">
                    Los cupones son exclusivos para miembros Premium
                </div>
                <div style="font-family:'Archivo Narrow',sans-serif;font-size:13px;color:rgba(245,241,234,0.55);line-height:1.5;">
                    Por solo <strong style="color:#a855f7;">5€ únicos</strong> desbloqueas todos los códigos de descuento de las promotoras en VIBEZ. Sin renovaciones.
                </div>
            </div>
        </div>
        @endif

        {{-- Flash: mensaje informativo (cancelación o ya-premium) --}}
        @if(session('info'))
        <div style="background:rgba(168,85,247,0.1);border:1px solid rgba(168,85,247,0.3);color:rgba(245,241,234,0.8);padding:12px 16px;font-family:'Archivo Narrow',sans-serif;font-size:13px;border-radius:8px;margin-bottom:1.5rem;">
            {{ session('info') }}
        </div>
        @endif

        {{-- Flash: error de Stripe --}}
        @if(session('error'))
        <div style="background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.25);color:#f87171;padding:12px 16px;font-family:'Archivo Narrow',sans-serif;font-size:13px;border-radius:8px;margin-bottom:1.5rem;">
            {{ session('error') }}
        </div>
        @endif

        {{-- Badge ya premium --}}
        @if($usuario->es_premium)
        <div style="background:rgba(124,58,237,0.15);border:1.5px solid rgba(124,58,237,0.5);padding:1.25rem 1.5rem;border-radius:12px;margin-bottom:2rem;display:flex;align-items:center;gap:14px;">
            <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="color:#a855f7;flex-shrink:0;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
            </svg>
            <div>
                <div class="display" style="font-size:1rem;color:var(--ink);margin-bottom:4px;">¡Ya eres Premium!</div>
                <div class="mono" style="font-size:10px;color:rgba(245,241,234,0.5);">Tienes acceso a todos los cupones exclusivos de las promotoras.</div>
            </div>
        </div>
        @endif

        <h2 class="display" style="font-size:1.6rem;color:var(--ink);margin:0 0 0.5rem;">
            Cupones exclusivos.<br>Solo para ti.
        </h2>
        <p style="font-family:'Archivo Narrow',sans-serif;font-size:15px;color:rgba(245,241,234,0.55);margin:0 0 2.5rem;line-height:1.6;">
            Haciéndote Premium de VIBEZ desbloqueas los códigos de descuento que las promotoras
            publican solo para miembros. Un único pago, sin renovaciones.
        </p>

        {{-- Lista de beneficios --}}
        <div style="display:flex;flex-direction:column;gap:16px;">

            @php
            $beneficios = [
                ['icono' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z', 'titulo' => 'Cupones exclusivos de promotoras', 'desc' => 'Las empresas publican descuentos especiales únicamente visibles para usuarios Premium.'],
                ['icono' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'titulo' => 'Pago único de 5€', 'desc' => 'Sin suscripciones ni renovaciones automáticas. Pagas una vez y es tuyo para siempre.'],
                ['icono' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z', 'titulo' => 'Pago seguro con Stripe', 'desc' => 'VIBEZ nunca ve tus datos de tarjeta. El pago lo procesa Stripe con cifrado bancario.'],
            ];
            @endphp

            @foreach($beneficios as $b)
            <div style="display:flex;align-items:flex-start;gap:14px;">
                <div style="width:36px;height:36px;border-radius:8px;background:rgba(124,58,237,0.15);border:1px solid rgba(124,58,237,0.3);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="color:#a855f7;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $b['icono'] }}"/>
                    </svg>
                </div>
                <div>
                    <div style="font-family:'Archivo Narrow',sans-serif;font-size:14px;font-weight:600;color:var(--ink);margin-bottom:4px;">{{ $b['titulo'] }}</div>
                    <div style="font-family:'Archivo Narrow',sans-serif;font-size:13px;color:rgba(245,241,234,0.45);line-height:1.5;">{{ $b['desc'] }}</div>
                </div>
            </div>
            @endforeach

        </div>

    </div>

    {{-- ── Columna derecha: panel de pago ── --}}
    <div class="vibe-card" style="padding:2rem;position:sticky;top:90px;">

        <div class="mono" style="font-size:10px;color:var(--magenta);margin-bottom:1.5rem;padding-bottom:1rem;border-bottom:1px solid var(--line);display:flex;align-items:center;gap:8px;">
            <span style="width:18px;height:1px;background:var(--magenta);display:inline-block;"></span>
            Hazte Premium
        </div>

        {{-- Precio --}}
        <div style="text-align:center;margin-bottom:2rem;">
            <div class="display" style="font-size:3.5rem;color:var(--magenta);line-height:1;">5,00 €</div>
            <div class="mono" style="font-size:9px;color:rgba(245,241,234,0.3);margin-top:8px;">Pago único · Sin renovaciones</div>
        </div>

        @if($usuario->es_premium)
            {{-- El usuario ya pagó: no mostramos el botón de pago --}}
            <div style="background:rgba(124,58,237,0.12);border:1px solid rgba(124,58,237,0.35);color:#a855f7;padding:14px 16px;font-family:'Archivo Narrow',sans-serif;font-size:13px;border-radius:8px;text-align:center;">
                ✓ Membresía activa
            </div>
        @else
            {{--
                Formulario POST hacia /premium/checkout.
                Al hacer submit, el servidor crea la Checkout Session de Stripe
                y devuelve un redirect a la URL de pago de Stripe.
                No se necesita JS: es un formulario HTML estándar.
            --}}
            <form action="{{ route('premium.checkout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-primary"
                        style="width:100%;padding:15px;border-radius:999px;font-size:14px;cursor:pointer;border:none;">
                    Hacerme Premium — 5,00 €
                </button>
            </form>

            <p class="mono" style="text-align:center;font-size:9px;color:rgba(245,241,234,0.2);margin-top:1rem;">
                Pago seguro · Procesado por Stripe
            </p>

            @if(app()->environment('local'))
            <div class="mono" style="background:rgba(168,85,247,0.06);border:1px solid rgba(168,85,247,0.15);padding:10px 14px;border-radius:8px;margin-top:1rem;font-size:9px;color:rgba(245,241,234,0.35);">
                Tarjeta de prueba: 4242 4242 4242 4242 · Cualquier fecha · Cualquier CVC
            </div>
            @endif
        @endif

    </div>

</div>
</div>

@include('partials.home.footer')

@endsection
