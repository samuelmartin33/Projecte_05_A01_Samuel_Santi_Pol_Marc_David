@extends('layouts.app')

@section('titulo', 'Canje de bonos — VIBEZ')

@section('contenido')
<div id="canje-contenedor"
     class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10"
     x-data="canjeBonoApp('{{ csrf_token() }}', '{{ route('camarero.bonos.canjear') }}')">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-black text-white tracking-tight">
                Canje de <span class="text-emerald-400">Bonos</span>
            </h1>
            <p class="text-white/50 text-sm mt-1">Escanea los códigos QR de los clientes para descontar bebidas.</p>
        </div>

        {{-- Selector de Evento --}}
        <div class="relative min-w-[250px]">
            <select
                x-model="eventoId"
                class="w-full appearance-none bg-white/5 border border-white/10 text-white text-sm rounded-xl px-4 py-3 pr-10 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500/50 transition-colors">
                <option value="">-- Selecciona un evento --</option>
                @foreach($eventos as $evento)
                    <option value="{{ $evento->id }}" class="bg-[#1a1033]">{{ $evento->titulo }}</option>
                @endforeach
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-white/50">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        {{-- Lado Izquierdo: Escáner QR --}}
        <div class="bg-white/5 border border-white/10 rounded-2xl p-6 flex flex-col items-center">
            <h2 class="text-lg font-bold text-white mb-4">Lector QR</h2>
            
            <div x-show="!eventoId" class="text-center py-10">
                <p class="text-amber-400 font-medium">⚠️ Selecciona un evento arriba para activar el escáner.</p>
            </div>

            <div x-show="eventoId" class="w-full max-w-[400px]">
                <div id="qr-reader" class="rounded-xl overflow-hidden bg-black ring-1 ring-white/10"></div>
            </div>
            
            {{-- Mensaje de Error / Info tras escanear --}}
            <div x-show="error" 
                 x-transition
                 class="w-full mt-4 bg-red-500/10 border border-red-500/20 rounded-xl p-4 text-center">
                <p class="text-red-400 text-sm font-semibold" x-text="error"></p>
            </div>

            <div x-show="ultimoEscaneo && !modalAbierto && !error"
                 x-transition
                 class="w-full mt-4 bg-emerald-500/10 border border-emerald-500/20 rounded-xl p-4">
                <p class="text-xs text-emerald-400/70 uppercase tracking-wider font-mono mb-1">Último canje exitoso</p>
                <p class="text-white font-semibold" x-text="ultimoEscaneo?.cliente"></p>
                <div class="flex justify-between items-center mt-2">
                    <span class="text-white/60 text-sm" x-text="'Bebida: ' + formatTipoProducto(ultimoEscaneo?.tipo_producto)"></span>
                    <span class="text-emerald-400 font-bold bg-emerald-500/20 px-2.5 py-0.5 rounded text-sm" x-text="ultimoEscaneo?.bebidas_restantes + ' restantes'"></span>
                </div>
            </div>
        </div>

        {{-- Lado Derecho: Historial de la Sesión --}}
        <div class="bg-white/5 border border-white/10 rounded-2xl p-6 flex flex-col">
            <h2 class="text-lg font-bold text-white mb-4">Historial de la sesión</h2>
            
            <div x-show="historial.length === 0" class="flex-1 flex items-center justify-center py-10">
                <p class="text-white/30 text-sm italic">No hay canjes registrados en esta sesión.</p>
            </div>

            <div x-show="historial.length > 0" class="flex-1 overflow-auto -mx-2 px-2">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-white/40 uppercase bg-white/5 sticky top-0 backdrop-blur-sm">
                        <tr>
                            <th class="px-4 py-3 rounded-l-lg font-medium">Hora</th>
                            <th class="px-4 py-3 font-medium">Cliente</th>
                            <th class="px-4 py-3 font-medium">Bebida</th>
                            <th class="px-4 py-3 rounded-r-lg font-medium text-right">Restan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(item, index) in historial" :key="index">
                            <tr class="border-b border-white/5 hover:bg-white/5 transition-colors">
                                <td class="px-4 py-3 text-white/50 font-mono text-xs" x-text="item.hora"></td>
                                <td class="px-4 py-3 text-white font-medium" x-text="item.cliente"></td>
                                <td class="px-4 py-3 text-white/70" x-text="formatTipoProducto(item.tipo_producto)"></td>
                                <td class="px-4 py-3 text-emerald-400 font-bold text-right" x-text="item.bebidas_restantes"></td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- ════════════════════════════════════════════════════════════════════
         MODAL — Selección de Tipo de Bebida
    ════════════════════════════════════════════════════════════════════ --}}
    <div
        x-show="modalAbierto"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center p-4">

        <div class="absolute inset-0 bg-black/70 backdrop-blur-sm"></div>

        <div
            x-show="modalAbierto"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="relative z-10 w-full max-w-sm bg-[#1a1033] rounded-2xl ring-1 ring-white/10 shadow-2xl shadow-black/60 overflow-hidden text-center p-6">

            <div class="w-16 h-16 bg-emerald-500/20 text-emerald-400 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            
            <h3 class="text-xl font-bold text-white mb-2">Código Válido</h3>
            <p class="text-white/60 text-sm mb-6">Selecciona el tipo de bebida a consumir.</p>

            <div class="space-y-3">
                <button 
                    @click="confirmarCanje('cocktail')"
                    :disabled="procesando"
                    class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-3 px-4 rounded-xl transition-colors flex items-center justify-center disabled:opacity-50">
                    🍸 Cóctel
                </button>
                <button 
                    @click="confirmarCanje('destilado')"
                    :disabled="procesando"
                    class="w-full bg-purple-600 hover:bg-purple-500 text-white font-bold py-3 px-4 rounded-xl transition-colors flex items-center justify-center disabled:opacity-50">
                    🥃 Destilado
                </button>
                <button 
                    @click="confirmarCanje('sin_alcohol')"
                    :disabled="procesando"
                    class="w-full bg-emerald-600 hover:bg-emerald-500 text-white font-bold py-3 px-4 rounded-xl transition-colors flex items-center justify-center disabled:opacity-50">
                    🥤 Sin Alcohol
                </button>
            </div>

            <button 
                @click="cerrarModal()"
                :disabled="procesando"
                class="mt-6 text-white/40 hover:text-white/80 text-sm font-medium transition-colors">
                Cancelar escaneo
            </button>
        </div>
    </div>

</div>
@endsection

@push('scripts')
{{-- Librería QR Html5Qrcode --}}
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
{{-- Alpine.js --}}
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

{{-- Lógica de negocio (aislada del DOM) --}}
<script src="{{ asset('js/camarero/canje-bono.js') }}"></script>


@endpush
