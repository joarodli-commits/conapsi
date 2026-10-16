USE conapsi;

INSERT INTO lineas (nombre, descripcion) VALUES
('Línea 1', 'Línea 1 del Metro de Santo Domingo'),
('Línea 2', 'Línea 2 del Metro de Santo Domingo');

INSERT INTO estaciones (linea_id, nombre) VALUES
(1, 'Mamá Tingó'),
(1, 'Hermanas Mirabal'),
(1, 'Máximo Gómez'),
(1, 'Centro de los Héroes'),
(2, 'Concepción Bona'),
(2, 'Manuel de Jesús Galván');

INSERT INTO turnos (nombre, hora_inicio, hora_fin, descripcion) VALUES
('Mañana', '05:00:00', '13:00:00', 'Turno matutino'),
('Tarde',  '13:00:00', '21:00:00', 'Turno vespertino'),
('Noche',  '21:00:00', '05:00:00', 'Turno nocturno');

INSERT INTO servicios (nombre, descripcion, estado) VALUES
('Conducción de trenes', 'Operación de trenes en línea', 'activo'),
('Operación de estación', 'Control y atención en estación', 'activo'),
('Supervisión de línea', 'Supervisión operativa de la línea', 'activo'),
('Atención al usuario', 'Orientación y asistencia a pasajeros', 'activo');

INSERT INTO empleados (codigo, nombre, apellido, cargo, telefono, estado) VALUES
('1001', 'Luis', 'Martínez', 'Conductor', '809-555-0101', 'activo'),
('1002', 'Ana', 'Pérez', 'Operador de estación', '809-555-0102', 'activo'),
('1003', 'Carlos', 'Gómez', 'Agente de explotación', '809-555-0103', 'activo'),
('1004', 'María', 'Rodríguez', 'Responsable de línea', '809-555-0104', 'activo'),
('1005', 'Pedro', 'Santana', 'Conductor', '809-555-0105', 'licencia'),
('1006', 'Rosa', 'Díaz', 'Operador de estación', '809-555-0106', 'activo');

INSERT INTO asignaciones (empleado_id, estacion_id, turno_id, servicio_id, fecha_inicio, fecha_fin, estado) VALUES
(1, 1, 1, 1, '2026-10-01', NULL, 'activa'),
(2, 3, 2, 2, '2026-10-01', NULL, 'activa'),
(3, 4, 1, 4, '2026-10-05', NULL, 'activa'),
(4, 2, 3, 3, '2026-10-01', '2026-10-31', 'activa');

INSERT INTO eventualidades (empleado_id, tipo, fecha_inicio, fecha_fin, descripcion, estado) VALUES
(5, 'vacaciones', '2026-10-10', '2026-10-24', 'Vacaciones programadas', 'aprobada');

INSERT INTO asistencias (empleado_id, fecha, estado, observacion) VALUES
(1, '2026-10-15', 'presente', NULL),
(2, '2026-10-15', 'tardanza', 'Llegó 15 minutos tarde');

INSERT INTO reservas (empleado_id, fecha_inicio, fecha_fin, disponibilidad, observacion) VALUES
(6, '2026-10-15', '2026-10-30', 'disponible', 'Disponible para cubrir ausencias');
