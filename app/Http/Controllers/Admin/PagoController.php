<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pago;
use App\Models\Pedido;
use App\Services\StripeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

/**
 * Controlador para la gestión administrativa de pagos.
 */
class PagoController extends Controller
{
    public function index(): View
    {
        $pagos = Pago::with('pedido.usuario', 'pedido.entradas.evento')
            ->orderByDesc('fecha_creacion')
            ->paginate(12);

        return view('admin.pagos.index', compact('pagos'));
    }

    public function create(): View
    {
        return view('admin.pagos.create', [
            'pago' => new Pago(),
            'pedidos' => Pedido::with('usuario')->orderByDesc('id')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $data['fecha_creacion'] = now();
        $data['fecha_actualizacion'] = null;

        Pago::create($data);

        return redirect()->route('admin.pagos.index')->with('success', 'Pago creado correctamente.');
    }

    public function edit(Pago $pago): View
    {
        return view('admin.pagos.edit', [
            'pago' => $pago,
            'pedidos' => Pedido::with('usuario')->orderByDesc('id')->get(),
        ]);
    }

    public function update(Request $request, Pago $pago): RedirectResponse
    {
        $data = $this->validatedData($request, $pago);
        $data['fecha_actualizacion'] = now();

        $pago->update($data);

        return redirect()->route('admin.pagos.index')->with('success', 'Pago actualizado correctamente.');
    }

    public function destroy(Pago $pago): RedirectResponse
    {
        $pago->delete();

        return redirect()->route('admin.pagos.index')->with('success', 'Pago eliminado correctamente.');
    }

    /**
     * Procesa el reembolso automático de un pago a través de Stripe Connect.
     *
     * Lógica del split al reembolsar:
     *  - reverse_transfer=true       → Stripe debita el 90% de la cuenta Express de la empresa.
     *  - refund_application_fee=true → Stripe devuelve el 10% de comisión de la cuenta VIBEZ.
     *  - El usuario recupera el 100% del importe original.
     *
     * Stripe se llama ANTES de la transacción BD: si Stripe falla, la BD no se modifica.
     * Si la BD falla después de un Stripe OK, el stripe_refund_id queda en los logs.
     */
    public function reembolsar(Request $request, Pago $pago): RedirectResponse
    {
        // Guardia: ya reembolsado
        if ((int) $pago->estado_pago === 3) {
            return redirect()->route('admin.pagos.index')
                ->with('error', 'Este pago ya ha sido reembolsado anteriormente.');
        }

        $pedido = $pago->pedido;

        // Guardia: evento gratuito o sin PaymentIntent de Stripe
        if (empty($pedido->stripe_payment_intent_id)) {
            return redirect()->route('admin.pagos.index')
                ->with('error', 'Este pedido no tiene pago de Stripe asociado (posiblemente un evento gratuito). No se puede reembolsar automáticamente.');
        }

        // Guardia: el evento ya ha tenido lugar
        $evento = $pedido->entradas()->with('evento')->first()?->evento;
        if ($evento && $evento->fecha_inicio->isPast()) {
            return redirect()->route('admin.pagos.index')
                ->with('error', 'No se puede reembolsar: el evento ya ha tenido lugar ('
                    . $evento->fecha_inicio->format('d/m/Y H:i') . ').');
        }

        // Guardia: entradas ya escaneadas (estado_entrada = 2)
        $entradasUsadas = $pedido->entradas()->where('estado_entrada', 2)->count();
        if ($entradasUsadas > 0) {
            return redirect()->route('admin.pagos.index')
                ->with('error', "No se puede reembolsar: {$entradasUsadas} entrada(s) del pedido ya han sido escaneadas y usadas en el evento.");
        }

        $request->validate([
            'motivo_reembolso' => ['required', 'string', 'min:5', 'max:500'],
        ]);

        try {
            $refund = (new StripeService())->procesarReembolso($pedido->stripe_payment_intent_id);

            DB::transaction(function () use ($pago, $pedido, $refund, $request) {
                $ahora = now();

                $pago->update([
                    'estado_pago'         => 3,
                    'fecha_reembolso'     => $ahora,
                    'importe_reembolso'   => $pago->importe,
                    'motivo_reembolso'    => $request->motivo_reembolso,
                    'stripe_refund_id'    => $refund->id,
                    'fecha_actualizacion' => $ahora,
                ]);

                $pedido->update(['estado' => 0, 'fecha_actualizacion' => $ahora]);

                // Solo cancela entradas válidas (1); las ya usadas (2) o canceladas (0) no se tocan
                $pedido->entradas()->where('estado_entrada', 1)
                    ->update(['estado_entrada' => 0, 'fecha_actualizacion' => $ahora]);
            });

            Log::info("Reembolso OK: pago #{$pago->id}, pedido #{$pedido->id}, refund Stripe: {$refund->id}");

            return redirect()->route('admin.pagos.index')
                ->with('success', "Reembolso procesado correctamente. ID Stripe: {$refund->id}");

        } catch (\Stripe\Exception\ApiErrorException $e) {
            Log::error("Error Stripe al reembolsar pago #{$pago->id}: {$e->getMessage()}");
            return redirect()->route('admin.pagos.index')
                ->with('error', 'Error de Stripe al procesar el reembolso: ' . $e->getMessage());

        } catch (\Throwable $e) {
            Log::error("Error inesperado al reembolsar pago #{$pago->id}: {$e->getMessage()}");
            return redirect()->route('admin.pagos.index')
                ->with('error', 'Error inesperado. Revisa los logs del servidor.');
        }
    }

    private function validatedData(Request $request, ?Pago $pago = null): array
    {
        $data = $request->validate([
            'pedido_id' => ['required', 'integer', 'exists:pedidos,id'],
            'metodo_pago' => ['required', 'integer', 'in:1,2,3,4'],
            'estado_pago' => ['required', 'integer', 'in:1,2,3'],
            'importe' => ['required', 'numeric', 'min:0'],
            'moneda' => ['required', 'string', 'size:3'],
            'fecha_pago' => ['nullable', 'date'],
            'fecha_reembolso' => ['nullable', 'date'],
            'importe_reembolso' => ['nullable', 'numeric', 'min:0'],
            'motivo_reembolso' => ['nullable', 'string', 'max:500'],
            'estado' => ['required', 'integer', 'in:0,1'],
        ]);

        $data['estado'] = (int) $request->input('estado', 1);

        return $data;
    }
}