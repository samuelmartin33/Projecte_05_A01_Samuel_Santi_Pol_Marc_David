@extends('layouts.app')

@section('titulo', 'Panel de Empresa — VIBEZ')

@section('contenido')

{{-- ════════════════════════════════════════════════════
     HERO — Panel de empresa
════════════════════════════════════════════════════ --}}
<section class="hero-home">

    <div class="hero-particula hero-particula-1"></div>
    <div class="hero-particula hero-particula-2"></div>
    <div class="hero-particula hero-particula-3"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center relative z-10">

        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold mb-5"
             style="background:rgba(168,85,247,0.15);border:1px solid rgba(168,85,247,0.3);color:#c084fc;letter-spacing:0.06em;text-transform:uppercase;">
            Panel de empresa
        </div>

        <h1 class="text-4xl sm:text-5xl font-black text-white tracking-tight leading-tight mb-4">
            Gestiona tu presencia<br>
            <span class="text-gradient-claro">en la escena joven</span>
        </h1>

        <p class="text-slate-400 text-lg max-w-xl mx-auto">
            Crea eventos, publica ofertas de trabajo y conecta con miles de jóvenes en VIBEZ.
        </p>

    </div>
</section>

{{-- ════════════════════════════════════════════════════
     ACCIONES PRINCIPALES
════════════════════════════════════════════════════ --}}
<section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

        {{-- Crear evento --}}
        <a href="#" class="group block rounded-2xl p-6 border border-white/10 bg-white/5 hover:bg-white/10 transition-all duration-200">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-4"
                 style="background: linear-gradient(135deg,#7c3aed,#a855f7)">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
            </div>
            <h3 class="text-white font-bold text-lg mb-1">Crear evento</h3>
            <p class="text-slate-400 text-sm">Publica un nuevo evento y llega a tu público objetivo.</p>
        </a>

        {{-- Subir oferta de trabajo --}}
        <a href="#" class="group block rounded-2xl p-6 border border-white/10 bg-white/5 hover:bg-white/10 transition-all duration-200">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-4"
                 style="background: linear-gradient(135deg,#7c3aed,#a855f7)">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <h3 class="text-white font-bold text-lg mb-1">Publicar oferta de trabajo</h3>
            <p class="text-slate-400 text-sm">Encuentra talento joven para tu equipo.</p>
        </a>

        {{-- Mis eventos --}}
        <a href="#" class="group block rounded-2xl p-6 border border-white/10 bg-white/5 hover:bg-white/10 transition-all duration-200">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-4"
                 style="background: linear-gradient(135deg,#7c3aed,#a855f7)">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <h3 class="text-white font-bold text-lg mb-1">Mis eventos</h3>
            <p class="text-slate-400 text-sm">Gestiona y revisa los eventos que ya has creado.</p>
        </a>

        {{-- Mis ofertas --}}
        <a href="#" class="group block rounded-2xl p-6 border border-white/10 bg-white/5 hover:bg-white/10 transition-all duration-200">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-4"
                 style="background: linear-gradient(135deg,#7c3aed,#a855f7)">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <h3 class="text-white font-bold text-lg mb-1">Mis ofertas</h3>
            <p class="text-slate-400 text-sm">Revisa los candidatos que han aplicado a tus ofertas.</p>
        </a>

    </div>

    {{-- Aviso: página en construcción --}}
    <div class="mt-10 text-center">
        <p class="text-slate-500 text-sm">
            Panel de empresa en construcción — más funcionalidades próximamente.
        </p>
    </div>

</section>

@endsection
