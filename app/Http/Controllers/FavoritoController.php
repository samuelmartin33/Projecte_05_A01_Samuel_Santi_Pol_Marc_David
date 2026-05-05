<?php

namespace App\Http\Controllers;

use App\Models\EventoFavorito;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * FavoritoController — Controlador de eventos favoritos de VIBEZ.
 *
 * Gestiona el marcado y desmarcado de eventos como favoritos por parte
 * del usuario autenticado.
 *
 * Patrón "soft toggle" (toggle suave):
 *  En lugar de insertar y borrar registros físicamente de la tabla
 *  'eventos_favoritos', este controlador NUNCA elimina filas. En su lugar,
 *  cambia el campo 'estado' entre 0 (desmarcado) y 1 (marcado).
 *  Esto permite mantener el historial de favoritos (cuándo se marcó por
 *  primera vez, cuántas veces se ha marcado y desmarcado, etc.) y facilita
 *  auditorías o estadísticas futuras.
 *
 * Los tres estados posibles para la combinación (usuario_id, evento_id):
 *  1. No existe ningún registro → el usuario nunca ha marcado ese evento.
 *  2. Existe registro con estado = 0 → fue favorito pero lo desmarcó.
 *  3. Existe registro con estado = 1 → actualmente es favorito.
 *
 * Por qué devuelve JSON:
 *  El toggle se invoca desde favoritos.js mediante una petición AJAX (fetch).
 *  No hay recarga de página: el JS recibe la respuesta JSON y actualiza
 *  el icono del corazón en tiempo real sin refrescar el navegador.
 */
class FavoritoController extends Controller
{
    /**
     * Activa o desactiva un evento como favorito para el usuario autenticado.
     *
     * Lógica del toggle suave (tres ramas):
     *  - Si existe registro con estado = 1 → pasa a estado = 0 (desmarca).
     *  - Si existe registro con estado = 0 → pasa a estado = 1 (vuelve a marcar).
     *  - Si no existe registro             → se crea con estado = 1 (primera vez).
     *
     * La respuesta JSON incluye el campo 'favorito' (true/false) para que el
     * JavaScript sepa si debe mostrar el corazón relleno o vacío.
     *
     * @param  Request      $request  Petición AJAX con el campo 'evento_id'.
     * @return JsonResponse           JSON con 'success', 'favorito' (bool) y 'message'.
     */
    public function toggle(Request $request): JsonResponse
    {
        // Validamos que evento_id es obligatorio, entero y que existe en la BD.
        // Si la validación falla, Laravel devuelve automáticamente un JSON 422.
        $validated = $request->validate([
            'evento_id' => ['required', 'integer', 'exists:eventos,id'],
        ]);

        // $request->user() es equivalente a Auth::user() cuando la petición
        // llega autenticada. Devuelve el objeto del usuario de la sesión activa.
        $usuario = $request->user();
        $ahora = now();

        // Buscamos si ya existe algún registro para esta combinación usuario/evento,
        // independientemente de si está marcado (1) o desmarcado (0).
        $favorito = EventoFavorito::where('usuario_id', $usuario->id)
            ->where('evento_id', $validated['evento_id'])
            ->first();

        // RAMA 1: Existe y está activo (estado = 1) → lo desmarcamos (estado = 0).
        // (int) hace un cast explícito para evitar comparaciones inesperadas si
        // la BD devuelve el valor como string en algunos drivers.
        if ($favorito && (int) $favorito->estado === 1) {
            $favorito->update([
                'estado' => 0,
                'fecha_actualizacion' => $ahora,
            ]);

            // Informamos al JS de que el evento ya NO es favorito.
            return response()->json([
                'success' => true,
                'favorito' => false,
                'message' => 'Evento eliminado de favoritos.',
            ]);
        }

        // RAMA 2: Existe pero está inactivo (estado = 0) → lo volvemos a marcar.
        // RAMA 3: No existe → creamos el registro por primera vez con estado = 1.
        if ($favorito) {
            // El registro ya existe, solo actualizamos el estado a activo.
            $favorito->update([
                'estado' => 1,
                'fecha_actualizacion' => $ahora,
            ]);
        } else {
            // Primera vez que el usuario marca este evento: creamos la fila.
            // Guardamos también fecha_creacion porque es el primer registro.
            EventoFavorito::create([
                'usuario_id' => $usuario->id,
                'evento_id' => $validated['evento_id'],
                'estado' => 1,
                'fecha_creacion' => $ahora,
                'fecha_actualizacion' => $ahora,
            ]);
        }

        // Informamos al JS de que el evento AHORA es favorito.
        return response()->json([
            'success' => true,
            'favorito' => true,
            'message' => 'Evento guardado en favoritos.',
        ]);
    }
}
