<?php

require_once __DIR__.'/../model/Jugador.php';
require_once __DIR__.'/../model/Partida.php';

class Factoria
{
    public static function crearJugador($id, $nombre, $email, $pass, $partidasJugadas, $partidasGanadas, $esAdmin)
    {
        return new Jugador($id, $nombre, $email, $pass, $partidasJugadas, $partidasGanadas, $esAdmin);
    }

    public static function crearPartida($id, $idJugador, $tableroSolucion, $tableroJugador, $fin)
    {
        return new Partida($id, $idJugador, $tableroSolucion, $tableroJugador, $fin);
    }
}
