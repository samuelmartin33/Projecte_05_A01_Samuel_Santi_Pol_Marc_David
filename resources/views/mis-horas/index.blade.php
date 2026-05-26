@extends('layouts.app')

@section('titulo', 'Mis horas — VIBEZ')

@push('estilos')
<style>
body { background:#07060c; }

.horas-wrap {
    max-width: 700px;
    margin: 0 auto;
    padding: 2.5rem 1rem 5rem;
}

/* ─── Hero ──────────────────────────────────── */
.horas-hero {
    background: radial-gradient(circle at 20% 30%, rgba(168,85,247,0.25), transparent 55%),
                radial-gradient(circle at 80% 70%, rgba(124,58,237,0.20), transparent 60%),
                #0d0820;
    border: 1px solid rgba(245,241,234,0.10);
    padding: 2rem 1.5rem;
    margin-bottom: 2rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
}
.horas-hero-titulo {
    font-family: 'Anton', sans-serif;
    font-size: clamp(1.75rem, 5vw, 3rem);
    color: #f5f1ea;
    text-transform: uppercase;
    letter-spacing: -0.02em;
    line-height: 1;
    margin: 0;
}
.horas-hero-sub {
    font-family: 'Archivo Narrow', sans-serif;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.15em;
    color: rgba(245,241,234,0.45);
    margin-top: 0.5rem;
}
.horas-stat {
    text-align: center;
    background: rgba(168,85,247,0.10);
    border: 1px solid rgba(168,85,247,0.25);
    padding: 1rem 1.5rem;
    flex-shrink: 0;
}
.horas-stat-num {
    font-family: 'Anton', sans-serif;
    font-size: 2.5rem;
    color: #c084fc;
    line-height: 1;
}
.horas-stat-label {
    font-family: 'Archivo Narrow', sans-serif;
    font-size: 10px;
    text-transform: uppercase;
    letter-spacing: 0.16em;
    color: rgba(245,241,234,0.45);
    margin-top: 4px;
}

/* ─── Formulario ──────────────────────────── */
.horas-form-card {
    background: #0d0a18;
    border: 1px solid rgba(245,241,234,0.10);
    padding: 1.75rem;
    margin-bottom: 2rem;
}
.horas-form-title {
    font-family: 'Archivo Narrow', sans-serif;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.16em;
    color: #c084fc;
    margin-bottom: 1.25rem;
    display: flex;
    align-items: center;
    gap: 8px;
}
.horas-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}
.horas-campo { display: flex; flex-direction: column; gap: 6px; }
.horas-campo.full { grid-column: 1 / -1; }
.horas-label {
    font-family: 'Archivo Narrow', sans-serif;
    font-size: 10px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.14em;
    color: rgba(245,241,234,0.50);
}
.horas-input {
    background: rgba(245,241,234,0.04);
    border: 1px solid rgba(245,241,234,0.12);
    color: #f5f1ea;
    padding: 10px 14px;
    font-family: 'Archivo Narrow', sans-serif;
    font-size: 14px;
    outline: none;
    width: 100%;
    transition: border-color 0.15s;
}
.horas-input:focus { border-color: rgba(168,85,247,0.6); }
.horas-hint {
    font-size: 11px;
    color: rgba(245,241,234,0.35);
    font-family: 'Archivo Narrow', sans-serif;
}
.horas-btn-enviar {
    font-family: 'Anton', sans-serif;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.03em;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 28px;
    background: #a855f7;
    color: #f5f1ea;
    border: none;
    cursor: pointer;
    transition: background 0.15s;
    margin-top: 0.5rem;
}
.horas-btn-enviar:hover { background: #c084fc; color: #07060c; }

/* ─── Historial ──────────────────────────── */
.horas-tabla-wrap {
    background: #0d0a18;
    border: 1px solid rgba(245,241,234,0.10);
    overflow: hidden;
}
.horas-tabla-head {
    background: rgba(168,85,247,0.06);
    border-bottom: 1px solid rgba(245,241,234,0.10);
    display: grid;
    grid-template-columns: 140px 80px 1fr;
    gap: 1rem;
    padding: 10px 20px;
}
.horas-tabla-th {
    font-family: 'Archivo Narrow', sans-serif;
    font-size: 10px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.16em;
    color: rgba(245,241,234,0.40);
}
.horas-fila {
    display: grid;
    grid-template-columns: 140px 80px 1fr;
    gap: 1rem;
    padding: 14px 20px;
    border-bottom: 1px solid rgba(245,241,234,0.06);
    align-items: center;
    transition: background 0.1s;
}
.horas-fila:last-child { border-bottom: none; }
.horas-fila:hover { background: rgba(245,241,234,0.03); }
.horas-fecha {
    font-family: 'Archivo Narrow', sans-serif;
    font-size: 13px;
    color: rgba(245,241,234,0.75);
}
.horas-num {
    font-family: 'Anton', sans-serif;
    font-size: 18px;
    color: #c084fc;
}
.horas-desc {
    font-size: 12px;
    color: rgba(245,241,234,0.45);
    font-family: 'Archivo Narrow', sans-serif;
}
.horas-empty {
    padding: 3rem;
    text-align: center;
    color: rgba(245,241,234,0.30);
    font-family: 'Archivo Narrow', sans-serif;
    font-size: 14px;
}
</style>
@endpush

@section('content')

@include('partials.home.nav')

<div class="horas-wrap">

    {{-- ─── Hero ─── --}}
    <div class="horas-hero">
        <div>
            <h1 class="horas-hero-titulo">Mis horas</h1>
            <p class="horas-hero-sub">
                @if(Auth::user()->isPortero()) Portero · @else Organizador · @endif
                Registro diario
            </p>
        </div>
        <div class="horas-stat">
            <p class="horas-stat-num">{{ number_format($horasMes, 1) }}</p>
            <p class="horas-stat-label">h este mes</p>
        </div>
    </div>

    {{-- ─── Formulario ─── --}}
    <div class="horas-form-card">
        <p class="horas-form-title">
            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Registrar horas
        </p>

        <form action="{{ route('horas.store') }}" method="POST" id="form-horas">
            @csrf
            <div class="horas-grid">

                <div class="horas-campo">
                    <label class="horas-label" for="fecha">Fecha <span style="color:#f87171">*</span></label>
                    <input type="date" id="fecha" name="fecha" class="horas-input"
                           value="{{ old('fecha', now()->toDateString()) }}"
                           max="{{ now()->toDateString() }}"
                           required>
                </div>

                <div class="horas-campo">
                    <label class="horas-label" for="horas">Horas trabajadas <span style="color:#f87171">*</span></label>
                    <input type="number" id="horas" name="horas" class="horas-input"
                           value="{{ old('horas') }}"
                           min="0.5" max="24" step="0.5"
                           placeholder="Ej: 8 o 4.5"
                           required>
                    <span class="horas-hint">De 0.5 a 24 h · Usa .5 para medias horas</span>
                </div>

                <div class="horas-campo full">
                    <label class="horas-label" for="descripcion">Descripción (opcional)</label>
                    <input type="text" id="descripcion" name="descripcion" class="horas-input"
                           value="{{ old('descripcion') }}"
                           placeholder="¿Qué hiciste hoy? Ej: Control de acceso evento Sala Razzmatazz"
                           maxlength="500">
                </div>

            </div>

            <button type="submit" class="horas-btn-enviar">
                <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                Guardar horas
            </button>
        </form>
    </div>

    {{-- ─── Historial ─── --}}
    <p style="font-family:'Archivo Narrow',sans-serif;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.16em;color:rgba(245,241,234,0.40);margin-bottom:0.75rem;">
        Historial reciente
    </p>

    <div class="horas-tabla-wrap">
        @if($registros->isEmpty())
            <p class="horas-empty">Aún no tienes horas registradas. Usa el formulario de arriba.</p>
        @else
            <div class="horas-tabla-head">
                <span class="horas-tabla-th">Fecha</span>
                <span class="horas-tabla-th">Horas</span>
                <span class="horas-tabla-th">Descripción</span>
            </div>
            @foreach($registros as $reg)
                <div class="horas-fila">
                    <span class="horas-fecha">
                        {{ \Carbon\Carbon::parse($reg->fecha)->locale('es')->isoFormat('ddd D MMM YYYY') }}
                    </span>
                    <span class="horas-num">{{ number_format($reg->horas, 1) }}<span style="font-size:11px;color:rgba(245,241,234,0.35);font-family:'Archivo Narrow',sans-serif;"> h</span></span>
                    <span class="horas-desc">{{ $reg->descripcion ?: '—' }}</span>
                </div>
            @endforeach
        @endif
    </div>

</div>

@endsection

@push('scripts')
<script>
/* ─── SweetAlert2: confirmación al guardar horas y alertas flash ─── */
document.addEventListener('DOMContentLoaded', function () {

    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: '¡Guardado!',
            text: '{{ session('success') }}',
            background: '#0d0a18',
            color: '#f5f1ea',
            confirmButtonColor: '#a855f7',
            timer: 3000,
            timerProgressBar: true,
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{{ session('error') }}',
            background: '#0d0a18',
            color: '#f5f1ea',
            confirmButtonColor: '#a855f7',
        });
    @endif

    @if($errors->any())
        Swal.fire({
            icon: 'warning',
            title: 'Revisa los campos',
            html: '@foreach($errors->all() as $e)• {{ $e }}<br>@endforeach',
            background: '#0d0a18',
            color: '#f5f1ea',
            confirmButtonColor: '#a855f7',
        });
    @endif

    /* Confirmación antes de enviar el formulario */
    var formHoras = document.getElementById('form-horas');
    if (formHoras) {
        formHoras.addEventListener('submit', function(e) {
            e.preventDefault();
            var horas = document.getElementById('horas').value;
            var fecha = document.getElementById('fecha').value;
            Swal.fire({
                title: '¿Confirmas el registro?',
                html: '<b>' + horas + ' h</b> el <b>' + fecha + '</b>',
                icon: 'question',
                background: '#0d0a18',
                color: '#f5f1ea',
                showCancelButton: true,
                confirmButtonColor: '#a855f7',
                cancelButtonColor: 'rgba(245,241,234,0.12)',
                confirmButtonText: 'Sí, guardar',
                cancelButtonText: 'Cancelar',
            }).then(function(result) {
                if (result.isConfirmed) formHoras.submit();
            });
        });
    }
});
</script>
@endpush
