<?php

require_once __DIR__.'\..\auxiliar\Conexion.php';
require_once __DIR__.'\..\auxiliar\Factoria.php';

class Controlador
{
    public static function login($datosRecibidos)
    {
        $jugador = Conexion::getJugadorFromEmail($datosRecibidos['email']);

        if ($jugador instanceof Jugador) {
            if ($jugador->getEmail() == $datosRecibidos['email'] && $jugador->getPass() == $datosRecibidos['pass']) {
                $esAdmin = false;

                if ($jugador->getEsAdmin()) {
                    $esAdmin = true;
                }

                return [true, $esAdmin];
            } else {
                $cod = 500;
                $mes = 'Error';

                return json_encode(['Codigo' => $cod, 'Mensaje' => $mes]);
            }
        }
    }

    public static function getJugadores($datosRecibidos)
    {
        $jugadores = Conexion::getJugadores();

        $esAdmin = Controlador::login($datosRecibidos);

        if ($esAdmin[0] && $esAdmin[1]) {
            // [0] login correcto [1] es Administrador

            if ($jugadores[0] instanceof Jugador) {
                $cod = 200;
                $mes = 'OK';

                return json_encode(['Codigo' => $cod, 'Mensaje' => $mes, 'Jugadores' => $jugadores]);
            } else {
                $cod = 500;
                $mes = 'Error';

                return json_encode(['Codigo' => $cod, 'Mensaje' => $mes]);
            }
        }
    }
}
