<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
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

    public function index()
    {
        $empresa = $this->empresa();

        $miembros = $empresa->organizadores()
            ->with('usuario')
            ->where('estado', 1)
            ->orderBy('rol')
            ->orderBy('fecha_creacion')
            ->get();

        return view('empresa.equipo.index', compact('empresa', 'miembros'));
    }

    public function store(Request $request)
    {
        $empresa = $this->empresa();

        $request->validate([
            'nombre'    => ['required', 'string', 'max:100'],
            'apellido1' => ['required', 'string', 'max:100'],
            'email'     => ['required', 'email', 'max:200', 'unique:usuarios,email'],
            'password'  => ['required', 'string', 'min:8', 'confirmed'],
            'rol'       => ['required', Rule::in(['organizador', 'portero'])],
        ], [
            'email.unique'      => 'Ya existe un usuario con ese correo electrónico.',
            'password.min'      => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed'=> 'Las contraseñas no coinciden.',
        ]);

        // Crear el usuario
        $usuario = Usuario::create([
            'nombre'             => $request->nombre,
            'apellido1'          => $request->apellido1,
            'email'              => $request->email,
            'password_hash'      => Hash::make($request->password),
            'tipo_cuenta'        => 'cliente',
            'estado'             => 1,
            'email_verificado'   => 1,
            'fecha_creacion'      => now(),
            'fecha_actualizacion' => now(),
        ]);

        // Asignar a la empresa con el rol indicado
        Organizador::create([
            'usuario_id'          => $usuario->id,
            'empresa_id'          => $empresa->id,
            'rol'                 => $request->rol,
            'estado'              => 1,
            'fecha_creacion'      => now(),
            'fecha_actualizacion' => now(),
        ]);

        return back()->with('success', "Usuario {$usuario->nombre} {$usuario->apellido1} creado como " . ucfirst($request->rol) . '.');
    }

    public function cambiarRol(Request $request, Organizador $organizador)
    {
        $empresa = $this->empresa();
        abort_if($organizador->empresa_id !== $empresa->id, 403);

        $request->validate([
            'rol' => ['required', Rule::in(['organizador', 'portero'])],
        ]);

        $organizador->update(['rol' => $request->rol]);

        return back()->with('success', 'Rol actualizado correctamente.');
    }

    public function destroy(Organizador $organizador)
    {
        $empresa = $this->empresa();
        abort_if($organizador->empresa_id !== $empresa->id, 403);

        // Desactivar (soft-delete lógico)
        $organizador->update(['estado' => 0]);

        return back()->with('success', 'Miembro eliminado del equipo.');
    }
}
