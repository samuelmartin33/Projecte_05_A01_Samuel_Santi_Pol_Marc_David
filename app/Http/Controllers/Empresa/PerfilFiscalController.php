<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Gestiona el perfil fiscal de la empresa (Fase 2 del onboarding).
 *
 * Tras registrarse y ser aprobada por el admin, la empresa debe completar
 * sus datos legales y bancarios antes de poder publicar eventos.
 * Este controlador gestiona el formulario de esos datos.
 */
class PerfilFiscalController extends Controller
{
    /**
     * Muestra el formulario de perfil fiscal con los datos actuales de la empresa.
     * GET /empresa/perfil-fiscal
     */
    public function show(): View
    {
        /** @var \App\Models\Usuario $usuario */
        $usuario = Auth::user();
        $empresa = $usuario->empresa;

        return view('empresa.perfil-fiscal', compact('empresa'));
    }

    /**
     * Valida y guarda los datos del perfil fiscal.
     * POST /empresa/perfil-fiscal
     *
     * Al terminar marca perfil_fiscal_completo = true si todos los campos
     * obligatorios están presentes, y redirige al panel de empresa.
     */
    public function update(Request $request): RedirectResponse
    {
        /** @var \App\Models\Usuario $usuario */
        $usuario = Auth::user();
        $empresa = $usuario->empresa;

        if (! $empresa) {
            return redirect()->route('empresa.home')
                ->with('error', 'No se encontró el perfil de empresa.');
        }

        $validated = $request->validate([
            'razon_social'       => ['required', 'string', 'max:300'],
            'nif_cif'            => ['required', 'string', 'max:20', 'regex:/^[A-Za-z0-9]{7,9}$/'],
            'tipo_empresa'       => ['required', 'in:autonomo,sl,sa,asociacion,otro'],
            'direccion'          => ['required', 'string', 'max:500'],
            'ciudad'             => ['required', 'string', 'max:100'],
            'codigo_postal'      => ['required', 'string', 'max:5', 'regex:/^[0-9]{5}$/'],
            'provincia'          => ['required', 'string', 'max:100'],
            'pais'               => ['required', 'string', 'max:100'],
            'email_facturacion'  => ['required', 'email', 'max:200'],
            'titular_cuenta'     => ['required', 'string', 'max:200'],
            'iban'               => ['required', 'string', 'regex:/^ES[0-9]{22}$/i'],
        ], [
            'razon_social.required'      => 'La razón social es obligatoria',
            'nif_cif.required'           => 'El NIF/CIF es obligatorio',
            'nif_cif.regex'              => 'El NIF/CIF no tiene el formato correcto (ej: B12345678)',
            'tipo_empresa.required'      => 'Selecciona la forma jurídica',
            'tipo_empresa.in'            => 'Tipo de empresa no válido',
            'direccion.required'         => 'La dirección es obligatoria',
            'ciudad.required'            => 'La ciudad es obligatoria',
            'codigo_postal.required'     => 'El código postal es obligatorio',
            'codigo_postal.regex'        => 'El código postal debe tener 5 dígitos',
            'provincia.required'         => 'La provincia es obligatoria',
            'pais.required'              => 'El país es obligatorio',
            'email_facturacion.required' => 'El email de facturación es obligatorio',
            'email_facturacion.email'    => 'El email de facturación no es válido',
            'titular_cuenta.required'    => 'El titular de la cuenta es obligatorio',
            'iban.required'              => 'El IBAN es obligatorio',
            'iban.regex'                 => 'El IBAN debe comenzar por ES seguido de 22 dígitos',
        ]);

        // El mutator setIbanAttribute() del modelo Empresa cifra el valor automáticamente.
        $empresa->update([
            'razon_social'           => $validated['razon_social'],
            'nif_cif'                => $validated['nif_cif'],
            'tipo_empresa'           => $validated['tipo_empresa'],
            'direccion'              => $validated['direccion'],
            'ciudad'                 => $validated['ciudad'],
            'codigo_postal'          => $validated['codigo_postal'],
            'provincia'              => $validated['provincia'],
            'pais'                   => $validated['pais'],
            'email_facturacion'      => $validated['email_facturacion'],
            'titular_cuenta'         => $validated['titular_cuenta'],
            'iban'                   => $validated['iban'],
            'perfil_fiscal_completo' => true,
            'fecha_actualizacion'    => now(),
        ]);

        return redirect()->route('empresa.home')
            ->with('success', '¡Perfil fiscal guardado correctamente! Ya puedes publicar eventos.');
    }
}
