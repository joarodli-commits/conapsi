@extends('layouts.app')
@section('titulo', $m['titulo'])
@section('contenido')
@php $lectura = ($m['solo_lectura'] ?? false) === true; @endphp
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">{{ $m['titulo'] }}</h3>
    @if (! $lectura)
        <a href="{{ route('modulo.create', $clave) }}" class="btn btn-success">+ Nuevo</a>
    @endif
</div>

<form method="GET" class="row g-2 mb-3">
    <div class="col-md-5">
        <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Buscar...">
    </div>
    <div class="col-auto">
        <button class="btn btn-outline-primary">Buscar</button>
        <a href="{{ route('modulo.index', $clave) }}" class="btn btn-outline-secondary">Limpiar</a>
    </div>
</form>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-striped table-hover mb-0 align-middle">
            <thead class="table-dark">
            <tr>
                @foreach ($m['campos'] as $c)
                    <th>{{ $c['label'] }}</th>
                @endforeach
                <th>{{ $lectura ? 'Fecha' : 'Acciones' }}</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($registros as $r)
                <tr>
                    @foreach ($m['campos'] as $campo => $c)
                        @php $v = $r->$campo; @endphp
                        <td>
                            @if ($c['type'] === 'fk')
                                {{ $mapas[$campo][$v] ?? '-' }}
                            @elseif ($c['type'] === 'select')
                                {{ ucfirst(str_replace('_', ' ', (string) $v)) }}
                            @elseif ($c['type'] === 'time')
                                {{ substr((string) $v, 0, 5) }}
                            @elseif ($c['type'] === 'textarea')
                                {{ \Illuminate\Support\Str::limit((string) $v, 70) }}
                            @else
                                {{ $v }}
                            @endif
                        </td>
                    @endforeach
                    <td class="text-nowrap">
                        @if ($lectura)
                            {{ $r->created_at }}
                        @else
                            <a href="{{ route('modulo.edit', [$clave, $r->id]) }}" class="btn btn-sm btn-warning">Editar</a>
                            <form method="POST" action="{{ route('modulo.destroy', [$clave, $r->id]) }}" class="d-inline"
                                  onsubmit="return confirm('¿Eliminar este registro?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Eliminar</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="{{ count($m['campos']) + 1 }}" class="text-center text-muted py-4">No hay registros.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $registros->links('pagination::bootstrap-5') }}</div>
@endsection
