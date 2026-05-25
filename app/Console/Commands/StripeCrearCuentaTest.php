<?php

namespace App\Console\Commands;

use App\Models\Empresa;
use App\Services\StripeService;
use Illuminate\Console\Command;

class StripeCrearCuentaTest extends Command
{
    protected $signature   = 'stripe:cuenta-test {empresa_id : ID de la empresa en la BD}';
    protected $description = 'Crea una cuenta Express de prueba en Stripe y muestra el enlace de onboarding';

    public function handle(StripeService $stripe): int
    {
        $empresa = Empresa::with('usuario')->find($this->argument('empresa_id'));

        if (! $empresa) {
            $this->error('No se encontró la empresa con ese ID.');
            return 1;
        }

        $this->info("Empresa: {$empresa->nombre_empresa} (usuario: {$empresa->usuario->email})");

        if ($empresa->stripe_account_id) {
            $this->warn("Ya tiene cuenta Stripe: {$empresa->stripe_account_id}");
            $this->line('Generando nuevo enlace de onboarding...');
        } else {
            $this->line('Creando cuenta Express en Stripe...');
            $stripe->crearCuentaExpress($empresa);
            $this->info("Cuenta creada: {$empresa->fresh()->stripe_account_id}");
        }

        $url = $stripe->generarEnlaceOnboarding($empresa->stripe_account_id);

        $this->newLine();
        $this->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->info('Enlace de onboarding (válido ~5 min):');
        $this->line($url);
        $this->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->newLine();
        $this->comment('Abre ese enlace en el navegador y completa el onboarding de Stripe.');
        $this->comment('En modo test puedes usar datos ficticios y la tarjeta 4000000400000008 (ES).');

        return 0;
    }
}
