@extends('layouts.app')
@section('titulo', 'Manifiesto — VIBEZ')
@section('contenido')

@php
$puntos = [
    'La noche es cultura. No un accidente.',
    'El techno, el indie, el perreo y el jazz tienen el mismo derecho a una plataforma de primera.',
    'Los promotores locales merecen las mismas herramientas que las multinacionales.',
    'Una entrada tiene que ser fácil de comprar y imposible de falsificar.',
    'Los jóvenes son el público más exigente del mundo. Les debemos lo mejor.',
    'BCN nunca duerme. VIBEZ tampoco.',
];
@endphp

<div style="max-width:860px;margin:80px auto;padding:0 32px;">
  <div class="mono" style="font-size:11px;color:var(--magenta);margin-bottom:16px;display:flex;align-items:center;gap:10px;">
    <span style="width:28px;height:1px;background:var(--magenta);display:inline-block;"></span>
    Lo que nos mueve
  </div>
  <h1 class="display" style="font-size:clamp(56px,8vw,120px);margin:0 0 56px;line-height:0.88;color:var(--ink);">
    El<br><em style="color:var(--magenta);font-style:italic;">manifiesto</em>.
  </h1>

  <div style="font-family:'Archivo Narrow',sans-serif;font-size:20px;color:var(--ink-dim);line-height:1.6;max-width:700px;">
    @foreach($puntos as $i => $punto)
    <div style="display:flex;gap:20px;margin-bottom:36px;align-items:flex-start;">
      <span class="display" style="font-size:48px;color:rgba(168,85,247,0.25);line-height:1;flex-shrink:0;">
        {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}
      </span>
      <p style="margin:0;padding-top:8px;">{{ $punto }}</p>
    </div>
    @endforeach
  </div>

  <div style="margin-top:60px;">
    <a href="{{ url('/') }}" style="display:inline-flex;align-items:center;gap:8px;color:var(--magenta);font-family:'Archivo Narrow',sans-serif;font-size:13px;text-transform:uppercase;letter-spacing:0.1em;text-decoration:none;border-bottom:1px solid var(--magenta);padding-bottom:2px;">
      ← Volver
    </a>
  </div>
</div>

@endsection
