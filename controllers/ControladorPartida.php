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
}
