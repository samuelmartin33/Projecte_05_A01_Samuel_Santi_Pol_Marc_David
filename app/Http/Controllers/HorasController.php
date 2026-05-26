<?php

namespace App\Http\Controllers;

use App\Models\RegistroHoras;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controlador para el registro de horas diarias de organizadores y porteros.
 */
class HorasController extends Controller
{
    /**
     * Muestra el formulario de horas de hoy y el historial del usuario autenticado.
     * GET /mis-horas
     */
    public function index()
    {
        $usuario = Auth::user();

        // Solo organizadores y porteros pueden acceder
        if (!$usuario->isOrganizador() && !$usuario->isPortero()) {
            abort(403, 'Solo el personal del equipo puede registrar horas.');
        }

        // Historial de horas del usuario, más reciente primero
        $registros = RegistroHoras::where('usuario_id', $usuario->id)
            ->orderByDesc('fecha')
            ->limit(60) // últimos 60 días mostrados
            ->get();

        // Total de horas del mes en curso
        $horasMes = RegistroHoras::where('usuario_id', $usuario->id)
            ->whereYear('fecha', now()->year)
            ->whereMonth('fecha', now()->month)
            ->sum('horas');

        return view('mis-horas.index', compact('registros', 'horasMes'));
    }

    /**
     * Guarda un nuevo registro de horas.
     * POST /mis-horas
     */
    public function store(Request $request)
    {
        $usuario = Auth::user();

        if (!$usuario->isOrganizador() && !$usuario->isPortero()) {
            abort(403);
        }

        $request->validate([
            'fecha'       => ['required', 'date', 'before_or_equal:today'],
            'horas'       => ['required', 'numeric', 'min:0.5', 'max:24'],
            'descripcion' => ['nullable', 'string', 'max:500'],
        ], [
            'fecha.required'          => 'La fecha es obligatoria.',
            'fecha.before_or_equal'   => 'No puedes registrar horas para fechas futuras.',
            'horas.required'          => 'Las horas trabajadas son obligatorias.',
            'horas.min'               => 'El mínimo es 0.5 horas.',
            'horas.max'               => 'El máximo por día es 24 horas.',
        ]);

        // Evitar registros duplicados para el mismo día
        $yaExiste = RegistroHoras::where('usuario_id', $usuario->id)
            ->where('fecha', $request->fecha)
            ->exists();

        if ($yaExiste) {
            return back()
                ->withInput()
                ->with('error', 'Ya tienes un registro para esa fecha. Edítalo si necesitas corregirlo.');
        }

        RegistroHoras::create([
            'usuario_id'   => $usuario->id,
            'fecha'        => $request->fecha,
            'horas'        => $request->horas,
            'descripcion'  => $request->descripcion,
            'fecha_creacion' => now(),
        ]);

        return back()->with('success', 'Horas registradas correctamente.');
    }

    /**
     * Muestra las horas de un miembro del equipo de la empresa.
     * Acceso directo desde la vista de equipo sin necesitar candidaturaId.
     * GET /empresa/equipo/{usuarioId}/horas
     */
    public function verHorasEquipo(int $usuarioId)
    {
        $usuarioAuth = Auth::user();

        if (!$usuarioAuth->isEmpresa()) {
            abort(403);
        }

        // Verificar que el usuario solicitado pertenece al equipo de esta empresa
        $empresa = $usuarioAuth->empresa;
        if (!$empresa) abort(403);

        $perteneceAlEquipo = \App\Models\Organizador::where('empresa_id', $empresa->id)
            ->where('usuario_id', $usuarioId)
            ->exists();

        if (!$perteneceAlEquipo) {
            abort(403, 'Este miembro no pertenece a tu equipo.');
        }

        $trabajador = Usuario::findOrFail($usuarioId);

        $registros = RegistroHoras::where('usuario_id', $trabajador->id)
            ->orderByDesc('fecha')
            ->limit(60)
            ->get();

        $horasMes = RegistroHoras::where('usuario_id', $trabajador->id)
            ->whereYear('fecha', now()->year)
            ->whereMonth('fecha', now()->month)
            ->sum('horas');

        // No aplica candidatura en este contexto
        $candidatura = null;

        return view('empresa.trabajador-horas', compact('trabajador', 'registros', 'horasMes', 'candidatura'));
    }

    /**
     * Muestra el historial de horas de un trabajador (para la empresa).
     * GET /empresa/candidaturas/{candidaturaId}/horas-trabajador
     */
    public function verHorasTrabajador(int $candidaturaId)
    {
        $usuarioAuth = Auth::user();

        // Solo empresas pueden ver el historial de sus trabajadores
        if (!$usuarioAuth->isEmpresa()) {
            abort(403);
        }

        // Cargar la candidatura con su relación de trabajo y verificar pertenencia
        $candidatura = \App\Models\CandidaturaTrabajo::with(['trabajo', 'oferta'])
            ->whereHas('oferta', function ($q) use ($usuarioAuth) {
                $empresa = $usuarioAuth->empresa;
                if (!$empresa) abort(403);
                $orgIds = $empresa->organizadores()->pluck('organizadores.id');
                $q->whereIn('organizador_id', $orgIds);
            })
            ->findOrFail($candidaturaId);

        // Resolver el usuario por email para acceder a sus horas
        $trabajador = null;
        $registros  = collect();
        $horasMes   = 0;

        if ($candidatura->email_candidato) {
            $trabajador = Usuario::where('email', $candidatura->email_candidato)->first();
        }

        if ($trabajador) {
            $registros = RegistroHoras::where('usuario_id', $trabajador->id)
                ->orderByDesc('fecha')
                ->limit(60)
                ->get();

            $horasMes = RegistroHoras::where('usuario_id', $trabajador->id)
                ->whereYear('fecha', now()->year)
                ->whereMonth('fecha', now()->month)
                ->sum('horas');
        }

        return view('empresa.trabajador-horas', compact('candidatura', 'trabajador', 'registros', 'horasMes'));
    }
}
