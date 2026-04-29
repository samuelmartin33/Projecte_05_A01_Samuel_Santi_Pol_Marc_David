<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\BolsaOfertaTrabajo;
use App\Models\CandidaturaTrabajo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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

        $query = CandidaturaTrabajo::where('oferta_id', $ofertaId)
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

        $request->validate(['estado' => 'required|in:1,2,3,4']);

        $candidatura->update([
            'estado_candidatura'  => $request->estado,
            'fecha_actualizacion' => now(),
        ]);

        return response()->json([
            'success' => true,
            'label'   => $candidatura->estadoLabel(),
            'clases'  => $candidatura->estadoClases(),
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

        $nombre = $candidatura->nombreCompleto();
        $ext    = pathinfo($candidatura->cv_url, PATHINFO_EXTENSION);

        return Storage::disk('public')->download(
            $candidatura->cv_url,
            "CV_{$nombre}.{$ext}"
        );
    }
}