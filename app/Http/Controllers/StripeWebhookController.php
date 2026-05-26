<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Webhook;

class StripeWebhookController extends Controller
{
    public function handle(Request $request): Response
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $payload = $request->getContent();
        $sig     = $request->header('Stripe-Signature');
        $secret  = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent($payload, $sig, $secret);
        } catch (\Throwable $e) {
            Log::warning('Stripe webhook inválido: ' . $e->getMessage());
            return response('Invalid signature', 400);
        }

        match ($event->type) {
            'account.updated'            => $this->cuentaActualizada($event->data->object),
            'checkout.session.completed' => $this->checkoutCompletado($event->data->object),
            default                      => null,
        };

        return response('OK', 200);
    }

    /**
     * Sincroniza el estado de la cuenta Express cuando Stripe notifica cambios.
     * Se dispara cuando la empresa completa o actualiza su onboarding.
     */
    private function cuentaActualizada(object $cuenta): void
    {
        $empresa = Empresa::where('stripe_account_id', $cuenta->id)->first();

        if (! $empresa) {
            return;
        }

        $empresa->update([
            'stripe_charges_enabled'   => $cuenta->charges_enabled,
            'stripe_payouts_enabled'   => $cuenta->payouts_enabled,
            'stripe_details_submitted' => $cuenta->details_submitted,
            'stripe_onboarding_status' => $cuenta->details_submitted ? 'complete' : 'pending',
            'fecha_actualizacion'      => now(),
        ]);

        Log::info("Stripe: cuenta {$cuenta->id} actualizada para empresa {$empresa->id}. charges_enabled={$cuenta->charges_enabled}");
    }

    /**
     * Activa el Premium del usuario cuando Stripe confirma el pago de 5€.
     *
     * Este método es llamado por el evento 'checkout.session.completed'.
     * Es la forma SEGURA de activar el Premium: el webhook viene firmado
     * por Stripe (verificado con STRIPE_WEBHOOK_SECRET) y no puede ser falsificado.
     *
     * La comprobación de metadata->tipo evita que otros Checkout Sessions
     * del proyecto (ej. futuros cobros de otro tipo) activen el Premium por error.
     *
     * @param object $session  Objeto Stripe CheckoutSession del evento.
     */
    private function checkoutCompletado(object $session): void
    {
        // Filtramos por tipo para no procesar checkouts que no sean de Premium.
        if (($session->metadata->tipo ?? null) !== 'premium') {
            return;
        }

        $usuarioId = $session->metadata->usuario_id ?? null;

        if (! $usuarioId) {
            Log::warning("Webhook checkout.session.completed: falta usuario_id en metadata. Session: {$session->id}");
            return;
        }

        // payment_status 'paid' significa que el dinero fue cobrado correctamente.
        if ($session->payment_status !== 'paid') {
            Log::warning("Webhook checkout.session.completed: estado inesperado '{$session->payment_status}' para session {$session->id}");
            return;
        }

        // Activamos el Premium con una consulta directa (UPDATE) para evitar
        // cargar el modelo completo. Esto es seguro y eficiente.
        $actualizado = Usuario::where('id', $usuarioId)
            ->where('es_premium', false) // Idempotencia: no actualizamos si ya es premium
            ->update([
                'es_premium'          => true,
                'fecha_actualizacion' => now(),
            ]);

        if ($actualizado) {
            Log::info("Premium activado para usuario {$usuarioId} via checkout {$session->id}");
        }
    }
}
