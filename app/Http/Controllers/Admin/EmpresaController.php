<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

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

        return redirect()->route('admin.empresas.index')
            ->with('error', "Solicitud de {$usuario->nombre} {$usuario->apellido1} rechazada.");
    }
}
