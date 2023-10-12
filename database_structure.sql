CREATE DATABASE buscaminas;

USE buscaminas;

CREATE TABLE jugador (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    email VARCHAR(100),
    pass VARCHAR(100),
    partidasJugadas INT(3) DEFAULT 0,
    partidasGanadas INT(3) DEFAULT 0,
    es_admin BOOLEAN DEFAULT 0
);

CREATE TABLE partida (
    id INT(3) AUTO_INCREMENT PRIMARY KEY,
    idJugador INT(3),
    tableroSolucion VARCHAR(100),
    tableroJugador VARCHAR(100),
    fin INT(2),
    FOREIGN KEY (idJugador) REFERENCES jugador(id)
);