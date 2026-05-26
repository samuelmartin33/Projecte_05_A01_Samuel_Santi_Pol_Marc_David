<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Mail\CandidatoSeleccionadoMail;
use App\Models\CandidaturaTrabajo;
use App\Models\InvitacionEquipo;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Controlador para la gestión de candidaturas de empresa.
 */
class CandidaturasController extends Controller
{
    // ── Guard helper ─────────────────────────────────────────────────────────

    /** Returns the authenticated empresa or aborts 403. */
    private function empresa()
    {
        /** @var \App\Models\Usuario $user */
        $user = Auth::user();

        if (!$user || !$user->isEmpresa()) {
            abort(403, 'Acceso restringido a empresas.');
        }

        $empresa = $user->empresa;

        if (!$empresa) {
            abort(403, 'Tu cuenta de empresa aún no tiene un perfil de empresa configurado. Contacta al administrador.');
        }

        return $empresa;
    }

    // ── Actions ──────────────────────────────────────────────────────────────

    /**
     * List all offers published by the empresa, with candidature counts and filters.
     * GET /empresa/candidaturas
     */
    public function ofertas(Request $request)
    {
        $empresa = $this->empresa();

        $query = $empresa->ofertas()
            ->with('categoria')
            ->withCount('candidaturas');

        // Filter by estado (1=activa, 0=cerrada)
        if ($request->filled('estado')) {
            $query->where('bolsa_ofertas_trabajo.estado', $request->estado);
        }

        // Sort
        match ($request->get('orden', 'reciente')) {
            'candidatos' => $query->orderByDesc('candidaturas_count'),
            'titulo'     => $query->orderBy('bolsa_ofertas_trabajo.titulo'),
            default      => $query->orderByDesc('bolsa_ofertas_trabajo.fecha_creacion'),
        };

        $ofertas = $query->paginate(12)->withQueryString();

        $totalCandidaturas = $empresa->ofertas()
            ->join('candidaturas_trabajo', 'bolsa_ofertas_trabajo.id', '=', 'candidaturas_trabajo.oferta_id')
            ->where('candidaturas_trabajo.estado', 1)
            ->count();

        return view('empresa.candidaturas.ofertas', compact('empresa', 'ofertas', 'totalCandidaturas'));
    }

    /**
     * List candidatures received for a specific offer.
     * GET /empresa/candidaturas/{ofertaId}
     */
    public function candidaturas(Request $request, int $ofertaId)
    {
        $empresa = $this->empresa();

        // Verify ownership: the offer must belong to this empresa
        $oferta = $empresa->ofertas()
            ->with('categoria')
            ->where('bolsa_ofertas_trabajo.id', $ofertaId)
            ->firstOrFail();

        $query = CandidaturaTrabajo::with('trabajo')
            ->where('oferta_id', $ofertaId)
            ->where('estado', 1);

        // Filter by estado_candidatura
        if ($request->filled('estado')) {
            $query->where('estado_candidatura', $request->estado);
        }

        // Sort
        match ($request->get('orden', 'reciente')) {
            'nombre'   => $query->orderBy('nombre_candidato')->orderBy('apellidos_candidato'),
            'estado'   => $query->orderBy('estado_candidatura'),
            default    => $query->orderByDesc('fecha_creacion'),
        };

        $candidaturas = $query->paginate(15)->withQueryString();

        // Count by state for the filter badges
        $conteos = CandidaturaTrabajo::where('oferta_id', $ofertaId)
            ->where('estado', 1)
            ->selectRaw('estado_candidatura, count(*) as total')
            ->groupBy('estado_candidatura')
            ->pluck('total', 'estado_candidatura');

        return view('empresa.candidaturas.detalle', compact('empresa', 'oferta', 'candidaturas', 'conteos'));
    }

    /**
     * Update the estado_candidatura of a candidature via AJAX.
     * PATCH /empresa/candidaturas/{candidaturaId}/estado
     */
    public function actualizarEstado(Request $request, int $candidaturaId)
    {
        $empresa = $this->empresa();

        $candidatura = CandidaturaTrabajo::with('oferta')
            ->whereHas('oferta', function ($q) use ($empresa) {
                $orgIds = $empresa->organizadores()->pluck('organizadores.id');
                $q->whereIn('organizador_id', $orgIds);
            })
            ->where('estado', 1)
            ->findOrFail($candidaturaId);

        $request->validate(['estado' => 'required|in:1,2,3,4,5']);

        $estadoAnterior = (int) $candidatura->estado_candidatura;
        $nuevoEstado    = (int) $request->estado;

        DB::transaction(function () use ($candidatura, $nuevoEstado) {
            if ($nuevoEstado === CandidaturaTrabajo::ESTADO_PRESELECCIONADO) {
                $candidatura->trabajador_id = $this->crearTrabajadorSiNoExiste($candidatura);
            }

            $candidatura->estado_candidatura = $nuevoEstado;
            $candidatura->fecha_actualizacion = now();
            $candidatura->save();
        });

        return response()->json([
            'success' => true,
            'label'   => $candidatura->estadoLabel(),
            'clases'  => $candidatura->estadoClases(),
        ]);
    }

    /**
     * Crea el perfil de trabajador asociado a la candidatura si todavía no existe.
     *
     * Busca primero un usuario registrado con el mismo email de la candidatura.
     * Si ya tiene perfil de trabajador, reutiliza ese registro.
     */
    private function crearTrabajadorSiNoExiste(CandidaturaTrabajo $candidatura): int
    {
        if (!empty($candidatura->trabajador_id)) {
            return (int) $candidatura->trabajador_id;
        }

        $usuario = Usuario::where('email', $candidatura->email_candidato)->first();

        if (!$usuario) {
            abort(409, 'No se puede crear el perfil de trabajador porque no existe un usuario registrado con ese email.');
        }

        $trabajadorId = DB::table('trabajadores')
            ->where('usuario_id', $usuario->id)
            ->value('id');

        if ($trabajadorId) {
            return (int) $trabajadorId;
        }

        return (int) DB::table('trabajadores')->insertGetId([
            'usuario_id'          => $usuario->id,
            'cv_url'              => $candidatura->cv_url,
            'disponibilidad'      => 1,
            'localidad'           => $candidatura->ciudad_candidato,
            'estado'              => 1,
            'fecha_creacion'      => now(),
            'fecha_actualizacion' => now(),
        ]);
    }

    /**
     * Toggle estado (1=activa / 0=cerrada) of an offer.
     * PATCH /empresa/candidaturas/oferta/{ofertaId}/cerrar
     */
    public function cerrarOferta(int $ofertaId)
    {
        $empresa = $this->empresa();

        $oferta = $empresa->ofertas()
            ->where('bolsa_ofertas_trabajo.id', $ofertaId)
            ->firstOrFail();

        $nuevoEstado = $oferta->estado ? 0 : 1;

        $oferta->update([
            'estado'               => $nuevoEstado,
            'fecha_actualizacion'  => now(),
        ]);

        return response()->json([
            'success' => true,
            'estado'  => $nuevoEstado,
            'label'   => $nuevoEstado ? 'Activa' : 'Cerrada',
        ]);
    }

    /**
     * Serve the uploaded CV file for download (with permission check).
     * GET /empresa/candidaturas/{candidaturaId}/descargar
     */
    public function descargarCv(int $candidaturaId)
    {
        $empresa = $this->empresa();

        $candidatura = CandidaturaTrabajo::with('oferta')
            ->whereHas('oferta', function ($q) use ($empresa) {
                $orgIds = $empresa->organizadores()->pluck('organizadores.id');
                $q->whereIn('organizador_id', $orgIds);
            })
            ->where('estado', 1)
            ->findOrFail($candidaturaId);

        if (!$candidatura->cv_url || !Storage::disk('public')->exists($candidatura->cv_url)) {
            abort(404, 'Archivo no encontrado.');
        }

        $nombre = $this->resolverNombreCandidato($candidatura);
        $ext    = pathinfo($candidatura->cv_url, PATHINFO_EXTENSION);

        return response()->download(
            Storage::disk('public')->path($candidatura->cv_url),
            "CV_{$nombre}.{$ext}"
        );
    }

    /**
     * Reenvía el correo de selección al candidato manualmente.
     * POST /empresa/candidaturas/{candidaturaId}/enviar-seleccion
     */
    public function enviarEmailSeleccion(int $candidaturaId)
    {
        $empresa = $this->empresa();

        $candidatura = CandidaturaTrabajo::with('oferta')
            ->whereHas('oferta', function ($q) use ($empresa) {
                $orgIds = $empresa->organizadores()->pluck('organizadores.id');
                $q->whereIn('organizador_id', $orgIds);
            })
            ->where('estado', 1)
            ->where('estado_candidatura', CandidaturaTrabajo::ESTADO_SELECCIONADO)
            ->findOrFail($candidaturaId);

        $email = $candidatura->emailResuelto();

        if (!$email) {
            return response()->json(['success' => false, 'message' => 'El candidato no tiene email registrado.'], 422);
        }

        $this->enviarSeleccionConInvitacion($candidatura, $empresa);

        return response()->json(['success' => true, 'message' => 'Correo enviado correctamente.']);
    }

    /**
     * Resuelve el nombre real del candidato buscando en el modelo Usuario.
     * Primero por email_candidato, luego por trabajador_id, y como fallback el nombre de la candidatura.
     */
    private function resolverNombreCandidato(CandidaturaTrabajo $candidatura): string
    {
        // Buscar por email guardado en la candidatura
        if ($candidatura->email_candidato) {
            $usuario = Usuario::where('email', $candidatura->email_candidato)->first();
            if ($usuario) {
                return trim($usuario->nombre . ' ' . ($usuario->apellido1 ?? '') . ' ' . ($usuario->apellido2 ?? ''));
            }
        }

        // Buscar por trabajador_id (candidaturas subidas solo con PDF, sin formulario)
        if ($candidatura->trabajador_id) {
            $usuarioId = DB::table('trabajadores')->where('id', $candidatura->trabajador_id)->value('usuario_id');
            if ($usuarioId) {
                $usuario = Usuario::find($usuarioId);
                if ($usuario) {
                    return trim($usuario->nombre . ' ' . ($usuario->apellido1 ?? '') . ' ' . ($usuario->apellido2 ?? ''));
                }
            }
        }

        return $candidatura->nombreCompleto();
    }

    /**
     * Genera un token de invitación con 5 días de validez y envía el correo de selección.
     * Usado tanto en el cambio automático de estado como en el reenvío manual.
     *
     * El rol de equipo se deduce del trabajo al que se postuló el candidato:
     *  - Si el trabajo contiene "portero" → rol 'portero' (validación QR)
     *  - Cualquier otro trabajo → rol 'organizador' (acceso completo de staff)
     */
    private function enviarSeleccionConInvitacion(CandidaturaTrabajo $candidatura, $empresa): void
    {
        $email = $candidatura->emailResuelto();

        if (!$email) {
            return;
        }

        // Determinar el rol de equipo según el tipo de trabajo de la candidatura
        $nombreTrabajo = $candidatura->trabajo?->nombre ?? '';
        $rolEquipo = str_contains(mb_strtolower($nombreTrabajo), 'portero') ? 'portero' : 'organizador';

        // Generar token único de 64 caracteres
        $token = Str::random(64);

        InvitacionEquipo::create([
            'token'          => $token,
            'candidatura_id' => $candidatura->id,
            'empresa_id'     => $empresa->id,
            'email'          => $email,
            'rol'            => $rolEquipo,
            'expira_en'      => now()->addDays(5),
            'fecha_creacion' => now(),
        ]);

        $urlInvitacion = route('equipo.aceptar', ['token' => $token]);

        Mail::to($email)->send(new CandidatoSeleccionadoMail($candidatura, $urlInvitacion));
    }
}