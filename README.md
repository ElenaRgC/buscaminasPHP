# Buscaminas

**Desafio 1 de la asignatura Desarrollo Web en Servidores.**

Este desafío consiste en la creación de un servicio web para gestionar partidas de buscaminas en una dimensión con PHP y mySQL.
Habrá dos tipos de usuarios: jugadores y administradores, donde los segundos podrán realizar todas las acciones que haga el primero, pero no en caso contrario.

- Los jugadores podrán crear partidas, abrir casillas, rendirse, solicitar un cambio de contraseña y ver el ranking de jugadores.
- Los administradores podrán crear, buscar, modificar y eliminar usuarios, así como todas las funciones anteriores.

Opcionalmente se pide que los usuarios puedan tener abiertas varias partidas a la vez. En esta solución se ha resuelto de forma que, por defecto, un jugador acceda a la última partida que ha creado o a una partida de la que conozca su ID. Si intenta abrir una casilla en una partida que no está abierta o no existe, se le mostrará un listado de las partidas abiertas bajo su ID

## Contenido

- [Objetivos](#objetivos)
- [Estructura de la Base de Datos](#estructura-de-la-base-de-datos)
- [Rutas admitidas y JSON esperados](#rutas-admitidas-y-json-esperados)
  - [Administrador](#administrador)
  - [Jugador](#jugador-1)

## Objetivos
![](https://geps.dev/progress/77)

-   [x] Definir la Base de Datos.
-   [x] Establecer una conexión a la Base de Datos.
-   [x] Crear las clases Usuario, Partida (sin funciones) y Factoria.
-   [x] Definir la estructura del servicio.
-   [x] Crear un login funcional (incluido hash). Diferenciar Admin.
-   [x] CRUD de Usuarios en el panel de Administración.
    -   [x] Crear
    -   [x] Leer
    -   [x] Modificar
    -   [x] Eliminar
-   [x] Crear un tablero, guardarlo en la BBDD y retornarlo.
-   [x] Comprobar partidas abiertas antes de crear un tablero nuevo.
-   [x] Abrir casillas, actualizarlas en la BBDD y mostrarlas al usuario.
-   [ ] Crear condición de victoria. Cerrar partidas.
-   [ ] Rendirse (cerrar partidas voluntariamente).
-   [ ] Solicitud de cambio de contraseña.

### Opcional

-   [x] Tener varias partidas abiertas por usuario.

## Estructura de la Base de Datos

La Base de Datos sigue la siguiente estructura:

### Jugador

| ID     | Nombre       | email        | pass         | partidasJugadas | partidasGanadas | es-admin |
| ------ | ------------ | ------------ | ------------ | --------------- | --------------- | -------- |
| int(3) | varchar(100) | varchar(100) | varchar(100) | int(3)          | int(3)          | boolean  |

### Partida

| ID     | IDjugador | tableroSolucion | tableroJugador | fin    |
| ------ | --------- | --------------- | -------------- | ------ |
| int(3) | int(3)    | varchar(100)    | varchar(100)   | int(2) |

Puede crearse la base de datos con el fichero `database_structure.sql`.

## Rutas admitidas y JSON esperados

### Administrador

#### GET /admin

-   Para leer la lista de jugadores en la BBDD.

```json
{
    "email": "tucorreo@email.com",
    "pass": "tucontraseña"
}
```

Como en el resto de casos en la ruta /admin, sólo serán satisfactorias las llamadas a la API si estos datos son de un administrador.

#### POST /admin

-   Para crear un nuevo usuario.

```json
{
    "email": "tucorreo@email.com",
    "pass": "tucontraseña",
    "nombre": "nombreUsuario",
    "user-email": "email@usuario.com",
    "user-pass": "contraseñaUsuario"
}
```

El jugador creado no será administrador y comenzará con 0 partidas jugadas y ganadas.
Su contraseña se almacenará en la BBDD con un hash MD5.

#### PUT /admin

-   Para modificar el nombre y el email de un usuario.

```json
{
    "email": "tucorreo@email.com",
    "pass": "tucontraseña",
    "id": "IDusuario",
    "nombre": "nombreUsuario",
    "user-email": "email@usuario.com"
}
```

No se modificarán ni su id o número de partidas jugadas y/o ganadas.

-   Para modificar la contraseña de un usuario.

```json
{
    "email": "tucorreo@email.com",
    "pass": "tucontraseña",
    "id": "IDusuario",
    "user-pass": "nuevaConstraseña"
}
```

Su contraseña se almacenará en la BBDD con un hash MD5.

#### DELETE /admin

-   Para eliminar un usuario dada su ID.

```json
{
    "email": "tucorreo@email.com",
    "pass": "tucontraseña",
    "id": "idUsuario"
}
```

### Jugador

#### GET /jugar

-   Para crear una partida de longitud y bombas por defecto.

```json
{
    "email": "tucorreo@email.com",
    "pass": "tucontraseña"
}
```

#### GET /jugar/longitud/minas

-   Para crear una partida de longitud y bombas determinadas.

```json
{
    "email": "tucorreo@email.com",
    "pass": "tucontraseña"
}
```

#### POST /jugar

-   Para jugar en la última partida creada que esté abierta.

```json
{
    "email": "tucorreo@email.com",
    "pass": "tucontraseña",
    "casilla": 1
}
```

-   Para jugar en una partida conocido su ID.

```json
{
    "email": "tucorreo@email.com",
    "pass": "tucontraseña",
    "id": "IDpartida",
    "casilla": 1
}
```

Si el ID no se corresponde a una partida abierta, se devolverá una lista con las partidas abiertas de ese jugador.
