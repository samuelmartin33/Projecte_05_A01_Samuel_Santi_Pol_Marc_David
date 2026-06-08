@forelse($tipos as $tipo)
    <tr>
        <td data-label="Bebidas">
            <span class="badge-saldo">{{ $tipo->cantidad_bebidas }} bebidas</span>
        </td>
        <td data-label="Precio">{{ number_format($tipo->precio, 2) }} €</td>
        <td data-label="Descripción">{{ $tipo->descripcion ?: '—' }}</td>
        <td data-label="Compras">{{ $tipo->compras()->count() }}</td>
        <td data-label="Estado">
            @if($tipo->activo)
                <span class="badge-activo">Activo</span>
            @else
                <span class="badge-inactivo">Inactivo</span>
            @endif
        </td>
        <td data-label="Acciones">
            <button class="btn btn-secondary btn-sm"
                    onclick="abrirModalEditarTipo({{ $tipo->id }})">
                Editar
            </button>
            <button class="btn btn-secondary btn-sm"
                    onclick="toggleActivoTipo({{ $tipo->id }})">
                {{ $tipo->activo ? 'Desactivar' : 'Activar' }}
            </button>
            <button class="btn btn-danger btn-sm"
                    onclick="confirmarEliminarTipo({{ $tipo->id }})">
                Eliminar
            </button>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6" class="stats-vacio">No hay tipos de bono creados todavía.</td>
    </tr>
@endforelse
