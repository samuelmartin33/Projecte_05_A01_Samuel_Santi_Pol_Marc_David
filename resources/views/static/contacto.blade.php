@extends('layouts.app')
@section('titulo', 'Contacto — VIBEZ')
@section('contenido')

<div style="max-width:860px;margin:80px auto;padding:0 32px;">
  <div class="mono" style="font-size:11px;color:var(--magenta);margin-bottom:16px;display:flex;align-items:center;gap:10px;">
    <span style="width:28px;height:1px;background:var(--magenta);display:inline-block;"></span>
    Habla con nosotros
  </div>
  <h1 class="display" style="font-size:clamp(56px,8vw,120px);margin:0 0 48px;line-height:0.88;color:var(--ink);">
    Contac<em style="color:var(--magenta);font-style:italic;">to</em>.
  </h1>

  <div style="display:grid;grid-template-columns:1fr 1fr;gap:48px;align-items:start;">
    <div style="font-family:'Archivo Narrow',sans-serif;font-size:16px;color:var(--ink-dim);line-height:1.7;">
      <p>Estamos aquí para ayudarte. Escríbenos por el motivo que sea — soporte, colaboraciones, prensa o simplemente para decirnos que VIBEZ mola.</p>
      <div style="margin-top:32px;display:flex;flex-direction:column;gap:16px;">
        <div>
          <div class="mono" style="font-size:9px;color:var(--ink-dim);margin-bottom:4px;">SOPORTE GENERAL</div>
          <a href="mailto:hola@vibez.es" style="color:var(--magenta);font-size:15px;text-decoration:none;">hola@vibez.es</a>
        </div>
        <div>
          <div class="mono" style="font-size:9px;color:var(--ink-dim);margin-bottom:4px;">EMPRESAS Y PROMOTORES</div>
          <a href="mailto:empresa@vibez.es" style="color:var(--magenta);font-size:15px;text-decoration:none;">empresa@vibez.es</a>
        </div>
        <div>
          <div class="mono" style="font-size:9px;color:var(--ink-dim);margin-bottom:4px;">PRENSA</div>
          <a href="mailto:prensa@vibez.es" style="color:var(--magenta);font-size:15px;text-decoration:none;">prensa@vibez.es</a>
        </div>
      </div>
    </div>

    <form onsubmit="vibezContacto(event)" style="display:flex;flex-direction:column;gap:16px;">
      @csrf
      <input name="nombre" placeholder="Nombre" required
             style="background:rgba(245,241,234,0.05);border:1px solid var(--line);color:var(--ink);padding:14px 16px;font-family:'Archivo Narrow',sans-serif;font-size:14px;outline:none;width:100%;box-sizing:border-box;">
      <input name="email" type="email" placeholder="Email" required
             style="background:rgba(245,241,234,0.05);border:1px solid var(--line);color:var(--ink);padding:14px 16px;font-family:'Archivo Narrow',sans-serif;font-size:14px;outline:none;width:100%;box-sizing:border-box;">
      <textarea name="mensaje" placeholder="Mensaje" rows="5" required
                style="background:rgba(245,241,234,0.05);border:1px solid var(--line);color:var(--ink);padding:14px 16px;font-family:'Archivo Narrow',sans-serif;font-size:14px;outline:none;width:100%;box-sizing:border-box;resize:vertical;"></textarea>
      <button type="submit" class="btn-primary" style="padding:16px;border-radius:999px;font-size:15px;">
        Enviar mensaje →
      </button>
      <div id="contacto-ok" style="display:none;color:var(--magenta);font-family:'Archivo Narrow',sans-serif;font-size:13px;text-align:center;">
        ✓ Mensaje enviado. Te respondemos en 24h.
      </div>
    </form>
  </div>

  <div style="margin-top:60px;">
    <a href="{{ route('home') }}" style="display:inline-flex;align-items:center;gap:8px;color:var(--magenta);font-family:'Archivo Narrow',sans-serif;font-size:13px;text-transform:uppercase;letter-spacing:0.1em;text-decoration:none;border-bottom:1px solid var(--magenta);padding-bottom:2px;">
      ← Volver al inicio
    </a>
  </div>
</div>

<script>
function vibezContacto(e) {
  e.preventDefault();
  document.getElementById('contacto-ok').style.display = 'block';
  e.target.reset();
}
</script>

@endsection
