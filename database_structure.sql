CREATE TABLE jugador(
    id int(3) PRIMARY KEY,
    nombre varchar(20),
    email varchar(20) REQUIRED,
    partidasJugadas int(3),
    partidasGanadas int(3)
);

CREATE TABLE partida(
    id varchar(3) PRIMARY KEY,
    idJugador int(3),
    tableroSolucion varchar(20),
    tableroJugador varchar(20),
    fin int(2)
);