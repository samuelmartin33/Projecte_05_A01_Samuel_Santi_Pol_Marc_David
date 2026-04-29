<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UsuarioController extends Controller
{
    public function index(): View
    {
        $usuarios = Usuario::orderByDesc('id')->paginate(12);

        return view('admin.usuarios.index', compact('usuarios'));
    }

    public function create(): View
    {
        return view('admin.usuarios.create', [
            'usuario' => new Usuario(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $data['fecha_creacion'] = now();
        $data['fecha_actualizacion'] = null;

        Usuario::create($data);

        return redirect()
            ->route('admin.usuarios.index')
            ->with('success', 'Usuario creado correctamente.');
    }

    public function edit(Usuario $usuario): View
    {
        return view('admin.usuarios.edit', compact('usuario'));
    }

    public function update(Request $request, Usuario $usuario): RedirectResponse
    {
        $data = $this->validatedData($request, $usuario);
        $data['fecha_actualizacion'] = now();

        if (($data['password_hash'] ?? null) === null) {
            unset($data['password_hash']);
        }

        $usuario->update($data);

        return redirect()
            ->route('admin.usuarios.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(Usuario $usuario): RedirectResponse
    {
        if (Auth::id() === $usuario->id) {
            return redirect()
                ->route('admin.usuarios.index')
                ->with('error', 'No puedes eliminar tu propio usuario administrador.');
        }

        $usuario->delete();

        return redirect()
            ->route('admin.usuarios.index')
            ->with('success', 'Usuario eliminado correctamente.');
    }

    private function validatedData(Request $request, ?Usuario $usuario = null): array
    {
        $rules = [
            'nombre' => ['required', 'string', 'max:100'],
            'apellido1' => ['nullable', 'string', 'max:150'],
            'apellido2' => ['nullable', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:255', Rule::unique('usuarios', 'email')->ignore($usuario?->id)],
            'foto_url' => ['nullable', 'url', 'max:500'],
            'biografia' => ['nullable', 'string'],
            'fecha_nacimiento' => ['nullable', 'date'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'tipo_cuenta' => ['required', 'in:cliente,empresa'],
            'estado_registro' => ['required', 'in:pendiente,aprobado,rechazado'],
            'email_verificado' => ['nullable', 'boolean'],
            'es_admin' => ['nullable', 'boolean'],
            'estado' => ['required', 'integer', 'in:0,1'],
        ];

        if ($usuario === null) {
            $rules['password_hash'] = ['required', 'string', 'min:8'];
        } else {
            $rules['password_hash'] = ['nullable', 'string', 'min:8'];
        }

        $data = $request->validate($rules);

        $data['email_verificado'] = $request->boolean('email_verificado') ? 1 : 0;
        $data['es_admin'] = $request->boolean('es_admin') ? 1 : 0;
        $data['estado'] = (int) $request->input('estado', 1);

        if (! array_key_exists('password_hash', $data) || $data['password_hash'] === '') {
            unset($data['password_hash']);
        }

        return $data;
    }
}