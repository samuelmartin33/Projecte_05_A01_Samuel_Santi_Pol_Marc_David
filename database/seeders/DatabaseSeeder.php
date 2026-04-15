<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Poblar la base de datos con los datos iniciales de VIBEZ.
     */
    public function run(): void
    {
        // Catálogos (sin dependencias)
        $this->call(CategoriasEventoSeeder::class);
        $this->call(InteresesSeeder::class);
        $this->call(CategoriasTrabajoSeeder::class);

        // Usuarios
        $this->call(UsuariosSeeder::class);
        $this->call(UsuarioInteresSeeder::class);

        // Empresas, organizadores y trabajadores
        $this->call(EmpresasSeeder::class);
        $this->call(OrganizadoresSeeder::class);
        $this->call(TrabajadoresSeeder::class);
        $this->call(TrabajadorCategoriaSeeder::class);

        // Eventos y contenido relacionado
        $this->call(EventosSeeder::class);
        $this->call(EventosImagenesSeeder::class);
        $this->call(EventosFavoritosSeeder::class);
        $this->call(ValoracionesEventosSeeder::class);

        // Entradas y pagos
        $this->call(PedidosSeeder::class);
        $this->call(EntradasSeeder::class);
        $this->call(PagosSeeder::class);

        // Cupones y descuentos
        $this->call(CuponesSeeder::class);
        $this->call(CuponesEventoSeeder::class);
        $this->call(CuponesUsoSeeder::class);

        // Bolsa de trabajo
        $this->call(BolsaOfertasTrabajoSeeder::class);
        $this->call(CandidaturasTrabajoSeeder::class);
        $this->call(ValoracionesTrabajoSeeder::class);

        // Social
        $this->call(SeguimientosSeeder::class);

        // Mensajería
        $this->call(ChatsSeeder::class);
        $this->call(MensajesSeeder::class);

        // Notificaciones
        $this->call(NotificacionesSeeder::class);
    }
}

