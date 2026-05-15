<?php

namespace App\Http\Controllers;

use App\Mail\EntradaComprada;
use App\Models\Cupon;
use App\Models\CuponUso;
use App\Models\Entrada;
use App\Models\Evento;
use App\Models\Pedido;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

/**
 * EntradaController — Controlador de entradas y pedidos de VIBEZ.
 *
 * Responsabilidades:
 *  - Gestionar la compra de entradas para eventos (creación de Pedido + Entradas).
 *  - Listar los pedidos del usuario autenticado.
 *  - Mostrar la página de confirmación de una compra.
 *
 * Conceptos clave para el alumno:
 *  - DB::transaction(): envuelve varias operaciones SQL en una sola unidad atómica.
 *    Si cualquier sentencia falla, se deshacen TODAS las anteriores (rollback).
 *    Así evitamos dejar un Pedido creado sin sus Entradas asociadas.
 *  - Str::uuid(): genera un identificador único universal (UUID v4) de 36 caracteres.
 *    Se usa como código QR de cada entrada para que sea irrepetible y verificable.
 *  - Auth::id(): devuelve el ID del usuario que tiene la sesión iniciada.
 *  - Auth::user(): devuelve el objeto completo del usuario autenticado.
 *  - Route Model Binding: cuando un método recibe un modelo (p.ej. Pedido $pedido),
 *    Laravel busca automáticamente en la BD el registro cuyo ID viene en la URL.
 */
class EntradaController extends Controller
{
    /**
     * Procesa la compra de entradas para un evento.
     *
     * Flujo:
     *  1. Comprueba que el usuario no es administrador (los admins no compran entradas).
     *  2. Valida los datos del formulario (evento_id y cantidad).
     *  3. Recupera el evento y verifica que hay aforo disponible.
     *  4. Abre una transacción DB: crea el Pedido, crea las Entradas (una por cantidad)
     *     y actualiza el aforo_actual del evento.
     *  5. Devuelve JSON con el ID del pedido y la URL de confirmación.
     *
     * Por qué DB::transaction():
     *  Si se crea el Pedido pero luego falla la creación de alguna Entrada (p.ej.
     *  por un error de BD), el Pedido también se revierte. Sin transacción quedarían
     *  pedidos huérfanos sin entradas, lo que sería un error grave de datos.
     *
     * Por qué Str::uuid():
     *  Cada entrada necesita un código único que el lector de QR pueda validar.
     *  Un UUID v4 tiene 2^122 combinaciones posibles, por lo que es prácticamente
     *  imposible que dos entradas tengan el mismo código.
     *
     * @param  Request $request  Petición HTTP con 'evento_id' y 'cantidad'.
     * @return JsonResponse      JSON con 'success', 'pedido_id' y 'redirect' (o mensaje de error).
     */
    public function comprar(Request $request): JsonResponse
    {
        /** @var \App\Models\Usuario $usuario */
        $usuario = Auth::user();

        // Solo los usuarios normales pueden comprar entradas. Los administradores
        // gestionan la plataforma pero no son asistentes a eventos.
        if ($usuario && $usuario->isAdmin()) {
            return response()->json(['success' => false, 'message' => 'Los administradores no pueden comprar entradas.'], 403);
        }

        // Validamos los datos enviados desde el frontend.
        // 'exists:eventos,id' comprueba que el evento_id existe en la tabla 'eventos'.
        // 'min:1' y 'max:10' limitan la compra a un rango razonable por petición.
        $request->validate([
            'evento_id'     => ['required', 'integer', 'exists:eventos,id'],
            'cantidad'      => ['required', 'integer', 'min:1', 'max:10'],
            'cupon_codigo'  => ['nullable', 'string', 'max:50'],
        ], [
            'evento_id.exists' => 'El evento no existe.',
            'cantidad.min'     => 'Debes seleccionar al menos 1 entrada.',
            'cantidad.max'     => 'No puedes comprar más de 10 entradas a la vez.',
        ]);

        $eventoId     = (int) $request->evento_id;
        $cantidad     = (int) $request->cantidad;
        $cuponCodigo  = $request->cupon_codigo ? strtoupper(trim($request->cupon_codigo)) : null;

        // findOrFail lanza una excepción 404 si el evento no existe o no está activo
        // (estado = 1 significa que el evento está publicado y disponible).
        $evento = Evento::where('estado', 1)->findOrFail($eventoId);

        // Comprobamos el aforo antes de iniciar la transacción para evitar abrirla
        // innecesariamente si ya no hay plazas. aforo_maximo === null significa
        // que el evento tiene aforo ilimitado.
        if ($evento->aforo_maximo !== null && ($evento->aforo_maximo - $evento->aforo_actual) < $cantidad) {
            return response()->json([
                'success' => false,
                'message' => 'No hay suficientes entradas disponibles.',
            ], 422);
        }

        // --- Validar cupón si se ha enviado uno ---
        $cupon           = null;
        $totalDescuento  = 0.00;

        if ($cuponCodigo) {
            $cupon = Cupon::with('eventos')->where('codigo', $cuponCodigo)->first();

            if (!$cupon || !$cupon->is_valido) {
                return response()->json([
                    'success' => false,
                    'message' => 'El cupón introducido no es válido o ha expirado.',
                ], 422);
            }

            if (!$cupon->aplicaAEvento($eventoId)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Este cupón no es válido para este evento.',
                ], 422);
            }

            // Verificar límite por usuario
            if ($cupon->limite_usos_por_usuario !== null) {
                $usosDelUsuario = $cupon->usosDeUsuario(Auth::id());
                if ($usosDelUsuario >= $cupon->limite_usos_por_usuario) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Ya has usado este cupón el máximo de veces permitido.',
                    ], 422);
                }
            }
        }

        try {
            // DB::transaction() garantiza atomicidad: las tres operaciones dentro
            // (crear Pedido, crear Entradas, incrementar aforo) se ejecutan como
            // una unidad. Si cualquiera falla, todas se revierten automáticamente.
            $pedido = DB::transaction(function () use ($evento, $cantidad, $cupon) {
                $ahora = now();
                // Precio base antes de descuento
                $totalBruto      = round($evento->precio_base * $cantidad, 2);
                $totalDescuento  = 0.00;
                $totalFinal      = $totalBruto;

                // Aplicar descuento del cupón (porcentaje)
                if ($cupon) {
                    $totalDescuento = round($totalBruto * ($cupon->valor_descuento / 100), 2);
                    $totalFinal     = round($totalBruto - $totalDescuento, 2);
                }

                // Precio unitario con descuento aplicado
                $precioUnitarioPagado = $cantidad > 0
                    ? round($totalFinal / $cantidad, 2)
                    : 0;

                $pedido = Pedido::create([
                    'usuario_id'          => Auth::id(),
                    'total'               => $totalBruto,
                    'total_descuento'     => $totalDescuento,
                    'total_final'         => $totalFinal,
                    'estado'              => 1,
                    'fecha_creacion'      => $ahora,
                    'fecha_actualizacion' => $ahora,
                ]);

                for ($i = 0; $i < $cantidad; $i++) {
                    Entrada::create([
                        'pedido_id'           => $pedido->id,
                        'evento_id'           => $evento->id,
                        'estado_entrada'      => 1,
                        'codigo_qr'           => Str::uuid()->toString(),
                        'precio_unitario'     => $evento->precio_base,
                        'precio_pagado'       => $precioUnitarioPagado,
                        'estado'              => 1,
                        'fecha_creacion'      => $ahora,
                        'fecha_actualizacion' => $ahora,
                    ]);
                }

                $evento->increment('aforo_actual', $cantidad);

                // Registrar el uso del cupón
                if ($cupon) {
                    CuponUso::create([
                        'cupon_id'            => $cupon->id,
                        'pedido_id'           => $pedido->id,
                        'descuento_aplicado'  => $totalDescuento,
                        'estado'              => 1,
                        'fecha_creacion'      => $ahora,
                        'fecha_actualizacion' => null,
                    ]);

                    // Incrementar contador de usos del cupón
                    $cupon->increment('usos_actuales');
                }

                return $pedido;
            });

            // Enviamos el correo con los QR al usuario (sin cortar el flujo si falla).
            try {
                $pedido->load(['entradas.evento', 'usuario']);
                Mail::to($usuario->email)->send(new EntradaComprada($pedido));
            } catch (\Throwable $e) {
                Log::warning('No se pudo enviar el correo de entradas: ' . $e->getMessage());
            }

            // Devolvemos la URL de confirmación para que el JS redirija al usuario.
            return response()->json([
                'success'   => true,
                'pedido_id' => $pedido->id,
                'redirect'  => route('entradas.confirmacion', $pedido->id),
            ]);
        } catch (\Throwable) {
            // Cualquier excepción dentro de la transacción provoca un rollback
            // automático, así que aquí solo necesitamos informar al usuario.
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la compra. Inténtalo de nuevo.',
            ], 500);
        }
    }

    /**
     * Muestra todos los pedidos del usuario autenticado con sus entradas y eventos.
     *
     * Usa eager loading (with) para cargar en una sola consulta adicional todas las
     * entradas de cada pedido y, a su vez, el evento asociado a cada entrada.
     * Sin eager loading se produciría el problema N+1: una consulta por cada pedido
     * para obtener sus entradas, y otra por cada entrada para obtener su evento.
     *
     * El método también bloquea el acceso a administradores, que no tienen pedidos.
     *
     * @return View  Vista 'entradas.mis-entradas' con la variable $pedidos.
     */
    public function misEntradas(): View
    {
        /** @var \App\Models\Usuario $usuario */
        $usuario = Auth::user();

        // Los administradores no tienen sección de "Mis entradas".
        // abort(403) lanza una respuesta HTTP 403 Forbidden y detiene la ejecución.
        if ($usuario && $usuario->isAdmin()) {
            abort(403, 'Los administradores no tienen entradas.');
        }

        // Cargamos todos los pedidos del usuario, junto con sus entradas y los
        // datos del evento de cada entrada (eager loading anidado: entradas.evento).
        // orderByDesc ordena del más reciente al más antiguo.
        $pedidos = Pedido::where('usuario_id', Auth::id())
            ->with(['entradas.evento'])
            ->orderByDesc('fecha_creacion')
            ->get();

        return view('entradas.mis-entradas', compact('pedidos'));
    }

    /**
     * Muestra la página de confirmación de una compra concreta.
     *
     * Usa Route Model Binding: al declarar el parámetro como 'Pedido $pedido',
     * Laravel busca automáticamente en la tabla 'pedidos' el registro cuyo ID
     * coincide con el segmento de la URL (p.ej. /entradas/confirmacion/42).
     * Si no existe, devuelve 404 sin que tengamos que escribir ningún código extra.
     *
     * Autorización manual:
     *  Comparamos $pedido->usuario_id con Auth::id() para garantizar que un usuario
     *  no pueda ver la confirmación de un pedido ajeno simplemente cambiando el ID
     *  en la URL. Si no coinciden, devolvemos 403 Forbidden.
     *
     * @param  Pedido $pedido  El pedido resuelto automáticamente por Laravel desde la URL.
     * @return View            Vista 'entradas.confirmacion' con la variable $pedido cargada.
     */
    public function confirmacion(Pedido $pedido): View
    {
        /** @var \App\Models\Usuario $usuario */
        $usuario = Auth::user();

        // Los administradores no tienen sección de confirmación de entradas.
        if ($usuario && $usuario->isAdmin()) {
            abort(403, 'Los administradores no tienen entradas.');
        }

        // Autorización: verificamos que el pedido pertenece al usuario autenticado.
        // Sin esta comprobación, cualquier usuario podría ver pedidos de otros
        // simplemente adivinando o probando IDs en la URL (IDOR vulnerability).
        if ($pedido->usuario_id !== Auth::id()) {
            abort(403);
        }

        // Cargamos de forma diferida las entradas y su evento asociado.
        // Usamos load() en lugar de with() porque el pedido ya está en memoria;
        // with() se usa en la query, load() se usa sobre el modelo ya recuperado.
        $pedido->load(['entradas.evento']);

        return view('entradas.confirmacion', compact('pedido'));
    }
}
