@extends('layouts.app')
@section('titulo', $registro ? 'Editar' : 'Nuevo')
@section('contenido')
<h3 class="mb-3">{{ $registro ? 'Editar' : 'Nuevo' }}: {{ $m['singular'] }}</h3>

<form method="POST"
      action="{{ $registro ? route('modulo.update', [$clave, $registro->id]) : route('modulo.store', $clave) }}"
      class="card card-body shadow-sm">
    @csrf
    @if ($registro)
        @method('PUT')
    @endif

    <div class="row">
        @foreach ($m['campos'] as $campo => $c)
            @php $valor = old($campo, $registro->$campo ?? null); @endphp
            <div class="col-md-6 mb-3">
                <label class="form-label" for="{{ $campo }}">{{ $c['label'] }}</label>

                @if ($c['type'] === 'select')
                    <select name="{{ $campo }}" id="{{ $campo }}" class="form-select @error($campo) is-invalid @enderror">
                        <option value="">-- Seleccione --</option>
                        @foreach ($c['options'] as $op)
                            <option value="{{ $op }}" @selected((string) $valor === $op)>{{ ucfirst(str_replace('_', ' ', $op)) }}</option>
                        @endforeach
                    </select>
                @elseif ($c['type'] === 'fk')
                    <select name="{{ $campo }}" id="{{ $campo }}" class="form-select @error($campo) is-invalid @enderror">
                        <option value="">-- Seleccione --</option>
                        @foreach ($mapas[$campo] as $id => $texto)
                            <option value="{{ $id }}" @selected((string) $valor === (string) $id)>{{ $texto }}</option>
                        @endforeach
                    </select>
                @elseif ($c['type'] === 'time')
                    <input type="time" name="{{ $campo }}" id="{{ $campo }}" value="{{ substr((string) $valor, 0, 5) }}"
                           class="form-control @error($campo) is-invalid @enderror">
                @else
                    <input type="{{ $c['type'] }}" name="{{ $campo }}" id="{{ $campo }}" value="{{ $valor }}"
                           class="form-control @error($campo) is-invalid @enderror">
                @endif

                @error($campo)<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>
        @endforeach
    </div>

    <div>
        <button class="btn btn-primary">Guardar</button>
        <a href="{{ route('modulo.index', $clave) }}" class="btn btn-secondary">Cancelar</a>
    </div>
</form>
@endsection
