@extends('layouts.app')
@section('titulo', 'Política de cookies — VIBEZ')
@section('contenido')

<div style="max-width:780px;margin:80px auto;padding:0 32px;">
  <div class="mono" style="font-size:11px;color:var(--magenta);margin-bottom:16px;">Legal</div>
  <h1 class="display" style="font-size:clamp(48px,7vw,100px);margin:0 0 48px;line-height:0.88;">
    Cook<em style="color:var(--magenta);font-style:italic;">ies</em>.
  </h1>

  <div style="font-family:'Archivo Narrow',sans-serif;font-size:16px;color:var(--ink-dim);line-height:1.75;">
    <p class="mono" style="font-size:10px;color:var(--ink-dim);margin-bottom:32px;">Última actualización: {{ date('d/m/Y') }}</p>

    <p style="margin-bottom:24px;">VIBEZ usa cookies para que la plataforma funcione correctamente y para mejorar tu experiencia. A continuación detallamos los tipos que utilizamos:</p>

    @foreach([
      ['Cookies técnicas (esenciales)', 'Necesarias para el funcionamiento de la sesión y el carrito de compra. No pueden desactivarse.', 'Propias', 'Sesión'],
      ['Cookies de preferencias', 'Recuerdan tus ajustes (idioma, ciudad preferida, filtros). Facilitan tu experiencia sin rastrear.', 'Propias', '30 días'],
      ['Cookies analíticas', 'Nos ayudan a entender cómo se usa VIBEZ para mejorarlo. Datos anonimizados (Plausible Analytics, sin GDPR concerns).', 'Terceros', '1 año'],
    ] as [$nombre, $desc, $origen, $duracion])
    <div style="margin-bottom:24px;padding:20px;border:1px solid var(--line);">
      <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:10px;flex-wrap:wrap;gap:8px;">
        <div class="mono" style="font-size:10px;color:var(--magenta);text-transform:uppercase;letter-spacing:0.1em;">{{ $nombre }}</div>
        <div style="display:flex;gap:10px;">
          <span class="mono" style="font-size:9px;color:var(--ink-dim);background:rgba(245,241,234,0.05);padding:2px 8px;">{{ $origen }}</span>
          <span class="mono" style="font-size:9px;color:var(--ink-dim);background:rgba(245,241,234,0.05);padding:2px 8px;">{{ $duracion }}</span>
        </div>
      </div>
      <p style="margin:0;font-size:14px;">{{ $desc }}</p>
    </div>
    @endforeach

    <p style="margin-top:24px;">Puedes gestionar las cookies desde la configuración de tu navegador. Desactivar las técnicas puede afectar al funcionamiento de la plataforma.</p>
  </div>

  <a href="{{ route('home') }}" style="display:inline-flex;align-items:center;gap:8px;color:var(--magenta);font-family:'Archivo Narrow',sans-serif;font-size:13px;text-transform:uppercase;letter-spacing:0.1em;text-decoration:none;border-bottom:1px solid var(--magenta);padding-bottom:2px;margin-top:40px;">
    ← Volver al inicio
  </a>
</div>

@endsection
