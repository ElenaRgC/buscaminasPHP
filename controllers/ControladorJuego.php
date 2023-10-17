<?php

require_once __DIR__.'\..\auxiliar\Conexion.php';
require_once __DIR__.'\..\auxiliar\Factoria.php';

class ControladorJuego
{
    public static function login($datosRecibidos)
    {
        $jugador = Conexion::getJugadorFromEmail($datosRecibidos['email']);

        if ($jugador instanceof Jugador) {
            if ($jugador->getEmail() == $datosRecibidos['email'] && $jugador->getPass() == md5($datosRecibidos['pass'])) {
                return $jugador;
            } elseif ($jugador->getEmail() != $datosRecibidos['email'] || $jugador->getPass() != md5($datosRecibidos['pass'])) {
                $cod = 401;
                $mes = 'Usuario o contraseña incorrectos.';

                header('HTTP/1.1 '.$cod.' '.$mes);

                return json_encode(['Codigo' => $cod, 'Mensaje' => $mes]);
            }
        } else {
            $cod = 401;
            $mes = 'Usuario o contraseña incorrectos.';

            header('HTTP/1.1 '.$cod.' '.$mes);

            return json_encode(['Codigo' => $cod, 'Mensaje' => $mes]);
        }
    }

    public static function getIdJugadorLogeado($datosRecibidos)
    {
        $jugador = Conexion::getJugadorFromEmail($datosRecibidos['email']);

        return $jugador->getId();
    }

    public static function insertPartida($idJugador, $longitud = 10, $bombas = 2)
    {
        $partidasAbiertas = ControladorJuego::getPartidasAbiertas($idJugador);

        if ($partidasAbiertas == 0) {
            $partida = Factoria::crearPartidaNueva($idJugador, $longitud, $bombas);

            if (Conexion::insertPartida($partida)) {
                $cod = 201;
                $mes = 'Partida creada';

                header('HTTP/1.1 '.$cod.' '.$mes);

                return json_encode(['Codigo' => $cod, 'Mensaje' => $mes,
                'Partida' => ControladorJuego::getPartidaReciente($idJugador)]);
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

        if (isset($partidas[0])) {
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
            $partida = ControladorJuego::getPartidabyId($idPartida, $idJugador);
        } else {
            $partida = ControladorJuego::getPartidaReciente($idJugador);
        }

        if ($partida instanceof Partida) {
            $tablero = $partida->getTableroSolucion();
            $tableroNuevo = $partida->getTableroJugador();
            $tableroNuevo[$casilla - 1] = $tablero[$casilla - 1];

            $partida->setTableroJugador($tableroNuevo);

            if ($tableroNuevo[$casilla - 1] == '*') {
                $partida->setFin(-1);
                ControladorJuego::finPartida($idJugador, $partida->getId(), -1);
            }

            if (substr_count($tableroNuevo, '-') == substr_count($tablero, '*')) {
                $partida->setFin(1);
                ControladorJuego::finPartida($idJugador, $partida->getId(), 1);
            }

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
            $mes = 'Partida no encontrada.';

            header('HTTP/1.1 '.$cod.' '.$mes);

            return json_encode(['Codigo' => $cod, 'Mensaje' => $mes,
            'Partidas abiertas' => ControladorJuego::getPartidasAbiertas($idJugador)]);
        }
    }

    public static function finPartida($idJugador, $idPartida, $resultado)
    {
        Conexion::closePartida($idPartida, $resultado);

        if ($resultado = -1) {
            $resultado = 0;
        }
        Conexion::updateStatsJugador($idJugador, $resultado);
    }

    public static function rendirse($idJugador, $idPartida = null)
    {
        if ($idPartida == null) {
            $partida = ControladorJuego::getPartidaReciente($idJugador);
            $idPartida = $partida->getId();
        }

        ControladorJuego::finPartida($idJugador, $idPartida, -1);
    }
}
