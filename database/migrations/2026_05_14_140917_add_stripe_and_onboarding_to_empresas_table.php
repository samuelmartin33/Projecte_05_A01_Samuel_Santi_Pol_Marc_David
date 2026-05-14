<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('empresas', function (Blueprint $table) {
            // Teléfono específico de la empresa (distinto del personal del representante)
            $table->string('telefono_empresa', 20)->nullable()->after('telefono_contacto');

            // Stripe Connect: identificador de la cuenta conectada de la empresa
            $table->string('stripe_account_id', 100)->nullable()->after('perfil_fiscal_completo');

            // Estado del proceso de verificación de Stripe (pending / incomplete / complete)
            $table->string('stripe_onboarding_status', 30)->nullable()->default('pending')->after('stripe_account_id');

            // Stripe indica si la cuenta puede recibir cobros y transferencias
            $table->boolean('stripe_charges_enabled')->default(false)->after('stripe_onboarding_status');
            $table->boolean('stripe_payouts_enabled')->default(false)->after('stripe_charges_enabled');

            // true cuando la empresa ha enviado todos los datos a Stripe
            $table->boolean('stripe_details_submitted')->default(false)->after('stripe_payouts_enabled');
        });
    }

    public function down(): void
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->dropColumn([
                'telefono_empresa',
                'stripe_account_id',
                'stripe_onboarding_status',
                'stripe_charges_enabled',
                'stripe_payouts_enabled',
                'stripe_details_submitted',
            ]);
        });
    }
};
