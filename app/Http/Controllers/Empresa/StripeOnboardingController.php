<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Services\StripeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class StripeOnboardingController extends Controller
{
    public function __construct(private StripeService $stripe) {}

    /**
     * Crea la cuenta Express si no existe y redirige al onboarding de Stripe.
     */
    public function iniciar(): RedirectResponse
    {
        $empresa = Auth::user()->empresa;

        if (! $empresa) {
            return redirect()->route('empresa.perfil-fiscal')
                ->with('error', 'No se encontró el perfil de empresa.');
        }

        try {
            if (! $empresa->stripe_account_id) {
                $this->stripe->crearCuentaExpress($empresa);
            }

            $url = $this->stripe->generarEnlaceOnboarding($empresa->stripe_account_id);
        } catch (\Throwable $e) {
            return redirect()->route('empresa.perfil-fiscal')
                ->with('error', 'Error al conectar con Stripe: ' . $e->getMessage());
        }

        return redirect($url);
    }

    /**
     * Stripe redirige aquí después de completar el onboarding.
     * Sincronizamos el estado actualizado de la cuenta.
     */
    public function retorno(): RedirectResponse
    {
        $empresa = Auth::user()->empresa;

        if ($empresa) {
            try {
                $this->stripe->sincronizarEstado($empresa);
            } catch (\Throwable $e) {
                // No bloqueamos el retorno si falla la sincronización
            }
        }

        $mensaje = ($empresa && $empresa->fresh()->stripe_charges_enabled)
            ? '¡Cuenta bancaria conectada! Ya puedes recibir pagos de tus eventos.'
            : 'Onboarding iniciado. Completa los datos en Stripe para activar los cobros.';

        return redirect()->route('empresa.perfil-fiscal')->with('success', $mensaje);
    }

    /**
     * El enlace de onboarding expiró; generamos uno nuevo.
     */
    public function refrescar(): RedirectResponse
    {
        $empresa = Auth::user()->empresa;

        if (! $empresa || ! $empresa->stripe_account_id) {
            return redirect()->route('empresa.perfil-fiscal');
        }

        try {
            $url = $this->stripe->generarEnlaceOnboarding($empresa->stripe_account_id);
        } catch (\Throwable $e) {
            return redirect()->route('empresa.perfil-fiscal')
                ->with('error', 'No se pudo regenerar el enlace de Stripe.');
        }

        return redirect($url);
    }
}
