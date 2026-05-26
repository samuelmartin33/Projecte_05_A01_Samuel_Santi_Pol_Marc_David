@extends('layouts.app')

@section('titulo', '¡Bienvenido a Premium! — VIBEZ')

@push('estilos')
<link rel="stylesheet" href="{{ asset('css/vibez-home.css') }}">
@endpush

@section('content')

@include('partials.home.nav')

<div style="background:radial-gradient(circle,rgba(124,58,237,0.08) 1.5px,transparent 1.5px),linear-gradient(160deg,#0d0820 0%,#130228 45%,#0d0820 100%);background-size:28px 28px,100% 100%;min-height:calc(100vh - 80px);display:flex;align-items:center;justify-content:center;padding:3rem 2rem;">

    <div class="vibe-card" style="max-width:480px;width:100%;padding:3rem 2.5rem;text-align:center;">

        {{-- Icono de éxito --}}
        <div style="width:72px;height:72px;border-radius:50%;background:rgba(124,58,237,0.15);border:1.5px solid rgba(124,58,237,0.4);display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem;">
            <svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="color:#a855f7;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
            </svg>
        </div>

        <h1 class="display" style="font-size:2rem;color:var(--ink);margin:0 0 0.75rem;">
            ¡Pago recibido!
        </h1>

        <p style="font-family:'Archivo Narrow',sans-serif;font-size:15px;color:rgba(245,241,234,0.55);line-height:1.6;margin:0 0 2rem;">
            Tu cuenta Premium se está activando. En unos segundos tendrás
            acceso a todos los cupones exclusivos de las promotoras.
        </p>

        {{--
            Nota pedagógica: la activación real se produce cuando Stripe dispara el
            webhook 'checkout.session.completed' a nuestro servidor. Puede tardar
            unos segundos en llegar después del redirect, de ahí el mensaje "en unos segundos".
        --}}
        <div class="mono" style="background:rgba(124,58,237,0.08);border:1px solid rgba(124,58,237,0.2);padding:12px 16px;border-radius:8px;font-size:9px;color:rgba(245,241,234,0.4);margin-bottom:2rem;text-align:left;">
            El pago ha sido procesado por Stripe de forma segura. Recibirás
            una confirmación por email.
        </div>

        <a href="{{ route('eventos.index') }}" class="btn-primary"
           style="display:inline-block;padding:13px 2rem;border-radius:999px;font-size:14px;text-decoration:none;">
            Ver eventos y cupones
        </a>

    </div>

</div>

@include('partials.home.footer')

@endsection
