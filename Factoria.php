<?php

require_once 'model/Jugador.php';
require_once 'model/Partida.php';

class Factoria {

    public static function crearJugador($id, $nombre, $email, $partidasJugadas, $partidasGanadas, $esAdmin) {
        return new Jugador($id, $nombre, $email, $partidasJugadas, $partidasGanadas, $esAdmin);
    }

    public static function crearPartida($id, $idJugador, $tableroSolucion, $tableroJugador, $fin) {
        return new Partida($id, $idJugador, $tableroSolucion, $tableroJugador, $fin);
    }

}