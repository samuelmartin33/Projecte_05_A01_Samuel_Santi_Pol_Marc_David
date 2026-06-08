@forelse($bonosActivos as $bono)
    <tr>
        <td data-label="Usuario">
            {{ $bono->usuario?->nombre ?? '—' }}
            <br>
            <small>{{ $bono->usuario?->email ?? '' }}</small>
        </td>
        <td data-label="Evento">{{ $bono->evento?->titulo ?? '—' }}</td>
        <td data-label="Tipo">
            <span class="badge-saldo">
                {{ $bono->tipo?->cantidad_bebidas ?? '?' }} bebidas
            </span>
        </td>
        <td data-label="Saldo restante">
            <span class="badge-saldo {{ $bono->bebidas_restantes === 0 ? 'agotado' : '' }}">
                {{ $bono->bebidas_restantes }} / {{ $bono->tipo?->cantidad_bebidas ?? '?' }}
            </span>
        </td>
        <td data-label="Fecha compra">
            {{ $bono->created_at?->format('d/m/Y H:i') ?? '—' }}
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="stats-vacio">No hay bonos activos con los filtros seleccionados.</td>
    </tr>
@endforelse
