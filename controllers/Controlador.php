<?php

require_once __DIR__.'..\model\Conexion.php';
require_once __DIR__.'..\model\Factoria.php';

class Controlador
{
    public static function login($jugador, $datosRecibidos)
    {
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
}
