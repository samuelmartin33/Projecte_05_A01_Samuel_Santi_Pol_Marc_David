<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CategoriaTrabajo;
use Illuminate\Http\Request;

/**
 * Controlador de admin para gestionar las categorías de trabajo (Camarero, Portero, etc.).
 * Las categorías creadas aquí aparecen en el selector del formulario de candidatura
 * y son las mismas que se usan al crear una oferta de trabajo.
 */
class TrabajosController extends Controller
{
    /**
     * Muestra la lista de categorías con el formulario de creación en la misma página.
     * GET /admin/trabajos
     */
    public function index()
    {
        $trabajos = CategoriaTrabajo::orderBy('nombre')->get();

        return view('admin.trabajos.index', compact('trabajos'));
    }

    /**
     * Guarda la nueva categoría de trabajo en la base de datos.
     * POST /admin/trabajos
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre'      => ['required', 'string', 'max:100', 'unique:categorias_trabajo,nombre'],
            'descripcion' => ['nullable', 'string', 'max:500'],
        ], [
            'nombre.required' => 'El nombre del puesto es obligatorio.',
            'nombre.max'      => 'El nombre no puede superar los 100 caracteres.',
            'nombre.unique'   => 'Ya existe un puesto con ese nombre.',
        ]);

        CategoriaTrabajo::create([
            'nombre'         => $request->nombre,
            'descripcion'    => $request->descripcion,
            'estado'         => 1,
            'fecha_creacion' => now(),
        ]);

        return redirect()
            ->route('admin.trabajos.index')
            ->with('success', 'Puesto "' . $request->nombre . '" creado correctamente.');
    }

    /**
     * Actualiza el nombre y descripción de una categoría de trabajo.
     * PATCH /admin/trabajos/{categoria}
     */
    public function update(Request $request, CategoriaTrabajo $categoria)
    {
        $request->validate([
            'nombre'      => ['required', 'string', 'max:100', "unique:categorias_trabajo,nombre,{$categoria->id}"],
            'descripcion' => ['nullable', 'string', 'max:500'],
        ], [
            'nombre.required' => 'El nombre del puesto es obligatorio.',
            'nombre.unique'   => 'Ya existe otro puesto con ese nombre.',
        ]);

        $categoria->update([
            'nombre'              => $request->nombre,
            'descripcion'         => $request->descripcion,
            'fecha_actualizacion' => now(),
        ]);

        return redirect()
            ->route('admin.trabajos.index')
            ->with('success', 'Puesto "' . $categoria->nombre . '" actualizado correctamente.');
    }

    /**
     * Activa o desactiva una categoría de trabajo (toggle de estado).
     * PATCH /admin/trabajos/{categoria}/estado
     */
    public function toggleEstado(CategoriaTrabajo $categoria)
    {
        $categoria->update([
            'estado'              => $categoria->estado ? 0 : 1,
            'fecha_actualizacion' => now(),
        ]);

        $label = $categoria->estado ? 'activado' : 'desactivado';

        return redirect()
            ->route('admin.trabajos.index')
            ->with('success', 'Puesto "' . $categoria->nombre . '" ' . $label . '.');
    }

    /**
     * Elimina una categoría de trabajo.
     * DELETE /admin/trabajos/{categoria}
     */
    public function destroy(CategoriaTrabajo $categoria)
    {
        $nombre = $categoria->nombre;
        $categoria->delete();

        return redirect()
            ->route('admin.trabajos.index')
            ->with('success', 'Puesto "' . $nombre . '" eliminado.');
    }
}
