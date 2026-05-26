@extends('layouts.app')
@section('titulo', 'Enlace inválido — VIBEZ')
@section('content')
@include('partials.home.nav')

<div style="min-height:80vh;display:flex;align-items:center;justify-content:center;background:#07060c;padding:2rem;">
    <div style="background:#0d0a18;border:1px solid rgba(245,241,234,0.10);padding:3rem 3.5rem;max-width:520px;width:100%;text-align:center;">

        <div style="width:64px;height:64px;background:rgba(148,163,184,0.12);border:1px solid rgba(148,163,184,0.25);display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem;">
            <svg width="28" height="28" fill="none" stroke="#94a3b8" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </div>

        <p style="font-family:'Archivo Narrow',sans-serif;font-size:0.625rem;font-weight:700;text-transform:uppercase;letter-spacing:0.16em;color:rgba(148,163,184,0.7);margin-bottom:0.75rem;">
            Enlace no reconocido
        </p>
        <h1 style="font-family:'Anton',sans-serif;font-size:2rem;color:#f5f1ea;text-transform:uppercase;letter-spacing:-0.005em;line-height:0.9;margin-bottom:1rem;">
            Invitación no encontrada
        </h1>
        <p style="font-family:'Archivo Narrow',sans-serif;font-size:0.9375rem;color:rgba(245,241,234,0.65);line-height:1.6;margin-bottom:2rem;">
            El enlace de invitación no existe o es incorrecto.<br>
            Comprueba que has copiado correctamente la URL del correo.
        </p>

        <a href="{{ route('welcome') }}"
           style="display:inline-block;background:rgba(245,241,234,0.08);border:1px solid rgba(245,241,234,0.15);color:rgba(245,241,234,0.65);font-family:'Anton',sans-serif;font-size:0.8125rem;text-transform:uppercase;letter-spacing:-0.005em;padding:12px 28px;text-decoration:none;">
            Volver al inicio
        </a>
    </div>
</div>
@endsection