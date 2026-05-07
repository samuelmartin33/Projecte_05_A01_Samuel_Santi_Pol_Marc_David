{{--
    Componente: <x-button />
    Props:
      - href (string|null): si se da, renderiza <a>; si no, <button>
      - variant (string): 'primary' (default) | 'ghost'
      - type (string): 'button' (default) | 'submit'
      - class (string): clases extra
    Slot: texto del botón

    Ejemplos:
      <x-button href="/register">Regístrate →</x-button>
      <x-button variant="ghost" href="/login">Iniciar sesión</x-button>
      <x-button type="submit">Enviar</x-button>
--}}
@props([
    'href'    => null,
    'variant' => 'primary',
    'type'    => 'button',
])

@php
$base = 'inline-block font-mono text-xs uppercase tracking-widest px-8 py-4
         text-center transition-colors duration-75 cursor-pointer';

$variants = [
    'primary' => 'bg-ink text-paper hover:bg-plum',
    'ghost'   => 'border border-ink/35 text-ink hover:bg-dusk hover:border-ink',
];

$classes = $base . ' ' . ($variants[$variant] ?? $variants['primary']);
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
