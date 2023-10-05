# Buscaminas

Desafio 1 de la asignatura Desarrollo Web en Servidores.

## Estructura de la Base de Datos

La Base de Datos sigue la siguiente estructura.

### Jugador

| ID     | Nombre      | email       | partidasGanadas | partidasJugadas |
| ------ | ----------- | ----------- | --------------- | --------------- |
| int(3) | varchar(20) | varchar(20) | int(3)          | int(3)          |

### Partida

| ID     | IDjugador | tableroSolucion | tableroJugador | fin    |
| ------ | --------- | --------------- | -------------- | ------ |
| int(3) | int(3)    | varchar(20)     | varchar(20)    | int(3) |

Pueden crearse la base de datos con el fichero `database_structure.sql`.
