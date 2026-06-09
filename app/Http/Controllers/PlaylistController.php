<?php

namespace App\Http\Controllers;

use App\Mail\CancionComprada;
use App\Models\Cancion;
use App\Models\Entrada;
use App\Models\Evento;
use App\Models\PlaylistEventoCancion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * PlaylistController — Gestiona la compra de canciones para la playlist de un evento Fiesta.
 *
 * Solo accesible para usuarios autenticados que tengan al menos una entrada
 * (válida o usada) para el evento en cuestión.
 *
 * Flujo de compra de pago:
 *  1. crearPaymentIntent() → devuelve client_secret de Stripe
 *  2. El frontend confirma el pago con stripe.confirmCardPayment()
 *  3. confirmarPago() → verifica el PI, registra en playlist y envía email
 *
 * Flujo canción gratuita:
 *  1. agregarGratis() → registra directamente y envía email
 */
class PlaylistController extends Controller
{
    /**
     * Vista del catálogo de canciones disponibles para el evento.
     * GET /eventos/{eventoId}/fiesta-playlist
     */
    public function vista(int $eventoId)
    {
        $evento = Evento::with('portada')->findOrFail($eventoId);

        // Solo usuarios con entrada válida (1) o usada (2) pueden acceder
        $tieneEntrada = Entrada::whereHas('pedido', fn($q) => $q->where('usuario_id', Auth::id()))
            ->where('evento_id', $eventoId)
            ->whereIn('estado_entrada', [1, 2])
            ->exists();

        if (!$tieneEntrada) {
            abort(403, 'Necesitas una entrada para acceder a la playlist de este evento.');
        }

        // Canciones activas del catálogo ordenadas por título
        $canciones = Cancion::where('activa', true)->orderBy('titulo')->get();

        // IDs de canciones ya añadidas por este usuario a esta playlist (restricción unique)
        $cancionesYaEnPlaylist = PlaylistEventoCancion::where('evento_id', $eventoId)
            ->where('usuario_id', Auth::id())
            ->pluck('cancion_id')
            ->toArray();

        return view('eventos.fiesta-playlist', compact(
            'evento',
            'canciones',
            'cancionesYaEnPlaylist'
        ));
    }

    /**
     * AJAX: Crea un Stripe PaymentIntent para comprar una canción de pago.
     * POST /api/playlist/crear-payment-intent
     */
    public function crearPaymentIntent(Request $request): JsonResponse
    {
        $request->validate([
            'evento_id'  => ['required', 'integer', 'exists:eventos,id'],
            'cancion_id' => ['required', 'integer', 'exists:canciones,id'],
        ]);

        $eventoId  = (int) $request->evento_id;
        $cancionId = (int) $request->cancion_id;
        $usuarioId = Auth::id();

        // Doble verificación de entrada en el endpoint AJAX
        $tieneEntrada = Entrada::whereHas('pedido', fn($q) => $q->where('usuario_id', $usuarioId))
            ->where('evento_id', $eventoId)
            ->whereIn('estado_entrada', [1, 2])
            ->exists();

        if (!$tieneEntrada) {
            return response()->json(['success' => false, 'message' => 'Necesitas una entrada para este evento.'], 403);
        }

        // Un usuario solo puede añadir la misma canción una vez al mismo evento
        $yaComprada = PlaylistEventoCancion::where('evento_id', $eventoId)
            ->where('usuario_id', $usuarioId)
            ->where('cancion_id', $cancionId)
            ->exists();

        if ($yaComprada) {
            return response()->json(['success' => false, 'message' => 'Ya tienes esta canción en tu playlist para este evento.'], 422);
        }

        $cancion = Cancion::where('activa', true)->findOrFail($cancionId);

        if ($cancion->precio <= 0) {
            return response()->json(['success' => false, 'message' => 'Esta canción es gratuita, usa el endpoint correspondiente.'], 422);
        }

        $amountCents = (int) round($cancion->precio * 100);

        try {
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

            // Cargo directo a VIBEZ (sin Connect, ya que el catálogo es propiedad de VIBEZ)
            $pi = \Stripe\PaymentIntent::create([
                'amount'   => $amountCents,
                'currency' => 'eur',
                'metadata' => [
                    'tipo'       => 'cancion_playlist',
                    'evento_id'  => $eventoId,
                    'cancion_id' => $cancionId,
                    'usuario_id' => $usuarioId,
                ],
            ]);

            return response()->json([
                'success'           => true,
                'client_secret'     => $pi->client_secret,
                'payment_intent_id' => $pi->id,
            ]);
        } catch (\Throwable $e) {
            Log::error('Stripe PI canción error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error al iniciar el pago. Inténtalo de nuevo.'], 500);
        }
    }

    /**
     * AJAX: Verifica el pago y registra la canción en la playlist.
     * POST /api/playlist/confirmar
     */
    public function confirmarPago(Request $request): JsonResponse
    {
        $request->validate([
            'payment_intent_id' => ['required', 'string', 'max:255'],
            'evento_id'         => ['required', 'integer', 'exists:eventos,id'],
            'cancion_id'        => ['required', 'integer', 'exists:canciones,id'],
        ]);

        $piId      = $request->payment_intent_id;
        $eventoId  = (int) $request->evento_id;
        $cancionId = (int) $request->cancion_id;
        $usuarioId = Auth::id();

        // Idempotencia: si ya se registró (doble submit), devolver éxito directamente
        if (PlaylistEventoCancion::where('evento_id', $eventoId)->where('usuario_id', $usuarioId)->where('cancion_id', $cancionId)->exists()) {
            return response()->json(['success' => true, 'mensaje' => '¡Canción ya añadida a tu playlist!']);
        }

        // Verificar que el PaymentIntent está realmente cobrado
        try {
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
            $pi = \Stripe\PaymentIntent::retrieve($piId);

            if ($pi->status !== 'succeeded') {
                return response()->json(['success' => false, 'message' => 'El pago no se ha completado todavía.'], 422);
            }
        } catch (\Throwable $e) {
            Log::error('Stripe PI retrieve canción: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'No se pudo verificar el pago.'], 500);
        }

        $cancion = Cancion::findOrFail($cancionId);

        try {
            $registro = DB::transaction(function () use ($eventoId, $usuarioId, $cancionId, $cancion) {
                // Siguiente posición en la playlist del evento (para el campo orden)
                $siguienteOrden = (PlaylistEventoCancion::where('evento_id', $eventoId)->max('orden') ?? 0) + 1;

                return PlaylistEventoCancion::create([
                    'evento_id'    => $eventoId,
                    'usuario_id'   => $usuarioId,
                    'cancion_id'   => $cancionId,
                    'orden'        => $siguienteOrden,
                    'precio_pagado' => $cancion->precio,
                ]);
            });

            // Email de confirmación (no bloquea si falla)
            try {
                Mail::to(Auth::user()->email)->send(new CancionComprada($registro, Auth::user()));
            } catch (\Throwable $e) {
                Log::error('Email cancion comprada: ' . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'mensaje' => '¡"' . $cancion->titulo . '" añadida a la playlist!',
            ]);
        } catch (\Throwable $e) {
            Log::error('Error registrar canción playlist: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error al registrar la canción.'], 500);
        }
    }

    /**
     * AJAX: Añade directamente una canción gratuita (precio = 0) a la playlist.
     * POST /api/playlist/agregar-gratis
     */
    public function agregarGratis(Request $request): JsonResponse
    {
        $request->validate([
            'evento_id'  => ['required', 'integer', 'exists:eventos,id'],
            'cancion_id' => ['required', 'integer', 'exists:canciones,id'],
        ]);

        $eventoId  = (int) $request->evento_id;
        $cancionId = (int) $request->cancion_id;
        $usuarioId = Auth::id();

        $tieneEntrada = Entrada::whereHas('pedido', fn($q) => $q->where('usuario_id', $usuarioId))
            ->where('evento_id', $eventoId)
            ->whereIn('estado_entrada', [1, 2])
            ->exists();

        if (!$tieneEntrada) {
            return response()->json(['success' => false, 'message' => 'Necesitas una entrada para este evento.'], 403);
        }

        $cancion = Cancion::where('activa', true)->findOrFail($cancionId);

        if ($cancion->precio > 0) {
            return response()->json(['success' => false, 'message' => 'Esta canción no es gratuita.'], 422);
        }

        if (PlaylistEventoCancion::where('evento_id', $eventoId)->where('usuario_id', $usuarioId)->where('cancion_id', $cancionId)->exists()) {
            return response()->json(['success' => false, 'message' => 'Ya tienes esta canción en tu playlist.'], 422);
        }

        $siguienteOrden = (PlaylistEventoCancion::where('evento_id', $eventoId)->max('orden') ?? 0) + 1;

        $registro = PlaylistEventoCancion::create([
            'evento_id'    => $eventoId,
            'usuario_id'   => $usuarioId,
            'cancion_id'   => $cancionId,
            'orden'        => $siguienteOrden,
            'precio_pagado' => 0,
        ]);

        try {
            Mail::to(Auth::user()->email)->send(new CancionComprada($registro, Auth::user()));
        } catch (\Throwable $e) {
            Log::error('Email cancion gratuita: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'mensaje' => '¡"' . $cancion->titulo . '" añadida a tu playlist!',
        ]);
    }
}
