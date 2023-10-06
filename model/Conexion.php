<?php

require_once '../Constantes.php';

class Conexion
{
    public static $conexion;

    public static function conectar()
    {
        try {
            self::$conexion = new mysqli(
                Constantes::$URL,
                Constantes::$USER,
                Constantes::$PASSWORD,
                Constantes::$DATABASE_NAME
            );

            return self::$conexion;
        } catch (Exception $e) {
            return 0;
        }
    }

    public static function desconectar()
    {
        self::$conexion->close();
    }
}
