@extends('layouts.app')

@section('titulo', 'Perfil de empresa — VIBEZ')

@push('estilos')
<link rel="stylesheet" href="{{ asset('css/empresa-home.css') }}">
@endpush

@section('content')

<link rel="stylesheet" href="{{ asset('css/vibez-home.css') }}">

@include('partials.home.nav')

<div style="min-height:100vh;background:#07060c;padding:48px 24px 80px;">
<div style="max-width:760px;margin:0 auto;">

    {{-- Cabecera --}}
    <div style="margin-bottom:36px;">
        <a href="{{ route('empresa.home') }}"
           style="display:inline-flex;align-items:center;gap:6px;font-family:'Archivo Narrow',sans-serif;font-size:12px;color:rgba(245,241,234,0.4);text-decoration:none;margin-bottom:20px;transition:color 0.15s;"
           onmouseover="this.style.color='rgba(245,241,234,0.75)'" onmouseout="this.style.color='rgba(245,241,234,0.4)'">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            Volver al panel
        </a>
        <p class="mono" style="font-size:10px;color:rgba(168,85,247,0.8);letter-spacing:0.2em;margin-bottom:10px;">— PERFIL PÚBLICO</p>
        <h1 class="display" style="font-size:clamp(2rem,5vw,3rem);color:#f5f1ea;line-height:0.95;margin-bottom:12px;">
            Editar perfil<br><em style="color:#a855f7;">de empresa</em>
        </h1>
        <p style="font-family:'Archivo Narrow',sans-serif;font-size:15px;color:rgba(245,241,234,0.5);max-width:520px;line-height:1.6;">
            Nombre comercial, descripción, logo y datos de contacto que aparecen en la plataforma.
        </p>
    </div>

    {{-- Alerta de éxito --}}
    @if(session('success'))
    <div style="background:rgba(16,185,129,0.12);border:1px solid rgba(16,185,129,0.35);border-radius:10px;padding:14px 18px;color:#6ee7b7;font-family:'Archivo Narrow',sans-serif;font-size:14px;margin-bottom:24px;">
        {{ session('success') }}
    </div>
    @endif

    {{-- Errores de validación --}}
    @if($errors->any())
    <div style="background:rgba(239,68,68,0.10);border:1px solid rgba(239,68,68,0.3);border-radius:10px;padding:14px 18px;color:#fca5a5;font-family:'Archivo Narrow',sans-serif;font-size:13px;margin-bottom:24px;">
        <strong>Corrige los siguientes errores:</strong>
        <ul style="margin:8px 0 0 18px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Formulario --}}
    <form method="POST" action="{{ route('empresa.perfil.guardar') }}" enctype="multipart/form-data" novalidate>
        @csrf

        {{-- ── Logo actual + subida ── --}}
        <div style="background:rgba(255,255,255,0.03);border:1px solid rgba(245,241,234,0.08);border-radius:12px;padding:28px 32px;margin-bottom:20px;">
            <p style="font-family:'Archivo Narrow',sans-serif;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.14em;color:rgba(168,85,247,0.8);margin-bottom:20px;">
                Logo de empresa
            </p>

            {{-- Vista previa del logo actual --}}
            <div style="display:flex;align-items:center;gap:20px;margin-bottom:20px;">
                <div id="logo-preview-wrap" style="width:80px;height:80px;border-radius:12px;overflow:hidden;border:1px solid rgba(245,241,234,0.12);background:rgba(168,85,247,0.08);flex-shrink:0;display:flex;align-items:center;justify-content:center;">
                    @if($empresa->logo_url)
                        <img id="logo-preview" src="{{ Storage::url($empresa->logo_url) }}" alt="Logo" style="width:100%;height:100%;object-fit:cover;">
                    @else
                        <svg id="logo-placeholder" width="32" height="32" fill="none" stroke="rgba(168,85,247,0.5)" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
                        </svg>
                    @endif
                </div>
                <div>
                    <label for="logo"
                           style="display:inline-block;cursor:pointer;background:rgba(168,85,247,0.12);border:1px solid rgba(168,85,247,0.35);color:#c084fc;font-family:'Archivo Narrow',sans-serif;font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;padding:8px 16px;border-radius:6px;transition:background 0.15s;"
                           onmouseover="this.style.background='rgba(168,85,247,0.22)'" onmouseout="this.style.background='rgba(168,85,247,0.12)'">
                        Cambiar logo
                    </label>
                    <input type="file" id="logo" name="logo" accept="image/jpeg,image/png,image/webp"
                           style="display:none;" onchange="previsualizarLogo(this)">
                    <p style="font-family:'Archivo Narrow',sans-serif;font-size:11px;color:rgba(245,241,234,0.3);margin-top:8px;">
                        JPG, PNG o WebP · Máx. 2 MB
                    </p>
                </div>
            </div>
        </div>

        {{-- ── Datos públicos ── --}}
        <div style="background:rgba(255,255,255,0.03);border:1px solid rgba(245,241,234,0.08);border-radius:12px;padding:28px 32px;margin-bottom:20px;">
            <p style="font-family:'Archivo Narrow',sans-serif;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.14em;color:rgba(168,85,247,0.8);margin-bottom:24px;">
                Información pública
            </p>

            {{-- Nombre comercial --}}
            <div style="margin-bottom:20px;">
                <label for="nombre_empresa"
                       style="display:block;font-family:'Archivo Narrow',sans-serif;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.12em;color:rgba(245,241,234,0.45);margin-bottom:8px;">
                    Nombre comercial <span style="color:#a855f7;">*</span>
                </label>
                <input type="text" id="nombre_empresa" name="nombre_empresa"
                       value="{{ old('nombre_empresa', $empresa->nombre_empresa) }}"
                       placeholder="Ej: VIBEZ Events"
                       style="width:100%;background:rgba(255,255,255,0.04);border:1px solid rgba(245,241,234,{{ $errors->has('nombre_empresa') ? '0.5' : '0.14' }});border-radius:8px;padding:11px 14px;color:#f5f1ea;font-family:'Archivo Narrow',sans-serif;font-size:14px;outline:none;box-sizing:border-box;transition:border-color 0.15s;"
                       onfocus="this.style.borderColor='rgba(168,85,247,0.6)'" onblur="this.style.borderColor='rgba(245,241,234,0.14)'"
                       required>
                @error('nombre_empresa')
                    <p style="font-family:'Archivo Narrow',sans-serif;font-size:12px;color:#f87171;margin-top:5px;">{{ $message }}</p>
                @enderror
            </div>

            {{-- Descripción --}}
            <div style="margin-bottom:20px;">
                <label for="descripcion"
                       style="display:block;font-family:'Archivo Narrow',sans-serif;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.12em;color:rgba(245,241,234,0.45);margin-bottom:8px;">
                    Descripción
                </label>
                <textarea id="descripcion" name="descripcion" rows="4"
                          placeholder="Describe brevemente tu empresa (aparece en tu perfil público)..."
                          style="width:100%;background:rgba(255,255,255,0.04);border:1px solid rgba(245,241,234,0.14);border-radius:8px;padding:11px 14px;color:#f5f1ea;font-family:'Archivo Narrow',sans-serif;font-size:14px;outline:none;resize:vertical;box-sizing:border-box;transition:border-color 0.15s;"
                          onfocus="this.style.borderColor='rgba(168,85,247,0.6)'" onblur="this.style.borderColor='rgba(245,241,234,0.14)'"
                          >{{ old('descripcion', $empresa->descripcion) }}</textarea>
                @error('descripcion')
                    <p style="font-family:'Archivo Narrow',sans-serif;font-size:12px;color:#f87171;margin-top:5px;">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- ── Contacto ── --}}
        <div style="background:rgba(255,255,255,0.03);border:1px solid rgba(245,241,234,0.08);border-radius:12px;padding:28px 32px;margin-bottom:32px;">
            <p style="font-family:'Archivo Narrow',sans-serif;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.14em;color:rgba(168,85,247,0.8);margin-bottom:24px;">
                Contacto
            </p>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">

                {{-- Sitio web --}}
                <div>
                    <label for="sitio_web"
                           style="display:block;font-family:'Archivo Narrow',sans-serif;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.12em;color:rgba(245,241,234,0.45);margin-bottom:8px;">
                        Sitio web
                    </label>
                    <input type="url" id="sitio_web" name="sitio_web"
                           value="{{ old('sitio_web', $empresa->sitio_web) }}"
                           placeholder="https://miempresa.com"
                           style="width:100%;background:rgba(255,255,255,0.04);border:1px solid rgba(245,241,234,0.14);border-radius:8px;padding:11px 14px;color:#f5f1ea;font-family:'Archivo Narrow',sans-serif;font-size:14px;outline:none;box-sizing:border-box;transition:border-color 0.15s;"
                           onfocus="this.style.borderColor='rgba(168,85,247,0.6)'" onblur="this.style.borderColor='rgba(245,241,234,0.14)'">
                    @error('sitio_web')
                        <p style="font-family:'Archivo Narrow',sans-serif;font-size:12px;color:#f87171;margin-top:5px;">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Teléfono --}}
                <div>
                    <label for="telefono_contacto"
                           style="display:block;font-family:'Archivo Narrow',sans-serif;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.12em;color:rgba(245,241,234,0.45);margin-bottom:8px;">
                        Teléfono de contacto
                    </label>
                    <input type="text" id="telefono_contacto" name="telefono_contacto"
                           value="{{ old('telefono_contacto', $empresa->telefono_contacto) }}"
                           placeholder="+34 600 000 000"
                           style="width:100%;background:rgba(255,255,255,0.04);border:1px solid rgba(245,241,234,0.14);border-radius:8px;padding:11px 14px;color:#f5f1ea;font-family:'Archivo Narrow',sans-serif;font-size:14px;outline:none;box-sizing:border-box;transition:border-color 0.15s;"
                           onfocus="this.style.borderColor='rgba(168,85,247,0.6)'" onblur="this.style.borderColor='rgba(245,241,234,0.14)'">
                    @error('telefono_contacto')
                        <p style="font-family:'Archivo Narrow',sans-serif;font-size:12px;color:#f87171;margin-top:5px;">{{ $message }}</p>
                    @enderror
                </div>

            </div>
        </div>

        {{-- ── Botones ── --}}
        <div style="display:flex;align-items:center;gap:12px;">
            <button type="submit"
                    style="background:linear-gradient(135deg,#7c3aed,#a855f7);color:#fff;font-family:'Archivo Narrow',sans-serif;font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;padding:13px 32px;border:none;border-radius:8px;cursor:pointer;transition:opacity 0.15s;"
                    onmouseover="this.style.opacity='0.88'" onmouseout="this.style.opacity='1'">
                Guardar cambios
            </button>
            <a href="{{ route('empresa.home') }}"
               style="font-family:'Archivo Narrow',sans-serif;font-size:13px;color:rgba(245,241,234,0.4);text-decoration:none;padding:13px 20px;border:1px solid rgba(245,241,234,0.10);border-radius:8px;transition:color 0.15s,border-color 0.15s;"
               onmouseover="this.style.color='rgba(245,241,234,0.75)';this.style.borderColor='rgba(245,241,234,0.25)'"
               onmouseout="this.style.color='rgba(245,241,234,0.4)';this.style.borderColor='rgba(245,241,234,0.10)'">
                Cancelar
            </a>
        </div>

    </form>

    {{-- Enlace al perfil fiscal --}}
    <div style="margin-top:36px;padding-top:24px;border-top:1px solid rgba(245,241,234,0.07);">
        <p style="font-family:'Archivo Narrow',sans-serif;font-size:13px;color:rgba(245,241,234,0.35);">
            ¿Quieres actualizar tus datos legales y bancarios?
            <a href="{{ route('empresa.perfil-fiscal') }}"
               style="color:rgba(168,85,247,0.8);text-decoration:underline;margin-left:4px;transition:color 0.15s;"
               onmouseover="this.style.color='#a855f7'" onmouseout="this.style.color='rgba(168,85,247,0.8)'">
                Ir a perfil fiscal →
            </a>
        </p>
    </div>

</div>
</div>

@endsection

@push('scripts')
<script>
/**
 * Previsualiza el logo seleccionado antes de guardar.
 * Llamado desde onchange del input file.
 */
function previsualizarLogo(input) {
    if (!input.files || !input.files[0]) return;

    var reader = new FileReader();
    reader.onload = function(e) {
        var wrap = document.getElementById('logo-preview-wrap');
        var placeholder = document.getElementById('logo-placeholder');
        var preview = document.getElementById('logo-preview');

        if (placeholder) placeholder.style.display = 'none';

        if (!preview) {
            preview = document.createElement('img');
            preview.id = 'logo-preview';
            preview.style.cssText = 'width:100%;height:100%;object-fit:cover;';
            wrap.appendChild(preview);
        }

        preview.src = e.target.result;
    };
    reader.readAsDataURL(input.files[0]);
}
</script>
@endpush