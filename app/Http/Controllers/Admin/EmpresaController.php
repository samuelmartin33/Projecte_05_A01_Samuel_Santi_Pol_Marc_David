<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\Usuario;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Controlador para la gestión administrativa de empresas.
 */
class EmpresaController extends Controller
{
    public function index(): View
    {
        $pendientes = Usuario::where('tipo_cuenta', 'empresa')
            ->where('estado_registro', 'pendiente')
            ->orderBy('fecha_creacion', 'asc')
            ->get();

        $gestionadas = Usuario::where('tipo_cuenta', 'empresa')
            ->whereIn('estado_registro', ['aprobado', 'rechazado'])
            ->orderBy('fecha_creacion', 'desc')
            ->get();

        return view('admin.empresas.index', compact('pendientes', 'gestionadas'));
    }

    /**
     * Muestra el detalle de una solicitud de empresa para que el admin la revise.
     * Carga el usuario y su empresa relacionada.
     */
    public function show(int $id): View
    {
        $usuario = Usuario::where('id', $id)
            ->where('tipo_cuenta', 'empresa')
            ->with('empresa')
            ->firstOrFail();

        return view('admin.empresas.show', compact('usuario'));
    }

    public function aprobar(int $id): RedirectResponse
    {
        $usuario = Usuario::where('id', $id)
            ->where('tipo_cuenta', 'empresa')
            ->where('estado_registro', 'pendiente')
            ->firstOrFail();

        $usuario->update([
            'estado_registro'     => 'aprobado',
            'email_verificado'    => 1,
            'fecha_actualizacion' => now(),
        ]);

        // Notificar al promotor que su cuenta está activa y debe completar el perfil fiscal
        \App\Models\Notificacion::crear(
            $usuario->id,
            \App\Models\Notificacion::PERFIL_FISCAL,
            '¡Cuenta aprobada! Completa tu perfil fiscal',
            'Ya puedes acceder a VIBEZ. Rellena tus datos legales y bancarios para publicar eventos.',
            route('empresa.perfil-fiscal')
        );

        return redirect()->route('admin.empresas.index')
            ->with('success', "Empresa de {$usuario->nombre} {$usuario->apellido1} aprobada correctamente.");
    }

    public function rechazar(int $id): RedirectResponse
    {
        $usuario = Usuario::where('id', $id)
            ->where('tipo_cuenta', 'empresa')
            ->where('estado_registro', 'pendiente')
            ->firstOrFail();

        $usuario->update([
            'estado_registro'     => 'rechazado',
            'fecha_actualizacion' => now(),
        ]);

        // Notificar al promotor del rechazo
        \App\Models\Notificacion::crear(
            $usuario->id,
            \App\Models\Notificacion::EMPRESA_RECHAZADA,
            'Solicitud de empresa rechazada',
            'Tu solicitud no ha sido aprobada. Contacta con soporte para más información.'
        );

        return redirect()->route('admin.empresas.index')
            ->with('error', "Solicitud de {$usuario->nombre} {$usuario->apellido1} rechazada.");
    }
}
