<?php

namespace App\Http\Controllers;

use App\Mail\BonoComprado;
use App\Models\BonoCompra;
use App\Models\BonoTipo;
use App\Models\Entrada;
use App\Models\Evento;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

/**
 * BonoController — Gestiona la compra de bonos de bebidas para eventos Fiesta.
 *
 * Un bono (2, 5 o 10 bebidas) es válido en CUALQUIER evento Fiesta de la misma
 * empresa, no solo en el evento donde se compró. El saldo se descuenta cuando
 * el camarero escanea el QR.
 *
 * Flujo de pago:
 *  1. crearPaymentIntent() → Stripe PI (split: 10% VIBEZ, 90% empresa)
 *  2. El frontend confirma con stripe.confirmCardPayment()
 *  3. confirmarPago()  → crea BonoCompra con QR único + envía email
 */
class BonoController extends Controller
{
    /**
     * Vista del catálogo de bonos disponibles para el evento.
     * GET /eventos/{eventoId}/bonos
     */
    public function vista(int $eventoId)
    {
        $evento = Evento::with(['portada', 'organizador.empresa'])->findOrFail($eventoId);

        // Solo usuarios con entrada válida (1) o usada (2) pueden acceder
        $tieneEntrada = Entrada::whereHas('pedido', fn($q) => $q->where('usuario_id', Auth::id()))
            ->where('evento_id', $eventoId)
            ->whereIn('estado_entrada', [1, 2])
            ->exists();

        if (!$tieneEntrada) {
            abort(403, 'Necesitas una entrada para acceder a los bonos de este evento.');
        }

        // Tipos de bono activos
        $tiposBono = BonoTipo::where('activo', true)->orderBy('cantidad_bebidas')->get();

        // Bonos activos del usuario en eventos de esta empresa (pueden usarse en cualquier evento Fiesta de la empresa)
        $empresaId = $evento->organizador?->empresa_id;
        $bonosActivos = BonoCompra::where('usuario_id', Auth::id())
            ->whereHas('evento.organizador', fn($q) => $q->where('empresa_id', $empresaId))
            ->where('bebidas_restantes', '>', 0)
            ->with(['tipo', 'evento'])
            ->get();

        return view('eventos.fiesta-bonos', compact('evento', 'tiposBono', 'bonosActivos'));
    }

    /**
     * AJAX: Crea un Stripe PaymentIntent para comprar un bono.
     * POST /api/bonos/crear-payment-intent
     */
    public function crearPaymentIntent(Request $request): JsonResponse
    {
        $request->validate([
            'evento_id'    => ['required', 'integer', 'exists:eventos,id'],
            'bono_tipo_id' => ['required', 'integer', 'exists:bonos_tipos,id'],
        ]);

        $eventoId   = (int) $request->evento_id;
        $bonoTipoId = (int) $request->bono_tipo_id;
        $usuarioId  = Auth::id();

        // Verificar entrada
        $tieneEntrada = Entrada::whereHas('pedido', fn($q) => $q->where('usuario_id', $usuarioId))
            ->where('evento_id', $eventoId)
            ->whereIn('estado_entrada', [1, 2])
            ->exists();

        if (!$tieneEntrada) {
            return response()->json(['success' => false, 'message' => 'Necesitas una entrada para este evento.'], 403);
        }

        $tipo   = BonoTipo::where('activo', true)->findOrFail($bonoTipoId);
        $evento = Evento::with('organizador.empresa')->findOrFail($eventoId);

        $empresa = $evento->organizador?->empresa;

        if (!$empresa || !$empresa->stripe_account_id || !$empresa->stripe_charges_enabled) {
            return response()->json(['success' => false, 'message' => 'Este evento no tiene pagos online configurados.'], 422);
        }

        $amountCents = (int) round($tipo->precio * 100);
        $feeCents    = (int) round($amountCents * 0.10);

        try {
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

            $pi = \Stripe\PaymentIntent::create([
                'amount'                 => $amountCents,
                'currency'               => 'eur',
                'application_fee_amount' => $feeCents,
                'transfer_data'          => ['destination' => $empresa->stripe_account_id],
                'metadata'               => [
                    'tipo'         => 'bono_bebidas',
                    'evento_id'    => $eventoId,
                    'bono_tipo_id' => $bonoTipoId,
                    'usuario_id'   => $usuarioId,
                ],
            ]);

            return response()->json([
                'success'           => true,
                'client_secret'     => $pi->client_secret,
                'payment_intent_id' => $pi->id,
            ]);
        } catch (\Throwable $e) {
            Log::error('Stripe PI bono error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error al iniciar el pago.'], 500);
        }
    }

    /**
     * AJAX: Verifica el pago y crea el BonoCompra con su QR único.
     * POST /api/bonos/confirmar
     */
    public function confirmarPago(Request $request): JsonResponse
    {
        $request->validate([
            'payment_intent_id' => ['required', 'string', 'max:255'],
            'evento_id'         => ['required', 'integer', 'exists:eventos,id'],
            'bono_tipo_id'      => ['required', 'integer', 'exists:bonos_tipos,id'],
        ]);

        $piId       = $request->payment_intent_id;
        $eventoId   = (int) $request->evento_id;
        $bonoTipoId = (int) $request->bono_tipo_id;
        $usuarioId  = Auth::id();

        // Idempotencia: si ya existe un bono para este PI, devolver éxito
        $existente = BonoCompra::where('codigo_qr', 'pi_' . $piId)->first();
        if ($existente) {
            return response()->json(['success' => true, 'mensaje' => '¡Bono ya registrado!']);
        }

        // Verificar el PaymentIntent en Stripe
        try {
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
            $pi = \Stripe\PaymentIntent::retrieve($piId);

            if ($pi->status !== 'succeeded') {
                return response()->json(['success' => false, 'message' => 'El pago no se ha completado todavía.'], 422);
            }
        } catch (\Throwable $e) {
            Log::error('Stripe PI bono retrieve: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'No se pudo verificar el pago.'], 500);
        }

        $tipo = BonoTipo::findOrFail($bonoTipoId);

        try {
            $bono = DB::transaction(function () use ($tipo, $eventoId, $usuarioId, $piId) {
                return BonoCompra::create([
                    'usuario_id'       => $usuarioId,
                    'bono_tipo_id'     => $tipo->id,
                    'evento_id'        => $eventoId,
                    // UUID único como código QR
                    'codigo_qr'        => Str::uuid()->toString(),
                    'bebidas_restantes' => $tipo->cantidad_bebidas,
                ]);
            });

            // Email con QR del bono
            $bono->load(['tipo', 'evento']);
            try {
                Mail::to(Auth::user()->email)->send(new BonoComprado($bono, Auth::user()));
            } catch (\Throwable $e) {
                Log::error('Email bono comprado: ' . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'mensaje' => '¡Bono de ' . $tipo->cantidad_bebidas . ' bebidas activado! Revisa tu email.',
            ]);
        } catch (\Throwable $e) {
            Log::error('Error crear bono: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error al registrar el bono.'], 500);
        }
    }
}
