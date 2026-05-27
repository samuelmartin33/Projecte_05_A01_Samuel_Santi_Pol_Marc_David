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

            <div class="grid-datos-2col" style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px;">
                {{-- Razón social --}}
                <div>
                    <label class="form-label">
                        Razón social *
                    </label>
                    <input type="text" name="razon_social" value="{{ old('razon_social', $empresa->razon_social ?? '') }}"
                           placeholder="Razzmatazz Entertainment S.L."
                           class="form-input">
                </div>

                {{-- NIF / CIF --}}
                <div>
                    <label class="form-label">
                        NIF / CIF *
                    </label>
                    <input type="text" name="nif_cif" value="{{ old('nif_cif', $empresa->nif_cif ?? '') }}"
                           placeholder="B12345678" maxlength="9"
                           oninput="this.value=this.value.toUpperCase()"
                           class="form-input">
                </div>
            </div>

            {{-- Tipo de empresa --}}
            @php
                $tipoActual = old('tipo_empresa', $empresa->tipo_empresa ?? '');
                $tipoLabels = ['autonomo' => 'Autónomo','sl' => 'Sociedad Limitada (S.L.)','sa' => 'Sociedad Anónima (S.A.)','asociacion' => 'Asociación','otro' => 'Otro'];
                $tipoLabelActual = $tipoLabels[$tipoActual] ?? 'Selecciona la forma jurídica';
            @endphp
            <div>
                <label style="display:block;font-family:'Archivo Narrow',sans-serif;font-size:11px;text-transform:uppercase;letter-spacing:0.1em;color:rgba(245,241,234,0.55);margin-bottom:6px;">
                    Forma jurídica *
                </label>
                <input type="hidden" id="tipo_empresa" name="tipo_empresa" value="{{ $tipoActual }}">
                <div class="ev-csel" id="te-csel">
                    <div class="ev-csel-trigger" id="te-csel-trigger" onclick="toggleTeCsel()">
                        <span id="te-csel-label">{{ $tipoLabelActual }}</span>
                        <span class="ev-csel-arrow" id="te-csel-arrow">▾</span>
                    </div>
                    <ul class="ev-csel-menu" id="te-csel-menu">
                        <li class="ev-csel-opt {{ $tipoActual === 'autonomo'   ? 'selected' : '' }}" onclick="pickTeCsel('autonomo','Autónomo')">Autónomo</li>
                        <li class="ev-csel-opt {{ $tipoActual === 'sl'         ? 'selected' : '' }}" onclick="pickTeCsel('sl','Sociedad Limitada (S.L.)')">Sociedad Limitada (S.L.)</li>
                        <li class="ev-csel-opt {{ $tipoActual === 'sa'         ? 'selected' : '' }}" onclick="pickTeCsel('sa','Sociedad Anónima (S.A.)')">Sociedad Anónima (S.A.)</li>
                        <li class="ev-csel-opt {{ $tipoActual === 'asociacion' ? 'selected' : '' }}" onclick="pickTeCsel('asociacion','Asociación')">Asociación</li>
                        <li class="ev-csel-opt {{ $tipoActual === 'otro'       ? 'selected' : '' }}" onclick="pickTeCsel('otro','Otro')">Otro</li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- ═══════════════════════════════════
             BLOQUE B — Dirección fiscal
        ═══════════════════════════════════ --}}
        <div style="background:rgba(255,255,255,0.03);border:1px solid rgba(245,241,234,0.08);border-radius:16px;padding:32px;margin-bottom:24px;">
            <p class="mono" style="font-size:10px;color:rgba(168,85,247,0.7);letter-spacing:0.18em;margin-bottom:20px;">B · DIRECCIÓN FISCAL</p>

            {{-- Dirección --}}
            <div style="margin-bottom:20px;">
                <label class="form-label">Calle y número *</label>
                <input type="text" name="direccion" value="{{ old('direccion', $empresa->direccion ?? '') }}"
                       placeholder="Calle Almogàvers, 122"
                       class="form-input">
            </div>

            <div class="grid-ciudad-cp" style="display:grid;grid-template-columns:1fr 120px 1fr;gap:16px;margin-bottom:20px;">
                {{-- Ciudad --}}
                <div>
                    <label class="form-label">Ciudad *</label>
                    <input type="text" name="ciudad" value="{{ old('ciudad', $empresa->ciudad ?? '') }}"
                           placeholder="Barcelona"
                           class="form-input">
                </div>

                {{-- Código postal --}}
                <div>
                    <label class="form-label">CP *</label>
                    <input type="text" name="codigo_postal" value="{{ old('codigo_postal', $empresa->codigo_postal ?? '') }}"
                           placeholder="08013" maxlength="5" pattern="[0-9]{5}"
                           oninput="this.value=this.value.replace(/[^0-9]/g,'')"
                           class="form-input">
                </div>

                {{-- Provincia --}}
                <div>
                    <label class="form-label">Provincia *</label>
                    <input type="text" name="provincia" value="{{ old('provincia', $empresa->provincia ?? '') }}"
                           placeholder="Barcelona"
                           class="form-input">
                </div>
            </div>

            <div class="grid-datos-2col" style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
                {{-- País --}}
                <div>
                    <label class="form-label">País *</label>
                    <input type="text" name="pais" value="{{ old('pais', $empresa->pais ?? 'España') }}"
                           placeholder="España"
                           class="form-input">
                </div>

                {{-- Email de facturación --}}
                <div>
                    <label class="form-label">Email para facturas *</label>
                    <input type="email" name="email_facturacion" value="{{ old('email_facturacion', $empresa->email_facturacion ?? '') }}"
                           placeholder="facturacion@empresa.com"
                           class="form-input">
                    <p class="form-hint">Puede ser diferente al email de acceso</p>
                </div>
            </div>
        </div>

        {{-- Botón submit —datos legales— --}}
        <button type="submit"
                style="display:inline-flex;align-items:center;gap:10px;padding:16px 32px;background:linear-gradient(135deg,#7c3aed,#a855f7);color:#f5f1ea;border:none;border-radius:999px;font-family:'Anton',sans-serif;font-size:16px;letter-spacing:0.04em;cursor:pointer;text-transform:uppercase;box-shadow:0 4px 24px rgba(124,58,237,0.4);transition:all 0.2s ease;"
                onmouseover="this.style.boxShadow='0 6px 32px rgba(124,58,237,0.6)'"
                onmouseout="this.style.boxShadow='0 4px 24px rgba(124,58,237,0.4)'">
            Guardar datos fiscales →
        </button>

    </form>

    {{-- ═══════════════════════════════════
         BLOQUE C — Cuenta bancaria Stripe
    ═══════════════════════════════════ --}}
    <div style="background:rgba(255,255,255,0.03);border:1px solid rgba(245,241,234,0.08);border-radius:16px;padding:32px;margin-top:24px;">
        <p class="mono" style="font-size:10px;color:rgba(168,85,247,0.7);letter-spacing:0.18em;margin-bottom:20px;">C · CUENTA BANCARIA PARA RECIBIR PAGOS</p>

        @php
            $stripeOk      = $empresa && $empresa->stripe_charges_enabled;
            $stripeInicio  = $empresa && $empresa->stripe_account_id && !$empresa->stripe_charges_enabled;
            $stripeSinCuenta = !$empresa || !$empresa->stripe_account_id;
        @endphp

        @if($stripeOk)
            {{-- ✅ Cuenta activa --}}
            <div style="display:flex;align-items:center;gap:16px;background:rgba(16,185,129,0.08);border:1px solid rgba(16,185,129,0.3);border-radius:12px;padding:20px 24px;margin-bottom:20px;">
                <div style="width:44px;height:44px;border-radius:50%;background:rgba(16,185,129,0.15);border:1px solid rgba(16,185,129,0.4);display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0;">✓</div>
                <div>
                    <p style="font-family:'Anton',sans-serif;font-size:16px;color:#6ee7b7;margin:0 0 4px;">Cuenta bancaria conectada</p>
                    <p style="font-family:'Archivo Narrow',sans-serif;font-size:13px;color:rgba(245,241,234,0.5);margin:0;">
                        Tu cuenta Stripe Express está activa. Recibirás el 90% de cada venta de entradas automáticamente.
                    </p>
                </div>
            </div>
            <a href="{{ route('empresa.stripe.refrescar') }}"
               style="display:inline-flex;align-items:center;gap:8px;padding:10px 20px;background:rgba(255,255,255,0.05);border:1px solid rgba(245,241,234,0.15);border-radius:999px;font-family:'Archivo Narrow',sans-serif;font-size:13px;color:rgba(245,241,234,0.6);text-decoration:none;">
                Gestionar cuenta en Stripe →
            </a>

        @elseif($stripeInicio)
            {{-- ⏳ Onboarding iniciado pero sin completar --}}
            <div style="display:flex;align-items:center;gap:16px;background:rgba(245,158,11,0.08);border:1px solid rgba(245,158,11,0.3);border-radius:12px;padding:20px 24px;margin-bottom:20px;">
                <div style="width:44px;height:44px;border-radius:50%;background:rgba(245,158,11,0.12);border:1px solid rgba(245,158,11,0.35);display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0;">⏳</div>
                <div>
                    <p style="font-family:'Anton',sans-serif;font-size:16px;color:#fcd34d;margin:0 0 4px;">Onboarding pendiente</p>
                    <p style="font-family:'Archivo Narrow',sans-serif;font-size:13px;color:rgba(245,241,234,0.5);margin:0;">
                        Has iniciado el proceso pero aún no has completado los datos bancarios en Stripe.
                    </p>
                </div>
            </div>
            <a href="{{ route('empresa.stripe.refrescar') }}"
               style="display:inline-flex;align-items:center;gap:8px;padding:14px 28px;background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;border:none;border-radius:999px;font-family:'Anton',sans-serif;font-size:15px;letter-spacing:0.04em;text-decoration:none;text-transform:uppercase;box-shadow:0 4px 16px rgba(245,158,11,0.35);">
                Continuar onboarding en Stripe →
            </a>

        @else
            {{-- 🔗 Sin cuenta Stripe —estado inicial— --}}
            <div style="display:flex;align-items:flex-start;gap:14px;background:rgba(124,58,237,0.08);border:1px solid rgba(124,58,237,0.25);border-radius:10px;padding:16px 20px;margin-bottom:24px;">
                <span style="font-size:18px;flex-shrink:0;margin-top:1px;">💳</span>
                <div>
                    <p style="font-family:'Archivo Narrow',sans-serif;font-size:14px;color:rgba(245,241,234,0.75);margin:0 0 4px;">
                        <strong style="color:#f5f1ea;">Conecta tu cuenta bancaria a través de Stripe</strong> para recibir el 90% de cada venta de entradas directamente en tu banco.
                    </p>
                    <p style="font-family:'Archivo Narrow',sans-serif;font-size:12px;color:rgba(245,241,234,0.4);margin:0;">
                        VIBEZ usa Stripe Connect. El proceso tarda ~2 minutos y es completamente seguro.
                    </p>
                </div>
            </div>
            <a href="{{ route('empresa.stripe.conectar') }}"
               style="display:inline-flex;align-items:center;gap:10px;padding:16px 32px;background:linear-gradient(135deg,#635BFF,#4F46E5);color:#fff;border:none;border-radius:999px;font-family:'Anton',sans-serif;font-size:16px;letter-spacing:0.04em;text-decoration:none;text-transform:uppercase;box-shadow:0 4px 24px rgba(99,91,255,0.45);">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                Conectar cuenta bancaria con Stripe
            </a>
        @endif
    </div>

</div>
</div>

@push('scripts')
<script src="{{ asset('js/empresa-perfil-fiscal.js') }}"></script>
{{-- JS en public/js/empresa-perfil-fiscal.js --}}
@endpush

@endsection
