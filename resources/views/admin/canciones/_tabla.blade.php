@forelse($canciones as $cancion)
    <tr>
        <td data-label="Portada">
            @if($cancion->portada_url)
                <img src="{{ $cancion->portada_url }}" alt="Portada {{ $cancion->titulo }}"
                     style="width:48px;height:48px;object-fit:cover;border-radius:4px;border:1px solid rgba(245,241,234,0.12);">
            @else
                <span style="display:flex;align-items:center;justify-content:center;width:48px;height:48px;border-radius:4px;background:rgba(124,58,237,0.15);border:1px solid rgba(124,58,237,0.25);font-size:1.2rem;">🎵</span>
            @endif
        </td>
        <td data-label="Título">
            <span style="font-weight:600;color:#f5f1ea;">{{ $cancion->titulo }}</span>
        </td>
        <td data-label="Artista">{{ $cancion->artista }}</td>
        <td data-label="Géneros">
            @foreach($cancion->generos ?? [] as $genero)
                <span class="badge-genero">{{ $genero }}</span>
            @endforeach
        </td>
        <td data-label="Duración">{{ $cancion->duracion_fmt }}</td>
        <td data-label="Precio">{{ number_format($cancion->precio, 2) }} €</td>
        <td data-label="Estado">
            <span class="estado {{ $cancion->activa ? 'activo' : 'inactivo' }}">
                {{ $cancion->activa ? 'Activa' : 'Inactiva' }}
            </span>
        </td>
        <td data-label="Acciones" class="acciones">
            <button type="button" class="btn btn-secondary"
                    onclick="abrirModalEditar({{ $cancion->id }})">
                Editar
            </button>
            @if($cancion->activa)
                <button type="button" class="btn btn-danger"
                        onclick="confirmarDesactivar({{ $cancion->id }}, '{{ addslashes($cancion->titulo) }}')">
                    Desactivar
                </button>
            @else
                <span style="font-size:12px;color:rgba(245,241,234,0.35);padding:0 4px;">Inactiva</span>
            @endif
        </td>
    </tr>
@empty
    <tr>
        <td colspan="8" class="empty">No hay canciones registradas.</td>
    </tr>
@endforelse

{{-- Paginación --}}
@if($canciones->hasPages())
    <tr>
        <td colspan="8" style="padding:1rem 0.5rem;border:none;">
            {{ $canciones->links() }}
        </td>
    </tr>
@endif
