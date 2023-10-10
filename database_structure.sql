CREATE DATABASE buscaminas;

USE buscaminas;

CREATE TABLE jugador (
    id INT(3) PRIMARY KEY,
    nombre VARCHAR,
    email VARCHAR,
    pass VARCHAR,
    partidasJugadas INT(3),
    partidasGanadas INT(3),
    es_admin BOOLEAN
);

CREATE TABLE partida (
    id VARCHAR(3) PRIMARY KEY,
    idJugador INT(3),
    tableroSolucion VARCHAR,
    tableroJugador VARCHAR,
    fin INT(2),
    FOREIGN KEY (idJugador) REFERENCES jugador(id)
);