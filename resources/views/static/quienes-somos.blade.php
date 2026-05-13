@extends('layouts.app')
@section('titulo', 'Quiénes somos — VIBEZ')
@section('contenido')

<div style="max-width:860px;margin:80px auto;padding:0 32px;">
  <div class="mono" style="font-size:11px;color:var(--magenta);margin-bottom:16px;display:flex;align-items:center;gap:10px;">
    <span style="width:28px;height:1px;background:var(--magenta);display:inline-block;"></span>
    Sobre VIBEZ
  </div>
  <h1 class="display" style="font-size:clamp(56px,8vw,120px);margin:0 0 40px;line-height:0.88;color:var(--ink);">
    Quiénes<br><em style="color:var(--magenta);font-style:italic;">somos</em>.
  </h1>

  <div style="font-family:'Archivo Narrow',sans-serif;font-size:18px;color:var(--ink-dim);line-height:1.7;max-width:680px;">
    <p>VIBEZ nació en 2024 en Barcelona con una idea simple: que descubrir eventos y comprar entradas no sea un trámite, sino parte de la experiencia.</p>
    <p style="margin-top:24px;">Somos un equipo joven obsesionado con la música, la cultura y la tecnología. Creemos que la escena nocturna merece una plataforma a su altura: sin comisiones abusivas, sin interfaces obsoletas y sin barreras entre el público y los promotores.</p>
    <p style="margin-top:24px;">VIBEZ conecta a artistas, organizadores, empresas y asistentes en un solo lugar. Desde el cartel hasta el QR de entrada — todo en tu bolsillo.</p>
  </div>

  <div style="margin-top:60px;display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:24px;">
    @foreach([['2024', 'Año de fundación'], ['BCN', 'Ciudad de origen'], ['16-35', 'Rango de edad'], ['0%', 'Comisión primeros 30 días']] as [$stat, $label])
    <div style="border:1px solid var(--line);padding:24px;">
      <div class="display" style="font-size:56px;color:var(--magenta);line-height:1;">{{ $stat }}</div>
      <div class="mono" style="font-size:10px;color:var(--ink-dim);margin-top:8px;text-transform:uppercase;letter-spacing:0.1em;">{{ $label }}</div>
    </div>
    @endforeach
  </div>

  <div style="margin-top:60px;">
    <a href="{{ route('home') }}" style="display:inline-flex;align-items:center;gap:8px;color:var(--magenta);font-family:'Archivo Narrow',sans-serif;font-size:13px;text-transform:uppercase;letter-spacing:0.1em;text-decoration:none;border-bottom:1px solid var(--magenta);padding-bottom:2px;">
      ← Volver al inicio
    </a>
  </div>
</div>

@endsection
