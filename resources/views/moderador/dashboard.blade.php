@extends('moderador.layouts.dashboard')

@section('title', 'Dashboard — Moderador VIBEZ')

@section('content')

{{-- ── Hero de bienvenida ── --}}
<div class="adm-hero">
    <div class="adm-hero-row">
        <div>
            <p class="adm-hero-kicker">
                ▸ Panel de moderación · {{ now()->locale('es')->isoFormat('dddd, D [de] MMMM') }}
            </p>
            <h1>Panel <em>Moderador</em></h1>
            <p class="adm-hero-sub">
                Gestiona el contenido del social y los usuarios de la plataforma.
            </p>
        </div>
        <div class="adm-hero-actions">
            <a href="{{ route('moderador.posts.index') }}" class="adm-btn-pri">
                Revisar publicaciones
            </a>
            <a href="{{ route('moderador.usuarios.index') }}" class="adm-btn-ghost">
                Gestionar usuarios
            </a>
        </div>
    </div>
</div>

{{-- ── KPI Grid ── --}}
<div class="adm-kpi-grid">

    <a href="{{ route('moderador.posts.index') }}" style="text-decoration:none">
        <div class="adm-kpi">
            <div class="adm-kpi-head">
                <div class="adm-kpi-label">Publicaciones activas</div>
                <div class="adm-kpi-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l4 4v10a2 2 0 01-2 2z"/>
                        <line x1="9" y1="11" x2="15" y2="11"/><line x1="9" y1="15" x2="15" y2="15"/>
                    </svg>
                </div>
            </div>
            <div class="adm-kpi-value">{{ $totalPosts }}</div>
            <div class="adm-kpi-foot">
                <span class="adm-kpi-lbl">posts en el social</span>
            </div>
            <svg class="adm-spark" viewBox="0 0 80 24" preserveAspectRatio="none">
                <polyline points="0,18 14,15 28,17 42,12 56,10 70,7 80,4"
                          fill="none" stroke="#a855f7" stroke-width="1.5"/>
            </svg>
        </div>
    </a>

    <a href="{{ route('moderador.historias.index') }}" style="text-decoration:none">
        <div class="adm-kpi">
            <div class="adm-kpi-head">
                <div class="adm-kpi-label">Historias activas</div>
                <div class="adm-kpi-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                </div>
            </div>
            <div class="adm-kpi-value">{{ $totalHistorias }}</div>
            <div class="adm-kpi-foot">
                <span class="adm-kpi-lbl">historias vigentes</span>
            </div>
            <svg class="adm-spark" viewBox="0 0 80 24" preserveAspectRatio="none">
                <polyline points="0,20 14,18 28,14 42,16 56,10 70,7 80,3"
                          fill="none" stroke="#a855f7" stroke-width="1.5"/>
            </svg>
        </div>
    </a>

    <a href="{{ route('moderador.comentarios.index') }}" style="text-decoration:none">
        <div class="adm-kpi">
            <div class="adm-kpi-head">
                <div class="adm-kpi-label">Comentarios activos</div>
                <div class="adm-kpi-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/>
                    </svg>
                </div>
            </div>
            <div class="adm-kpi-value">{{ $totalComentarios }}</div>
            <div class="adm-kpi-foot">
                <span class="adm-kpi-lbl">comentarios visibles</span>
            </div>
            <svg class="adm-spark" viewBox="0 0 80 24" preserveAspectRatio="none">
                <polyline points="0,20 14,17 28,19 42,13 56,11 70,8 80,5"
                          fill="none" stroke="#a855f7" stroke-width="1.5"/>
            </svg>
        </div>
    </a>

    <a href="{{ route('moderador.usuarios.index') }}" style="text-decoration:none">
        <div class="adm-kpi">
            <div class="adm-kpi-head">
                <div class="adm-kpi-label">Usuarios baneados</div>
                <div class="adm-kpi-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <line x1="18" y1="8" x2="23" y2="13"/><line x1="23" y1="8" x2="18" y2="13"/>
                    </svg>
                </div>
            </div>
            <div class="adm-kpi-value">{{ $totalBaneados }}</div>
            <div class="adm-kpi-foot">
                <span class="adm-kpi-lbl">cuentas desactivadas</span>
            </div>
            <svg class="adm-spark" viewBox="0 0 80 24" preserveAspectRatio="none">
                <polyline points="0,22 14,21 28,20 42,18 56,17 70,15 80,14"
                          fill="none" stroke="#ef4444" stroke-width="1.5"/>
            </svg>
        </div>
    </a>

</div>

@endsection
