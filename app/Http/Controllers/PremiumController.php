<?php

namespace App\Http\Controllers;

use App\Models\PagoPremium;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Stripe;

/**
 * PremiumController — Gestiona la suscripción Premium de un usuario (pago único de 5€).
 *
 * Flujo completo:
 *  1. Usuario visita /premium → ve la página de oferta (mostrar).
 *  2. Hace clic en "Hacerme Premium" → formulario POST a /premium/checkout (iniciarCheckout).
 *  3. Creamos una Stripe Checkout Session y redirigimos al usuario a la URL de Stripe.
 *  4. Usuario paga en la página de Stripe (nosotros no vemos la tarjeta en ningún momento).
 *  5a. Si paga → Stripe redirige a /premium/exito (exito). La página solo muestra una
 *      confirmación visual. La activación real del Premium la hace el webhook (ver paso 5b).
 *  5b. Stripe envía un webhook 'checkout.session.completed' a /stripe/webhook.
 *      StripeWebhookController verifica la firma y activa es_premium en la BD.
 *      Este es el mecanismo seguro: no depende de la URL de redirect.
 *  6. Si cancela → Stripe redirige a /premium/cancelado (cancelado).
 *
 * Por qué Stripe Checkout y no PaymentIntent + formulario propio:
 *  - Con Checkout, Stripe presenta su propia página de pago (mucho más simple de implementar).
 *  - Nosotros no necesitamos montar un elemento de tarjeta ni gestionar errores de 3DS/SCA.
 *  - Para un cobro único de importe fijo, Checkout es la opción recomendada por Stripe.
 *  - Además, nuestro proyecto ya usa PaymentIntents para entradas de eventos; mantener
 *    Checkout para el Premium evita confundir los dos flujos.
 */
class PremiumController extends Controller
{
    /**
     * Muestra la página de oferta Premium.
     *
     * Si el usuario ya tiene Premium activo, la vista puede mostrar un mensaje diferente
     * en lugar del botón de pago (ver el @if($usuario->es_premium) en la vista).
     *
     * @return View
     */
    public function mostrar(): View
    {
        /** @var \App\Models\Usuario $usuario */
        $usuario = Auth::user();

        return view('premium.checkout', compact('usuario'));
    }

    /**
     * Crea una Stripe Checkout Session de 5,00€ y redirige al usuario a Stripe.
     *
     * Conceptos clave:
     *  - unit_amount: importe en CÉNTIMOS (500 = 5,00€). Stripe siempre trabaja
     *    en la unidad mínima de la moneda para evitar errores de coma flotante.
     *  - mode 'payment': cobro puntual, no suscripción recurrente.
     *  - success_url con {CHECKOUT_SESSION_ID}: Stripe sustituye ese literal por el
     *    ID real de la sesión cuando redirige. Nos sirve para logging o para mostrar
     *    un resumen, aunque la activación real no dependa de este parámetro.
     *  - metadata: datos libres que Stripe adjunta a la sesión. El webhook los lee
     *    para saber a qué usuario activar el Premium.
     *
     * @return RedirectResponse
     */
    public function iniciarCheckout(): RedirectResponse
    {
        /** @var \App\Models\Usuario $usuario */
        $usuario = Auth::user();

        // Si ya es premium, no tiene sentido volver a cobrarle.
        if ($usuario->es_premium) {
            return redirect()->route('premium')->with('info', 'Ya tienes una cuenta Premium activa.');
        }

        try {
            // Usamos la misma forma de inicializar Stripe que el resto del proyecto.
            Stripe::setApiKey(config('services.stripe.secret'));

            $session = StripeSession::create([
                'payment_method_types' => ['card'],

                // line_items: lista de productos a cobrar.
                // Para un precio fijo usamos price_data en lugar de un Price de Stripe,
                // así no necesitamos crear productos en el Dashboard de Stripe.
                'line_items' => [[
                    'price_data' => [
                        'currency'     => 'eur',
                        'unit_amount'  => 500, // 5,00 € en céntimos
                        'product_data' => [
                            'name'        => 'VIBEZ Premium',
                            'description' => 'Acceso a cupones exclusivos de las promotoras en VIBEZ',
                        ],
                    ],
                    'quantity' => 1,
                ]],

                // mode 'payment' = cobro único (frente a 'subscription' = suscripción recurrente).
                'mode' => 'payment',

                // Prellenamos el email para que el formulario de Stripe sea más rápido.
                'customer_email' => $usuario->email,

                // Stripe redirige aquí cuando el pago se completa.
                // {CHECKOUT_SESSION_ID} es un literal que Stripe sustituye por el ID real.
                'success_url' => route('premium.exito') . '?session_id={CHECKOUT_SESSION_ID}',

                // Stripe redirige aquí si el usuario cierra o cancela el pago.
                'cancel_url' => route('premium.cancelado'),

                // metadata: el webhook lee usuario_id para saber a quién activar.
                // tipo: distingue este checkout de otros usos de Checkout en el proyecto.
                'metadata' => [
                    'usuario_id' => $usuario->id,
                    'tipo'       => 'premium',
                ],
            ]);

            // session->url es la URL de la página de pago de Stripe.
            return redirect($session->url);

        } catch (\Throwable $e) {
            Log::error('Stripe Premium checkout error: ' . $e->getMessage());
            return redirect()->route('premium')
                ->with('error', 'Error al conectar con la pasarela de pago. Inténtalo de nuevo.');
        }
    }

    /**
     * Página de éxito tras el pago.
     *
     * Verifica directamente con la API de Stripe que el pago está completado usando
     * el session_id que Stripe añade a la URL de retorno. Esto es seguro porque
     * consultamos el estado desde los servidores de Stripe (no confiamos en parámetros
     * manipulables por el usuario) y sirve de respaldo cuando el webhook no llega
     * (p. ej. en entorno local donde Stripe no puede alcanzar localhost).
     *
     * El webhook de StripeWebhookController sigue siendo el mecanismo principal;
     * este método actúa como red de seguridad.
     *
     * @return View
     */
    public function exito(Request $request): View
    {
        /** @var \App\Models\Usuario $usuario */
        $usuario   = Auth::user();
        $sessionId = $request->get('session_id');

        // Si el usuario aún no es premium e Stripe nos envió un session_id, verificamos.
        if ($sessionId && $usuario && ! $usuario->es_premium) {
            try {
                Stripe::setApiKey(config('services.stripe.secret'));
                $session = StripeSession::retrieve($sessionId);

                // Comprobamos que la sesión pertenece a este usuario, que es del tipo
                // correcto y que el pago fue completado.
                $mismoUsuario = ((int) ($session->metadata->usuario_id ?? 0)) === $usuario->id;
                $esPremium    = ($session->metadata->tipo ?? '') === 'premium';
                $pagado       = $session->payment_status === 'paid';

                if ($mismoUsuario && $esPremium && $pagado) {
                    $ahora = now();

                    $usuario->update([
                        'es_premium'          => true,
                        'fecha_actualizacion' => $ahora,
                    ]);

                    // Registramos el pago como fallback si el webhook no llegó (ej. entorno local).
                    // insertOrIgnore garantiza idempotencia por el UNIQUE de stripe_session_id.
                    $importe = ($session->amount_total ?? 500) / 100;
                    DB::table('pagos_premium')->insertOrIgnore([
                        'usuario_id'               => $usuario->id,
                        'stripe_session_id'        => $sessionId,
                        'stripe_payment_intent_id' => $session->payment_intent ?? null,
                        'importe'                  => $importe,
                        'moneda'                   => strtoupper($session->currency ?? 'eur'),
                        'estado'                   => 1,
                        'fecha_pago'               => $ahora,
                        'fecha_creacion'           => $ahora,
                        'fecha_actualizacion'      => null,
                    ]);

                    Log::info("Premium activado via success_url para usuario {$usuario->id}, session {$sessionId}");
                }
            } catch (\Throwable $e) {
                // No bloqueamos la página si Stripe falla; el webhook lo reintentará.
                Log::error("Error verificando sesión Stripe en exito(): " . $e->getMessage());
            }
        }

        return view('premium.exito');
    }

    /**
     * Redirige al usuario a la página de oferta si cancela el checkout de Stripe.
     *
     * @return RedirectResponse
     */
    public function cancelado(): RedirectResponse
    {
        return redirect()->route('premium')
            ->with('info', 'Has cancelado el proceso de pago. Puedes intentarlo cuando quieras.');
    }
}
