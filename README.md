# Buscaminas

Desafio 1 de la asignatura Desarrollo Web en Servidores.

## Objetivos ![](https://geps.dev/progress/40)

-   [x] Definir la Base de Datos.
-   [x] Establecer una conexión a la Base de Datos.
-   [x] Crear las clases Usuario, Partida (sin funciones) y Factoria.
-   [x] Definir la estructura del servicio.
-   [x] Crear un login funcional (incluido hash). Diferenciar Admin.
-   [ ] CRUD de Usuarios en el panel de Administración.
    -   [x] Crear
    -   [x] Leer
    -   [ ] Modificar
    -   [x] Eliminar
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

| ID     | Nombre       | email        | pass         | partidasJugadas | partidasGanadas |
| ------ | ------------ | ------------ | ------------ | --------------- | --------------- |
| int(3) | varchar(100) | varchar(100) | varchar(100) | int(3)          | int(3)          |

### Partida

| ID     | IDjugador | tableroSolucion | tableroJugador | fin    |
| ------ | --------- | --------------- | -------------- | ------ |
| int(3) | int(3)    | varchar(100)    | varchar(100)   | int(2) |

Puede crearse la base de datos con el fichero `database_structure.sql`.

## JSON y rutas esperadas

### GET /admin
Para leer la lista de jugadores en la BBDD.

```json
{
    "email": "tucorreo@email.com",
    "pass": "tucontraseña"
}
```
Como en el resto de casos en la ruta /admin, sólo serán satisfactorias las llamadas a la API si estos datos son de un administrador.

### POST /admin
Para crear un nuevo usuario.

```json
{
    "email": "tucorreo@email.com",
    "pass": "tucontraseña",
    "nombre" : "nombreUsuario",
    "user-email": "email@usuario.com",
    "user-pass": "contraseñaUsuario"
}
```
El jugador creado no será administrador y comenzará con 0 partidas jugadas y ganadas.
Su contraseña se almacenará en la BBDD con un hash MD5.

### DELETE /admin
Para eliminar un usuario dada su ID.

```json
{
    "email": "tucorreo@email.com",
    "pass": "tucontraseña",
    "id": "idUsuario",
}
```
