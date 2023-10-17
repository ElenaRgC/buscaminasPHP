<?php

require_once __DIR__.'\..\auxiliar\Conexion.php';
require_once __DIR__.'\..\auxiliar\Factoria.php';
require_once __DIR__.'\..\auxiliar\Constantes.php';

class ControladorAdministrador
{
    public static function getJugadorFromId($idJugador)
    {
        $jugador = Conexion::getJugadorFromId($idJugador);

        if ($jugador instanceof Jugador) {
            $cod = 200;
            $mes = 'OK';

            header('HTTP/1.1 '.$cod.' '.$mes);

            return json_encode(['Codigo' => $cod, 'Mensaje' => $mes, 'Jugador' => $jugador]);
        } else {
            $cod = 201;
            $mes = 'Jugador no encontrado';

            header('HTTP/1.1 '.$cod.' '.$mes);

            return json_encode(['Codigo' => $cod, 'Mensaje' => $mes]);
        }
    }

    public static function getJugadores()
    {
        $jugadores = Conexion::getJugadores();

        if ($jugadores[0] instanceof Jugador) {
            $cod = 200;
            $mes = 'OK';

            header('HTTP/1.1 '.$cod.' '.$mes);

            return json_encode(['Codigo' => $cod, 'Mensaje' => $mes, 'Jugadores' => $jugadores]);
        } else {
            $cod = 500;
            $mes = 'Error';

            header('HTTP/1.1 '.$cod.' '.$mes);

            return json_encode(['Codigo' => $cod, 'Mensaje' => $mes]);
        }
    }

    public static function insertJugador($datosRecibidos)
    {
        $jugador = Factoria::crearJugador(0,
            $datosRecibidos['user-nombre'],
            $datosRecibidos['user-email'],
            md5($datosRecibidos['user-pass']),
            0, 0, 0
        );

        if (Conexion::insertJugador($jugador)) {
            $cod = 201;
            $mes = 'Jugador insertado';

            return json_encode(['Codigo' => $cod, 'Mensaje' => $mes]);
        } else {
            $cod = 500;
            $mes = 'Error en la base de datos.';

            header('HTTP/1.1 '.$cod.' '.$mes);

            return json_encode(['Codigo' => $cod, 'Mensaje' => $mes]);
        }
    }

    public static function updateJugador($datosRecibidos)
    {
        $jugador = Factoria::crearJugador($datosRecibidos['user-id'], $datosRecibidos['user-nombre'], $datosRecibidos['user-email'], 0, 0, 0, 0);

        if (Conexion::updateJugador($jugador)) {
            $cod = 200;
            $mes = 'OK';

            header('HTTP/1.1 '.$cod.' '.$mes);

            return json_encode(['Codigo' => $cod, 'Mensaje' => $mes]);
        } else {
            $cod = 500;
            $mes = 'Error';

            header('HTTP/1.1 '.$cod.' '.$mes);

            return json_encode(['Codigo' => $cod, 'Mensaje' => $mes]);
        }
    }

    public static function updatePassword($idJugador, $pass)
    {
        $jugador = Factoria::crearJugador($idJugador, 0, 0, md5($pass), 0, 0, 0);

        if (Conexion::updatePassword($jugador)) {
            $cod = 200;
            $mes = 'OK';

            header('HTTP/1.1 '.$cod.' '.$mes);

            return json_encode(['Codigo' => $cod, 'Mensaje' => $mes]);
        } else {
            $cod = 500;
            $mes = 'Error';

            header('HTTP/1.1 '.$cod.' '.$mes);

            return json_encode(['Codigo' => $cod, 'Mensaje' => $mes]);
        }
    }

    public static function deleteJugador($id)
    {
        if (Conexion::deleteJugador($id)) {
            $cod = 200;
            $mes = 'Usuario eliminado.';

            header('HTTP/1.1 '.$cod.' '.$mes);

            return json_encode(['Codigo' => $cod, 'Mensaje' => $mes]);
        } else {
            $cod = 500;
            $mes = 'Error en la base de datos.';

            header('HTTP/1.1 '.$cod.' '.$mes);

            return json_encode(['Codigo' => $cod, 'Mensaje' => $mes]);
        }
    }
}
