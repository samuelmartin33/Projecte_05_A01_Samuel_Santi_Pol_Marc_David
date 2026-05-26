<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\CategoriaTrabajo;
use App\Models\Organizador;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class EquipoController extends Controller
{
    private function empresa()
    {
        $user = Auth::user();
        if (!$user || !$user->isEmpresa()) abort(403);
        $empresa = $user->empresa;
        if (!$empresa) abort(403);
        return $empresa;
    }

    /**
     * Lista el equipo con sus puestos de trabajo cargados.
     */
    public function index()
    {
        $empresa = $this->empresa();

        $miembros = $empresa->organizadores()
            ->with(['usuario', 'categoriaTrabajo'])
            ->where('estado', 1)
            ->orderBy('rol')
            ->orderBy('fecha_creacion')
            ->get();

        // Categorías activas de la tabla unificada para los selectores
        $categorias = CategoriaTrabajo::where('estado', 1)
            ->orderBy('nombre')
            ->get();

        return view('empresa.equipo.index', compact('empresa', 'miembros', 'categorias'));
    }

    /**
     * Crea un nuevo usuario y lo asigna al equipo con rol y puesto.
     */
    public function store(Request $request)
    {
        $empresa = $this->empresa();

        $request->validate([
            'nombre'               => ['required', 'string', 'max:100'],
            'apellido1'            => ['required', 'string', 'max:100'],
            'email'                => ['required', 'email', 'max:200', 'unique:usuarios,email'],
            'password'             => ['required', 'string', 'min:8', 'confirmed'],
            'rol'                  => ['required', Rule::in(['organizador', 'portero'])],
            'categoria_trabajo_id' => ['nullable', 'integer', 'exists:categorias_trabajo,id'],
        ], [
            'email.unique'       => 'Ya existe un usuario con ese correo electrónico.',
            'password.min'       => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        $usuario = Usuario::create([
            'nombre'              => $request->nombre,
            'apellido1'           => $request->apellido1,
            'email'               => $request->email,
            'password_hash'       => Hash::make($request->password),
            'tipo_cuenta'         => 'cliente',
            'estado'              => 1,
            'email_verificado'    => 1,
            'fecha_creacion'      => now(),
            'fecha_actualizacion' => now(),
        ]);

        Organizador::create([
            'usuario_id'           => $usuario->id,
            'empresa_id'           => $empresa->id,
            'rol'                  => $request->rol,
            'categoria_trabajo_id' => $request->categoria_trabajo_id ?: null,
            'estado'               => 1,
            'fecha_creacion'       => now(),
            'fecha_actualizacion'  => now(),
        ]);

        return back()->with('success', "Usuario {$usuario->nombre} {$usuario->apellido1} creado como " . ucfirst($request->rol) . '.');
    }

    /**
     * Actualiza el rol de permiso y el puesto de trabajo del miembro.
     */
    public function cambiarRol(Request $request, Organizador $organizador)
    {
        $empresa = $this->empresa();
        abort_if($organizador->empresa_id !== $empresa->id, 403);

        $request->validate([
            'rol'                  => ['required', Rule::in(['organizador', 'portero'])],
            'categoria_trabajo_id' => ['nullable', 'integer', 'exists:categorias_trabajo,id'],
        ]);

        $organizador->update([
            'rol'                  => $request->rol,
            'categoria_trabajo_id' => $request->categoria_trabajo_id ?: null,
        ]);

        return back()->with('success', 'Miembro actualizado correctamente.');
    }

    /**
     * Desactiva (soft-delete) al miembro del equipo.
     */
    public function destroy(Organizador $organizador)
    {
        $empresa = $this->empresa();
        abort_if($organizador->empresa_id !== $empresa->id, 403);

        $organizador->update(['estado' => 0]);

        return back()->with('success', 'Miembro eliminado del equipo.');
    }
}
