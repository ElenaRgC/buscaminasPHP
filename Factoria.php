<?php

require_once 'model/Jugador.php';
require_once 'model/Partida.php';

class Factoria {

    public static function crearJugador($id, $nombre, $email, $partidasJugadas, $partidasGanadas) {
        return new Jugador($id, $nombre, $email, $partidasJugadas, $partidasGanadas);
    }

}