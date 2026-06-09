@extends('layouts.app')

@section('titulo', 'Stock de barra — VIBEZ')

{{-- ══════════════════════════════════════════════════════════════════════════
     STOCK DE BARRA — Vista del camarero
     Gestión AJAX de productos con Alpine.js + SweetAlert2
     Sin CSS inline, sin JS inline, sin addEventListener/querySelector en JS.
══════════════════════════════════════════════════════════════════════════ --}}

@section('contenido')

@php
    $stockBajoCount = $productos->filter(fn($p) => $p->stockBajo())->count();
@endphp

{{-- ── Contenedor principal con data-* para el JS ─────────────────────── --}}
<div id="stock-contenedor"
     class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10"
     data-url-store="{{ route('camarero.stock.store') }}"
     data-url-base="{{ route('camarero.stock.index') }}"
     data-url-reponer-base="/camarero/stock/"
     data-csrf="{{ csrf_token() }}"
     data-stripe-key="{{ config('services.stripe.key') }}"
     x-data="stockApp()">

    {{-- ── Cabecera ─────────────────────────────────────────────────────── --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-black text-white tracking-tight">
                Stock de barra 🍹
            </h1>
            <p class="text-white/50 text-sm mt-1 font-mono uppercase tracking-widest">
                {{ $productos->count() }} producto{{ $productos->count() !== 1 ? 's' : '' }} registrado{{ $productos->count() !== 1 ? 's' : '' }}
                @if($stockBajoCount > 0)
                    &nbsp;·&nbsp;
                    <span class="text-red-400 font-bold">
                        ⚠️ {{ $stockBajoCount }} con stock bajo
                    </span>
                @endif
            </p>
        </div>

        <button
            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-violet-600 hover:bg-violet-500 text-white font-semibold text-sm transition-colors duration-150 shadow-lg shadow-violet-900/40"
            x-on:click="abrirModalCrear()">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Añadir producto
        </button>
    </div>

    {{-- ── Filtro por tipo ─────────────────────────────────────────────── --}}
    <div class="flex items-center gap-3 mb-6">
        <span class="text-white/40 text-xs font-mono uppercase tracking-widest">Filtrar por tipo:</span>
        <div class="flex gap-2">
            <button
                class="px-3 py-1 rounded-full text-xs font-semibold transition-colors duration-150"
                :class="filtro === 'todos'
                    ? 'bg-violet-600 text-white'
                    : 'bg-white/10 text-white/60 hover:bg-white/20'"
                x-on:click="filtro = 'todos'">
                Todos
            </button>
            <button
                class="px-3 py-1 rounded-full text-xs font-semibold transition-colors duration-150"
                :class="filtro === 'cocktail'
                    ? 'bg-orange-500 text-white'
                    : 'bg-white/10 text-white/60 hover:bg-white/20'"
                x-on:click="filtro = 'cocktail'">
                Cocktail
            </button>
            <button
                class="px-3 py-1 rounded-full text-xs font-semibold transition-colors duration-150"
                :class="filtro === 'destilado'
                    ? 'bg-red-500 text-white'
                    : 'bg-white/10 text-white/60 hover:bg-white/20'"
                x-on:click="filtro = 'destilado'">
                Destilado
            </button>
            <button
                class="px-3 py-1 rounded-full text-xs font-semibold transition-colors duration-150"
                :class="filtro === 'sin_alcohol'
                    ? 'bg-emerald-500 text-white'
                    : 'bg-white/10 text-white/60 hover:bg-white/20'"
                x-on:click="filtro = 'sin_alcohol'">
                Sin alcohol
            </button>
        </div>
    </div>

    {{-- ── Tabla de productos ───────────────────────────────────────────── --}}
    <div class="overflow-x-auto rounded-2xl ring-1 ring-white/10 bg-white/5 backdrop-blur-sm">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-white/10 text-white/40 uppercase text-xs tracking-widest font-mono">
                    <th class="text-left px-5 py-3">Producto</th>
                    <th class="text-left px-5 py-3">Tipo</th>
                    <th class="text-left px-5 py-3">Proveedor</th>
                    <th class="text-left px-5 py-3">Stock</th>
                    <th class="text-left px-5 py-3">Precio</th>
                    <th class="text-left px-5 py-3">Evento</th>
                    <th class="text-right px-5 py-3">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($productos as $producto)
                    <tr
                        x-show="filtro === 'todos' || filtro === '{{ $producto->tipo_producto }}'"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        class="{{ $producto->stockBajo() ? 'bg-red-950/30' : 'hover:bg-white/5' }} transition-colors duration-100">

                        {{-- Producto --}}
                        <td class="px-5 py-4 font-semibold text-white">
                            {{ $producto->nombre }}
                        </td>

                        {{-- Tipo (badge) --}}
                        <td class="px-5 py-4">
                            @if($producto->tipo_producto === 'sin_alcohol')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-500/20 text-emerald-300 ring-1 ring-emerald-500/30">
                                    Sin alcohol
                                </span>
                            @elseif($producto->tipo_producto === 'cocktail')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-orange-500/20 text-orange-300 ring-1 ring-orange-500/30">
                                    Cocktail
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-500/20 text-red-300 ring-1 ring-red-500/30">
                                    Destilado
                                </span>
                            @endif
                        </td>

                        {{-- Proveedor --}}
                        <td class="px-5 py-4 text-white/70">{{ $producto->proveedor }}</td>

                        {{-- Stock --}}
                        <td class="px-5 py-4">
                            @if($producto->stockBajo())
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-500/20 text-red-300 ring-1 ring-red-500/40">
                                    ⚠️ Stock bajo ({{ $producto->stock }})
                                </span>
                            @else
                                <span class="text-white font-mono font-semibold">{{ $producto->stock }}</span>
                            @endif
                        </td>

                        {{-- Precio unitario --}}
                        <td class="px-5 py-4 text-white/70 font-mono">
                            {{ number_format($producto->precio_unitario, 2) }} €
                        </td>

                        {{-- Evento --}}
                        <td class="px-5 py-4 text-white/60 text-xs">
                            {{ $producto->evento?->titulo ?? '—' }}
                        </td>

                        {{-- Acciones --}}
                        <td class="px-5 py-4">
                            <div class="flex items-center justify-end gap-2">

                                {{-- Editar --}}
                                <button
                                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-violet-600/20 hover:bg-violet-600/40 text-violet-300 text-xs font-semibold transition-colors duration-150"
                                    x-on:click="editarProducto({{ $producto->id }})">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Editar
                                </button>

                                {{-- Reponer — issue #75 --}}
                                <button
                                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-emerald-600/20 hover:bg-emerald-600/40 text-emerald-300 text-xs font-semibold transition-colors duration-150"
                                    x-on:click="abrirModalReponer({{ $producto->id }}, '{{ addslashes($producto->nombre) }}', '{{ addslashes($producto->proveedor) }}', {{ $producto->precio_unitario }})">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    Reponer
                                </button>

                                {{-- Eliminar --}}
                                <button
                                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-red-500/20 hover:bg-red-500/40 text-red-300 text-xs font-semibold transition-colors duration-150"
                                    x-on:click="confirmarEliminar({{ $producto->id }}, '{{ addslashes($producto->nombre) }}')">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Eliminar
                                </button>

                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-16 text-center text-white/30 text-sm">
                            No hay productos de barra registrados todavía.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ════════════════════════════════════════════════════════════════════
         MODAL — Crear / Editar producto
         Gestionado completamente con Alpine.js (x-show, x-data en el wrapper)
    ════════════════════════════════════════════════════════════════════ --}}
    <div
        x-show="modalAbierto"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
        x-on:keydown.escape.window="cerrarModal()">

        {{-- Fondo oscuro --}}
        <div
            class="absolute inset-0 bg-black/70 backdrop-blur-sm"
            x-on:click="cerrarModal()">
        </div>

        {{-- Panel del modal --}}
        <div
            x-show="modalAbierto"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="relative z-10 w-full max-w-lg bg-[#1a1033] rounded-2xl ring-1 ring-white/10 shadow-2xl shadow-black/60 overflow-hidden">

            {{-- Cabecera modal --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-white/10">
                <h2 class="text-lg font-bold text-white" x-text="editandoId ? 'Editar producto' : 'Añadir producto'"></h2>
                <button
                    class="text-white/40 hover:text-white transition-colors duration-100"
                    x-on:click="cerrarModal()">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Cuerpo del modal --}}
            <div class="px-6 py-5 space-y-4">

                {{-- Nombre --}}
                <div>
                    <label class="block text-xs font-semibold text-white/50 uppercase tracking-wider mb-1.5">
                        Nombre del producto
                    </label>
                    <input
                        type="text"
                        x-model="form.nombre"
                        class="w-full rounded-lg bg-white/8 border border-white/10 text-white placeholder-white/30 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent transition"
                        placeholder="Ej. Ron Barceló, Agua Mineral…">
                </div>

                {{-- Tipo y Proveedor --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-white/50 uppercase tracking-wider mb-1.5">
                            Tipo
                        </label>
                        <select
                            x-model="form.tipo_producto"
                            class="w-full rounded-lg bg-white/8 border border-white/10 text-white px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500 transition">
                            <option value="" class="bg-[#1a1033]">Selecciona…</option>
                            <option value="cocktail" class="bg-[#1a1033]">Cocktail</option>
                            <option value="destilado" class="bg-[#1a1033]">Destilado</option>
                            <option value="sin_alcohol" class="bg-[#1a1033]">Sin alcohol</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-white/50 uppercase tracking-wider mb-1.5">
                            Proveedor
                        </label>
                        <input
                            type="text"
                            x-model="form.proveedor"
                            class="w-full rounded-lg bg-white/8 border border-white/10 text-white placeholder-white/30 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500 transition"
                            placeholder="Ej. Makro, Costco…">
                    </div>
                </div>

                {{-- Stock y Precio --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-white/50 uppercase tracking-wider mb-1.5">
                            Stock (unidades)
                        </label>
                        <input
                            type="number"
                            x-model="form.stock"
                            min="0"
                            class="w-full rounded-lg bg-white/8 border border-white/10 text-white placeholder-white/30 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500 transition"
                            placeholder="0">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-white/50 uppercase tracking-wider mb-1.5">
                            Precio unitario (€)
                        </label>
                        <input
                            type="number"
                            x-model="form.precio_unitario"
                            min="0"
                            step="0.01"
                            class="w-full rounded-lg bg-white/8 border border-white/10 text-white placeholder-white/30 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500 transition"
                            placeholder="0.00">
                    </div>
                </div>

                {{-- Evento (solo en creación) --}}
                <div x-show="!editandoId">
                    <label class="block text-xs font-semibold text-white/50 uppercase tracking-wider mb-1.5">
                        Evento
                    </label>
                    <select
                        x-model="form.evento_id"
                        class="w-full rounded-lg bg-white/8 border border-white/10 text-white px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500 transition">
                        <option value="" class="bg-[#1a1033]">Selecciona un evento…</option>
                        @foreach($eventos as $evento)
                            <option value="{{ $evento->id }}" class="bg-[#1a1033]">
                                {{ $evento->titulo }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Mensaje de error --}}
                <p
                    x-show="errorMensaje"
                    x-text="errorMensaje"
                    class="text-red-400 text-xs font-semibold bg-red-500/10 rounded-lg px-4 py-2.5 ring-1 ring-red-500/20">
                </p>

            </div>

            {{-- Pie del modal --}}
            <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-white/10 bg-white/3">
                <button
                    class="px-4 py-2 rounded-lg text-sm font-semibold text-white/50 hover:text-white transition-colors duration-100"
                    x-on:click="cerrarModal()">
                    Cancelar
                </button>
                <button
                    class="inline-flex items-center gap-2 px-5 py-2 rounded-lg bg-violet-600 hover:bg-violet-500 text-white text-sm font-semibold transition-colors duration-150 disabled:opacity-50 disabled:cursor-not-allowed"
                    :disabled="guardando"
                    x-on:click="enviarFormulario()">
                    <svg x-show="guardando" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                    </svg>
                    <span x-text="guardando ? 'Guardando…' : (editandoId ? 'Actualizar' : 'Añadir')"></span>
                </button>
            </div>

        </div>
    </div>

    {{-- ════════════════════════════════════════════════════════════════════
         MODAL — Reposición de stock con pago Stripe
    ════════════════════════════════════════════════════════════════════ --}}
    <div
        x-show="modalReponerAbierto"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
        x-on:keydown.escape.window="cerrarModalReponer()">

        <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" x-on:click="cerrarModalReponer()"></div>

        <div
            x-show="modalReponerAbierto"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="relative z-10 w-full max-w-md bg-[#1a1033] rounded-2xl ring-1 ring-white/10 shadow-2xl shadow-black/60 overflow-hidden">

            {{-- Cabecera --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-white/10">
                <h2 class="text-lg font-bold text-white">Reponer stock 📦</h2>
                <button class="text-white/40 hover:text-white transition-colors duration-100" x-on:click="cerrarModalReponer()">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Cuerpo --}}
            <div class="px-6 py-5 space-y-4">

                {{-- Info producto (solo lectura) --}}
                <div class="bg-white/5 rounded-xl px-4 py-3 space-y-1">
                    <p class="text-xs text-white/40 uppercase tracking-wider font-mono">Producto</p>
                    <p class="text-white font-semibold" x-text="reponer.nombre"></p>
                    <p class="text-white/50 text-sm">Proveedor: <span x-text="reponer.proveedor"></span></p>
                </div>

                {{-- Cantidad --}}
                <div x-show="!reponer.pagoIniciado">
                    <label class="block text-xs font-semibold text-white/50 uppercase tracking-wider mb-1.5">
                        Cantidad a reponer (unidades)
                    </label>
                    <input
                        type="number"
                        x-model="reponer.cantidad"
                        min="1"
                        class="w-full rounded-lg bg-white/8 border border-white/10 text-white placeholder-white/30 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
                    <p class="text-white/40 text-xs mt-2">
                        Total estimado:
                        <span class="text-emerald-400 font-bold" x-text="(reponer.cantidad * reponer.precioUnitario).toFixed(2) + ' €'"></span>
                    </p>
                </div>

                {{-- Panel Stripe Elements (aparece tras iniciar el pago) --}}
                <div x-show="reponer.pagoIniciado" class="space-y-3">
                    <div class="bg-white/5 rounded-xl px-4 py-3">
                        <p class="text-xs text-white/40 uppercase tracking-wider font-mono mb-1">Total a pagar</p>
                        <p class="text-emerald-400 font-bold text-xl" x-text="reponer.precioTotal.toFixed(2) + ' €'"></p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-white/50 uppercase tracking-wider mb-1.5">Datos de pago</label>
                        <div id="stripe-card-element"
                             class="w-full rounded-lg bg-white/10 border border-white/10 px-4 py-3 text-white">
                        </div>
                        <p x-show="reponer.stripeError" x-text="reponer.stripeError" class="text-red-400 text-xs mt-2"></p>
                    </div>
                </div>

                {{-- Error general --}}
                <p
                    x-show="reponer.errorMensaje"
                    x-text="reponer.errorMensaje"
                    class="text-red-400 text-xs font-semibold bg-red-500/10 rounded-lg px-4 py-2.5 ring-1 ring-red-500/20">
                </p>

            </div>

            {{-- Pie --}}
            <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-white/10 bg-white/3">
                <button
                    class="px-4 py-2 rounded-lg text-sm font-semibold text-white/50 hover:text-white transition-colors duration-100"
                    x-on:click="cerrarModalReponer()">
                    Cancelar
                </button>

                {{-- Paso 1: Iniciar pago --}}
                <button
                    x-show="!reponer.pagoIniciado"
                    class="inline-flex items-center gap-2 px-5 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-semibold transition-colors duration-150 disabled:opacity-50 disabled:cursor-not-allowed"
                    :disabled="reponer.cargando || reponer.cantidad < 1"
                    x-on:click="iniciarPago()">
                    <svg x-show="reponer.cargando" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                    </svg>
                    <span x-text="reponer.cargando ? 'Preparando pago…' : 'Confirmar cantidad'"></span>
                </button>

                {{-- Paso 2: Confirmar pago con Stripe --}}
                <button
                    x-show="reponer.pagoIniciado"
                    class="inline-flex items-center gap-2 px-5 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-semibold transition-colors duration-150 disabled:opacity-50 disabled:cursor-not-allowed"
                    :disabled="reponer.cargando"
                    x-on:click="pagarYConfirmar()">
                    <svg x-show="reponer.cargando" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                    </svg>
                    <span x-text="reponer.cargando ? 'Procesando pago…' : 'Pagar y reponer'"></span>
                </button>
            </div>

        </div>
    </div>

</div>{{-- fin #stock-contenedor --}}

@endsection

@push('scripts')
{{-- Stripe.js --}}
<script src="https://js.stripe.com/v3/"></script>
{{-- Alpine.js CDN (si no está ya incluido en el layout) --}}
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

{{-- Módulo de fetch del stock --}}
<script src="{{ asset('js/camarero/stock.js') }}"></script>


@endpush
