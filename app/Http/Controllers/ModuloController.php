<?php

namespace App\Http\Controllers;

use App\Models\Historial;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ModuloController extends Controller
{
    private array $mensajes = [
        'required' => 'El campo :attribute es obligatorio.',
        'max' => 'El campo :attribute no puede superar :max caracteres.',
        'unique' => 'Ya existe un registro con ese valor en :attribute.',
        'date' => 'El campo :attribute debe ser una fecha válida.',
        'after_or_equal' => 'El campo :attribute debe ser igual o posterior a :date.',
        'exists' => 'El valor elegido en :attribute no es válido.',
        'in' => 'El valor elegido en :attribute no es válido.',
        'date_format' => 'El campo :attribute no tiene un formato válido.',
    ];

    private function modulo(string $clave): array
    {
        $m = config("modulos.$clave");
        abort_if($m === null, 404);
        return $m;
    }

    private function soloEscritura(array $m): void
    {
        abort_if(($m['solo_lectura'] ?? false) === true, 403, 'Módulo de solo lectura');
    }

    private function nombres(array $m): array
    {
        $nombres = [];
        foreach ($m['campos'] as $campo => $c) {
            $nombres[$campo] = strtolower($c['label']);
        }
        return $nombres;
    }

    private function reglas(array $m, ?int $id = null): array
    {
        $modelo = $m['model'];
        $tabla = (new $modelo)->getTable();
        $reglas = [];
        foreach ($m['campos'] as $campo => $c) {
            $lista = [];
            foreach (explode('|', $c['rules'] ?? 'nullable') as $parte) {
                $lista[] = ($parte === 'unique') ? Rule::unique($tabla, $campo)->ignore($id) : $parte;
            }
            if ($c['type'] === 'select') {
                $lista[] = Rule::in($c['options']);
            }
            if ($c['type'] === 'fk') {
                $relacionado = $c['model'];
                $lista[] = Rule::exists((new $relacionado)->getTable(), 'id');
            }
            $reglas[$campo] = $lista;
        }
        return $reglas;
    }

    private function mapas(array $m): array
    {
        $mapas = [];
        foreach ($m['campos'] as $campo => $c) {
            if ($c['type'] === 'fk') {
                $relacionado = $c['model'];
                $mapas[$campo] = $relacionado::all()->mapWithKeys(function ($r) use ($c) {
                    $texto = collect($c['mostrar'])->map(fn ($col) => $r->$col)->implode(' ');
                    return [$r->id => $texto];
                })->all();
            }
        }
        return $mapas;
    }

    private function historial(string $accion, string $modulo, $registro): void
    {
        $m = config("modulos.$modulo");
        $empleadoId = $registro->getAttribute('empleado_id');
        if ($modulo === 'empleados' && $accion !== 'eliminar') {
            $empleadoId = $registro->id;
        }
        Historial::create([
            'empleado_id' => $empleadoId,
            'accion' => $accion,
            'tabla_afectada' => $modulo,
            'detalle' => json_encode($registro->only(array_keys($m['campos'])), JSON_UNESCAPED_UNICODE),
            'usuario' => auth()->user()->email,
        ]);
    }

    public function inicio()
    {
        $conteos = [];
        foreach (config('modulos') as $clave => $m) {
            $modelo = $m['model'];
            $conteos[$clave] = $modelo::count();
        }
        return view('inicio', ['conteos' => $conteos]);
    }

    public function index(Request $request, string $modulo)
    {
        $m = $this->modulo($modulo);
        $modelo = $m['model'];
        $consulta = $modelo::query();

        if ($buscar = $request->query('q')) {
            $consulta->where(function ($w) use ($m, $buscar) {
                foreach ($m['campos'] as $campo => $c) {
                    if (in_array($c['type'], ['text', 'textarea'])) {
                        $w->orWhere($campo, 'like', "%$buscar%");
                    }
                }
            });
        }

        $registros = $consulta->orderByDesc('id')->paginate(10)->withQueryString();

        return view('modulos.index', [
            'clave' => $modulo, 'm' => $m, 'registros' => $registros, 'mapas' => $this->mapas($m),
        ]);
    }

    public function create(string $modulo)
    {
        $m = $this->modulo($modulo);
        $this->soloEscritura($m);
        return view('modulos.form', ['clave' => $modulo, 'm' => $m, 'registro' => null, 'mapas' => $this->mapas($m)]);
    }

    public function store(Request $request, string $modulo)
    {
        $m = $this->modulo($modulo);
        $this->soloEscritura($m);
        $datos = $request->validate($this->reglas($m), $this->mensajes, $this->nombres($m));
        $modelo = $m['model'];

        try {
            $registro = $modelo::create($datos);
        } catch (QueryException $e) {
            return back()->withInput()->with('error', 'No se pudo guardar. Verifique que no esté duplicado y que los datos sean válidos.');
        }

        $this->historial('crear', $modulo, $registro);
        return redirect()->route('modulo.index', $modulo)->with('ok', $m['singular'] . ' creado correctamente.');
    }

    public function edit(string $modulo, int $id)
    {
        $m = $this->modulo($modulo);
        $this->soloEscritura($m);
        $modelo = $m['model'];
        $registro = $modelo::findOrFail($id);
        return view('modulos.form', ['clave' => $modulo, 'm' => $m, 'registro' => $registro, 'mapas' => $this->mapas($m)]);
    }

    public function update(Request $request, string $modulo, int $id)
    {
        $m = $this->modulo($modulo);
        $this->soloEscritura($m);
        $modelo = $m['model'];
        $registro = $modelo::findOrFail($id);
        $datos = $request->validate($this->reglas($m, $id), $this->mensajes, $this->nombres($m));

        try {
            $registro->update($datos);
        } catch (QueryException $e) {
            return back()->withInput()->with('error', 'No se pudo actualizar. Verifique que no esté duplicado y que los datos sean válidos.');
        }

        $this->historial('editar', $modulo, $registro);
        return redirect()->route('modulo.index', $modulo)->with('ok', $m['singular'] . ' actualizado correctamente.');
    }

    public function destroy(string $modulo, int $id)
    {
        $m = $this->modulo($modulo);
        $this->soloEscritura($m);
        $modelo = $m['model'];
        $registro = $modelo::findOrFail($id);

        try {
            $registro->delete();
        } catch (QueryException $e) {
            return back()->with('error', 'No se puede eliminar: el registro está relacionado con otros datos.');
        }

        $this->historial('eliminar', $modulo, $registro);
        return redirect()->route('modulo.index', $modulo)->with('ok', $m['singular'] . ' eliminado correctamente.');
    }
}
