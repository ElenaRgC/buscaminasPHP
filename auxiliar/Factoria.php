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

    public static function crearPartidaNueva($idJugador, $longitud, $bombas)
    {
        $tableroSolucion = Factoria::crearTableroSolucion($longitud, $bombas);
        $tableroJugador = Factoria::crearTableroJugador($longitud);

        return Factoria::crearPartida(0, $idJugador, $tableroSolucion, $tableroJugador, 0);
    }

    private static function crearTableroSolucion($longitud, $bombas)
    {
        $tablero = array_fill(1, $longitud, 0);

        while ($bombas > 0) {
            $rand = rand(1, $longitud);

            if ($tablero[$rand] != '*') {
                $tablero[$rand] = '*';
                --$bombas;
            }
        }

        for ($i = 1; $i <= $longitud; ++$i) {
            if ($tablero[$i] != '*') {
                if ($i > 1 && $tablero[$i - 1] == '*') {
                    ++$tablero[$i];
                }
                if ($i < $longitud && $tablero[$i + 1] == '*') {
                    ++$tablero[$i];
                }
            }
        }

        return implode('', $tablero);
    }

    private static function crearTableroJugador($longitud)
    {
        return implode('', array_fill(1, $longitud, '-'));
    }
}
