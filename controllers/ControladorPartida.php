<?php

require_once __DIR__.'\..\auxiliar\Conexion.php';
require_once __DIR__.'\..\auxiliar\Factoria.php';

class ControladorPartida
{
    public static function insertPartida($idJugador, $longitud = 10, $bombas = 2)
    {
        $partidasAbiertas = ControladorPartida::getPartidasAbiertas($idJugador);

        if ($partidasAbiertas == 0) {
            $partida = Factoria::crearPartidaNueva($idJugador, $longitud, $bombas);

            if (Conexion::insertPartida($partida)) {
                $cod = 201;
                $mes = 'Partida creada';

                header('HTTP/1.1 '.$cod.' '.$mes);

                return json_encode(['Codigo' => $cod, 'Mensaje' => $mes,
                'Partida' => ControladorPartida::getPartidaReciente($idJugador)]);
            } else {
                $cod = 500;
                $mes = 'Error en la base de datos.';

                header('HTTP/1.1 '.$cod.' '.$mes);

                return json_encode(['Codigo' => $cod, 'Mensaje' => $mes]);
            }
        } else {
            $cod = 204;
            $mes = 'Ya hay partidas abiertas.';

            header('HTTP/1.1 '.$cod.' '.$mes);

            return json_encode(['Codigo' => $cod, 'Mensaje' => $mes,
            'Partidas Abiertas' => $partidasAbiertas]);
        }
    }

    public static function getPartidasAbiertas($idJugador)
    {
        $partidas = Conexion::getPartidasAbiertas($idJugador);

        if ($partidas[0] instanceof Partida) {
            return $partidas;
        } else {
            return 0;
        }
    }

    public static function getPartidabyId($id, $idJugador)
    {
        $partida = Conexion::getPartidabyId($id);

        if ($partida instanceof Partida) {
            return $partida;
        } else {
            return 0;
        }
    }

    public static function getPartidaReciente($idJugador)
    {
        $ultimaPartida = Conexion::getPartidaReciente($idJugador);

        if ($ultimaPartida instanceof Partida) {
            return $ultimaPartida;
        } else {
            return 0;
        }
    }

    public static function abrirCasilla($casilla, $idJugador, $idPartida = 0)
    {
        if ($idPartida != 0) {
            $partida = ControladorPartida::getPartidabyId($idPartida, $idJugador);
        } else {
            $partida = ControladorPartida::getPartidaReciente($idJugador);
        }

        if ($partida instanceof Partida) {
            $tableroNuevo = $partida->getTableroJugador();
            $tableroNuevo[$casilla - 1] = $partida->getTableroSolucion()[$casilla - 1];

            $partida->setTableroJugador($tableroNuevo);

            if (Conexion::updateTableroJugador($partida)) {
                $cod = 200;
                $mes = 'OK';

                header('HTTP/1.1 '.$cod.' '.$mes);

                return json_encode(['Codigo' => $cod, 'Mensaje' => $mes, 'Partida' => $partida]);
            } else {
                $cod = 500;
                $mes = 'Error';

                header('HTTP/1.1 '.$cod.' '.$mes);

                return json_encode(['Codigo' => $cod, 'Mensaje' => $mes]);
            }
        } else {
            $cod = 201;
            $mes = 'No hay partidas abiertas.';

            header('HTTP/1.1 '.$cod.' '.$mes);

            return json_encode(['Codigo' => $cod, 'Mensaje' => $mes,
            'Partidas abiertas' => ControladorPartida::getPartidasAbiertas($idJugador)]);
        }
    }
}
