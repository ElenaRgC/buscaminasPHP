# Buscaminas

Desafio 1 de la asignatura Desarrollo Web en Servidores.

## Objetivos ![](https://geps.dev/progress/77)

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

-   Para modificar el nombre y el email de un usuario.

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

#### PUT /jugar

-   Para rendirse en la última partida creada que esté abierta.

```json
{
    "email": "tucorreo@email.com",
    "pass": "tucontraseña",
    "fin": true
}
```

Aceptará cualquier valor distinto de 0 o `false`.

-   Para rendirse en una partida conocida su ID.

```json
{
    "email": "tucorreo@email.com",
    "pass": "tucontraseña",
    "id": "IDpartida",
    "fin": 1
}
```

Aceptará cualquier valor distinto de 0 o `false`.
