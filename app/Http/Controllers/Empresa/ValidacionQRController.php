<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\Entrada;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ValidacionQRController extends Controller
{
    private function empresa()
    {
        $user = Auth::user();

        if (!$user || !$user->isEmpresa()) {
            abort(403, 'Acceso restringido a empresas.');
        }

        $empresa = $user->empresa;

        if (!$empresa) {
            abort(403, 'Tu cuenta no tiene un perfil de empresa configurado.');
        }

        return $empresa;
    }

    /**
     * GET /empresa/validacion
     * Página del escáner QR.
     */
    public function index()
    {
        $empresa = $this->empresa();

        $eventos = $empresa->eventos()
            ->where('eventos.estado', 1)
            ->orderBy('eventos.fecha_inicio', 'desc')
            ->get();

        return view('empresa.validacion.index', compact('empresa', 'eventos'));
    }

    /**
     * POST /empresa/validacion/validar  (AJAX)
     * Recibe un codigo_qr, verifica que pertenezca a un evento de la empresa
     * y, si es válida, marca la entrada como usada (estado_entrada = 2).
     */
    public function validar(Request $request)
    {
        $empresa = $this->empresa();

        $request->validate(['codigo_qr' => 'required|string|max:255']);

        $entrada = Entrada::with(['evento', 'pedido.usuario'])
            ->where('codigo_qr', trim($request->codigo_qr))
            ->first();

        if (!$entrada) {
            return response()->json([
                'ok'    => false,
                'tipo'  => 'no_encontrada',
                'error' => 'QR no reconocido. El código no existe en el sistema.',
            ], 404);
        }

        // Verificar que el evento pertenece a esta empresa
        $esDeEmpresa = $empresa->eventos()
            ->where('eventos.id', $entrada->evento_id)
            ->exists();

        if (!$esDeEmpresa) {
            return response()->json([
                'ok'    => false,
                'tipo'  => 'no_autorizada',
                'error' => 'Esta entrada no pertenece a ninguno de tus eventos.',
            ], 403);
        }

        $evento  = $entrada->evento;
        $usuario = $entrada->pedido->usuario;

        if ((int) $entrada->estado_entrada === 0) {
            return response()->json([
                'ok'     => false,
                'tipo'   => 'cancelada',
                'error'  => 'Esta entrada ha sido cancelada y no da acceso al evento.',
                'evento' => $evento->titulo,
                'nombre' => $usuario->nombre . ' ' . $usuario->apellido1,
            ]);
        }

        if ((int) $entrada->estado_entrada === 2) {
            return response()->json([
                'ok'       => false,
                'tipo'     => 'ya_usada',
                'error'    => 'Esta entrada ya fue utilizada y ha quedado invalidada.',
                'fecha_uso' => $entrada->fecha_uso?->format('d/m/Y \a \l\a\s H:i'),
                'evento'   => $evento->titulo,
                'nombre'   => $usuario->nombre . ' ' . $usuario->apellido1,
            ]);
        }

        // Entrada válida → marcar como usada
        $entrada->estado_entrada     = 2;
        $entrada->fecha_uso          = now();
        $entrada->fecha_actualizacion = now();
        $entrada->save();

        return response()->json([
            'ok'     => true,
            'evento' => $evento->titulo,
            'nombre' => $usuario->nombre . ' ' . $usuario->apellido1,
            'codigo' => substr($entrada->codigo_qr, 0, 8) . '…',
        ]);
    }
}
