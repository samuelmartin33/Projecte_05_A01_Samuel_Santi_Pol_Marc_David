@extends('layouts.app')

@section('titulo', 'Gestión de Stock — ' . $empresa->nombre_empresa . ' — VIBEZ')

@push('estilos')
<link rel="stylesheet" href="{{ asset('css/camarero-stock.css') }}">
@endpush

@section('content')

@include('partials.home.nav')

<div class="stock-wrapper">

    {{-- Hero --}}
    <div class="stock-hero">
        <div style="font-size:2.5rem;margin-bottom:12px;position:relative;">🍺</div>
        <h1>Gestión de Stock</h1>
        <p>{{ $empresa->nombre_empresa }} · Control de productos de barra</p>
    </div>

    {{-- Alertas de stock bajo --}}
    @php $productosBajos = $productos->filter(fn($p) => $p->stock <= 2); @endphp
    @if($productosBajos->isNotEmpty())
    <div class="stock-alerta-banner" id="banner-alerta">
        <span class="stock-alerta-icono">⚠️</span>
        <div>
            <strong>Stock bajo detectado</strong>
            <span>{{ $productosBajos->count() }} {{ $productosBajos->count() === 1 ? 'producto' : 'productos' }} con 2 unidades o menos</span>
        </div>
        <button onclick="document.getElementById('banner-alerta').style.display='none'" class="stock-alerta-cerrar">✕</button>
    </div>
    @endif

    {{-- Cabecera + botón nuevo producto --}}
    <div class="stock-card">
        <div class="stock-card__header">
            <div class="stock-card__titulo">📦 Productos en barra</div>
            <button class="btn-nuevo-producto" onclick="abrirModalProducto(null)">+ Nuevo producto</button>
        </div>

        @if($productos->isEmpty())
        <div class="stock-vacio">
            <p>No hay productos registrados todavía.</p>
            <p>Crea el primer producto para comenzar a gestionar el stock.</p>
            <button class="btn-primary-stock" onclick="abrirModalProducto(null)">Crear producto</button>
        </div>
        @else
        <div class="stock-tabla-wrap">
            <table class="stock-tabla">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Tipo</th>
                        <th>Proveedor</th>
                        <th>Stock</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="tbody-productos">
                @foreach($productos as $prod)
                @php $bajo = $prod->stock <= 2; @endphp
                <tr class="fila-producto {{ $bajo ? 'stock-bajo' : '' }}" data-id="{{ $prod->id }}">
                    <td class="col-nombre">
                        {{ $prod->nombre }}
                        @if($bajo)
                        <span class="badge-bajo">⚠ Bajo</span>
                        @endif
                    </td>
                    <td>{{ $prod->tipo_producto }}</td>
                    <td>{{ $prod->proveedor }}</td>
                    <td class="col-stock">
                        <span class="stock-num {{ $bajo ? 'stock-num--bajo' : ($prod->stock === 0 ? 'stock-num--cero' : '') }}">
                            {{ $prod->stock }}
                        </span>
                    </td>
                    <td class="col-acciones">
                        <button class="btn-accion btn-editar"
                                onclick="abrirModalProducto({{ json_encode(['id'=>$prod->id,'nombre'=>$prod->nombre,'tipo_producto'=>$prod->tipo_producto,'proveedor'=>$prod->proveedor]) }})"
                                title="Editar">✏️</button>
                        <button class="btn-accion btn-reponer"
                                onclick="abrirModalPedido({{ $prod->id }}, '{{ addslashes($prod->nombre) }}')"
                                title="Reponer stock">📦</button>
                        <button class="btn-accion btn-eliminar"
                                onclick="eliminarProducto({{ $prod->id }}, '{{ addslashes($prod->nombre) }}')"
                                title="Eliminar">🗑️</button>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

    {{-- Facturas pendientes de pago --}}
    @if($pedidosPendientes->isNotEmpty())
    <div class="stock-card">
        <div class="stock-card__titulo">🧾 Facturas pendientes de pago</div>
        <div class="facturas-lista">
            @foreach($pedidosPendientes as $ped)
            <div class="factura-card" id="factura-{{ $ped->id }}">
                <div class="factura-header">
                    <span class="factura-num">Pedido #{{ $ped->id }}</span>
                    <span class="factura-fecha">{{ $ped->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="factura-body">
                    <div class="factura-linea">
                        <span>Producto</span>
                        <strong>{{ $ped->producto?->nombre }}</strong>
                    </div>
                    <div class="factura-linea">
                        <span>Proveedor</span>
                        <strong>{{ $ped->producto?->proveedor }}</strong>
                    </div>
                    <div class="factura-linea">
                        <span>Cantidad</span>
                        <strong>{{ $ped->cantidad }} unidades</strong>
                    </div>
                    <div class="factura-linea">
                        <span>Precio/ud.</span>
                        <strong>{{ number_format(\App\Models\ProductoBarra::PRECIO_PROVEEDOR, 2, ',', '.') }} €</strong>
                    </div>
                    <div class="factura-total">
                        <span>Total a pagar</span>
                        <strong class="factura-importe">{{ number_format($ped->precio_total, 2, ',', '.') }} €</strong>
                    </div>
                </div>
                <button class="btn-pagar" onclick="abrirModalPago({{ $ped->id }}, '{{ number_format($ped->precio_total, 2, ',', '.') }}')">
                    💳 Pagar ahora
                </button>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>

{{-- Modal: Pagar pedido con Stripe --}}
<div class="modal-overlay" id="modal-pago" style="display:none;" onclick="cerrarModalSiOverlay(event,'modal-pago')">
    <div class="modal-box">
        <div class="modal-titulo">💳 Pagar pedido al proveedor</div>
        <input type="hidden" id="pago-pedido-id" value="">
        <div class="factura-previa" style="margin-bottom:16px;">
            <div class="factura-linea">
                <span>Pedido</span>
                <strong id="pago-pedido-ref">—</strong>
            </div>
            <div class="factura-total">
                <span>Total a pagar</span>
                <strong class="factura-importe" id="pago-total">—</strong>
            </div>
        </div>
        <div class="form-grupo">
            <label class="stock-label">Datos de tarjeta</label>
            <div id="stripe-pedido-card-el" style="background:#07060c;border:1.5px solid rgba(245,241,234,0.14);padding:12px 14px;"></div>
            <div id="stripe-pedido-error" style="display:none;color:#f87171;font-family:'Archivo Narrow',sans-serif;font-size:12px;margin-top:8px;text-transform:uppercase;letter-spacing:0.06em;"></div>
        </div>
        @if(app()->environment('local'))
        <p style="font-family:'Archivo Narrow',sans-serif;font-size:10px;color:rgba(245,241,234,0.25);margin:0 0 12px;text-transform:uppercase;letter-spacing:0.08em;">
            Prueba: 4242 4242 4242 4242 · Cualquier fecha · Cualquier CVC
        </p>
        @endif
        <div class="modal-acciones">
            <button class="btn-cancelar" id="btn-cancelar-pago" onclick="cerrarModal('modal-pago')">Cancelar</button>
            <button class="btn-guardar" id="btn-confirmar-pago" onclick="procesarPagoPedido()"
                    style="background:linear-gradient(135deg,#16a34a,#4ade80);color:#07060c;">
                💳 Pagar ahora
            </button>
        </div>
    </div>
</div>

{{-- Modal: Crear / Editar producto --}}
<div class="modal-overlay" id="modal-producto" style="display:none;" onclick="cerrarModalSiOverlay(event,'modal-producto')">
    <div class="modal-box">
        <div class="modal-titulo" id="modal-producto-titulo">Nuevo producto</div>
        <input type="hidden" id="producto-id" value="">

        <div class="form-grupo">
            <label class="stock-label">Nombre del producto *</label>
            <input type="text" id="producto-nombre" class="stock-input" placeholder="Ej: Mojito, Whisky Jack Daniels…" maxlength="100">
        </div>
        <div class="form-grupo">
            <label class="stock-label">Tipo de bebida *</label>
            <select class="stock-select" id="producto-tipo" onchange="verificarTipoPersonalizado()">
                @foreach($tipos as $tipo)
                <option value="{{ $tipo->nombre }}">{{ $tipo->icono }} {{ $tipo->nombre }}</option>
                @endforeach
                <option value="__nuevo__">➕ Nuevo tipo…</option>
            </select>
            <input type="text" id="producto-tipo-nuevo" class="stock-input"
                   placeholder="Nombre del nuevo tipo (Ej: Cerveza)" style="display:none;margin-top:8px;" maxlength="100">
        </div>
        <div class="form-grupo">
            <label class="stock-label">Proveedor *</label>
            <input type="text" id="producto-proveedor" class="stock-input" placeholder="Ej: Mahou, Bacardí, Coca-Cola…" maxlength="150">
        </div>
        <div class="form-grupo">
            <label class="stock-label" style="color:rgba(245,241,234,0.25);">Precio de reposición</label>
            <div style="font-family:'Anton',sans-serif;font-size:1.1rem;color:rgba(245,241,234,0.35);letter-spacing:0.04em;padding:10px 0 4px;">
                {{ number_format(\App\Models\ProductoBarra::PRECIO_PROVEEDOR, 2, ',', '.') }} € / unidad
                <span style="font-family:'Archivo Narrow',sans-serif;font-size:0.625rem;font-weight:400;text-transform:uppercase;letter-spacing:0.1em;color:rgba(245,241,234,0.2);margin-left:8px;">(fijo)</span>
            </div>
        </div>

        <div class="modal-acciones">
            <button class="btn-cancelar" onclick="cerrarModal('modal-producto')">Cancelar</button>
            <button class="btn-guardar" onclick="guardarProducto()">Guardar producto</button>
        </div>
    </div>
</div>

{{-- Modal: Crear pedido --}}
<div class="modal-overlay" id="modal-pedido" style="display:none;" onclick="cerrarModalSiOverlay(event,'modal-pedido')">
    <div class="modal-box">
        <div class="modal-titulo">Generar pedido al proveedor</div>
        <input type="hidden" id="pedido-producto-id" value="">
        <p class="modal-subtitulo" id="pedido-producto-nombre"></p>

        <div class="form-grupo">
            <label class="stock-label">Cantidad a pedir (unidades) *</label>
            <input type="number" id="pedido-cantidad" class="stock-input" min="1" max="9999" value="10"
                   oninput="calcularTotalPedido()">
        </div>

        <div class="factura-previa">
            <div class="factura-linea">
                <span>Precio por unidad (fijo)</span>
                <strong style="color:#f5f1ea;">{{ number_format(\App\Models\ProductoBarra::PRECIO_PROVEEDOR, 2, ',', '.') }} €</strong>
            </div>
            <div class="factura-total">
                <span>Total estimado</span>
                <strong class="factura-importe" id="pedido-total-estimado">—</strong>
            </div>
        </div>

        <div class="modal-acciones">
            <button class="btn-cancelar" onclick="cerrarModal('modal-pedido')">Cancelar</button>
            <button class="btn-guardar" onclick="confirmarPedido()">Generar pedido</button>
        </div>
    </div>
</div>

@include('partials.home.footer')

@endsection

@push('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
window.stockData = {
    urlStore:         '{{ route('camarero.stock.store') }}',
    urlUpdate:        '{{ route('camarero.stock.update', ['id' => '__ID__']) }}',
    urlDestroy:       '{{ route('camarero.stock.destroy', ['id' => '__ID__']) }}',
    urlPedido:        '{{ route('camarero.stock.pedido', ['id' => '__ID__']) }}',
    urlPaymentIntent: '{{ route('camarero.stock.pedido.payment-intent', ['pedidoId' => '__ID__']) }}',
    urlConfirmarPago: '{{ route('camarero.stock.pedido.confirmar', ['pedidoId' => '__ID__']) }}',
    precioUnidad:     {{ \App\Models\ProductoBarra::PRECIO_PROVEEDOR }},
    stripeKey:        '{{ config('services.stripe.key') }}',
    csrf:             '{{ csrf_token() }}',
};
</script>
<script src="{{ asset('js/camarero-stock.js') }}"></script>
@endpush
