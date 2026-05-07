<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CategoriaEvento;
use Illuminate\Http\Request;

class CategoriaEventoController extends Controller
{
    public function index()
    {
        $categorias = CategoriaEvento::orderBy('nombre')->get();
        return view('admin.categorias.index', compact('categorias'));
    }

    public function create()
    {
        return view('admin.categorias.create', ['categoria' => new CategoriaEvento()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:191'],
            'descripcion' => ['nullable', 'string', 'max:1000'],
            'estado' => ['nullable', 'in:0,1'],
        ]);

        $data['estado'] = $request->input('estado', 1);
        CategoriaEvento::create($data);

        return redirect()->route('admin.categorias.index')->with('success', 'Categoría creada correctamente.');
    }

    public function edit(CategoriaEvento $categoria)
    {
        return view('admin.categorias.edit', compact('categoria'));
    }

    public function update(Request $request, CategoriaEvento $categoria)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:191'],
            'descripcion' => ['nullable', 'string', 'max:1000'],
            'estado' => ['nullable', 'in:0,1'],
        ]);

        $data['estado'] = $request->input('estado', 1);
        $categoria->update($data);

        return redirect()->route('admin.categorias.index')->with('success', 'Categoría actualizada correctamente.');
    }

    public function destroy(CategoriaEvento $categoria)
    {
        $categoria->delete();
        return redirect()->route('admin.categorias.index')->with('success', 'Categoría eliminada.');
    }
}
