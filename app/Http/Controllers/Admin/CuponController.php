<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cupon;
use App\Models\CuponEvento;
use App\Models\Evento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Admin\CuponController — CRUD de cupones en el panel de administración.
 */
class CuponController extends Controller
{
    /** Lista todos los cupones con paginación. */
    public function index()
    {
        $cupones = Cupon::with('eventos')
            ->orderBy('fecha_creacion', 'desc')
            ->paginate(15);

        return view('admin.cupones.index', compact('cupones'));
    }

    /** Formulario de creación. */
    public function create()
    {
        $eventos = Evento::where('estado', 1)
            ->orderBy('fecha_inicio', 'asc')
            ->get();

        return view('admin.cupones.create', compact('eventos'));
    }

    /** Guarda un nuevo cupón. */
    public function store(Request $request)
    {
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
            'codigo.unique'         => 'Ya existe un cupón con ese código.',
            'fecha_fin.after'       => 'La fecha de fin debe ser posterior a la de inicio.',
            'valor_descuento.max'   => 'El descuento no puede superar el 100%.',
        ]);

        $ahora = now();

        $cupon = Cupon::create([
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
            ->route('admin.cupones.index')
            ->with('success', "Cupón «{$cupon->codigo}» creado correctamente.");
    }

    /** Formulario de edición. */
    public function edit($id)
    {
        $cupon   = Cupon::with('eventos')->findOrFail($id);
        $eventos = Evento::where('estado', 1)
            ->orderBy('fecha_inicio', 'asc')
            ->get();

        $eventosSeleccionados = $cupon->eventos->pluck('id')->toArray();

        return view('admin.cupones.edit', compact('cupon', 'eventos', 'eventosSeleccionados'));
    }

    /** Actualiza un cupón existente. */
    public function update(Request $request, $id)
    {
        $cupon = Cupon::findOrFail($id);

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
            ->route('admin.cupones.index')
            ->with('success', "Cupón «{$cupon->codigo}» actualizado correctamente.");
    }

    /** Elimina un cupón y sus registros relacionados. */
    public function destroy($id)
    {
        $cupon = Cupon::findOrFail($id);
        $codigo = $cupon->codigo;

        DB::transaction(function () use ($cupon) {
            CuponEvento::where('cupon_id', $cupon->id)->delete();
            $cupon->delete();
        });

        return redirect()
            ->route('admin.cupones.index')
            ->with('success', "Cupón «{$codigo}» eliminado.");
    }
}
