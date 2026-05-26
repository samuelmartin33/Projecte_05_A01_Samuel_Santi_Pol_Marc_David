<?php

namespace App\Services;

use App\Models\Empresa;
use Stripe\Account;
use Stripe\AccountLink;
use Stripe\Refund;
use Stripe\Stripe;

class StripeService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Crea una cuenta Express en Stripe para la empresa y guarda el ID.
     */
    public function crearCuentaExpress(Empresa $empresa): string
    {
        $cuenta = Account::create([
            'type'    => 'express',
            'country' => 'ES',
            'email'   => $empresa->usuario->email,
            'capabilities' => [
                'card_payments' => ['requested' => true],
                'transfers'     => ['requested' => true],
            ],
            'business_profile' => [
                'name' => $empresa->nombre_empresa,
            ],
        ]);

        $empresa->update([
            'stripe_account_id'        => $cuenta->id,
            'stripe_onboarding_status' => 'pending',
            'fecha_actualizacion'      => now(),
        ]);

        return $cuenta->id;
    }

    /**
     * Genera un enlace de onboarding de Stripe para la cuenta Express.
     */
    public function generarEnlaceOnboarding(string $accountId): string
    {
        $link = AccountLink::create([
            'account'     => $accountId,
            'refresh_url' => route('empresa.stripe.refrescar'),
            'return_url'  => route('empresa.stripe.retorno'),
            'type'        => 'account_onboarding',
        ]);

        return $link->url;
    }

    /**
     * Procesa un reembolso de un pago con Stripe Connect (destination charge).
     *
     * reverse_transfer=true       → Revierte el 90% transferido a la cuenta Express de la empresa.
     * refund_application_fee=true → Devuelve el 10% de comisión retenido por VIBEZ.
     * Resultado: el usuario recupera el 100% del importe pagado.
     *
     * @param  string   $paymentIntentId  ID del PaymentIntent de Stripe.
     * @param  int|null $amountCents      Importe en céntimos (null = reembolso total).
     */
    public function procesarReembolso(string $paymentIntentId, ?int $amountCents = null): Refund
    {
        $params = [
            'payment_intent'         => $paymentIntentId,
            'reverse_transfer'       => true,
            'refund_application_fee' => true,
        ];

        if ($amountCents !== null) {
            $params['amount'] = $amountCents;
        }

        return Refund::create($params);
    }

    /**
     * Consulta el estado actual de la cuenta en Stripe y lo sincroniza en la BD.
     */
    public function sincronizarEstado(Empresa $empresa): void
    {
        if (! $empresa->stripe_account_id) {
            return;
        }

        $cuenta = Account::retrieve($empresa->stripe_account_id);

        $empresa->update([
            'stripe_charges_enabled'   => $cuenta->charges_enabled,
            'stripe_payouts_enabled'   => $cuenta->payouts_enabled,
            'stripe_details_submitted' => $cuenta->details_submitted,
            'stripe_onboarding_status' => $cuenta->details_submitted ? 'complete' : 'pending',
            'fecha_actualizacion'      => now(),
        ]);
    }
}
