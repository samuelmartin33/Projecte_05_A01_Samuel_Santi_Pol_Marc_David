<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Mail\PedidoPagado;
use App\Models\PedidoProveedor;
use App\Models\ProductoBarra;
use App\Models\TipoBebida;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * ProductoBarraController — CRUD de stock de barra por empresa.
 *
 * El stock es global por empresa (no por evento), para reutilizarlo entre eventos.
 * El precio de reposición al proveedor es siempre 2.50 €/ud (fijo).
 *
 * Flujo de reposición:
 *  1. El camarero crea un producto (stock = 0).
 *  2. Genera un pedido al proveedor indicando la cantidad deseada.
 *  3. El sistema muestra la factura ficticia (cantidad × 2.50 €).
 *  4. El camarero paga con Stripe → stock += cantidad + email de confirmación.
 */
class ProductoBarraController extends Controller
{
    /**
     * Resuelve la empresa del usuario autenticado (empresa directa u organizador/camarero).
     */
    private function empresa()
    {
        $user = Auth::user();
        if (!$user) abort(403);

        if ($user->isEmpresa()) {
            $empresa = $user->empresa;
        } elseif ($user->isOrganizador()) {
            $empresa = $user->organizador?->empresa ?? null;
        } else {
            abort(403, 'Acceso restringido a empresas y camareros.');
        }

        if (!$empresa) {
            abort(403, 'Tu cuenta no tiene un perfil de empresa configurado.');
        }

        return $empresa;
    }

    /**
     * GET /camarero/stock
     * Vista principal del CRUD de stock por empresa.
     */
    public function index()
    {
        $empresa = $this->empresa();
        $tipos   = TipoBebida::activos()->orderBy('nombre')->get();

        // Todos los productos de la empresa ordenados por tipo y nombre
        $productos = ProductoBarra::where('empresa_id', $empresa->id)
            ->with(['pedidos' => fn($q) => $q->orderBy('created_at', 'desc')])
            ->orderBy('tipo_producto')
            ->orderBy('nombre')
            ->get();

        // Pedidos pendientes de pago de la empresa
        $pedidosPendientes = PedidoProveedor::whereHas('producto', function ($q) use ($empresa) {
            $q->where('empresa_id', $empresa->id);
        })
        ->where('estado', 'pendiente')
        ->with('producto')
        ->orderBy('created_at', 'desc')
        ->get();

        return view('camarero.stock.index', compact(
            'empresa', 'tipos', 'productos', 'pedidosPendientes'
        ));
    }

    /**
     * POST /camarero/stock  (AJAX)
     * Crea un nuevo producto con stock inicial = 0.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'nombre'        => ['required', 'string', 'max:100'],
            'tipo_producto' => ['required', 'string', 'max:100'],
            'proveedor'     => ['required', 'string', 'max:150'],
        ]);

        $empresa = $this->empresa();

        // Registrar tipo de bebida nuevo si no existe
        TipoBebida::firstOrCreate(
            ['nombre' => $request->tipo_producto],
            ['icono' => '🍹', 'activo' => true]
        );

        $producto = ProductoBarra::create([
            'empresa_id'     => $empresa->id,
            'nombre'         => $request->nombre,
            'tipo_producto'  => $request->tipo_producto,
            'proveedor'      => $request->proveedor,
            'stock'          => 0,
            'precio_unitario'=> ProductoBarra::PRECIO_PROVEEDOR,
        ]);

        return response()->json([
            'ok'      => true,
            'mensaje' => 'Producto "' . $producto->nombre . '" creado correctamente.',
            'producto'=> $producto,
        ]);
    }

    /**
     * PUT /camarero/stock/{id}  (AJAX)
     * Edita nombre, tipo y proveedor. El stock solo cambia mediante pedidos.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'nombre'        => ['required', 'string', 'max:100'],
            'tipo_producto' => ['required', 'string', 'max:100'],
            'proveedor'     => ['required', 'string', 'max:150'],
        ]);

        $empresa  = $this->empresa();
        $producto = $this->productoDeEmpresa($id, $empresa->id);

        if (!$producto) {
            return response()->json(['ok' => false, 'error' => 'Producto no encontrado.'], 404);
        }

        TipoBebida::firstOrCreate(
            ['nombre' => $request->tipo_producto],
            ['icono' => '🍹', 'activo' => true]
        );

        $producto->update([
            'nombre'        => $request->nombre,
            'tipo_producto' => $request->tipo_producto,
            'proveedor'     => $request->proveedor,
        ]);

        return response()->json([
            'ok'      => true,
            'mensaje' => 'Producto actualizado correctamente.',
            'producto'=> $producto->fresh(),
        ]);
    }

    /**
     * DELETE /camarero/stock/{id}  (AJAX)
     * Elimina un producto. Solo si no tiene pedidos pagados (historial contable).
     */
    public function destroy(int $id): JsonResponse
    {
        $empresa  = $this->empresa();
        $producto = $this->productoDeEmpresa($id, $empresa->id);

        if (!$producto) {
            return response()->json(['ok' => false, 'error' => 'Producto no encontrado.'], 404);
        }

        if ($producto->pedidos()->where('estado', 'pagado')->exists()) {
            return response()->json([
                'ok'    => false,
                'error' => 'No se puede eliminar: el producto tiene pedidos pagados en el historial.',
            ], 422);
        }

        $producto->pedidos()->where('estado', 'pendiente')->delete();
        $producto->delete();

        return response()->json(['ok' => true, 'mensaje' => 'Producto eliminado correctamente.']);
    }

    /**
     * POST /camarero/stock/{id}/pedido  (AJAX)
     * Genera un pedido de reposición al proveedor.
     * Precio fijo: 2.50 €/ud. La cantidad la especifica el camarero.
     */
    public function crearPedido(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'cantidad' => ['required', 'integer', 'min:1', 'max:9999'],
        ]);

        $empresa  = $this->empresa();
        $producto = $this->productoDeEmpresa($id, $empresa->id);

        if (!$producto) {
            return response()->json(['ok' => false, 'error' => 'Producto no encontrado.'], 404);
        }

        // Solo puede haber un pedido pendiente por producto a la vez
        if ($producto->pedidos()->where('estado', 'pendiente')->exists()) {
            return response()->json([
                'ok'    => false,
                'error' => 'Ya existe un pedido pendiente para este producto. Págalo antes de crear otro.',
            ], 422);
        }

        $cantidad    = (int) $request->cantidad;
        $precioTotal = round($cantidad * ProductoBarra::PRECIO_PROVEEDOR, 2);

        $pedido = PedidoProveedor::create([
            'producto_barra_id' => $producto->id,
            'cantidad'          => $cantidad,
            'precio_total'      => $precioTotal,
            'estado'            => 'pendiente',
        ]);

        return response()->json([
            'ok'    => true,
            'pedido'=> [
                'id'            => $pedido->id,
                'cantidad'      => $pedido->cantidad,
                'precio_total'  => number_format($pedido->precio_total, 2, ',', '.'),
                'precio_unidad' => number_format(ProductoBarra::PRECIO_PROVEEDOR, 2, ',', '.'),
                'proveedor'     => $producto->proveedor,
                'producto'      => $producto->nombre,
                'fecha'         => $pedido->created_at->format('d/m/Y H:i'),
            ],
        ]);
    }

    /**
     * POST /camarero/stock/pedidos/{pedidoId}/crear-payment-intent  (AJAX)
     * Paso 1 Stripe: crea un PaymentIntent por el importe del pedido (precio × 2.50 €).
     */
    public function crearPaymentIntentPedido(int $pedidoId): JsonResponse
    {
        $empresa = $this->empresa();
        $pedido  = PedidoProveedor::with('producto')->find($pedidoId);

        if (!$pedido) {
            return response()->json(['success' => false, 'message' => 'Pedido no encontrado.'], 404);
        }

        if (!$this->productoDeEmpresa($pedido->producto_barra_id, $empresa->id)) {
            return response()->json(['success' => false, 'message' => 'No autorizado.'], 403);
        }

        if ($pedido->estaPagado()) {
            return response()->json(['success' => false, 'message' => 'Este pedido ya está pagado.'], 422);
        }

        $amountCents = (int) round($pedido->precio_total * 100);

        try {
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

            $pi = \Stripe\PaymentIntent::create([
                'amount'      => $amountCents,
                'currency'    => 'eur',
                'description' => 'Pedido #' . $pedido->id . ' — ' . $pedido->producto?->nombre . ' · VIBEZ',
                'metadata'    => [
                    'tipo'       => 'pedido_proveedor',
                    'pedido_id'  => $pedidoId,
                    'empresa_id' => $empresa->id,
                ],
            ]);

            return response()->json([
                'success'           => true,
                'client_secret'     => $pi->client_secret,
                'payment_intent_id' => $pi->id,
            ]);
        } catch (\Throwable $e) {
            Log::error('Stripe PI pedido proveedor: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error al iniciar el pago con Stripe.'], 500);
        }
    }

    /**
     * POST /camarero/stock/pedidos/{pedidoId}/confirmar-pago  (AJAX)
     * Paso 3 Stripe: verifica PI succeeded → stock += cantidad + email.
     */
    public function confirmarPagoPedido(Request $request, int $pedidoId): JsonResponse
    {
        $request->validate([
            'payment_intent_id' => ['required', 'string', 'max:255'],
        ]);

        $empresa = $this->empresa();
        $pedido  = PedidoProveedor::with('producto')->find($pedidoId);

        if (!$pedido) {
            return response()->json(['success' => false, 'message' => 'Pedido no encontrado.'], 404);
        }

        if (!$this->productoDeEmpresa($pedido->producto_barra_id, $empresa->id)) {
            return response()->json(['success' => false, 'message' => 'No autorizado.'], 403);
        }

        if ($pedido->estaPagado()) {
            return response()->json(['success' => true, 'mensaje' => 'Pedido ya registrado como pagado.']);
        }

        try {
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
            $pi = \Stripe\PaymentIntent::retrieve($request->payment_intent_id);

            if ($pi->status !== 'succeeded') {
                return response()->json(['success' => false, 'message' => 'El pago no se ha completado todavía.'], 422);
            }
        } catch (\Throwable $e) {
            Log::error('Stripe PI pedido retrieve: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'No se pudo verificar el pago con Stripe.'], 500);
        }

        try {
            DB::transaction(function () use ($pedido) {
                $pedido->update(['estado' => 'pagado']);
                $pedido->producto->increment('stock', $pedido->cantidad);
            });

            try {
                Mail::to(Auth::user()->email)->send(new PedidoPagado($pedido->fresh(['producto'])));
            } catch (\Throwable $e) {
                Log::error('Email pedido pagado: ' . $e->getMessage());
            }

            return response()->json([
                'success'     => true,
                'mensaje'     => 'Pago confirmado. Se han añadido ' . $pedido->cantidad . ' unidades al stock.',
                'stock_nuevo' => $pedido->producto->fresh()->stock,
            ]);
        } catch (\Throwable $e) {
            Log::error('Error confirmar pedido: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error al registrar el pago.'], 500);
        }
    }

    /**
     * GET /camarero/stock/alertas  (AJAX)
     * Devuelve productos con stock ≤ 2 de la empresa. Usado por el badge del navbar.
     */
    public function alertas(): JsonResponse
    {
        $empresa = $this->empresa();
        $count   = ProductoBarra::where('empresa_id', $empresa->id)
            ->where('stock', '<=', 2)
            ->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Helper: obtiene un ProductoBarra verificando que pertenece a la empresa.
     */
    private function productoDeEmpresa(int $productoId, int $empresaId): ?ProductoBarra
    {
        return ProductoBarra::where('empresa_id', $empresaId)->find($productoId);
    }
}
