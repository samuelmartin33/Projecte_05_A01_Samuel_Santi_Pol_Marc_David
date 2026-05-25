<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
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
            'account.updated' => $this->cuentaActualizada($event->data->object),
            default           => null,
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
}
