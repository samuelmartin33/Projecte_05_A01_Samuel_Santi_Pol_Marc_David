<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

/**
 * Gestiona el perfil público de la empresa: nombre comercial, descripción,
 * logo, web y teléfono. Diferente del perfil fiscal (datos legales/bancarios).
 */
class PerfilEmpresaController extends Controller
{
    /**
     * Devuelve la empresa autenticada o aborta con 403.
     */
    private function empresa()
    {
        /** @var \App\Models\Usuario $user */
        $user = Auth::user();

        if (!$user || !$user->isEmpresa()) {
            abort(403);
        }

        $empresa = $user->empresa;

        if (!$empresa) {
            abort(403, 'No tienes un perfil de empresa configurado.');
        }

        return $empresa;
    }

    /**
     * Muestra el formulario de edición del perfil de empresa.
     * GET /empresa/perfil
     */
    public function show(): View
    {
        $empresa = $this->empresa();

        return view('empresa.perfil', compact('empresa'));
    }

    /**
     * Valida y guarda los cambios del perfil de empresa.
     * POST /empresa/perfil
     *
     * Gestiona la subida del logo si se envía un archivo nuevo.
     */
    public function update(Request $request): RedirectResponse
    {
        $empresa = $this->empresa();

        $validated = $request->validate([
            'nombre_empresa'     => ['required', 'string', 'max:200'],
            'descripcion'        => ['nullable', 'string', 'max:1000'],
            'sitio_web'          => ['nullable', 'url', 'max:300'],
            'telefono_contacto'  => ['nullable', 'string', 'max:30'],
            'logo'               => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'nombre_empresa.required' => 'El nombre de la empresa es obligatorio.',
            'nombre_empresa.max'      => 'El nombre no puede superar 200 caracteres.',
            'descripcion.max'         => 'La descripción no puede superar 1000 caracteres.',
            'sitio_web.url'           => 'El sitio web debe ser una URL válida (ej: https://miempresa.com).',
            'telefono_contacto.max'   => 'El teléfono no puede superar 30 caracteres.',
            'logo.image'              => 'El logo debe ser una imagen.',
            'logo.mimes'              => 'El logo debe ser JPG, PNG o WebP.',
            'logo.max'                => 'El logo no puede pesar más de 2 MB.',
        ]);

        // Procesar subida del nuevo logo si viene en la petición
        $logoUrl = $empresa->logo_url;

        if ($request->hasFile('logo')) {
            // Borrar el logo anterior si existe
            if ($logoUrl && Storage::disk('public')->exists($logoUrl)) {
                Storage::disk('public')->delete($logoUrl);
            }
            $logoUrl = $request->file('logo')->store('logos-empresa', 'public');
        }

        $empresa->update([
            'nombre_empresa'     => $validated['nombre_empresa'],
            'descripcion'        => $validated['descripcion'] ?? null,
            'sitio_web'          => $validated['sitio_web']   ?? null,
            'telefono_contacto'  => $validated['telefono_contacto'] ?? null,
            'logo_url'           => $logoUrl,
            'fecha_actualizacion' => now(),
        ]);

        return redirect()->route('empresa.perfil')
            ->with('success', 'Perfil de empresa actualizado correctamente.');
    }
}