<?php

namespace App\Http\Controllers\Camarero;

use App\Http\Controllers\Controller;
use App\Models\Evento;
use App\Models\Notificacion;
use App\Models\Organizador;
use App\Models\ProductoBarra;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * StockController — Gestión del stock de barra para el rol camarero.
 *
 * El camarero sólo puede ver y modificar productos de los eventos donde
 * él mismo está asignado como camarero_id en la tabla eventos.
 */
class StockController extends Controller
{
    /**
     * Devuelve el Organizador del usuario autenticado o lanza 404.
     */
    private function organizadorActual(): Organizador
    {
        return Organizador::where('usuario_id', Auth::id())->firstOrFail();
    }

    /**
     * Devuelve los IDs de eventos asignados al organizador dado.
     */
    private function eventosDelCamarero(Organizador $organizador): \Illuminate\Support\Collection
    {
        return Evento::where('camarero_id', $organizador->id)->pluck('id');
    }

    /**
     * Busca un ProductoBarra y verifica que pertenece a un evento del camarero.
     * Lanza 403 si no tiene permiso.
     */
    private function productoDelCamarero(int $id, Organizador $organizador): ProductoBarra
    {
        $producto = ProductoBarra::with('evento')->findOrFail($id);

        $eventoIds = $this->eventosDelCamarero($organizador);

        if (! $eventoIds->contains($producto->evento_id)) {
            abort(403, 'No tienes permiso para gestionar este producto.');
        }

        return $producto;
    }

    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Lista todos los productos de barra de los eventos del camarero.
     */
    public function index()
    {
        $organizador = $this->organizadorActual();

        $eventoIds = $this->eventosDelCamarero($organizador);

        $productos = ProductoBarra::with('evento')
            ->whereIn('evento_id', $eventoIds)
            ->orderBy('nombre')
            ->get();

        $eventos = Evento::whereIn('id', $eventoIds)->orderBy('titulo')->get();

        return view('camarero.stock.index', compact('productos', 'organizador', 'eventos'));
    }

    /**
     * Crea un nuevo producto de barra.
     */
    public function store(Request $request): JsonResponse
    {
        $organizador = $this->organizadorActual();

        $datos = $request->validate([
            'nombre'          => ['required', 'string', 'max:255'],
            'tipo_producto'   => ['required', 'in:cocktail,destilado,sin_alcohol'],
            'proveedor'       => ['required', 'string', 'max:255'],
            'stock'           => ['required', 'integer', 'min:0'],
            'precio_unitario' => ['required', 'numeric', 'min:0'],
            'evento_id'       => ['required', 'exists:eventos,id'],
        ]);

        // Verifica que el evento pertenece a este camarero
        $eventoIds = $this->eventosDelCamarero($organizador);
        if (! $eventoIds->contains((int) $datos['evento_id'])) {
            return response()->json(['ok' => false, 'mensaje' => 'No tienes permiso sobre ese evento.'], 403);
        }

        $producto = ProductoBarra::create($datos);

        if ($producto->stockBajo()) {
            $usuario = Auth::user();
            Notificacion::crear(
                $usuario->id,
                Notificacion::GENERAL,
                '⚠️ Stock bajo: ' . $producto->nombre,
                'Quedan ' . $producto->stock . ' unidades. Considera reponer.',
                route('camarero.stock.index')
            );
        }

        return response()->json(['ok' => true, 'mensaje' => 'Producto añadido correctamente.']);
    }

    /**
     * Devuelve los datos de un producto para precargar el modal de edición.
     */
    public function show(int $id): JsonResponse
    {
        $organizador = $this->organizadorActual();
        $producto    = $this->productoDelCamarero($id, $organizador);

        return response()->json([
            'id'              => $producto->id,
            'nombre'          => $producto->nombre,
            'tipo_producto'   => $producto->tipo_producto,
            'proveedor'       => $producto->proveedor,
            'stock'           => $producto->stock,
            'precio_unitario' => $producto->precio_unitario,
            'evento_id'       => $producto->evento_id,
            'created_at'      => $producto->created_at,
            'updated_at'      => $producto->updated_at,
        ]);
    }

    /**
     * Actualiza un producto de barra existente.
     * No se permite cambiar el evento al que pertenece.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $organizador = $this->organizadorActual();
        $producto    = $this->productoDelCamarero($id, $organizador);

        $datos = $request->validate([
            'nombre'          => ['required', 'string', 'max:255'],
            'tipo_producto'   => ['required', 'in:cocktail,destilado,sin_alcohol'],
            'proveedor'       => ['required', 'string', 'max:255'],
            'stock'           => ['required', 'integer', 'min:0'],
            'precio_unitario' => ['required', 'numeric', 'min:0'],
        ]);

        $producto->update($datos);

        if ($producto->stockBajo()) {
            $usuario = Auth::user();
            Notificacion::crear(
                $usuario->id,
                Notificacion::GENERAL,
                '⚠️ Stock bajo: ' . $producto->nombre,
                'Quedan ' . $producto->stock . ' unidades. Considera reponer.',
                route('camarero.stock.index')
            );
        }

        return response()->json(['ok' => true, 'mensaje' => 'Producto actualizado.']);
    }

    /**
     * Elimina un producto de barra si no tiene pedidos asociados.
     */
    public function destroy(int $id): JsonResponse
    {
        $organizador = $this->organizadorActual();
        $producto    = $this->productoDelCamarero($id, $organizador);

        if ($producto->pedidos()->exists()) {
            return response()->json([
                'ok'      => false,
                'mensaje' => 'No se puede eliminar: este producto tiene pedidos de proveedor asociados.',
            ], 422);
        }

        $producto->delete();

        return response()->json(['ok' => true]);
    }
}
