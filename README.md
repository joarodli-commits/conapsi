# CONAPSI - Control y Administración Operativa del Sistema

Sistema web para la administración y el control operativo del personal del Metro de Santo Domingo.
Proyecto final de la asignatura **Desarrollo de Software Libre** (Universidad Abierta Para Adultos, UAPA).

## Tecnologías (software libre)

- Sistema operativo: Ubuntu Server (GNU/Linux) en una máquina virtual VirtualBox
- Servidor web: Apache HTTP Server 2.4
- Lenguaje y framework: PHP y Laravel
- Base de datos: MySQL
- Seguridad: firewall UFW (solo puertos 22, 80 y 443) y acceso remoto por SSH

## Módulos del sistema

Cada módulo tiene su CRUD completo (listar, buscar, crear, editar y eliminar):

- Empleados
- Líneas
- Estaciones
- Turnos
- Servicios
- Asignaciones
- Eventualidades (vacaciones, licencias, permisos, ausencias)
- Asistencia
- Personal de reserva
- Historial (solo lectura, registra automáticamente cada cambio)

Incluye inicio de sesión con usuario y contraseña, validación de formularios y claves foráneas que protegen la integridad de los datos.

## Base de datos

Los scripts están en `database/sql/`:

- `conapsi.sql`: crea la base de datos `conapsi`, las 11 tablas, claves primarias, claves foráneas y restricciones.
- `datos.sql`: datos de ejemplo (ficticios) para probar el sistema.

## Instalación

Requisitos: Apache, PHP 8.2 o superior con las extensiones mysql, mbstring, xml, curl, zip y bcmath, MySQL y Composer.

1. Clonar el repositorio y entrar a la carpeta del proyecto.
2. Instalar dependencias: `composer install`
3. Copiar la configuración: `cp .env.example .env` y generar la llave con `php artisan key:generate`
4. Crear la base de datos y cargar los scripts:

        sudo mysql < database/sql/conapsi.sql
        sudo mysql < database/sql/datos.sql

5. En `.env` configurar `DB_CONNECTION=mysql`, `DB_HOST=127.0.0.1`, `DB_PORT=3306`, `DB_DATABASE=conapsi`, `DB_USERNAME` y `DB_PASSWORD` (el usuario de MySQL con acceso a la base `conapsi`). También `SESSION_DRIVER=file`, `CACHE_STORE=file` y `QUEUE_CONNECTION=sync`.
6. Crear el usuario administrador: `php artisan conapsi:admin`
7. Apuntar el VirtualHost de Apache a la carpeta `public` y activar `mod_rewrite`.

## Acceso de demostración

- Correo: `admin@conapsi.local`
- Contraseña: `Admin2026`

Son credenciales de práctica; en un entorno real deben cambiarse.

## Estructura principal

- `app/Models`: modelos de cada tabla
- `app/Http/Controllers`: controladores (autenticación y CRUD de módulos)
- `config/modulos.php`: definición de campos y relaciones de cada módulo
- `resources/views`: vistas Blade (login, inicio, listado y formularios)
- `routes/web.php`: rutas de la aplicación
- `database/sql`: scripts SQL

## Equipo (Grupo 1)

Alexander Cuello Hernández, Carla Virginia Feliz Santos, Antony Felix Mora Valdez, Jose Aníbal Rodríguez Liriano, Kevin Andrés González Polanco, Ismael Peña de Jesús, Raúl Leonardo y Roger Suberví Martínez.

Facilitador: Maikel Aparicio Reytor.
