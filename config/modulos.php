<?php

use App\Models\Asignacion;
use App\Models\Asistencia;
use App\Models\Empleado;
use App\Models\Estacion;
use App\Models\Eventualidad;
use App\Models\Historial;
use App\Models\Linea;
use App\Models\Reserva;
use App\Models\Servicio;
use App\Models\Turno;

return [

    'empleados' => [
        'titulo' => 'Empleados', 'singular' => 'Empleado', 'model' => Empleado::class,
        'campos' => [
            'codigo'   => ['label' => 'Código', 'type' => 'text', 'rules' => 'required|max:20|unique'],
            'nombre'   => ['label' => 'Nombre', 'type' => 'text', 'rules' => 'required|max:50'],
            'apellido' => ['label' => 'Apellido', 'type' => 'text', 'rules' => 'required|max:50'],
            'cargo'    => ['label' => 'Cargo', 'type' => 'text', 'rules' => 'required|max:80'],
            'telefono' => ['label' => 'Teléfono', 'type' => 'text', 'rules' => 'nullable|max:20'],
            'estado'   => ['label' => 'Estado', 'type' => 'select', 'options' => ['activo', 'inactivo', 'licencia'], 'rules' => 'required'],
        ],
    ],

    'lineas' => [
        'titulo' => 'Líneas', 'singular' => 'Línea', 'model' => Linea::class,
        'campos' => [
            'nombre'      => ['label' => 'Nombre', 'type' => 'text', 'rules' => 'required|max:100|unique'],
            'descripcion' => ['label' => 'Descripción', 'type' => 'text', 'rules' => 'nullable|max:255'],
        ],
    ],

    'estaciones' => [
        'titulo' => 'Estaciones', 'singular' => 'Estación', 'model' => Estacion::class,
        'campos' => [
            'linea_id'  => ['label' => 'Línea', 'type' => 'fk', 'model' => Linea::class, 'mostrar' => ['nombre'], 'rules' => 'required'],
            'nombre'    => ['label' => 'Nombre', 'type' => 'text', 'rules' => 'required|max:100'],
            'ubicacion' => ['label' => 'Ubicación', 'type' => 'text', 'rules' => 'nullable|max:150'],
        ],
    ],

    'turnos' => [
        'titulo' => 'Turnos', 'singular' => 'Turno', 'model' => Turno::class,
        'campos' => [
            'nombre'      => ['label' => 'Nombre', 'type' => 'text', 'rules' => 'required|max:50|unique'],
            'hora_inicio' => ['label' => 'Hora de inicio', 'type' => 'time', 'rules' => 'required|date_format:H:i,H:i:s'],
            'hora_fin'    => ['label' => 'Hora de fin', 'type' => 'time', 'rules' => 'required|date_format:H:i,H:i:s'],
            'descripcion' => ['label' => 'Descripción', 'type' => 'text', 'rules' => 'nullable|max:255'],
        ],
    ],

    'servicios' => [
        'titulo' => 'Servicios', 'singular' => 'Servicio', 'model' => Servicio::class,
        'campos' => [
            'nombre'      => ['label' => 'Nombre', 'type' => 'text', 'rules' => 'required|max:100|unique'],
            'descripcion' => ['label' => 'Descripción', 'type' => 'text', 'rules' => 'nullable|max:255'],
            'estado'      => ['label' => 'Estado', 'type' => 'select', 'options' => ['activo', 'inactivo'], 'rules' => 'required'],
        ],
    ],

    'asignaciones' => [
        'titulo' => 'Asignaciones', 'singular' => 'Asignación', 'model' => Asignacion::class,
        'campos' => [
            'empleado_id'  => ['label' => 'Empleado', 'type' => 'fk', 'model' => Empleado::class, 'mostrar' => ['nombre', 'apellido'], 'rules' => 'required'],
            'estacion_id'  => ['label' => 'Estación', 'type' => 'fk', 'model' => Estacion::class, 'mostrar' => ['nombre'], 'rules' => 'required'],
            'turno_id'     => ['label' => 'Turno', 'type' => 'fk', 'model' => Turno::class, 'mostrar' => ['nombre'], 'rules' => 'required'],
            'servicio_id'  => ['label' => 'Servicio', 'type' => 'fk', 'model' => Servicio::class, 'mostrar' => ['nombre'], 'rules' => 'required'],
            'fecha_inicio' => ['label' => 'Fecha de inicio', 'type' => 'date', 'rules' => 'required|date'],
            'fecha_fin'    => ['label' => 'Fecha de fin', 'type' => 'date', 'rules' => 'nullable|date|after_or_equal:fecha_inicio'],
            'estado'       => ['label' => 'Estado', 'type' => 'select', 'options' => ['activa', 'finalizada', 'cancelada'], 'rules' => 'required'],
        ],
    ],

    'eventualidades' => [
        'titulo' => 'Eventualidades', 'singular' => 'Eventualidad', 'model' => Eventualidad::class,
        'campos' => [
            'empleado_id'  => ['label' => 'Empleado', 'type' => 'fk', 'model' => Empleado::class, 'mostrar' => ['nombre', 'apellido'], 'rules' => 'required'],
            'tipo'         => ['label' => 'Tipo', 'type' => 'select', 'options' => ['vacaciones', 'licencia_medica', 'permiso', 'ausencia_justificada'], 'rules' => 'required'],
            'fecha_inicio' => ['label' => 'Fecha de inicio', 'type' => 'date', 'rules' => 'required|date'],
            'fecha_fin'    => ['label' => 'Fecha de fin', 'type' => 'date', 'rules' => 'required|date|after_or_equal:fecha_inicio'],
            'descripcion'  => ['label' => 'Descripción', 'type' => 'text', 'rules' => 'nullable|max:255'],
            'estado'       => ['label' => 'Estado', 'type' => 'select', 'options' => ['pendiente', 'aprobada', 'rechazada'], 'rules' => 'required'],
        ],
    ],

    'asistencias' => [
        'titulo' => 'Asistencia', 'singular' => 'Asistencia', 'model' => Asistencia::class,
        'campos' => [
            'empleado_id' => ['label' => 'Empleado', 'type' => 'fk', 'model' => Empleado::class, 'mostrar' => ['nombre', 'apellido'], 'rules' => 'required'],
            'fecha'       => ['label' => 'Fecha', 'type' => 'date', 'rules' => 'required|date'],
            'estado'      => ['label' => 'Estado', 'type' => 'select', 'options' => ['presente', 'ausente', 'tardanza', 'justificado'], 'rules' => 'required'],
            'observacion' => ['label' => 'Observación', 'type' => 'text', 'rules' => 'nullable|max:255'],
        ],
    ],

    'reservas' => [
        'titulo' => 'Personal de reserva', 'singular' => 'Reserva', 'model' => Reserva::class,
        'campos' => [
            'empleado_id'    => ['label' => 'Empleado', 'type' => 'fk', 'model' => Empleado::class, 'mostrar' => ['nombre', 'apellido'], 'rules' => 'required'],
            'fecha_inicio'   => ['label' => 'Fecha de inicio', 'type' => 'date', 'rules' => 'required|date'],
            'fecha_fin'      => ['label' => 'Fecha de fin', 'type' => 'date', 'rules' => 'required|date|after_or_equal:fecha_inicio'],
            'disponibilidad' => ['label' => 'Disponibilidad', 'type' => 'select', 'options' => ['disponible', 'asignado', 'no_disponible'], 'rules' => 'required'],
            'observacion'    => ['label' => 'Observación', 'type' => 'text', 'rules' => 'nullable|max:255'],
        ],
    ],

    'historial' => [
        'titulo' => 'Historial', 'singular' => 'Historial', 'model' => Historial::class, 'solo_lectura' => true,
        'campos' => [
            'accion'         => ['label' => 'Acción', 'type' => 'text'],
            'tabla_afectada' => ['label' => 'Módulo', 'type' => 'text'],
            'detalle'        => ['label' => 'Detalle', 'type' => 'textarea'],
            'usuario'        => ['label' => 'Usuario', 'type' => 'text'],
        ],
    ],

];
