# Buscaminas

Desafio 1 de la asignatura Desarrollo Web en Servidores.

## Objetivos

-   [x] Definir la Base de Datos.
-   [x] Establecer una conexión a la Base de Datos.
-   [x] Crear las clases Usuario, Partida (sin funciones) y Factoria.
-   [x] Definir la estructura del servicio.
-   [x] Crear un login funcional (incluido hash). Diferenciar Admin.
-   [x] CRUD de Usuarios en el panel de Administración.
-   [ ] Crear un tablero, guardarlo en la BBDD y retornarlo.
-   [ ] Comprobar partidas abiertas antes de crear un tablero nuevo.
-   [ ] Abrir casillas, actualizarlas en la BBDD y mostrarlas al usuario.
-   [ ] Crear condición de victoria. Cerrar partidas.
-   [ ] Rendirse (cerrar partidas voluntariamente).
-   [ ] Solicitud de cambio de contraseña.

### Opcional

-   [ ] Tener varias partidas abiertas por usuario.

## Estructura de la Base de Datos

La Base de Datos sigue la siguiente estructura:

### Jugador

| ID     | Nombre       | email        | pass         | partidasJugadas | partidasGanadas | es_admin |
| ------ | ------------ | ------------ | ------------ | --------------- | --------------- | -------- |
| int(3) | varchar(100) | varchar(100) | varchar(100) | int(3)          | int(3)          | boolean  |

### Partida

| ID     | IDjugador | tableroSolucion | tableroJugador | fin    |
| ------ | --------- | --------------- | -------------- | ------ |
| int(3) | int(3)    | varchar(100)    | varchar(100)   | int(2) |

Puede crearse la base de datos con el fichero `database_structure.sql`.

## Formato de JSON esperado

```json
{
    "email": "tucorreo@email.com",
    "pass": "tucontraseña",
    "casilla": "posicion"
}
```

La `posicion` de la casilla es opcional (no es necesaria para las funciones de administrador o crear partidas, por ejemplo).
