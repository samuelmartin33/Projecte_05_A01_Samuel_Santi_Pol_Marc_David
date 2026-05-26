@extends('layouts.app')
@section('titulo', 'Invitación aceptada — VIBEZ')
@section('content')
@include('partials.home.nav')

<div style="min-height:80vh;display:flex;align-items:center;justify-content:center;background:#07060c;padding:2rem;">
    <div style="background:#0d0a18;border:1px solid rgba(245,241,234,0.10);padding:3rem 3.5rem;max-width:520px;width:100%;text-align:center;">

        <div style="width:64px;height:64px;background:rgba(52,211,153,0.15);border:1px solid rgba(52,211,153,0.4);display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem;">
            <svg width="28" height="28" fill="none" stroke="#34d399" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
            </svg>
        </div>

        <p style="font-family:'Archivo Narrow',sans-serif;font-size:0.625rem;font-weight:700;text-transform:uppercase;letter-spacing:0.16em;color:rgba(52,211,153,0.8);margin-bottom:0.75rem;">
            Invitación aceptada
        </p>
        <h1 style="font-family:'Anton',sans-serif;font-size:2rem;color:#f5f1ea;text-transform:uppercase;letter-spacing:-0.005em;line-height:0.9;margin-bottom:1rem;">
            ¡Bienvenido al equipo!
        </h1>
        <p style="font-family:'Archivo Narrow',sans-serif;font-size:0.9375rem;color:rgba(245,241,234,0.65);line-height:1.6;margin-bottom:0.5rem;">
            Ya formas parte del equipo de <strong style="color:#c084fc;">{{ $invitacion->empresa->nombre_empresa }}</strong>.
        </p>
        <p style="font-family:'Archivo Narrow',sans-serif;font-size:0.875rem;color:rgba(245,241,234,0.45);margin-bottom:2rem;">
            Puesto: {{ $invitacion->candidatura->oferta->titulo }}
        </p>

        <a href="{{ route('home') }}"
           style="display:inline-block;background:#a855f7;color:#f5f1ea;font-family:'Anton',sans-serif;font-size:0.8125rem;text-transform:uppercase;letter-spacing:-0.005em;padding:12px 28px;text-decoration:none;transition:background 0.15s;">
            Ir al inicio
        </a>
    </div>
</div>
@endsection