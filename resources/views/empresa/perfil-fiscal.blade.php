@extends('layouts.app')

@section('titulo', 'Perfil fiscal — VIBEZ')

@push('estilos')
<link rel="stylesheet" href="{{ asset('css/empresa-home.css') }}">
@endpush

@section('content')

<link rel="stylesheet" href="{{ asset('css/vibez-home.css') }}">

@include('partials.home.nav')

<div style="min-height:100vh;background:#07060c;padding:48px 24px 80px;">
<div style="max-width:860px;margin:0 auto;">

    {{-- Cabecera --}}
    <div style="margin-bottom:40px;">
        <p class="mono" style="font-size:10px;color:rgba(168,85,247,0.8);letter-spacing:0.2em;margin-bottom:12px;">— DATOS LEGALES Y BANCARIOS</p>
        <h1 class="display" style="font-size:clamp(2rem,5vw,3.5rem);color:#f5f1ea;line-height:0.9;margin-bottom:16px;">
            Completa tu<br><em style="color:var(--magenta);font-family:'Bebas Neue',sans-serif;">perfil fiscal</em>
        </h1>
        <p style="font-family:'Archivo Narrow',sans-serif;font-size:15px;color:rgba(245,241,234,0.55);max-width:540px;">
            Necesitamos tus datos legales y bancarios para gestionar los pagos de tus entradas. Solo lo pedimos una vez.
        </p>
    </div>

    {{-- Alertas --}}
    @if (session('success'))
        <div style="background:rgba(16,185,129,0.12);border:1px solid rgba(16,185,129,0.35);border-radius:10px;padding:14px 18px;color:#6ee7b7;font-family:'Archivo Narrow',sans-serif;font-size:14px;margin-bottom:24px;">
            {{ session('success') }}
        </div>
    @endif
    @if ($errors->any())
        <div style="background:rgba(239,68,68,0.10);border:1px solid rgba(239,68,68,0.3);border-radius:10px;padding:14px 18px;color:#fca5a5;font-family:'Archivo Narrow',sans-serif;font-size:13px;margin-bottom:24px;">
            <strong>Corrige los siguientes errores:</strong>
            <ul style="margin:8px 0 0 18px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('empresa.perfil-fiscal.guardar') }}" novalidate>
        @csrf

        {{-- ═══════════════════════════════════
             BLOQUE A — Datos legales
        ═══════════════════════════════════ --}}
        <div style="background:rgba(255,255,255,0.03);border:1px solid rgba(245,241,234,0.08);border-radius:16px;padding:32px;margin-bottom:24px;">
            <p class="mono" style="font-size:10px;color:rgba(168,85,247,0.7);letter-spacing:0.18em;margin-bottom:20px;">A · DATOS LEGALES</p>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px;">
                {{-- Razón social --}}
                <div>
                    <label style="display:block;font-family:'Archivo Narrow',sans-serif;font-size:11px;text-transform:uppercase;letter-spacing:0.1em;color:rgba(245,241,234,0.55);margin-bottom:6px;">
                        Razón social *
                    </label>
                    <input type="text" name="razon_social" value="{{ old('razon_social', $empresa->razon_social ?? '') }}"
                           placeholder="Razzmatazz Entertainment S.L."
                           style="width:100%;background:rgba(255,255,255,0.04);border:1px solid rgba(245,241,234,0.14);border-radius:8px;padding:11px 14px;color:#f5f1ea;font-family:'Archivo Narrow',sans-serif;font-size:14px;outline:none;">
                </div>

                {{-- NIF / CIF --}}
                <div>
                    <label style="display:block;font-family:'Archivo Narrow',sans-serif;font-size:11px;text-transform:uppercase;letter-spacing:0.1em;color:rgba(245,241,234,0.55);margin-bottom:6px;">
                        NIF / CIF *
                    </label>
                    <input type="text" name="nif_cif" value="{{ old('nif_cif', $empresa->nif_cif ?? '') }}"
                           placeholder="B12345678" maxlength="9"
                           oninput="this.value=this.value.toUpperCase()"
                           style="width:100%;background:rgba(255,255,255,0.04);border:1px solid rgba(245,241,234,0.14);border-radius:8px;padding:11px 14px;color:#f5f1ea;font-family:'Archivo Narrow',sans-serif;font-size:14px;outline:none;">
                </div>
            </div>

            {{-- Tipo de empresa --}}
            <div>
                <label style="display:block;font-family:'Archivo Narrow',sans-serif;font-size:11px;text-transform:uppercase;letter-spacing:0.1em;color:rgba(245,241,234,0.55);margin-bottom:6px;">
                    Forma jurídica *
                </label>
                <select name="tipo_empresa"
                        style="width:100%;background:rgba(255,255,255,0.04);border:1px solid rgba(245,241,234,0.14);border-radius:8px;padding:11px 14px;color:#f5f1ea;font-family:'Archivo Narrow',sans-serif;font-size:14px;outline:none;">
                    <option value="" disabled {{ old('tipo_empresa', $empresa->tipo_empresa ?? '') === '' ? 'selected' : '' }}>Selecciona la forma jurídica</option>
                    <option value="autonomo"    {{ old('tipo_empresa', $empresa->tipo_empresa ?? '') === 'autonomo'    ? 'selected' : '' }}>Autónomo</option>
                    <option value="sl"          {{ old('tipo_empresa', $empresa->tipo_empresa ?? '') === 'sl'          ? 'selected' : '' }}>Sociedad Limitada (S.L.)</option>
                    <option value="sa"          {{ old('tipo_empresa', $empresa->tipo_empresa ?? '') === 'sa'          ? 'selected' : '' }}>Sociedad Anónima (S.A.)</option>
                    <option value="asociacion"  {{ old('tipo_empresa', $empresa->tipo_empresa ?? '') === 'asociacion'  ? 'selected' : '' }}>Asociación</option>
                    <option value="otro"        {{ old('tipo_empresa', $empresa->tipo_empresa ?? '') === 'otro'        ? 'selected' : '' }}>Otro</option>
                </select>
            </div>
        </div>

        {{-- ═══════════════════════════════════
             BLOQUE B — Dirección fiscal
        ═══════════════════════════════════ --}}
        <div style="background:rgba(255,255,255,0.03);border:1px solid rgba(245,241,234,0.08);border-radius:16px;padding:32px;margin-bottom:24px;">
            <p class="mono" style="font-size:10px;color:rgba(168,85,247,0.7);letter-spacing:0.18em;margin-bottom:20px;">B · DIRECCIÓN FISCAL</p>

            {{-- Dirección --}}
            <div style="margin-bottom:20px;">
                <label style="display:block;font-family:'Archivo Narrow',sans-serif;font-size:11px;text-transform:uppercase;letter-spacing:0.1em;color:rgba(245,241,234,0.55);margin-bottom:6px;">
                    Calle y número *
                </label>
                <input type="text" name="direccion" value="{{ old('direccion', $empresa->direccion ?? '') }}"
                       placeholder="Calle Almogàvers, 122"
                       style="width:100%;background:rgba(255,255,255,0.04);border:1px solid rgba(245,241,234,0.14);border-radius:8px;padding:11px 14px;color:#f5f1ea;font-family:'Archivo Narrow',sans-serif;font-size:14px;outline:none;">
            </div>

            <div style="display:grid;grid-template-columns:1fr 120px 1fr;gap:16px;margin-bottom:20px;">
                {{-- Ciudad --}}
                <div>
                    <label style="display:block;font-family:'Archivo Narrow',sans-serif;font-size:11px;text-transform:uppercase;letter-spacing:0.1em;color:rgba(245,241,234,0.55);margin-bottom:6px;">Ciudad *</label>
                    <input type="text" name="ciudad" value="{{ old('ciudad', $empresa->ciudad ?? '') }}"
                           placeholder="Barcelona"
                           style="width:100%;background:rgba(255,255,255,0.04);border:1px solid rgba(245,241,234,0.14);border-radius:8px;padding:11px 14px;color:#f5f1ea;font-family:'Archivo Narrow',sans-serif;font-size:14px;outline:none;">
                </div>

                {{-- Código postal --}}
                <div>
                    <label style="display:block;font-family:'Archivo Narrow',sans-serif;font-size:11px;text-transform:uppercase;letter-spacing:0.1em;color:rgba(245,241,234,0.55);margin-bottom:6px;">CP *</label>
                    <input type="text" name="codigo_postal" value="{{ old('codigo_postal', $empresa->codigo_postal ?? '') }}"
                           placeholder="08013" maxlength="5" pattern="[0-9]{5}"
                           oninput="this.value=this.value.replace(/[^0-9]/g,'')"
                           style="width:100%;background:rgba(255,255,255,0.04);border:1px solid rgba(245,241,234,0.14);border-radius:8px;padding:11px 14px;color:#f5f1ea;font-family:'Archivo Narrow',sans-serif;font-size:14px;outline:none;">
                </div>

                {{-- Provincia --}}
                <div>
                    <label style="display:block;font-family:'Archivo Narrow',sans-serif;font-size:11px;text-transform:uppercase;letter-spacing:0.1em;color:rgba(245,241,234,0.55);margin-bottom:6px;">Provincia *</label>
                    <input type="text" name="provincia" value="{{ old('provincia', $empresa->provincia ?? '') }}"
                           placeholder="Barcelona"
                           style="width:100%;background:rgba(255,255,255,0.04);border:1px solid rgba(245,241,234,0.14);border-radius:8px;padding:11px 14px;color:#f5f1ea;font-family:'Archivo Narrow',sans-serif;font-size:14px;outline:none;">
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
                {{-- País --}}
                <div>
                    <label style="display:block;font-family:'Archivo Narrow',sans-serif;font-size:11px;text-transform:uppercase;letter-spacing:0.1em;color:rgba(245,241,234,0.55);margin-bottom:6px;">País *</label>
                    <input type="text" name="pais" value="{{ old('pais', $empresa->pais ?? 'España') }}"
                           placeholder="España"
                           style="width:100%;background:rgba(255,255,255,0.04);border:1px solid rgba(245,241,234,0.14);border-radius:8px;padding:11px 14px;color:#f5f1ea;font-family:'Archivo Narrow',sans-serif;font-size:14px;outline:none;">
                </div>

                {{-- Email de facturación --}}
                <div>
                    <label style="display:block;font-family:'Archivo Narrow',sans-serif;font-size:11px;text-transform:uppercase;letter-spacing:0.1em;color:rgba(245,241,234,0.55);margin-bottom:6px;">Email para facturas *</label>
                    <input type="email" name="email_facturacion" value="{{ old('email_facturacion', $empresa->email_facturacion ?? '') }}"
                           placeholder="facturacion@empresa.com"
                           style="width:100%;background:rgba(255,255,255,0.04);border:1px solid rgba(245,241,234,0.14);border-radius:8px;padding:11px 14px;color:#f5f1ea;font-family:'Archivo Narrow',sans-serif;font-size:14px;outline:none;">
                    <p style="font-family:'Archivo Narrow',sans-serif;font-size:11px;color:rgba(245,241,234,0.35);margin-top:4px;">Puede ser diferente al email de acceso</p>
                </div>
            </div>
        </div>

        {{-- ═══════════════════════════════════
             BLOQUE C — Datos bancarios
        ═══════════════════════════════════ --}}
        <div style="background:rgba(255,255,255,0.03);border:1px solid rgba(245,241,234,0.08);border-radius:16px;padding:32px;margin-bottom:32px;">
            <p class="mono" style="font-size:10px;color:rgba(168,85,247,0.7);letter-spacing:0.18em;margin-bottom:20px;">C · DATOS BANCARIOS PARA RECIBIR PAGOS</p>

            {{-- Aviso de seguridad --}}
            <div style="background:rgba(124,58,237,0.10);border:1px solid rgba(124,58,237,0.25);border-radius:8px;padding:12px 16px;display:flex;align-items:center;gap:10px;margin-bottom:24px;">
                <span style="font-size:16px;">🔒</span>
                <p style="font-family:'Archivo Narrow',sans-serif;font-size:13px;color:rgba(245,241,234,0.6);margin:0;">
                    Tu IBAN se almacena <strong style="color:rgba(245,241,234,0.85);">cifrado</strong> con encriptación de clave privada y nunca se comparte con terceros.
                </p>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
                {{-- Titular --}}
                <div>
                    <label style="display:block;font-family:'Archivo Narrow',sans-serif;font-size:11px;text-transform:uppercase;letter-spacing:0.1em;color:rgba(245,241,234,0.55);margin-bottom:6px;">
                        Titular de la cuenta *
                    </label>
                    <input type="text" name="titular_cuenta" value="{{ old('titular_cuenta', $empresa->titular_cuenta ?? '') }}"
                           placeholder="Nombre del titular"
                           style="width:100%;background:rgba(255,255,255,0.04);border:1px solid rgba(245,241,234,0.14);border-radius:8px;padding:11px 14px;color:#f5f1ea;font-family:'Archivo Narrow',sans-serif;font-size:14px;outline:none;">
                </div>

                {{-- IBAN --}}
                <div>
                    <label style="display:block;font-family:'Archivo Narrow',sans-serif;font-size:11px;text-transform:uppercase;letter-spacing:0.1em;color:rgba(245,241,234,0.55);margin-bottom:6px;">
                        IBAN *
                    </label>
                    <input type="text" id="iban-input" name="iban"
                           value="{{ old('iban') }}"
                           placeholder="ES00 0000 0000 0000 0000 0000"
                           maxlength="29"
                           oninput="formatearIban(this)"
                           onblur="validarIban(this)"
                           style="width:100%;background:rgba(255,255,255,0.04);border:1px solid rgba(245,241,234,0.14);border-radius:8px;padding:11px 14px;color:#f5f1ea;font-family:'Archivo Narrow',sans-serif;font-size:14px;outline:none;letter-spacing:0.05em;">
                    <span id="error-iban" style="font-size:11px;color:#f87171;margin-top:4px;display:block;"></span>
                </div>
            </div>
        </div>

        {{-- Botón submit --}}
        <button type="submit"
                style="display:inline-flex;align-items:center;gap:10px;padding:16px 32px;background:linear-gradient(135deg,#7c3aed,#a855f7);color:#f5f1ea;border:none;border-radius:999px;font-family:'Anton',sans-serif;font-size:16px;letter-spacing:0.04em;cursor:pointer;text-transform:uppercase;box-shadow:0 4px 24px rgba(124,58,237,0.4);transition:all 0.2s ease;"
                onmouseover="this.style.boxShadow='0 6px 32px rgba(124,58,237,0.6)'"
                onmouseout="this.style.boxShadow='0 4px 24px rgba(124,58,237,0.4)'">
            Guardar datos fiscales →
        </button>

    </form>
</div>
</div>

<script>
    /* Formatea el IBAN en grupos de 4 caracteres: ES00 0000 0000... */
    function formatearIban(input) {
        var raw   = input.value.replace(/\s/g, '').toUpperCase();
        var groups = raw.match(/.{1,4}/g) || [];
        input.value = groups.join(' ');
    }

    /* Validación básica: empieza por ES + 22 dígitos = 24 chars en total (sin espacios) */
    function validarIban(input) {
        var raw   = input.value.replace(/\s/g, '');
        var error = document.getElementById('error-iban');
        if (!raw) { error.textContent = ''; return; }
        if (!/^ES[0-9]{22}$/i.test(raw)) {
            error.textContent = 'El IBAN debe comenzar por ES seguido de 22 dígitos';
            input.style.borderColor = 'rgba(239,68,68,0.6)';
        } else {
            error.textContent = '';
            input.style.borderColor = 'rgba(16,185,129,0.5)';
        }
    }
</script>

@endsection
