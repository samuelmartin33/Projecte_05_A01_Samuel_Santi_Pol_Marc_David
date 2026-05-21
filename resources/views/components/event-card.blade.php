{{--
    Componente: <x-event-card :evento="$evento" :index="$loop->index" :favorito="bool" />
    Diseño editorial Swiss: sin border-radius, borde sólido ink, numeración visible.
    Props:
      - evento (Evento model)
      - index (int): posición en el grid para numeración 01, 02...
      - favorito (bool): si el usuario ha marcado favorito
--}}
@props([
    'evento',
    'index'    => 0,
    'favorito' => false,
])

@php
    $num    = str_pad($index + 1, 2, '0', STR_PAD_LEFT);
    $fecha  = \Carbon\Carbon::parse($evento->fecha_inicio)->format('d.m.y');
    $precio = $evento->es_gratuito ? 'GRATIS' : $evento->precio_formateado;
@endphp

<article
    class="border border-ink/15 bg-paper hover:border-lilac hover:shadow-none
           transition-colors duration-150 cursor-pointer flex flex-col group"
    onclick="irADetalle('evento', {{ $evento->id }})">

    {{-- Imagen full-bleed --}}
    <div class="relative overflow-hidden aspect-[4/3] bg-dusk">
        <img src="{{ $evento->url_portada }}"
             alt="{{ $evento->titulo }}"
             class="w-full h-full object-cover group-hover:scale-[1.03] transition-transform duration-300"
             onerror="this.src='https://picsum.photos/seed/fallback-{{ $evento->id }}/600/400'">

        {{-- Precio top-right --}}
        <span class="absolute top-0 right-0 bg-ink text-paper
                     font-mono text-xs uppercase tracking-widest px-3 py-1.5">
            {{ $precio }}
        </span>

        {{-- Numeración top-left --}}
        <span class="absolute top-0 left-0 bg-paper/90 text-ink
                     font-mono text-xs tracking-widest px-3 py-1.5">
            {{ $num }}
        </span>

        {{-- Botón favorito --}}
        <button type="button"
                class="btn-favorito-card absolute bottom-2 right-2
                       {{ $favorito ? 'activo' : '' }}"
                data-evento-id="{{ $evento->id }}"
                data-favorito="{{ $favorito ? '1' : '0' }}"
                aria-label="Marcar favorito"
                aria-pressed="{{ $favorito ? 'true' : 'false' }}"
                onclick="toggleFavorito(event, this)">
            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
            </svg>
        </button>
    </div>

    {{-- Bloque inferior ink --}}
    <div class="bg-ink text-paper p-4 flex flex-col gap-2 flex-1">

        {{-- Fecha + categoría --}}
        <div class="flex items-center justify-between">
            <span class="font-mono text-xs tracking-widest text-paper/40">
                {{ $fecha }}
            </span>
            @if($evento->categorias->isNotEmpty())
                <span class="font-mono text-xs uppercase tracking-widest text-lilac">
                    {{ $evento->categorias->pluck('nombre')->join(' · ') }}
                </span>
            @endif
        </div>

        {{-- Título --}}
        <h3 class="font-display font-black text-xl sm:text-2xl uppercase
                   tracking-tightest leading-[0.9] text-paper
                   line-clamp-2">
            {{ $evento->titulo }}
        </h3>

        {{-- Ubicación --}}
        @if($evento->ubicacion_nombre)
            <p class="font-mono text-xs uppercase tracking-widest text-paper/40 mt-auto">
                {{ $evento->ubicacion_nombre }}
            </p>
        @endif
    </div>
</article>
