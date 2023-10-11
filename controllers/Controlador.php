<?php

require_once __DIR__.'\..\auxiliar\Conexion.php';
require_once __DIR__.'\..\auxiliar\Factoria.php';

class Controlador
{
    public static function login($datosRecibidos)
    {
        $jugador = Conexion::getJugadorFromEmail($datosRecibidos['email']);

        if ($jugador instanceof Jugador) {
            if ($jugador->getEmail() == $datosRecibidos['email'] && $jugador->getPass() == md5($datosRecibidos['pass'])) {
                return $jugador;
            } else {
                $cod = 401;
                $mes = 'Usuario o contraseña incorrectos.';

                header('HTTP/1.1 '.$cod.' '.$mes);

                return json_encode(['Codigo' => $cod, 'Mensaje' => $mes]);
            }
        } else {
            $cod = 500;
            $mes = 'Error en la base de datos.';

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
            $datosRecibidos['nombre'],
            $datosRecibidos['user-email'],
            md5($datosRecibidos['user-pass']),
            0, 0, 0
        );

        if (Conexion::insertJugador($jugador)) {
            $cod = 201;
            $mes = 'Jugador insertado';

            header('HTTP/1.1 '.$cod.' '.$mes);

            return json_encode(['Codigo' => $cod, 'Mensaje' => $mes]);
        } else {
            $cod = 500;
            $mes = 'Error';

            header('HTTP/1.1 '.$cod.' '.$mes);

            return json_encode(['Codigo' => $cod, 'Mensaje' => $mes]);
        }
    }
}
