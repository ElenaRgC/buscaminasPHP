<?php

require_once __DIR__.'\..\auxiliar\Conexion.php';
require_once __DIR__.'\..\auxiliar\Factoria.php';

class ControladorPartida
{
    public static function insertPartida($idJugador, $longitud = 10, $bombas = 2)
    {
        $partida = Factoria::crearPartidaNueva($idJugador, $longitud, $bombas);

        if (Conexion::insertPartida($partida)) {
            $cod = 201;
            $mes = 'Partida creada';

            header('HTTP/1.1 '.$cod.' '.$mes);

            return json_encode(['Codigo' => $cod, 'Mensaje' => $mes]);
        } else {
            $cod = 500;
            $mes = 'Error en la base de datos.';

            header('HTTP/1.1 '.$cod.' '.$mes);

            return json_encode(['Codigo' => $cod, 'Mensaje' => $mes]);
        }
    }

    public static function getPartidasAbiertas()
    {
        $partidas = Conexion::getPartidasAbiertas();

        if ($partidas[0] instanceof Partida) {
            $rutas = [];

            foreach ($partidas as $tablero) {
                $tablero = $tablero->getTableroSolucion();

                $rutas[] = [
                    'Longitud' => count($tablero),
                    'Bombas' => substr_count($tablero, '*'),
                ];
            }

            $cod = 200;
            $mes = 'OK';

            header('HTTP/1.1 '.$cod.' '.$mes);

            return json_encode(['Codigo' => $cod, 'Mensaje' => $mes,
            'Partidas Abiertas' => $partidas, 'Rutas' => $rutas]);
        } else {
            $cod = 500;
            $mes = 'Error';

            header('HTTP/1.1 '.$cod.' '.$mes);

            return json_encode(['Codigo' => $cod, 'Mensaje' => $mes]);
        }
    }
}
