@extends('layouts.app')
@section('titulo', 'Inicio')
@section('contenido')
<h3 class="mb-4">Panel de control</h3>
<div class="row g-3">
    @foreach (config('modulos') as $clave => $m)
        <div class="col-md-4 col-lg-3">
            <a href="{{ route('modulo.index', $clave) }}" class="text-decoration-none">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="display-6 text-primary">{{ $conteos[$clave] }}</div>
                        <div class="text-dark">{{ $m['titulo'] }}</div>
                    </div>
                </div>
            </a>
        </div>
    @endforeach
</div>
@endsection
