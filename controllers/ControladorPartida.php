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

                return json_encode(['Codigo' => $cod, 'Mensaje' => $mes]);
            } else {
                $cod = 500;
                $mes = 'Error en la base de datos.';

                return json_encode(['Codigo' => $cod, 'Mensaje' => $mes]);
                header('HTTP/1.1 '.$cod.' '.$mes);
            }
        } else {
            $cod = 204;
            $mes = 'Ya hay partidas abiertas.';

            return json_encode(['Codigo' => $cod, 'Mensaje' => $mes,
            'Partidas Abiertas' => $partidasAbiertas[0], 'Rutas' => $partidasAbiertas[1]]);
            header('HTTP/1.1 '.$cod.' '.$mes);
        }
    }

    public static function getPartidasAbiertas($idJugador)
    {
        $partidas = Conexion::getPartidasAbiertas($idJugador);

        if ($partidas[0] instanceof Partida) {
            $rutas = [];

            foreach ($partidas as $tablero) {
                $tablero = $tablero->getTableroSolucion();

                $rutas[] = [
                    'Longitud' => strlen($tablero),
                    'Bombas' => substr_count($tablero, '*'),
                ];
            }

            return [$partidas, $rutas];
        } else {
            return 0;
        }
    }

    public static function getPartida($idJugador, $longitud, $bombas)
    {
        $partidasAbiertas = ControladorPartida::getPartidasAbiertas($idJugador);

        if ($partidasAbiertas != 0) {
            $partidas = $partidasAbiertas[0];
            $rutas = $partidasAbiertas[1];

            for ($i = 0; $i < count($rutas); ++$i) {
                $ruta = $rutas[$i];
                if ($ruta['Longitud'] == $longitud && $ruta['Bombas'] == $bombas) {
                    $cod = 200;
                    $mes = 'OK';

                    header('HTTP/1.1 '.$cod.' '.$mes);

                    return json_encode(['Codigo' => $cod, 'Mensaje' => $mes, 'Partida' => $partidas[$i]]);
                }
            }
        } else {
            $cod = 404;
            $mes = 'Partida no encontrada';

            header('HTTP/1.1 '.$cod.' '.$mes);

            return json_encode(['Codigo' => $cod, 'Mensaje' => $mes]);
        }
    }
}
