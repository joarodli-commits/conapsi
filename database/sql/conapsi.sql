CREATE DATABASE IF NOT EXISTS conapsi
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE conapsi;

CREATE TABLE users (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  remember_token VARCHAR(100) NULL,
  created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL
) ENGINE=InnoDB;

CREATE TABLE lineas (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL UNIQUE,
  descripcion VARCHAR(255) NULL,
  created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL
) ENGINE=InnoDB;

CREATE TABLE estaciones (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  linea_id BIGINT UNSIGNED NOT NULL,
  nombre VARCHAR(100) NOT NULL,
  ubicacion VARCHAR(150) NULL,
  created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL,
  CONSTRAINT fk_estaciones_linea FOREIGN KEY (linea_id)
    REFERENCES lineas(id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE turnos (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(50) NOT NULL UNIQUE,
  hora_inicio TIME NOT NULL,
  hora_fin TIME NOT NULL,
  descripcion VARCHAR(255) NULL,
  created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL
) ENGINE=InnoDB;

CREATE TABLE servicios (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL UNIQUE,
  descripcion VARCHAR(255) NULL,
  estado ENUM('activo','inactivo') NOT NULL DEFAULT 'activo',
  created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL
) ENGINE=InnoDB;

CREATE TABLE empleados (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  codigo VARCHAR(20) NOT NULL UNIQUE,
  nombre VARCHAR(50) NOT NULL,
  apellido VARCHAR(50) NOT NULL,
  cargo VARCHAR(80) NOT NULL,
  telefono VARCHAR(20) NULL,
  estado ENUM('activo','inactivo','licencia') NOT NULL DEFAULT 'activo',
  created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL
) ENGINE=InnoDB;

CREATE TABLE asignaciones (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  empleado_id BIGINT UNSIGNED NOT NULL,
  estacion_id BIGINT UNSIGNED NOT NULL,
  turno_id BIGINT UNSIGNED NOT NULL,
  servicio_id BIGINT UNSIGNED NOT NULL,
  fecha_inicio DATE NOT NULL,
  fecha_fin DATE NULL,
  estado ENUM('activa','finalizada','cancelada') NOT NULL DEFAULT 'activa',
  created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL,
  CONSTRAINT fk_asig_empleado FOREIGN KEY (empleado_id) REFERENCES empleados(id) ON DELETE CASCADE,
  CONSTRAINT fk_asig_estacion FOREIGN KEY (estacion_id) REFERENCES estaciones(id) ON DELETE RESTRICT,
  CONSTRAINT fk_asig_turno    FOREIGN KEY (turno_id)    REFERENCES turnos(id)     ON DELETE RESTRICT,
  CONSTRAINT fk_asig_servicio FOREIGN KEY (servicio_id) REFERENCES servicios(id)  ON DELETE RESTRICT,
  CONSTRAINT ck_asig_fechas CHECK (fecha_fin IS NULL OR fecha_fin >= fecha_inicio)
) ENGINE=InnoDB;

CREATE TABLE eventualidades (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  empleado_id BIGINT UNSIGNED NOT NULL,
  tipo ENUM('vacaciones','licencia_medica','permiso','ausencia_justificada') NOT NULL,
  fecha_inicio DATE NOT NULL,
  fecha_fin DATE NOT NULL,
  descripcion VARCHAR(255) NULL,
  estado ENUM('pendiente','aprobada','rechazada') NOT NULL DEFAULT 'pendiente',
  created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL,
  CONSTRAINT fk_event_empleado FOREIGN KEY (empleado_id) REFERENCES empleados(id) ON DELETE CASCADE,
  CONSTRAINT ck_event_fechas CHECK (fecha_fin >= fecha_inicio)
) ENGINE=InnoDB;

CREATE TABLE asistencias (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  empleado_id BIGINT UNSIGNED NOT NULL,
  fecha DATE NOT NULL,
  estado ENUM('presente','ausente','tardanza','justificado') NOT NULL,
  observacion VARCHAR(255) NULL,
  created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL,
  CONSTRAINT fk_asist_empleado FOREIGN KEY (empleado_id) REFERENCES empleados(id) ON DELETE CASCADE,
  CONSTRAINT uq_asist_dia UNIQUE (empleado_id, fecha)
) ENGINE=InnoDB;

CREATE TABLE reservas (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  empleado_id BIGINT UNSIGNED NOT NULL,
  fecha_inicio DATE NOT NULL,
  fecha_fin DATE NOT NULL,
  disponibilidad ENUM('disponible','asignado','no_disponible') NOT NULL DEFAULT 'disponible',
  observacion VARCHAR(255) NULL,
  created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL,
  CONSTRAINT fk_reserva_empleado FOREIGN KEY (empleado_id) REFERENCES empleados(id) ON DELETE CASCADE,
  CONSTRAINT ck_reserva_fechas CHECK (fecha_fin >= fecha_inicio)
) ENGINE=InnoDB;

CREATE TABLE historial (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  empleado_id BIGINT UNSIGNED NULL,
  accion VARCHAR(50) NOT NULL,
  tabla_afectada VARCHAR(50) NOT NULL,
  detalle TEXT NULL,
  usuario VARCHAR(100) NULL,
  created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL,
  CONSTRAINT fk_hist_empleado FOREIGN KEY (empleado_id) REFERENCES empleados(id) ON DELETE SET NULL
) ENGINE=InnoDB;
