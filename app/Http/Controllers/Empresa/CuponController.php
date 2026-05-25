<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\Cupon;
use App\Models\CuponEvento;
use App\Models\Evento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Empresa\CuponController — CRUD de cupones del panel de empresa.
 * Cada empresa solo puede ver y gestionar SUS propios cupones.
 */
class CuponController extends Controller
{
    /**
     * Devuelve la empresa del usuario logueado.
     * Si no tiene empresa redirige al home con un error.
     */
    private function getEmpresa()
    {
        return Auth::user()->empresa;
    }

    /** Lista los cupones de la empresa logueada. */
    public function index()
    {
        $empresa = $this->getEmpresa();

        // Solo los cupones de ESTA empresa, ordenados por fecha de creación
        $cupones = Cupon::with('eventos')
            ->where('empresa_id', $empresa->id)
            ->orderBy('fecha_creacion', 'desc')
            ->paginate(15);

        return view('empresa.cupones.index', compact('cupones'));
    }

    /** Formulario para crear un nuevo cupón. */
    public function create()
    {
        $empresa = $this->getEmpresa();

        // Solo los eventos de ESTA empresa (a través de sus organizadores)
        $eventos = Evento::whereHas('organizador', function ($q) use ($empresa) {
            $q->where('empresa_id', $empresa->id);
        })
        ->where('estado', 1)
        ->orderBy('fecha_inicio', 'asc')
        ->get();

        return view('empresa.cupones.create', compact('eventos'));
    }

    /** Guarda un nuevo cupón asociado a la empresa logueada. */
    public function store(Request $request)
    {
        $empresa = $this->getEmpresa();

        $data = $request->validate([
            'codigo'                  => ['required', 'string', 'max:50', 'unique:cupones,codigo'],
            'descripcion'             => ['nullable', 'string', 'max:255'],
            'valor_descuento'         => ['required', 'numeric', 'min:0.01', 'max:100'],
            'fecha_inicio'            => ['required', 'date'],
            'fecha_fin'               => ['required', 'date', 'after:fecha_inicio'],
            'limite_usos_total'       => ['nullable', 'integer', 'min:1'],
            'limite_usos_por_usuario' => ['nullable', 'integer', 'min:1'],
            'estado'                  => ['required', 'in:0,1'],
            'eventos'                 => ['nullable', 'array'],
            'eventos.*'               => ['integer', 'exists:eventos,id'],
        ], [
            'codigo.unique'       => 'Ya existe un cupón con ese código.',
            'fecha_fin.after'     => 'La fecha de fin debe ser posterior a la de inicio.',
            'valor_descuento.max' => 'El descuento no puede superar el 100%.',
        ]);

        $ahora = now();

        // Crear el cupón asignando automáticamente la empresa del usuario logueado
        $cupon = Cupon::create([
            'empresa_id'              => $empresa->id,
            'codigo'                  => strtoupper($data['codigo']),
            'descripcion'             => $data['descripcion'] ?? null,
            'valor_descuento'         => $data['valor_descuento'],
            'fecha_inicio'            => $data['fecha_inicio'],
            'fecha_fin'               => $data['fecha_fin'],
            'limite_usos_total'       => $data['limite_usos_total'] ?? null,
            'limite_usos_por_usuario' => $data['limite_usos_por_usuario'] ?? null,
            'usos_actuales'           => 0,
            'estado'                  => (int) $data['estado'],
            'fecha_creacion'          => $ahora,
            'fecha_actualizacion'     => null,
        ]);

        // Vincular el cupón a los eventos seleccionados
        if (!empty($data['eventos'])) {
            foreach ($data['eventos'] as $eventoId) {
                CuponEvento::create([
                    'cupon_id'            => $cupon->id,
                    'evento_id'           => $eventoId,
                    'estado'              => 1,
                    'fecha_creacion'      => $ahora,
                    'fecha_actualizacion' => null,
                ]);
            }
        }

        return redirect()
            ->route('empresa.cupones.index')
            ->with('success', "Cupón «{$cupon->codigo}» creado correctamente.");
    }

    /** Formulario de edición de un cupón. */
    public function edit($id)
    {
        $empresa = $this->getEmpresa();

        // Busca el cupón y verifica que pertenezca a ESTA empresa
        $cupon = Cupon::with('eventos')
            ->where('id', $id)
            ->where('empresa_id', $empresa->id)
            ->firstOrFail();

        // Solo los eventos de ESTA empresa
        $eventos = Evento::whereHas('organizador', function ($q) use ($empresa) {
            $q->where('empresa_id', $empresa->id);
        })
        ->where('estado', 1)
        ->orderBy('fecha_inicio', 'asc')
        ->get();

        $eventosSeleccionados = $cupon->eventos->pluck('id')->toArray();

        return view('empresa.cupones.edit', compact('cupon', 'eventos', 'eventosSeleccionados'));
    }

    /** Actualiza un cupón existente. */
    public function update(Request $request, $id)
    {
        $empresa = $this->getEmpresa();

        // Verifica que el cupón sea de ESTA empresa antes de actualizar
        $cupon = Cupon::where('id', $id)
            ->where('empresa_id', $empresa->id)
            ->firstOrFail();

        $data = $request->validate([
            'codigo'                  => ['required', 'string', 'max:50', "unique:cupones,codigo,{$id}"],
            'descripcion'             => ['nullable', 'string', 'max:255'],
            'valor_descuento'         => ['required', 'numeric', 'min:0.01', 'max:100'],
            'fecha_inicio'            => ['required', 'date'],
            'fecha_fin'               => ['required', 'date', 'after:fecha_inicio'],
            'limite_usos_total'       => ['nullable', 'integer', 'min:1'],
            'limite_usos_por_usuario' => ['nullable', 'integer', 'min:1'],
            'estado'                  => ['required', 'in:0,1'],
            'eventos'                 => ['nullable', 'array'],
            'eventos.*'               => ['integer', 'exists:eventos,id'],
        ]);

        $cupon->update([
            'codigo'                  => strtoupper($data['codigo']),
            'descripcion'             => $data['descripcion'] ?? null,
            'valor_descuento'         => $data['valor_descuento'],
            'fecha_inicio'            => $data['fecha_inicio'],
            'fecha_fin'               => $data['fecha_fin'],
            'limite_usos_total'       => $data['limite_usos_total'] ?? null,
            'limite_usos_por_usuario' => $data['limite_usos_por_usuario'] ?? null,
            'estado'                  => (int) $data['estado'],
            'fecha_actualizacion'     => now(),
        ]);

        // Reemplazar los eventos vinculados
        CuponEvento::where('cupon_id', $cupon->id)->delete();

        if (!empty($data['eventos'])) {
            $ahora = now();
            foreach ($data['eventos'] as $eventoId) {
                CuponEvento::create([
                    'cupon_id'            => $cupon->id,
                    'evento_id'           => $eventoId,
                    'estado'              => 1,
                    'fecha_creacion'      => $ahora,
                    'fecha_actualizacion' => null,
                ]);
            }
        }

        return redirect()
            ->route('empresa.cupones.index')
            ->with('success', "Cupón «{$cupon->codigo}» actualizado correctamente.");
    }

    /** Elimina un cupón (solo si pertenece a esta empresa). */
    public function destroy($id)
    {
        $empresa = $this->getEmpresa();

        // Verifica que el cupón sea de ESTA empresa antes de borrar
        $cupon = Cupon::where('id', $id)
            ->where('empresa_id', $empresa->id)
            ->firstOrFail();

        $codigo = $cupon->codigo;

        DB::transaction(function () use ($cupon) {
            CuponEvento::where('cupon_id', $cupon->id)->delete();
            $cupon->delete();
        });

        return redirect()
            ->route('empresa.cupones.index')
            ->with('success', "Cupón «{$codigo}» eliminado.");
    }
}
