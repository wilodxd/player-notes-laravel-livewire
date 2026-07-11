# Player Notes - Historial de Notas de Jugador

Módulo de backoffice para que los agentes de soporte registren y consulten notas internas sobre jugadores de la plataforma. Cada nota queda asociada al jugador que describe y al agente que la escribió, con su fecha de creación.

Prueba técnica para el rol de Technical Lead Laravel/PHP.

## Stack

- PHP 8.3
- Laravel 13
- Livewire 4 con Flux UI (starter kit oficial)
- Pest para tests

## Instalación

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm install
composer run dev
```

El seeder crea dos usuarios para probar el sistema de permisos:

| Correo | Contraseña | Rol |
|---|---|---|
| soporte@correo.com | password | support (puede crear notas) |
| invitado@correo.com | password | viewer (solo lectura) |

También se crean jugadores y notas de ejemplo.

## Arquitectura

El flujo de una petición es: componente Livewire -> repositorio (vía interfaz) -> Eloquent.

- **Patrón Repositorio.** El acceso a datos vive en `app/Repositories`, detrás de interfaces (`app/Repositories/Contracts`) enlazadas en el service container (`AppServiceProvider`). El componente Livewire depende de la abstracción, no de la implementación, lo que permite sustituir la persistencia o mockearla en tests sin tocar el componente.
- **Sin capa de servicio.** Se evaluó agregarla y se descartó: la única lógica del módulo es validar y persistir, por lo que un servicio sería una capa de paso (`return $this->repository->create($data)`) que agrega indirección sin aportar valor. Si el módulo creciera (notificaciones al crear una nota, reglas de negocio sobre el contenido), ese sería el momento de introducirla.
- **DTO.** `PlayerNoteData` (`final readonly`, constructor promotion) transporta los datos del componente al repositorio con tipos explícitos, en lugar de arrays asociativos.
- **Inyección en Livewire.** El repositorio se inyecta en `boot()` sobre una propiedad protegida, porque las dependencias no serializables no pueden viajar en el payload que Livewire hidrata entre requests.

## Decisiones de diseño

1. **Se reutiliza el modelo `User` de Laravel** para los agentes de soporte. Cumple con lo necesario (autenticación, nombre para mostrar como autor); crear un modelo de staff aparte habría sido sobreingeniería para este alcance. Los jugadores sí son un modelo propio (`Player`), porque en el dominio real el staff y los clientes de la plataforma son entidades distintas.
2. **Las notas son inmutables por diseño.** Funcionan como registro de auditoría: la policy niega `update` y `delete`, y las foreign keys de `player_notes` restringen el borrado de jugadores y usuarios con notas asociadas. En producción evaluaría soft deletes para `Player` y `User`, pero borrar esos modelos está fuera del alcance de este módulo. `updated_at` se mantiene porque es el comportamiento por defecto del framework y no molesta.
3. **Permisos con Policy nativa en lugar de spatie/laravel-permission.** El requerimiento es un único permiso (crear notas), así que un paquete de roles y permisos completo sería desproporcionado. `PlayerNotePolicy::create()` decide en función del rol del usuario, un enum respaldado (`UserRole`) casteado en el modelo. La visibilidad del botón se controla con `@can` en la vista, y la acción `save()` vuelve a autorizar en el backend: ocultar la UI no es autorizar, las acciones de Livewire son endpoints HTTP invocables aunque el botón no exista.
4. **Largo máximo del contenido con una sola fuente de verdad.** `PlayerNote::CONTENT_MAX_LENGTH` define tanto el tamaño de la columna en la migración como la regla de validación, de modo que no puedan divergir.
5. **Listado global con filtro por jugador.** El componente muestra el historial completo y permite filtrar por un jugador específico desde un selector. En un backoffice real este componente viviría embebido en la ficha del jugador recibiendo el player por parámetro; se optó por la vista global con filtro para que el módulo sea navegable por sí solo en esta prueba. Con volumen real de datos el listado se paginaría.

## Tests

```bash
php artisan test tests/Feature/PlayerNotesTest.php
```

Cubren el módulo completo:

- Un usuario support guarda una nota y queda persistida en base de datos.
- Contenido vacío falla la validación y no persiste nada.
- Contenido sobre el largo máximo falla la validación.
- Un usuario viewer recibe 403 al intentar guardar (autorización de backend, no solo UI).
- El botón de nueva nota solo es visible para usuarios con rol support.
